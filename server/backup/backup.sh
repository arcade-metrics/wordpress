#!/usr/bin/env bash
# Backup diário da rede (spec 07): dump do banco + uploads. Rodado pelo cron como root.
# Local: /opt/wordpress/backups (7 dias). Fora da máquina: s3://$BACKUP_S3_URI (definido no .env).
set -euo pipefail

STACK=/opt/wordpress
DEST="${STACK}/backups"
STAMP="$(date -u +%Y%m%dT%H%M%SZ)"
KEEP_DAYS=7

set -a; source "${STACK}/.env"; source "${STACK}/.env.deploy"; set +a
compose() { docker compose -f "${STACK}/bundle/compose.yml" --project-directory "${STACK}" \
              --env-file "${STACK}/.env" --env-file "${STACK}/.env.deploy" "$@"; }

umask 077
echo "$(date -u +%FT%TZ) backup ${STAMP}"

compose exec -T db sh -c 'exec mariadb-dump -uroot -p"$MARIADB_ROOT_PASSWORD" --single-transaction --routines --databases "$MARIADB_DATABASE"' \
  | gzip > "${DEST}/db-${STAMP}.sql.gz"
gzip -t "${DEST}/db-${STAMP}.sql.gz"

docker run --rm -v wordpress_uploads:/data:ro alpine tar czf - -C /data . > "${DEST}/uploads-${STAMP}.tgz"

if [[ -n "${BACKUP_S3_URI:-}" ]]; then
  aws s3 cp --only-show-errors "${DEST}/db-${STAMP}.sql.gz"   "${BACKUP_S3_URI%/}/db/"
  aws s3 cp --only-show-errors "${DEST}/uploads-${STAMP}.tgz" "${BACKUP_S3_URI%/}/uploads/"
else
  echo "AVISO: BACKUP_S3_URI não definido; backup só local" >&2
fi

find "${DEST}" -maxdepth 1 -name 'db-*.sql.gz' -mtime +"${KEEP_DAYS}" -delete
find "${DEST}" -maxdepth 1 -name 'uploads-*.tgz' -mtime +"${KEEP_DAYS}" -delete
echo "$(date -u +%FT%TZ) ok ($(du -ch "${DEST}"/*-"${STAMP}"* | tail -1 | cut -f1))"
