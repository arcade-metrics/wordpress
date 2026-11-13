#!/usr/bin/env bash
# Configura a rede depois de instalada: plugins e temas da rede, sites de platform/sites.conf, tema ativo e seed de cada site.
# Roda DENTRO do container, como www-data, em dev e em produção:
#   docker compose exec -T -u www-data wordpress network-setup
# Idempotente: rodar de novo não muda nada que já esteja certo.
set -euo pipefail

DOMAIN="${WP_DOMAIN_CURRENT_SITE:?WP_DOMAIN_CURRENT_SITE não definido}"
LOCK=/opt/platform/plugins.lock
SITES=/opt/platform/sites.conf

wp core is-installed --network --url="${DOMAIN}" || { echo "Rede não instalada em ${DOMAIN}" >&2; exit 1; }

echo "==> Plugins e temas da rede (${LOCK})"
while read -r type slug _version mode; do
  [[ -z "${type}" || "${type}" == \#* ]] && continue
  if [[ "${type}" == plugin && "${mode}" == network ]]; then
    wp plugin is-active "${slug}" --network --url="${DOMAIN}" 2>/dev/null \
      || wp plugin activate "${slug}" --network --url="${DOMAIN}"
  elif [[ "${type}" == theme && "${mode}" == network ]]; then
    wp theme enable "${slug}" --network --url="${DOMAIN}" >/dev/null
  fi
done < "${LOCK}"

echo "==> Sites (${SITES})"
existing="$(wp site list --field=domain --url="${DOMAIN}")"
while read -r slug sub title; do
  [[ -z "${slug}" || "${slug}" == \#* ]] && continue
  if [[ "${sub}" == "@" ]]; then url="${DOMAIN}"; else url="${sub}.${DOMAIN}"; fi

  if ! grep -qx "${url}" <<<"${existing}"; then
    echo "--> criando ${url}"
    wp site create --slug="${sub}" --title="${title}" --url="${DOMAIN}"
  fi

  echo "--> ${slug} (${url})"
  wp theme enable "${slug}" --network --url="${DOMAIN}" >/dev/null
  [[ "$(wp option get stylesheet --url="${url}")" == "${slug}" ]] || wp theme activate "${slug}" --url="${url}"
  wp eval-file "/opt/sites/${slug}/seed.php" --url="${url}"
done < "${SITES}"

echo "==> ok"
