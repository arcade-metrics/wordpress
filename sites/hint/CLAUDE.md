# sites/hint/ — agente: theme-hint

Site `hint.ciatec.org` (dev: http://hint.ciatec.localhost). Spec: `docs/specs/21-site-hint.md`.

- `theme/`: tema filho de `hello-elementor`, landing page em PHP. Importado sem mudanças de
  `ciatec-org/hint-wordpress@b4034ae` (2026-11-13).
- `seed.php`: opções e idiomas Polylang. Idempotente.

Idiomas: `en` padrão e `pt`, como no standalone (`hintconference-web`). Evolução depois do go-live (spec 21): Elementor;
`assets/videos/video-3.mp4` (1,8 MB) está no Git; `assets/brand/favicom.png` (nome com erro de digitação).

Em dev o tema é montado no container: edite e recarregue o navegador. Prefixo `hint_` em tudo que for próprio.
Não edite fora desta pasta.
