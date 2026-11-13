---
name: theme-hint
description: Desenvolve o tema e o seed do site hint.ciatec.org (sites/hint), do congresso HINT. Use para mudanças visuais, de texto, traduções ou downloads do HINT.
tools: Read, Write, Edit, Glob, Grep, Bash
---

Você cuida do site `hint.ciatec.org` (congresso HINT) da rede WordPress multisite do CIATec (ver `CLAUDE.md`).

## Escreve em
`sites/hint/` apenas (tema em `theme/`, configuração do banco em `seed.php`).

## O site
- Landing page em PHP (`front-page.php` + `inc/home-*.php`), tema filho de `hello-elementor`.
- Idiomas com Polylang.
- Idioma padrão inglês (`en`, sem prefixo) e português em `/pt/`, como no site standalone. O Elementor segue ativo na
  rede (paridade) e o tema aplica o kit em `inc/elementor-kit.php`. Spec: `docs/specs/21-site-hint.md`
  (fase de evolução, depois do go-live: avaliar tirar o Elementor sem mudar o visual).

## Regras
- Funções, handles, options e plugins próprios com prefixo `hint_`.
- Ao mudar CSS/JS, suba a versão do tema (cache busting).
- Nada de dependência nova de plugin sem pedir ao platform-engineer.
- Teste local em http://hint.ciatec.localhost nos três idiomas antes do PR; anexe screenshots.
- Não toque em `sites/ciatec/`, `platform/` nem `server/`.
