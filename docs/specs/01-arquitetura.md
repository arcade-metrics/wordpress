# 01 — Arquitetura

- **Status:** rascunho
- **Agente responsável:** spec-architect
- **Depende de:** ADRs 0001–0005
- **Atualizado em:** 2026-11-13

## Objetivo

Descrever como a rede é montada em cada ambiente, para que todos os agentes partam do mesmo desenho.

## Visão geral (produção)

```
Internet ──80/443──▶ Nginx do host (ciatec-games)
                      ├─ *.game.ciatec.org ──▶ jogos (NÃO MEXER)
                      └─ ciatec.org, www, hint.ciatec.org
                            │ cache curto p/ visitantes anônimos
                            ▼ proxy_pass 127.0.0.1:8082
                     ┌─ Docker: projeto "wordpress" ───────────────┐
                     │ wordpress  (imagem ghcr.io/arcade-metrics/   │
                     │             wordpress:<sha>, php8.3-apache)  │
                     │     │                                        │
                     │ db (mariadb:11.4, sem porta publicada)       │
                     │ volumes: db_data, uploads                    │
                     └──────────────────────────────────────────────┘
backup diário ──▶ S3 (dump + uploads)        deploy ──▶ GitHub Actions → GHCR → SSM
```

## Ambientes

| | Local (dev) | Produção |
|---|---|---|
| Onde | Docker Desktop | EC2 `ciatec-games` (us-east-2), Elastic IP `18.119.16.166` |
| Compose | `compose.yaml` (build local) | `deploy/compose.yml` (imagem do GHCR) |
| Domínio principal | `ciatec.localhost` | `ciatec.org` |
| Site hint | `hint.ciatec.localhost` | `hint.ciatec.org` |
| Porta | `80` | `127.0.0.1:8082` |
| TLS | não | Nginx do host + certbot |
| Temas | bind mount de `sites/*/theme` (edição ao vivo) | dentro da imagem |

Multisite em modo subdomínio. Em dev, `hint.ciatec.localhost` é subdomínio nativo de `ciatec.localhost`, espelhando a produção.

## Componentes

| Componente | Origem | Observação |
|---|---|---|
| Imagem WordPress | `platform/Dockerfile` | core + `hello-elementor` + plugins do lock + temas + mu-plugins + WP-CLI |
| Config da rede | `platform/config/network.php` | constantes do multisite, segurança; incluída via `WORDPRESS_CONFIG_EXTRA` |
| Banco | `mariadb:11.4` | uma base, tabelas `wp_` (rede e site 1) e `wp_2_` (hint) |
| Uploads | volume `uploads` | único estado em disco além do banco |
| Config dos sites no banco | `sites/*/seed.php` | idiomas Polylang, opções, tema ativo |

## Orçamento de memória (produção)

A máquina tem ~1,9 GB de RAM; em 2026-11-13 havia ~1,2 GB disponível com os jogos rodando.

| Serviço | Limite (`mem_limit`) | Ajuste |
|---|---|---|
| db (MariaDB) | 256 MB | `innodb_buffer_pool_size=64M`, `performance_schema=OFF`, `max_connections=30` |
| wordpress (Apache + PHP) | 384 MB | `MaxRequestWorkers` 6, `memory_limit` PHP 128M, OPcache ligado |
| **Total do stack** | **640 MB** | + swap de 2 GB no host (spec 05) |

O cache do Nginx para visitantes anônimos (spec 04) deve fazer a maior parte dos acessos não chegar ao PHP.
Se o pico medido passar de 700 MB de forma recorrente, a saída é subir a instância (decisão humana, gera custo).

## Portas na `ciatec-games`

| Porta | Uso |
|---|---|
| 80, 443 | Nginx do host (jogos + WordPress) |
| 127.0.0.1:8082 | WordPress (este projeto) |
| 127.0.0.1:8790, 8791 | apps Node dos jogos (não mexer) |

## Critérios de aceite

- [ ] CA1 — O desenho acima bate com `compose.yaml`, `deploy/compose.yml` e `server/nginx/`. **Como verificar:** revisão do `reviewer`.
- [ ] CA2 — Os limites de memória da tabela estão no `deploy/compose.yml`. **Como verificar:** `grep mem_limit deploy/compose.yml`.
- [ ] CA3 — Nenhuma porta do stack é publicada fora de `127.0.0.1` em produção.

## Riscos e pontos em aberto

- Memória: medir na F3 com os jogos em uso real.
- O runner do GitHub já existente na máquina é dos jogos; o deploy do WordPress não o usa (ADR 0005).
