#!/usr/bin/env bash
# Deploy de uma tag da imagem (spec 08, ADR 0005). Chamado pelo GitHub Actions via SSM, ou à mão para reverter:
#   sudo bash /opt/wordpress/bundle/server/deploy.sh sha-abc1234
# Passos: lock → extrai o bundle da imagem → compose up → update-db → network-setup → smoke test.
# Se o smoke test falhar, volta para a tag anterior.
set -euo pipefail

TAG="${1:?uso: deploy.sh <tag>}"
IMAGE="${IMAGE:-ghcr.io/arcade-metrics/wordpress}"
STACK=/opt/wordpress
BUNDLE="${STACK}/bundle"
LOG="${STACK}/deploy.log"

exec 9>/var/lock/wordpress-deploy.lock
flock -n 9 || { echo "outro deploy em andamento" >&2; exit 1; }

log() { echo "$(date -u +%FT%TZ) $*" | tee -a "${LOG}"; }
compose() { docker compose -f "${BUNDLE}/compose.yml" --project-directory "${STACK}" \
              --env-file "${STACK}/.env" --env-file "${STACK}/.env.deploy" "$@"; }
wpx() { compose exec -T -u www-data wordpress "$@"; }

[[ -s "${STACK}/.env" ]] || { echo "${STACK}/.env não existe (rode env-from-ssm.sh)" >&2; exit 1; }
PREV="$(sed -n 's/^IMAGE_TAG=//p' "${STACK}/.env.deploy" 2>/dev/null || true)"

log "deploy ${TAG} (anterior: ${PREV:-nenhum})"
docker pull -q "${IMAGE}:${TAG}"

# Bundle (compose, scripts) vem da própria imagem: o que roda é exatamente o que foi testado.
tmp="$(mktemp -d)"
docker run --rm --entrypoint tar "${IMAGE}:${TAG}" -C /opt/stack -c . | tar -x -C "${tmp}"
rm -rf "${BUNDLE}.new" && mv "${tmp}" "${BUNDLE}.new"
rm -rf "${BUNDLE}.old" && { [[ -d "${BUNDLE}" ]] && mv "${BUNDLE}" "${BUNDLE}.old" || true; }
mv "${BUNDLE}.new" "${BUNDLE}"

printf 'IMAGE=%s\nIMAGE_TAG=%s\n' "${IMAGE}" "${TAG}" > "${STACK}/.env.deploy"

rollback() {
  log "FALHA no deploy ${TAG}"
  if [[ -n "${PREV}" && "${PREV}" != "${TAG}" && "${ROLLING_BACK:-0}" != 1 ]]; then
    log "revertendo para ${PREV}"
    export ROLLING_BACK=1
    exec bash "${BUNDLE}.old/server/deploy.sh" "${PREV}"
  fi
  exit 1
}
trap rollback ERR

compose up -d --wait --wait-timeout 120
wpx wp core update-db --network --url=ciatec.org
wpx network-setup

DOMAIN=ciatec.org ORIGIN=http://127.0.0.1:8082 SCHEME=https bash "${BUNDLE}/scripts/smoke-test.sh"

# O container serve a tag certa? Imagens até sha-1134f59 não têm X-Release: aí só avisa (reverter continua possível).
release="$(curl -sI --max-time 30 -H 'Host: ciatec.org' -H 'X-Forwarded-Proto: https' http://127.0.0.1:8082/ \
            | tr -d '\r' | sed -n 's/^[Xx]-[Rr]elease: //p')"
if [[ -z "${release}" ]]; then
  log "aviso: imagem sem X-Release"
elif [[ "${release}" != "${TAG}" ]]; then
  log "X-Release ${release} != ${TAG}"; false
fi

trap - ERR

# Cache do Nginx (só a pasta da rede; os jogos não usam): sem isto, visitantes veem a versão anterior por até 5 min.
CACHE=/var/cache/nginx/wordpress
[[ -d "${CACHE}" ]] && find "${CACHE}" -mindepth 1 -delete && log "cache do Nginx limpo"
log "OK ${TAG}"
