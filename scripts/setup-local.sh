#!/usr/bin/env bash
# Instala a rede local e configura os sites. Idempotente.
# Rode da raiz do repositório depois de `docker compose up -d --build`.
# Credenciais admin/admin: SÓ para *.localhost.
set -euo pipefail
export MSYS_NO_PATHCONV=1

DOMAIN="${WP_DOMAIN_CURRENT_SITE:-ciatec.localhost}"
if [[ ! "${DOMAIN}" =~ ^([a-z0-9-]+\.)*localhost$ ]]; then
  echo "Recusado: '${DOMAIN}' não é *.localhost (admin/admin é só local)." >&2
  exit 1
fi

exec_wp() { docker compose exec -T -u www-data "$@"; }

echo "==> Aguardando o WordPress"
for _ in $(seq 1 60); do
  docker compose exec -T wordpress test -s /var/www/wordpress/wp-config.php 2>/dev/null && break
  sleep 2
done

if ! exec_wp wordpress wp core is-installed --network --url="${DOMAIN}" >/dev/null 2>&1; then
  echo "==> Instalando a rede em ${DOMAIN}"
  # WP_NETWORK_READY=0: as constantes do multisite ficam desligadas até as tabelas da rede existirem.
  exec_wp -e WP_NETWORK_READY=0 wordpress wp core multisite-install \
    --url="http://${DOMAIN}" --title="CIATec (local)" \
    --admin_user=admin --admin_password=admin --admin_email="dev@${DOMAIN}" \
    --subdomains --skip-email --skip-config
fi

echo "==> Configurando a rede e os sites"
exec_wp wordpress network-setup

echo
echo "Pronto:"
echo "  http://${DOMAIN}            (admin / admin em /wp-admin, rede em /wp-admin/network/)"
echo "  http://hint.${DOMAIN}"
