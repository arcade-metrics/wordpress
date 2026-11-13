# CIATec WordPress

Rede WordPress multisite do CIATec: `ciatec.org` e `hint.ciatec.org`.

## Requisitos

- Git, Docker Desktop (ou Docker Engine com Compose v2), Bash (Git Bash no Windows)
- Porta 80 livre

## Rodar local

```bash
cp .env.example .env            # opcional
docker compose up -d --build
bash scripts/setup-local.sh
```

- http://ciatec.localhost — site principal; painel da rede em `/wp-admin/network/`
- http://hint.ciatec.localhost — site do congresso HINT
- Login local: `admin` / `admin` (só em `*.localhost`)

Os temas em `sites/*/theme` são montados no container: editar e recarregar o navegador.

## Estrutura

```
.claude/        regras e agentes do Claude Code
docs/           specs, ADRs e runbooks
platform/       imagem Docker, plugins, configuração da rede
sites/          um diretório por site: tema e seed
compose.yaml    ambiente local
deploy/         compose de produção
server/         provisionamento, Nginx, backup e deploy no servidor
scripts/        setup local, WP-CLI, smoke test
.github/        CI e deploy
```

## Deploy

Merge no `main` → GitHub Actions gera a imagem, publica no GHCR e atualiza o servidor por SSM.
Detalhes em `docs/specs/08-deploy.md` e `docs/adr/0005-deploy.md`.

## Documentação

Comece por `docs/specs/README.md`. Regras para agentes: `CLAUDE.md`.
