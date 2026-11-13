# 0001 — Monorepo

- **Status:** aceita
- **Data:** 2026-11-13

## Contexto

A rede anterior tinha três repositórios (`ciatec-wordpress-network`, `ciatec-wordpress`, `hint-wordpress`) e dois runners
de deploy com ordem obrigatória entre eles (site antes da rede). Mudanças que cruzavam rede e tema exigiam PRs coordenados.

## Decisão

Um único repositório, `github.com/arcade-metrics/wordpress`, com a plataforma, os sites, o servidor e a documentação.
O escopo de cada agente é delimitado por pasta (ver `CLAUDE.md`).

## Consequências

- Uma mudança que cruza rede e tema é um PR só, testado junto.
- Uma imagem só, com tudo dentro: não há ordem de deploy entre repositórios.
- Todo merge gera imagem nova para os dois sites; o smoke test cobre os dois.
- Separar um site no futuro exige extrair a pasta `sites/<site>` (simples, mas não automático).

## Alternativas descartadas

- **Um repositório por site + um da rede** (modelo anterior): mais coordenação, mais runners, mais pontos de falha.
