# 21 — Site hint

- **Status:** rascunho
- **Agente responsável:** theme-hint
- **Depende de:** 00 (visão), 01 (arquitetura), 02 (plataforma), 03 (plugins e temas: Polylang)
- **Atualizado em:** 2026-11-25

## Objetivo

Evoluir o site `hint.ciatec.org` (HINT 2027, 25/02/2027, EACH-USP, São Paulo), já em produção, de página "em breve" para a
landing page completa do congresso: conteúdo real em português e inglês, programa com palestrantes, inscrição, chamada de
trabalhos, local e acessibilidade, com um painel de acessibilidade do próprio site. Atende participantes e autores, incluindo
pessoas com baixa visão, que são parte central do público do congresso.

Esta spec é da **fase 2 (evolução)**. A paridade da fase 1 está entregue; o tema importado de
`ciatec-org/hint-wordpress@b4034ae` é o ponto de partida.

## Escopo

### 1. Internacionalização PT/EN (Polylang)

- Idiomas como no `seed.php`: `en` padrão, sem prefixo; `pt` em `/pt/`. O seletor de idioma do header usa o Polylang.
- **Conteúdo editorial** (programa, minicurrículos, textos das seções) fica em arrays PHP com chaves `pt` e `en` no tema
  (`inc/sections/*.php`, um arquivo por seção), escolhidos por `hint_pick()` (`inc/i18n.php`, sobre
  `pll_current_language()` com fallback para `en`). O conteúdo nasce em português; o inglês é tradução editorial, não
  literal, em grafia britânica.
- **Strings de interface** (botões, rótulos do painel, `aria-label`) usam `__()`/`esc_html__()` com text domain `hint`,
  e a tradução pt_BR vive em `theme/languages/pt_BR.l10n.php` (formato PHP, sem compilar `.mo`).
- `<html lang>` correto em cada idioma; trechos em outro idioma (ex.: nomes de instituições) não precisam de `lang`.

### 2. Painel "Acessibilidade" no header

- Botão no header abre um painel (disclosure, não modal) com dois grupos independentes:
  - **Aparência:** claro, escuro, alto contraste.
  - **Tamanho do texto:** normal (100%), grande (125%), muito grande (150%).
- Independentes entre si: alto contraste com texto grande é uma combinação válida.
- **Implementação só em CSS + JS mínimo, sem plugin:** atributos `data-theme` e `data-text` no `<html>` sobrescrevem os tokens
  de `assets/css/tokens.css`. Tamanhos em `rem`; o nível de texto ajusta só o `font-size` raiz.
- **Persistência** em `localStorage` (chave `hint-a11y`), aplicada por script inline no `<head>` antes da primeira pintura,
  para não piscar. Com `localStorage` indisponível o site funciona com os padrões.
- **Padrão sem escolha salva:** respeita `prefers-color-scheme` (claro/escuro) e `prefers-contrast: more` (alto contraste).
- **Alto contraste é tema próprio:** fundo preto, texto branco, links sublinhados, contraste mínimo 7:1 (WCAG AAA) em todo
  texto e componente, foco com contorno amarelo de 3px, cards com borda no lugar de sombra, nenhum texto sobre imagem,
  e o vídeo do hero substituído por fundo preto sólido (o vídeo é ocultado e pausado; sem poster).
- **Movimento:** o vídeo do hero tem botão pausar/tocar (WCAG 2.2.2). Com `prefers-reduced-motion: reduce` ele começa
  pausado. Transições e animações também respeitam a preferência.
- **VLibras:** widget oficial do gov.br com o próprio botão flutuante, fora do painel; carregado de forma assíncrona e
  sem bloquear a renderização.
- Painel operável por teclado, fecha com Esc devolvendo o foco ao botão, `aria-expanded` e `aria-pressed` corretos,
  rótulos nos dois idiomas. No celular, entra no menu hambúrguer, ao lado do seletor de idioma.
- Layout fluido: sem alturas fixas, menu que quebra linha, programa em coluna única quando faltar largura.

### 3. Seções da home

Ordem e ids fixos (âncoras estáveis, usadas pelo menu e pelos CTAs):

| # | id | Conteúdo |
|---|---|---|
| 1 | `about` | Tema, objetivos, ODS 4, 9, 10 e 11 |
| 2 | `program` | Programa de 25/02/2027 por horário; palestrantes com foto e minicurrículo expansível |
| 3 | `registration` | Inscrição gratuita via Even3 |
| 4 | `papers` | Chamada de trabalhos, prazos e orientações de pôster |
| 5 | `venue` | Como chegar e onde ficar: endereço e mapa, formas de chegada, hospedagem, Conheça São Paulo |
| 6 | `accessibility` | Acessibilidade e inclusão no evento |
| 7 | `partners` | Organização e apoio (logos EACH, CIATec, PATER, FAPESP); fora do menu |

- A página "coming soon" (`inc/coming-soon.php`, rota `/coming-soon/`, classe `hint-coming-soon`) é **removida**, junto com
  seus usos em `functions.php`. Os CTAs do hero e do header levam a `#registration` (inscrição) e `#papers` (trabalhos).
- Minicurrículos usam `<details>/<summary>` (nativo, acessível por teclado, sem JS).

### 4. Conteúdo

Fontes: `sites/hint/docs/programa-v1.md` (descrições) e `sites/hint/docs/Hint site (1).docx.md` (brief e minicurrículos).
Onde divergem, vale o brief (mais recente); ver pontos em aberto.

- **Programa (25/02/2027), manhã:** 08:00 credenciamento; 08:30 abertura; 09:00 Vivência I (Jogos CIATec); 10:00 mesa redonda
  "Da ciência à vivência: avaliação, intervenção e inclusão na prática" (10:00 Carlos Bandeira de Mello Monteiro; 10:20
  Eduardo Dati Dias; 10:40 Ruth Caldeira de Mello; 11:00 Isabela Barbieri; 11:20 discussão, moderadora Talita Dias da
  Silva-Magalhães); 12:00 Vivência II; 12:30 almoço e apresentação dos resumos aceitos.
- **Tarde:** 13:30 mesa redonda DUA e inovação educacional (13:30 Eladio Sebastián Heredero; 13:50 Eniceia Mendes; 14:10
  Maraína Fernandes de Aguiar; 14:30 discussão, moderadora Mariana Moraes Lopes); 15:00 intervalo; 15:30 mesa redonda IA
  aplicada a jogos sérios (15:30 José Eduardo Santarem Segundo; 15:50 Fátima de Lourdes dos Santos Nunes Marques; 16:10
  Helen Dawes e Talita Dias da Silva-Magalhães; 16:30 "Do genoma ao smartphone: biomarcadores digitais em doenças neurogenéticas", Andrea Németh e Talita Dias da Silva-Magalhães; 16:50 discussão, moderador Íbis Ariana Pena de Moraes); 17:30 encerramento.
- **14 palestrantes e moderadores, cada um com foto** (`docs/Palestrantes/`, otimizada para web, em `theme/assets/speakers/`)
  e minicurrículo PT/EN: Carlos Bandeira de Mello Monteiro, Eduardo Dati Dias, Ruth Caldeira de Mello, Isabela Barbieri,
  Talita Dias da Silva-Magalhães, Eladio Sebastián Heredero, Eniceia Mendes, Maraína Fernandes de Aguiar, Mariana Moraes
  Lopes, José Eduardo Santarem Segundo, Fátima de Lourdes dos Santos Nunes Marques, Helen Dawes, Íbis Ariana Pena de Moraes.
  Toda foto com `alt` (nome e papel).
- **Inscrição:** gratuita, **até 18/02/2027, sujeita à disponibilidade de vagas**, pelo widget Even3
  (`even3-widget-ticket`, script do Even3, evento `international-congress-of-human-centred-inclusive-new-technologies-hint-2027-791040`),
  com link de apoio para quem não carrega o script. O formulário pergunta por recursos de acessibilidade necessários.
- **Trabalhos (CFP):** submissão do resumo até **31/01/2027**, aceite até **15/02/2027**, pelo Even3
  (`even3.com.br/organizador/trabalhocientifico/inserirsubmissao/`); modelos de resumo já em `assets/downloads/`.
  Apresentação oral com pôster impresso **A0** (84,1 x 118,9 cm, retrato), no almoço (pitch), com certificado de
  apresentação oral; conteúdo, legibilidade e preparação do arquivo conforme o brief; link do modelo de banner no Canva.
- **Logos:** EACH-USP, CIATec, PATER-USP e FAPESP, em SVG embutido no HTML (`assets/brand/partners/<slug>.svg`), com nome
  acessível. As partes monocromáticas usam `currentColor`: pretas no tema claro (FAPESP mantém o azul `#324B89`) e
  brancas no escuro e no alto contraste; as partes coloridas não mudam.
- **Local:** EACH-USP, **Auditório Vermelho**, Rua Arlindo Béttio, 1000, Ermelino Matarazzo, São Paulo - SP, CEP 03828-000;
  link para o mapa e mapa embutido do Google Maps (`loading="lazy"`, sem chave de API). Abaixo, na mesma seção:
  "Planeje sua chegada" (quatro painéis `<details>`: trem e metrô, avião, ônibus, carro e aplicativo); "Onde ficar"
  (regiões Tatuapé e Guarulhos com links de busca no Booking e no Airbnb, sem citar hotéis e com aviso de que não há
  parceria); "Conheça São Paulo" (links para fontes oficiais de turismo). Linhas de ônibus, trajetos e estacionamento
  são revisados pela organização antes da publicação.
  As buscas de hospedagem já abrem com entrada em 24/02 e saída em 26/02/2027; cada link de "Conheça São Paulo" tem um
  ícone ilustrativo próprio (SVG), sem fotos nem logos de terceiros.
- **Salvar na agenda** (em Inscrições): link pré-preenchido do Google Calendar e arquivo `assets/downloads/hint-2027.ics`
  (Apple, Outlook e outros), ambos para 25/02/2027, das 8h às 18h (horário de Brasília; o encerramento começa às 17h30).
- **Trabalhos:** os dois prazos abrem a seção em cartões grandes, com o de submissão em destaque. "Áreas temáticas"
  fica sempre visível, com "Em breve" até a organização definir a lista. Todo o bloco "Orientações para apresentação
  dos trabalhos" fica recolhido em um único `<details>`.
- **Salvar na agenda:** os botões levam o ícone da marca (Google e Apple) em uma cor só, seguindo o tema.
- **Acessibilidade:** a seção não descreve as ferramentas do próprio site (painel e VLibras).
- **Acessibilidade:** texto do brief (ambiente inclusivo, campo de recursos no formulário, estacionamento com vagas
  especiais).

## Fora do escopo

- Migrar para Elementor, plugin de acessibilidade ou construtor de páginas (a evolução prevê tirar o Elementor, spec 03).
- Blog, notícias, área logada, pagamento (evento gratuito).
- Idiomas além de PT e EN; tradução automática.
- Plataforma, Nginx, deploy e backup (specs 02, 04, 08).
- Hospedar o formulário de inscrição ou submissão: continua no Even3.

## Decisões

- **Tudo no tema, sem plugin de acessibilidade** — leve, reproduzível pelo repositório e cobre o conteúdo próprio, o que
  plugins costumam fazer mal (princípios 1 e 2 da spec 00).
- **Temas por atributos no `<html>` e tokens CSS** — o tema já usa `tokens.css`; trocar tokens é barato e as seções novas
  nascem obedecendo.
- **Texto em `rem` e escala no `font-size` raiz** — os três níveis viram um ajuste único; o critério de aceite é 150%.
- **Conteúdo em arrays `pt`/`en` no tema, UI via `__()`** — não há banco de conteúdo (spec 00); strings de interface
  seguem o padrão WordPress e o `.l10n.php` evita compilar `.mo`.
- **VLibras fora do painel** — é padrão gov.br, reconhecido por quem usa.
- **Alto contraste substitui o vídeo por fundo preto sólido** — vídeo atrás de texto não passa de contraste e é movimento;
  um poster traria imagem atrás do texto, por isso o hero fica preto, com o vídeo oculto e pausado.
- **Painel é disclosure, não modal** — `aria-expanded`/`aria-controls` no botão, painel como `role="group"`; o foco pode
  sair com Tab (o painel fecha) e Esc devolve o foco ao botão.
- **Links de conteúdo em cor de texto** — o kit do Elementor pinta todo `a` em ciano; nas seções o link usa a cor do texto e
  só o hover usa o token da marca (regra em `base.css`). Cabeçalhos e `<p>` das seções levam o prefixo `.hint-home` para
  vencer as regras em px do kit.
- **`<details>` para minicurrículos** — acessível e sem JavaScript.
- **Seed:** `sites/hint/seed.php` continua configurando só idiomas e opções; nenhum conteúdo no banco.

## Interfaces

- `sites/hint/theme/front-page.php`: `inc/home-hero.php` (hero, `<h1>`) e `inc/home-sections.php`, que carrega
  `inc/sections/<nome>.php` na ordem de `hint_get_home_sections()`, com ids `about`, `program`, `registration`, `papers`,
  `venue`, `accessibility`, `partners`; helpers em `inc/sections/shared.php`.
- `sites/hint/theme/inc/sections/*.php`: conteúdo pt/en de cada seção (programa e palestrantes em `program.php`);
  `inc/i18n.php`: `hint_lang()` e `hint_pick()`.
- `sites/hint/theme/inc/header.php` (`hint_render_a11y_panel()`): painel de acessibilidade; `assets/js/a11y.js`: estado,
  persistência e controle do vídeo; temas em `assets/css/tokens.css` (tokens por `data-theme`/`data-text`), `base.css`
  (regras globais dos modos) e `header.css` (chrome).
- `sites/hint/theme/assets/css/sections.css` + `assets/css/sections/<nome>.css`: estilos das seções.
- `sites/hint/theme/languages/pt_BR.l10n.php`: tradução das strings de interface.
- `sites/hint/theme/assets/speakers/` e `assets/brand/partners/`: fotos e logos; `assets/downloads/`: modelos de resumo.
- Atributos `data-theme` (`light` | `dark` | `contrast`) e `data-text` (`1` | `2` | `3`, 100% / 125% / 150%) no `<html>`;
  `localStorage["hint-a11y"]` = `{"theme":…,"text":…}`.
- Domínios externos: `www.even3.com.br` (widget e submissão), `vlibras.gov.br` (widget), `canva.com` (link),
  `www.google.com/maps` (iframe do mapa); links de saída para CPTM, GRU Airport, SPTrans, EACH, Booking, Airbnb e sites
  oficiais de turismo.
- Removidos: `inc/coming-soon.php` e a rota `/coming-soon/`.

## Critérios de aceite

- [ ] CA1 — Sintaxe PHP limpa. **Como verificar:** `find sites/hint -name '*.php' -exec php -l {} \;` sem erros
- [ ] CA2 — Setup e smoke test passam. **Como verificar:** `bash scripts/setup-local.sh && bash scripts/smoke-test.sh`
- [ ] CA3 — `/` e `/pt/` renderizam as 7 seções, em ordem, com ids corretos. **Como verificar:**
      `curl -s http://hint.ciatec.localhost/ | grep -oE 'id="(about|program|registration|papers|venue|accessibility|partners)"'` (idem em `/pt/`)
- [ ] CA4 — Cada idioma mostra só o seu texto (sem português em `/` nem inglês em `/pt/`, exceto nomes próprios) e
      `<html lang>` correto. **Como verificar:** inspeção das duas páginas e `curl -s … | grep -o '<html[^>]*>'`
- [ ] CA5 — Contraste AAA (7:1) em todo texto e controle no modo alto contraste, com foco amarelo de 3px visível e vídeo
      substituído por fundo sólido. **Como verificar:** axe/Lighthouse com `data-theme="contrast"` e screenshot
- [ ] CA6 — Layout íntegro com texto "muito grande" (150%) em 360, 768 e 1280 px: sem sobreposição, sem corte, sem rolagem
      horizontal. **Como verificar:** screenshots nas três larguras
- [ ] CA7 — Toda imagem tem `alt` (decorativas com `alt=""`). **Como verificar:** `curl -s … | grep -oP '<img(?![^>]*\balt=)[^>]*>'`
      sem resultados, nos dois idiomas
- [ ] CA8 — Painel operável só por teclado, com `aria-expanded`/`aria-pressed` corretos, fecha com Esc e devolve o foco.
      **Como verificar:** teste manual com Tab, Enter/Espaço e Esc
- [ ] CA9 — Escolhas persistem entre recarregamentos sem flash do tema errado; sem escolha, valem `prefers-color-scheme` e
      `prefers-contrast`. **Como verificar:** emulação de preferências no DevTools e recarga com `localStorage` limpo e preenchido
- [ ] CA10 — Vídeo do hero com botão pausar/tocar; com `prefers-reduced-motion: reduce` começa pausado. **Como verificar:**
      emulação no DevTools
- [ ] CA11 — Nenhum link para `/coming-soon/` e o arquivo `inc/coming-soon.php` não existe. **Como verificar:**
      `grep -ri 'coming-soon' sites/hint` sem resultados
- [ ] CA12 — Conteúdo conferido contra o brief: 14 palestrantes com foto e minicurrículo, horários do programa, datas
      (18/02, 31/01, 15/02/2027), pôster A0, Auditório Vermelho, logos EACH/CIATec/FAPESP. **Como verificar:** revisão
      lado a lado com `docs/Hint site (1).docx.md`
- [ ] CA13 — CTAs do hero e do header levam a `#registration` e `#papers`; o widget Even3 carrega e o link de apoio funciona.
      **Como verificar:** clique nos CTAs e no widget
- [ ] CA14 — VLibras carrega sem bloquear a renderização e seu botão não sobrepõe o painel nem o menu. **Como verificar:**
      screenshot em desktop e celular
- [ ] CA15 — Fotos de palestrantes otimizadas (cada uma abaixo de ~150 KB). **Como verificar:** `ls -l sites/hint/theme/assets/speakers`

## Tarefas

| # | Tarefa | Agente | Depende de humano? |
|---|---|---|---|
| 1 | Aprovar esta spec e resolver os pontos em aberto de conteúdo | humano | sim |
| 2 | Conteúdo: arrays pt/en (programa, palestrantes, textos) e `languages/pt_BR.l10n.php` | theme-hint | não |
| 3 | Otimizar fotos e preparar logos (`assets/speakers/`, `assets/brand/`) | theme-hint | sim (logos oficiais) |
| 4 | Painel de acessibilidade, tokens e temas (`a11y.css/js`), vídeo com pausa, VLibras | theme-hint | não |
| 5 | Seções da home, remoção do coming-soon, CTAs | theme-hint | não |
| 6 | Verificar CA1 a CA15 e abrir PR (impacto em produção e reversão descritos) | theme-hint, reviewer | revisão e merge humanos |

## Riscos e pontos em aberto

- **Logos EACH, CIATec e FAPESP:** estão só embutidos (base64) no brief; é preciso extrair ou receber os arquivos originais,
  de preferência em SVG, e confirmar as regras de uso da marca FAPESP.
- **Divergências entre as fontes:** a palestra das 11:00 é "Inteligência artificial na linguagem de sinais" em
  `programa-v1.md` e "Tecnologia Assistiva e Acessibilidade em Libras" no brief; a palestrante da tarde aparece como
  "Maraina" e "Maraína". Proposta: seguir o brief; confirmar. O brief também traz notas internas ("Próxima: verificar
  online", "Material?") que **não** vão ao site.
- **Data de abertura da inscrição:** o brief diz "31/10/27" (provável erro de digitação). Proposta: não exibir data de
  abertura, só o prazo de 18/02/2027.
- **Nome do moderador da última mesa:** "Íbis Ariane Penã de Moraes" no programa e "Íbis Ariana Pena de Moraes" no brief;
  usar o do brief.
- **Minicurrículo da Isabela Barbieri** é curto e traz "Bolsista de Treinamento Técnico" como linha solta; revisar o texto
  final com ela.
- **Prazos do CFP** (31/01 e 15/02/2027) e o rótulo "pitch no almoço" precisam de confirmação da organização; o brief
  fala em "apresentação oral com pôster" no almoço.
- **Widget Even3 e VLibras** são scripts de terceiros: dependem de disponibilidade externa, podem ter acessibilidade própria
  fora do nosso controle (inclusive contraste em alto contraste) e exigem ajuste de CSP, se houver. Manter o link de apoio.
- **Peso do site** (vídeo de 1,8 MB mais 13 fotos): manter lazy-loading e dimensões nas imagens.
- **Tradução EN** é editorial, feita por agente: pedir revisão humana antes do merge.
- Mudança no tema publica em produção quando for ao `main`: o PR precisa descrever impacto e reversão (reverter o merge).
