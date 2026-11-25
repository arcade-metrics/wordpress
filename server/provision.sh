#!/usr/bin/env bash
# Prepara a ciatec-games para a rede WordPress (spec 05). Idempotente. Rodar como root:
#   sudo bash provision.sh --dry-run    # mostra o que faria
#   sudo bash provision.sh
# Não toca nos jogos, no Nginx (ver install-nginx.sh) nem em segredos (ver env-from-ssm.sh).
set -euo pipefail

DRY=0; [[ "${1:-}" == "--dry-run" ]] && DRY=1
STACK=/opt/wordpress
run() { echo "+ $*"; [[ "${DRY}" == 1 ]] || "$@"; }

[[ "$(id -u)" == 0 ]] || { echo "rode como root (sudo)" >&2; exit 1; }

echo "==> Swap de 2 GB"
if swapon --show=NAME --noheadings | grep -q .; then
  echo "    já existe swap: $(swapon --show=NAME,SIZE --noheadings | xargs)"
else
  run fallocate -l 2G /swapfile
  run chmod 600 /swapfile
  run mkswap /swapfile
  run swapon /swapfile
  grep -q '^/swapfile ' /etc/fstab || run sh -c "echo '/swapfile none swap sw 0 0' >> /etc/fstab"
  run sh -c "echo 'vm.swappiness=10' > /etc/sysctl.d/90-wordpress-swap.conf"
  run sysctl -p /etc/sysctl.d/90-wordpress-swap.conf
fi

echo "==> Docker"
if command -v docker >/dev/null && docker compose version >/dev/null 2>&1; then
  echo "    já instalado: $(docker --version)"
else
  run apt-get update
  run apt-get install -y docker.io docker-compose-v2
  run systemctl enable --now docker
fi
# Docker: portas publicadas só em 127.0.0.1 por padrão (o Docker passa por cima do UFW, e o SG da games tem
# 8080-8082 abertas para a internet: relatório aws-cli/reports/2026-11-13-exposicao-rede.md, A1) e logs com limite.
# Não afeta containers já criados.
if [[ ! -f /etc/docker/daemon.json ]]; then
  run sh -c 'printf "%s\n" "{\"ip\":\"127.0.0.1\",\"log-driver\":\"json-file\",\"log-opts\":{\"max-size\":\"10m\",\"max-file\":\"3\"}}" > /etc/docker/daemon.json'
  run systemctl restart docker
fi

echo "==> Pastas do stack"
run install -d -m 750 "${STACK}" "${STACK}/bundle" "${STACK}/backups"

echo "==> Cache do Nginx"
run install -d -o www-data -g www-data -m 750 /var/cache/nginx/wordpress

echo "==> Cron de backup (03:30 UTC)"
CRON=/etc/cron.d/wordpress-backup
LINE="30 3 * * * root ${STACK}/bundle/server/backup/backup.sh >> ${STACK}/backups/backup.log 2>&1"
if [[ ! -f "${CRON}" ]] || ! grep -qF "${LINE}" "${CRON}"; then
  run sh -c "echo '${LINE}' > ${CRON}"
fi

echo "==> AWS CLI (backup no S3, segredos do SSM)"
command -v aws >/dev/null && echo "    já instalado" || run snap install aws-cli --classic

echo "Pronto. Próximos passos: env-from-ssm.sh, certificados + install-nginx.sh, primeiro deploy (runbook)."
