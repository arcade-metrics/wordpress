#!/usr/bin/env bash
# Baixa os plugins e temas do plugins.lock de downloads.wordpress.org (versões fixas).
# Uso: fetch.sh <plugins.lock> <pasta-de-saída>   (roda dentro do estágio "fetch" do Dockerfile)
set -euo pipefail

LOCK="${1:?plugins.lock}"
OUT="${2:?saída}"
mkdir -p "${OUT}/plugins" "${OUT}/themes" /tmp/dl

while read -r type slug version _mode; do
  [[ -z "${type}" || "${type}" == \#* ]] && continue
  case "${type}" in
    plugin) url="https://downloads.wordpress.org/plugin/${slug}.${version}.zip"; dest="${OUT}/plugins" ;;
    theme)  url="https://downloads.wordpress.org/theme/${slug}.${version}.zip";  dest="${OUT}/themes" ;;
    *) echo "tipo inválido em ${LOCK}: ${type}" >&2; exit 1 ;;
  esac
  echo "==> ${type} ${slug} ${version}"
  curl -fsSL --retry 3 -o "/tmp/dl/${slug}.zip" "${url}"
  unzip -q -o "/tmp/dl/${slug}.zip" -d "${dest}"
  test -d "${dest}/${slug}" || { echo "zip de ${slug} não criou ${dest}/${slug}" >&2; exit 1; }
done < "${LOCK}"
