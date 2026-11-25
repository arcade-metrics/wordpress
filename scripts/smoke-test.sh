#!/usr/bin/env bash
# Confere que os sites respondem com o tema certo, nos três idiomas.
# Local:    bash scripts/smoke-test.sh
# Servidor: DOMAIN=ciatec.org ORIGIN=http://127.0.0.1:8082 SCHEME=https bash smoke-test.sh
#   (fala direto com o container, mandando Host e X-Forwarded-Proto como o Nginx faria)
set -uo pipefail

DOMAIN="${DOMAIN:-${WP_DOMAIN_CURRENT_SITE:-ciatec.localhost}}"
ORIGIN="${ORIGIN:-http://127.0.0.1:${WORDPRESS_PORT:-80}}"
SCHEME="${SCHEME:-http}"
fail=0

check() {  # host caminho código-esperado texto-esperado
  local host="$1" path="$2" want="$3" needle="${4:-}" body code
  body="$(mktemp)"
  # A primeira requisição depois do setup pode demorar (Elementor gera CSS): timeout folgado e 2 tentativas.
  code="$(curl -s -o "${body}" -w '%{http_code}' --max-time 45 --retry 2 --retry-all-errors \
           -H "Host: ${host}" -H "X-Forwarded-Proto: ${SCHEME}" "${ORIGIN}${path}")"
  if [[ "${code}" != "${want}" ]]; then
    echo "FALHA ${host}${path}: HTTP ${code} (esperado ${want})"; fail=1
  elif [[ -n "${needle}" ]] && ! grep -q -- "${needle}" "${body}"; then
    echo "FALHA ${host}${path}: não contém '${needle}'"; fail=1
  else
    echo "ok    ${host}${path} (${code})"
  fi
  rm -f "${body}"
}

# tema:host:caminhos (idiomas de cada site, ver sites/*/seed.php)
for site in "ciatec:${DOMAIN}:/ /en/ /es/" "hint:hint.${DOMAIN}:/ /pt/"; do
  IFS=: read -r theme host paths <<<"${site}"
  for path in ${paths}; do
    check "${host}" "${path}" 200 "themes/${theme}/"
  done
done
check "${DOMAIN}" "/wp-admin/network/" 302

[[ "${fail}" == 0 ]] && echo "Smoke test OK" || { echo "Smoke test FALHOU"; exit 1; }
