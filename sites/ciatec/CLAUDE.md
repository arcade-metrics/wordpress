# sites/ciatec/ — agente: theme-ciatec

Site `ciatec.org` (dev: http://ciatec.localhost). Spec: `docs/specs/20-site-ciatec.md`.

- `theme/`: tema filho de `hello-elementor`, one-page em PHP, sem Elementor. Importado sem mudanças de
  `ciatec-org/ciatec-wordpress@035f39c` (2026-11-13).
- `seed.php`: opções, idiomas Polylang (`pt` padrão, `en`, `es`) e traduções do tema (`inc/strings-en.php`, `strings-es.php`). Idempotente.

Em dev o tema é montado no container: edite e recarregue o navegador. Prefixo `ciatec_` em tudo que for próprio.
Não edite fora desta pasta.
