#!/usr/bin/env bash
# Restaura banco e uploads a partir de um backup (spec 07). SUBSTITUI o estado atual. Rodar como root:
#   sudo bash restore.sh /opt/wordpress/backups/db-<stamp>.sql.gz /opt/wordpress/backups/uploads-<stamp>.tgz
# Faz um backup do estado atual antes de restaurar.
set -euo pipefail

DB_FILE="${1:?arquivo db-*.sql.gz}"
UP_FILE="${2:?arquivo uploads-*.tgz}"
STACK=/opt/wordpress
compose() { docker compose -f "${STACK}/bundle/compose.yml" --project-directory "${STACK}" \
              --env-file "${STACK}/.env" --env-file "${STACK}/.env.deploy" "$@"; }

gzip -t "${DB_FILE}"; tar tzf "${UP_FILE}" >/dev/null

read -r -p "Isto substitui banco e uploads da rede. Digite RESTAURAR para continuar: " ok
[[ "${ok}" == RESTAURAR ]] || { echo "cancelado"; exit 1; }

echo "==> backup de segurança do estado atual"
bash "$(dirname "$0")/backup.sh"

echo "==> banco"
gunzip -c "${DB_FILE}" | compose exec -T db sh -c 'exec mariadb -uroot -p"$MARIADB_ROOT_PASSWORD"'

echo "==> uploads"
docker run --rm -i -v wordpress_uploads:/data alpine sh -c 'find /data -mindepth 1 -delete && tar xzf - -C /data && chown -R 33:33 /data' < "${UP_FILE}"

echo "==> cache"
compose exec -T -u www-data wordpress wp cache flush --url=ciatec.org
rm -rf /var/cache/nginx/wordpress/* 2>/dev/null || true

echo "OK. Rode o smoke test: DOMAIN=ciatec.org ORIGIN=http://127.0.0.1:8082 SCHEME=https bash ${STACK}/bundle/scripts/smoke-test.sh"
