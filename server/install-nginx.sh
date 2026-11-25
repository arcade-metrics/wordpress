#!/usr/bin/env bash
# Instala os blocos Nginx da rede (spec 04). Rodar como root, DEPOIS de emitir os certificados:
#   sudo certbot certonly --nginx -d ciatec.org -d www.ciatec.org
#   sudo certbot certonly --nginx -d hint.ciatec.org
#   sudo bash install-nginx.sh --dry-run
#   sudo bash install-nginx.sh
# Só cria/atualiza arquivos nossos; nunca toca nos blocos dos jogos. nginx -t antes de reload; se falhar, desfaz.
set -euo pipefail

DRY=0; [[ "${1:-}" == "--dry-run" ]] && DRY=1
SRC="$(cd "$(dirname "$0")/nginx" && pwd)"
[[ "$(id -u)" == 0 ]] || { echo "rode como root (sudo)" >&2; exit 1; }

for cert in ciatec.org hint.ciatec.org; do
  [[ -f "/etc/letsencrypt/live/${cert}/fullchain.pem" ]] \
    || { echo "certificado de ${cert} não existe: emita antes (ver cabeçalho)" >&2; exit 1; }
done

declare -A FILES=(
  ["${SRC}/conf.d/wordpress-cache.conf"]=/etc/nginx/conf.d/wordpress-cache.conf
  ["${SRC}/snippets/wordpress-proxy.conf"]=/etc/nginx/snippets/wordpress-proxy.conf
  ["${SRC}/sites/ciatec.org.conf"]=/etc/nginx/sites-available/ciatec.org.conf
  ["${SRC}/sites/hint.ciatec.org.conf"]=/etc/nginx/sites-available/hint.ciatec.org.conf
)

BACKUP="/root/nginx-backup-$(date -u +%Y%m%dT%H%M%SZ).tgz"
echo "==> backup de /etc/nginx em ${BACKUP}"
[[ "${DRY}" == 1 ]] || tar czf "${BACKUP}" -C /etc nginx

for src in "${!FILES[@]}"; do
  dst="${FILES[$src]}"
  if cmp -s "${src}" "${dst}"; then echo "    igual: ${dst}"; continue; fi
  echo "==> ${dst}"; [[ -f "${dst}" ]] && diff -u "${dst}" "${src}" || true
  [[ "${DRY}" == 1 ]] || install -m 644 "${src}" "${dst}"
done

for site in ciatec.org.conf hint.ciatec.org.conf; do
  [[ -L "/etc/nginx/sites-enabled/${site}" ]] && continue
  echo "==> habilitando ${site}"
  [[ "${DRY}" == 1 ]] || ln -s "/etc/nginx/sites-available/${site}" "/etc/nginx/sites-enabled/${site}"
done

[[ "${DRY}" == 1 ]] && { echo "(dry-run: nada alterado)"; exit 0; }

if nginx -t; then
  systemctl reload nginx
  echo "OK: Nginx recarregado"
else
  echo "nginx -t falhou: restaurando ${BACKUP}" >&2
  tar xzf "${BACKUP}" -C /etc
  nginx -t && echo "restaurado (nada foi recarregado)"
  exit 1
fi
