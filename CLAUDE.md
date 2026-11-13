# CIATec WordPress (monorepo)

Rede WordPress **multisite (modo subdomínio)** do CIATec, com os sites `ciatec.org` e `hint.ciatec.org`.
Os sites são landing pages em PHP, dentro dos temas, sem blog. Repositório: `github.com/arcade-metrics/wordpress`.

**Fase atual: paridade.** O objetivo é pôr os dois sites no ar exatamente como eram (mesmo core, plugins, idiomas e
textos), verificado por comparação com os ambientes antigos. Melhorias (tirar Elementor, novos idiomas, redesenho)
só depois do go-live, em PRs próprios.

Tudo é reproduzível a partir deste repositório: imagem, configuração da rede, temas, seed do banco, Nginx e deploy.
Se o servidor sumir, recriar é provisionar a máquina, subir o compose e restaurar o backup.

## Mapa

| Pasta | Conteúdo | Spec | Agente |
|---|---|---|---|
| `docs/specs/` | Contratos com critérios de aceite. **Leia a spec antes de mexer.** | — | spec-architect |
| `docs/adr/` | Decisões de arquitetura | — | spec-architect |
| `docs/runbooks/` | Passo a passo de operação (humano) | — | spec-architect |
| `platform/` | Imagem Docker, plugins, `wp-config` da rede, mu-plugins | 02, 03 | platform-engineer |
| `sites/<site>/theme/` | Tema de cada site | 20, 21 | theme-ciatec, theme-hint |
| `sites/<site>/seed.php` | Configuração do site no banco (idiomas, opções) | 02 | dono do site |
| `compose.yaml` | Ambiente local (dev) | 02 | platform-engineer |
| `deploy/` | Compose de produção | 02, 08 | platform-engineer |
| `server/` | Provisionamento do servidor, Nginx, backup, deploy remoto | 04, 05, 07, 08 | server-ops |
| `scripts/` | Setup local, WP-CLI, seed, smoke test | 02 | platform-engineer |
| `.github/workflows/` | CI e deploy | 08 | platform-engineer |

## Comandos (local)

```bash
cp .env.example .env            # opcional, há padrões de dev
docker compose up -d --build    # http://ciatec.localhost e http://hint.ciatec.localhost
bash scripts/setup-local.sh     # instala a rede, cria o hint, roda os seeds (idempotente)
bash scripts/smoke-test.sh      # confere os dois sites
bash scripts/wp.sh site list    # WP-CLI; para comandos de site use --url=
```

## Regras

1. **Branch e PR sempre.** Nunca push no `main`. Revisão e merge são humanos. Merge no `main` publica em produção.
2. **Spec primeiro.** Mudança de comportamento começa pela spec (ou por um ADR). O PR cita a spec e os critérios de aceite atendidos.
3. **Cada agente escreve só na sua pasta** (tabela acima). Mudança fora dela vira pedido para o agente dono.
4. **Nada é configurado à mão** no painel ou no servidor. Se precisou clicar, vira código (seed, script ou config).
5. **Segredos:** não leia, não imprima e não commite `.env`, senhas ou chaves. Use `.env.example` só com nomes.
6. **Servidor compartilhado:** o WordPress roda na EC2 `ciatec-games`, que também serve os jogos (`*.game.ciatec.org`)
   e um runner do GitHub. Nunca altere blocos Nginx, serviços ou pastas dos jogos.
7. **Proibido sem aprovação humana explícita:** `docker compose down -v`, `docker system/volume prune`, `rm -rf` fora do repo,
   comandos na AWS, DNS, emissão de certificados, qualquer ação em produção.
8. WP-CLI: `bash scripts/wp.sh <comando>`; comandos de um site levam `--url=`.
9. Temas e plugins próprios têm prefixo do site (`ciatec_`, `hint_`). Plugins de terceiros só pelo `platform/plugins.lock`.
10. Em arquivos Compose, `$` literal é `$$`. Confira que domínios não viraram links markdown.

## PR

Descreva o que mudou, quais sites são afetados, como testar e como reverter. Checklist em `docs/specs/README.md`.
