# Specs

Cada spec é um contrato: o que precisa existir e como verificar. O agente implementa, o `reviewer` confere os
critérios de aceite, um humano aprova o merge. Modelo: [`_template.md`](_template.md).

Status: **rascunho** → **aprovada** (humano) → **implementada** (critérios verificados).

| # | Spec | Agente | Status | Critério de aceite principal |
|---|---|---|---|---|
| 00 | [Visão e princípios](00-visao.md) | spec-architect | rascunho | — |
| 01 | [Arquitetura](01-arquitetura.md) | spec-architect | rascunho | Diagrama, portas, domínios e orçamento de memória por ambiente |
| 02 | Plataforma multisite | platform-engineer | a escrever | `compose up` + `setup-local.sh` do zero → 2 sites prontos, sem painel; 2ª execução não muda nada |
| 03 | Plugins e temas | platform-engineer | a escrever | A imagem contém exatamente o `plugins.lock`; atualização = PR dedicado |
| 04 | Nginx, TLS e domínios | server-ops | a escrever | HTTPS em `ciatec.org`, `www` e `hint.ciatec.org`; `nginx -t` ok; blocos `*.game` intactos |
| 05 | Provisionamento do servidor | server-ops | a escrever | `provision.sh` roda 2× sem efeito colateral; jogos continuam respondendo |
| 06 | Segredos | platform-engineer | a escrever | Nada no Git; `.env` do servidor gerado do SSM Parameter Store, permissão 600 |
| 07 | Backup e restauração | server-ops | a escrever | Backup diário no S3; restore em máquina limpa testado e cronometrado |
| 08 | Deploy | platform-engineer | a escrever | Merge → imagem no GHCR → SSM → `compose up`; reversão para a tag anterior em 1 comando |
| 09 | Recursos e observabilidade | server-ops | a escrever | Pico de memória do stack < 700 MB; alerta se um site cair |
| 10 | E-mail (SES) | platform-engineer | a escrever | E-mail de teste entregue a partir de cada site |
| 20 | Site ciatec | theme-ciatec | a escrever | Visual idêntico ao tema importado (screenshots nos 3 idiomas) |
| 21 | [Site hint](21-site-hint.md) | theme-hint | rascunho | Fase 2: i18n PT/EN, painel de acessibilidade (AAA, texto 150%), 6 seções da home, conteúdo do HINT 2027 |

## Fases do projeto

1. **Paridade (atual):** os dois sites no ar exatamente como eram: mesmo core (7.0.8), plugins (Elementor, Polylang),
   idiomas e textos. Critério: diff do HTML e screenshots contra os ambientes antigos (`ciatec-wordpress-dev`,
   `hintconference-web`). Nenhuma melhoria entra nesta fase.
2. **Evolução:** depois do go-live, em PRs próprios (tirar Elementor e plugins inativos, idiomas, redesenho).

## Ordem de construção (fase 1)

| Fase | Specs | Toca servidor/AWS? |
|---|---|---|
| F0 Fundação | 00, 01, ADRs | não |
| F1 Plataforma local | 02, 03, 06 | não |
| F2 Sites | 20, 21 | não |
| F3 Servidor | 04, 05 | sim (humano executa) |
| F4 Backup e deploy | 07, 08 | sim (humano executa) |
| F5 Go-live e operação | 09, 10 | sim (humano executa) |

## Checklist de PR

- [ ] Cita a spec e os critérios de aceite atendidos
- [ ] Arquivos alterados dentro do escopo do agente
- [ ] Sem segredos
- [ ] `setup-local.sh` e `smoke-test.sh` passando
- [ ] Sites afetados, como testar e como reverter descritos
- [ ] Spec/runbook atualizados se o comportamento mudou
