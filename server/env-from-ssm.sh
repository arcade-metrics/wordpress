#!/usr/bin/env bash
# Gera /opt/wordpress/.env a partir do SSM Parameter Store (spec 06). Rodar como root.
# Parâmetros esperados (SecureString) em /wordpress/prod/:
#   DB_PASSWORD, DB_ROOT_PASSWORD, WORDPRESS_AUTH_KEY ... WORDPRESS_NONCE_SALT
# Requer que o papel da instância tenha ssm:GetParametersByPath em /wordpress/prod/* (runbook).
set -euo pipefail

PREFIX="${SSM_PREFIX:-/wordpress/prod/}"
REGION="${AWS_REGION:-us-east-2}"
OUT=/opt/wordpress/.env
TMP="$(mktemp)"; trap 'rm -f "${TMP}"' EXIT
umask 077

aws ssm get-parameters-by-path --region "${REGION}" --path "${PREFIX}" --with-decryption \
  --query 'Parameters[].[Name,Value]' --output text \
| while IFS=$'\t' read -r name value; do
    printf '%s=%s\n' "${name##*/}" "${value}"
  done > "${TMP}"

for required in DB_PASSWORD DB_ROOT_PASSWORD WORDPRESS_AUTH_KEY WORDPRESS_NONCE_SALT; do
  grep -q "^${required}=" "${TMP}" || { echo "faltando ${PREFIX}${required}" >&2; exit 1; }
done

install -m 600 -o root -g root "${TMP}" "${OUT}"
echo "OK: ${OUT} com $(wc -l < "${OUT}") variáveis (valores não exibidos)"
