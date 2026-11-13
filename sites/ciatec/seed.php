<?php
/**
 * Configuração do site ciatec.org no banco. Roda com:
 *   wp eval-file /opt/sites/ciatec/seed.php --url=<domínio do site>
 * (chamado por platform/bin/network-setup.sh). Idempotente.
 *
 * Fase 1 (paridade): valores copiados da rede antiga (banco local ciatec-wordpress-dev, 2026-11-13).
 * Os textos da página não ficam aqui: estão no tema (theme/inc/strings*.php).
 */

cnet_seed_options(
	array(
		'blogname'        => 'CIATec',
		'blogdescription' => '',
		'date_format'     => 'F j, Y',
	)
);

// A rede antiga usava /%year%/%monthnum%/%day%/%postname%/; o site não tem posts, então o efeito visível é nulo.
cnet_seed_permalinks( '/%postname%/' );

// Igual à rede antiga: en (en_US, bandeira us), pt padrão sem prefixo, es.
cnet_seed_polylang(
	array(
		array( 'slug' => 'en', 'locale' => 'en_US', 'name' => 'English',   'flag' => 'us' ),
		array( 'slug' => 'pt', 'locale' => 'pt_BR', 'name' => 'Português', 'flag' => 'br' ),
		array( 'slug' => 'es', 'locale' => 'es_ES', 'name' => 'Español',   'flag' => 'es' ),
	),
	array(
		'default_lang'  => 'pt',
		'browser'       => 0,
		'hide_default'  => 1,
		'force_lang'    => 1,
		'rewrite'       => 1,
		'redirect_lang' => 0,
	)
);

// Traduções do tema (antes: scripts/setup-polylang-strings.sh do repositório antigo).
// O português é o texto original (inc/strings.php); en/es vêm de inc/strings-en.php e strings-es.php.
require_once get_stylesheet_directory() . '/inc/strings-en.php';
require_once get_stylesheet_directory() . '/inc/strings-es.php';
cnet_seed_polylang_strings(
	ciatec_strings(),
	array(
		'en' => ciatec_strings_en(),
		'es' => ciatec_strings_es(),
	)
);
