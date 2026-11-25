#!/usr/bin/env bash
# Fluxo 1 — testar local. Sobe a rede, roda o setup duas vezes (idempotência) e o smoke test.
# O CI roda exatamente este script em todo PR; se passa aqui, passa lá.
#   bash scripts/test-local.sh
# Não apaga o banco local. Para começar do zero, apague o volume db_data à mão (com cuidado).
set -euo pipefail
cd "$(dirname "$0")/.."

echo "==> Subindo a rede (build da imagem)"
docker compose up -d --build --wait

echo "==> Setup (1ª vez)"
bash scripts/setup-local.sh

# Smoke logo depois da 1ª execução: é o que acontece num servidor novo (pegou o 404 de /en/ no go-live).
echo "==> Smoke test (após a 1ª vez)"
bash scripts/smoke-test.sh

echo "==> Setup (2ª vez, idempotência)"
bash scripts/setup-local.sh

echo "==> Smoke test"
bash scripts/smoke-test.sh
