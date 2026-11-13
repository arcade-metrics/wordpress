<?php
/**
 * Page copy (PT-BR source) registered in Polylang so EN/ES can be translated later
 * in Languages > Translations (group "CIATec"). Use ciatec_t( 'key' ) in templates.
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

define( 'CIATEC_EMAIL', 'educacao.ciatec@usp.br' );

/**
 * Social profiles shown in the contact section. Fill in each URL; an empty one renders as an inert icon.
 */
function ciatec_social_links() {
	return apply_filters(
		'ciatec_social_links',
		array(
			'facebook'  => array( 'Facebook', '' ),
			'instagram' => array( 'Instagram', '' ),
			'youtube'   => array( 'YouTube', '' ),
			'linkedin'  => array( 'LinkedIn', '' ),
			'github'    => array( 'GitHub', '' ),
		)
	);
}

/**
 * Source strings, keyed.
 */
function ciatec_strings() {
	return array(
		'nav_home'         => 'Início',
		'nav_about'        => 'Quem Somos',
		'nav_projects'     => 'Projetos',
		'nav_contact'      => 'Contato',
		'menu_toggle'      => 'Menu',
		'lang_label'       => 'Idioma',
		'theme_dark'       => 'Tema escuro',
		'video_pause'      => 'Pausar vídeo de fundo',
		'video_play'       => 'Reproduzir vídeo de fundo',
		'social_label'     => 'CIATec no %s',
		'social_soon'      => '%s (em breve)',
		'hero_title'       => 'Tecnologia e Inteligência Artificial a Serviço da Inclusão Escolar',
		'hero_subtitle'    => 'O CIATec (Centro de Inclusão e Acessibilidade por meio da Tecnologia) une ciência e inovação para transformar a educação escolar.',
		'about_title'      => 'Quem Somos',
		'about_text'       => 'O CIATec desenvolve uma plataforma digital de jogos educacionais adaptativos guiados por Inteligência Artificial, voltada à equidade e baseada nos princípios do Desenho Universal para Aprendizagem (DUA). A iniciativa atende estudantes do ensino fundamental da rede pública, com atenção especial a estudantes com Transtorno do Espectro Autista (TEA).',
		'mission_label'    => 'Missão',
		'mission_text'     => 'Criar soluções digitais gratuitas, fáceis de usar e baseadas em evidências científicas, para ampliar o acesso, a participação e a aprendizagem de todos os estudantes no currículo escolar.',
		'pillar_1_title'   => 'Acessibilidade',
		'pillar_1_text'    => 'Materiais multimodais baseados no DUA, com múltiplas formas de acesso, participação e aprendizagem.',
		'pillar_2_title'   => 'Educação Inclusiva e Equidade',
		'pillar_2_text'    => 'Alinhamento com as políticas públicas de inclusão escolar.',
		'pillar_3_title'   => 'Saúde & IA',
		'pillar_3_text'    => 'Algoritmos inteligentes focados na análise de movimentos e no bem-estar humano.',
		'projects_title'   => 'Projetos em andamento',
		'project_1_title'  => 'Plataforma Aberta de Jogos Adaptativos',
		'project_1_text'   => 'Jogos educativos alinhados ao currículo e planejados segundo o DUA, incluindo jogos colaborativos (Jogando Juntos) e uma IA personalizada que acompanha atenção, memória, raciocínio lógico, planejamento, resolução de problemas e autorregulação, ajustando os desafios em tempo real a cada criança.',
		'project_2_title'  => 'Ferramentas de Apoio para Educadores',
		'project_2_text'   => 'Relatórios simples e automatizados que mapeiam potencialidades e dificuldades dos estudantes, apoiando o planejamento de aulas e as políticas públicas.',
		'project_3_title'  => 'Ciência Aberta e Tecnologia Acessível',
		'project_3_text'   => 'Roda em computadores comuns, celulares, tablets ou webcams que escolas e famílias já têm, com código aberto e gratuito para qualquer instituição pública ou social do Brasil adaptar.',
		'project_4_title'  => 'Pesquisa de Interação e Bem-Estar Escolar',
		'project_4_text'   => 'Estudos sobre o impacto das ferramentas digitais no bem-estar físico e emocional de pessoas neurodivergentes e com deficiência, com escuta ativa de cada usuário.',
		'project_5_title'  => 'Metodologia Científica e Diretrizes Pedagógicas',
		'project_5_text'   => 'DUA, os quatro pilares da UNESCO (aprender a conhecer, a fazer, a conviver e a ser), aprendizagem baseada em jogos e avaliação baseada em evidências.',
		'hint_region'      => 'Evento: HINT 2027',
		'hint_alt'         => 'HINT 2027, Congresso Internacional de Tecnologias Inclusivas e Centradas no Ser Humano. 25 de fevereiro de 2027, EACH, Universidade de São Paulo, São Paulo, Brasil. Abre o site do congresso em nova janela.',
		'contact_title'    => 'Contato',
		'contact_text'     => 'Tem dúvidas, quer conhecer nossas pesquisas ou deseja colaborar com o CIATec? Escreva para nós.',
		'footer_inst'      => 'Escola de Artes, Ciências e Humanidades (EACH-USP)',
		'meta_description' => 'O CIATec (Centro de Inclusão e Acessibilidade por meio da Tecnologia) une ciência e inovação para transformar a educação escolar.',
	);
}

add_action(
	'init',
	function () {
		if ( ! function_exists( 'pll_register_string' ) ) {
			return;
		}
		foreach ( ciatec_strings() as $key => $text ) {
			pll_register_string( $key, $text, 'CIATec', strlen( $text ) > 60 );
		}
	}
);

/**
 * Translated string for the current language (PT-BR source when Polylang is inactive).
 *
 * @param string $key Key from ciatec_strings().
 */
function ciatec_t( $key ) {
	$strings = ciatec_strings();
	$text    = isset( $strings[ $key ] ) ? $strings[ $key ] : '';
	return function_exists( 'pll__' ) ? pll__( $text ) : $text;
}
