# 00 — Visão e princípios

- **Status:** rascunho
- **Agente responsável:** spec-architect
- **Atualizado em:** 2026-11-13

## Objetivo

Reconstruir a rede WordPress do CIATec do zero, de forma que ela possa ser recriada a qualquer momento a partir deste
repositório. A rede anterior rodava numa EC2 montada à mão, com backups no próprio disco, e foi perdida junto com a máquina.

## Sites

| Domínio | Papel | Conteúdo |
|---|---|---|
| `ciatec.org` (+ `www`) | Site principal da rede; painel em `/wp-admin/network/` | Landing page one-page (tema `ciatec`) |
| `hint.ciatec.org` | Congresso HINT | Landing page (tema `hint`) |

Os sites são pequenos e devem continuar pequenos: sem blog, sem loja. O conteúdo está nos temas
(PHP + strings traduzidas com Polylang: ciatec em `pt`, `en`, `es`; hint em `en`, `pt`).

## Princípios

1. **Reproduzível.** Repositório + backup = rede inteira. Nada configurado à mão no painel ou no servidor.
2. **Simples.** A menor solução que atende. Cada plugin precisa justificar a memória e a manutenção que custa.
3. **Leve.** Roda numa t3.small compartilhada com os jogos (`ciatec-games`). Orçamento de memória na spec 01.
4. **Isolado.** Um site não altera nem derruba o outro; a rede não altera nem derruba os jogos.
5. **Spec primeiro.** Mudança de comportamento começa por spec ou ADR, com critérios verificáveis.
6. **Humano no controle de produção.** Agentes escrevem código e scripts; ações na AWS, DNS, certificados e merge são humanas.

## Fora do escopo

- Migrar conteúdo de banco antigo (não existe; o conteúdo está nos temas).
- Staging permanente (pode entrar depois como `127.0.0.1:8083`).
- Sites além de ciatec e hint (o processo de adicionar site vira runbook depois do go-live).

## Critérios de sucesso do projeto

- [ ] Os dois sites no ar com HTTPS, visual idêntico aos temas importados.
- [ ] Recriar a rede numa máquina limpa leva menos de 1 hora, seguindo só os runbooks.
- [ ] Backup diário fora da máquina, com restauração testada.
- [ ] Deploy por merge no `main`, com reversão em 1 comando.
