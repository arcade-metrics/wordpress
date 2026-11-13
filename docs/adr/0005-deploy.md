# 0005 — Deploy por GitHub Actions, GHCR e SSM

- **Status:** proposta (validar na F4)
- **Data:** 2026-11-13

## Contexto

A rede anterior usava runners self-hosted na EC2 com acesso ao Docker, o que equivale a root na máquina. A `ciatec-games`
já tem um runner, que pertence aos jogos.

## Decisão

1. **CI** (PR): build da imagem, sobe o stack local na CI, roda `setup-local.sh` e `smoke-test.sh`.
2. **Deploy** (merge no `main` ou manual): build e push para `ghcr.io/arcade-metrics/wordpress:sha-<commit>`; o job
   assume um papel IAM via **OIDC** (sem chave guardada no GitHub) e chama **SSM Run Command** na `ciatec-games`, que executa
   `server/deploy.sh <tag>`: pull, `compose up -d`, `wp core update-db --network`, seed, smoke test.
3. O job de deploy usa o *environment* `production` do GitHub, com aprovação obrigatória.
4. Reversão: `workflow_dispatch` com a tag anterior.

## Consequências

- Nenhum runner novo na máquina; o GitHub nunca tem shell direto nela.
- Depende de recursos AWS criados por um humano: provedor OIDC do GitHub, papel IAM com permissão só para `ssm:SendCommand`
  nessa instância, e acesso da instância ao GHCR (imagem pública ou token em SSM Parameter Store).

## Pontos em aberto

- Imagem pública ou privada no GHCR? Ela só tem código público e os temas; pública simplifica o pull.
- Onde fica o stack no servidor: proposta `/opt/wordpress`.

## Alternativas descartadas

- **Runner self-hosted:** acesso root implícito, já foi problema.
- **SSH a partir do Actions:** exige chave guardada e porta 22 aberta.
