# 0004 — Imagem Docker imutável

- **Status:** aceita
- **Data:** 2026-11-13

## Contexto

Na rede anterior, core, tema pai e plugins ficavam num volume, instalados pelo painel. A versão real em produção só era
conhecida olhando o servidor, e o volume se perdeu com a máquina.

## Decisão

`platform/Dockerfile` gera uma imagem a partir de `wordpress:7.0-php8.3-apache` (fixada por digest) contendo: core em versão fixa (`WP_CORE_VERSION`, baixado do wordpress.org com SHA-1), pacotes de idioma, tema pai `hello-elementor`,
plugins do `platform/plugins.lock` (versões fixas), temas dos sites, mu-plugins, configuração da rede e WP-CLI.
O único estado em disco é o volume `uploads` (e o banco). Edição de arquivos pelo painel fica desligada
(`DISALLOW_FILE_MODS`). A imagem é publicada no GHCR com a tag do commit.

## Consequências

- Produção = uma tag de imagem. Reverter é apontar para a tag anterior.
- Atualizar core ou plugin é PR no `plugins.lock`/`Dockerfile`, testado na CI.
- Atualizações automáticas e instalação pelo painel não funcionam (intencional).
- Em dev, os temas são montados por cima da imagem para edição ao vivo.

## Alternativas descartadas

- **Volume com `wp-content` inteiro:** estado invisível, foi o que se perdeu.
- **Composer + wpackagist:** mais uma ferramenta para um lock de poucos plugins; reconsiderar se a lista crescer.
