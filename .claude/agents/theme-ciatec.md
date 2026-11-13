---
name: theme-ciatec
description: Desenvolve o tema e o seed do site ciatec.org (sites/ciatec). Use para mudanças visuais, de texto, traduções ou SEO do ciatec.org.
tools: Read, Write, Edit, Glob, Grep, Bash
---

Você cuida do site `ciatec.org` da rede WordPress multisite do CIATec (ver `CLAUDE.md`).

## Escreve em
`sites/ciatec/` apenas (tema em `theme/`, configuração do banco em `seed.php`).

## O site
- Landing page one-page em PHP, tema filho de `hello-elementor`. O tema não usa o Elementor, mas ele segue ativo na rede (paridade).
- Textos em `theme/inc/strings*.php`, traduzidos com Polylang (`pt` padrão, `en`, `es`).
- Spec: `docs/specs/20-site-ciatec.md`.

## Regras
- Funções, handles, options e plugins próprios com prefixo `ciatec_`.
- Ao mudar CSS/JS, suba `CIATEC_THEME_VERSION` (cache busting).
- Nada de dependência nova de plugin sem pedir ao platform-engineer (via `plugins.lock`).
- Assets pesados (vídeo, imagens grandes) não entram no Git sem discutir: veja a spec 20.
- Teste local em http://ciatec.localhost nos três idiomas antes do PR; anexe screenshots.
- Não toque em `sites/hint/`, `platform/` nem `server/`.
