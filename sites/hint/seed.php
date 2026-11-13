<?php
/**
 * Configuração do site hint.ciatec.org no banco. Roda com:
 *   wp eval-file /opt/sites/hint/seed.php --url=<domínio do site>
 * (chamado por platform/bin/network-setup.sh). Idempotente.
 *
 * Fase 1 (paridade): valores copiados do HINT standalone (banco local hintconference-web, 2026-11-13).
 * Lá o idioma padrão era en_GB e havia pt_BR pelo TranslatePress, sem nenhuma tradução preenchida:
 * o site era, na prática, só em inglês. Os textos estão no tema.
 */

cnet_seed_options(
	array(
		'blogname'        => 'HINT - International Congress of Human-centred Inclusive New Technologies',
		'blogdescription' => '',
		'timezone_string' => 'Europe/London',
		'date_format'     => 'j F Y',
		'WPLANG'          => 'en_GB', // pacote en_GB vem na imagem
	)
);

// O standalone usava links simples; o Polylang precisa de links permanentes para /pt/.
cnet_seed_permalinks( '/%postname%/' );

// Igual ao standalone: inglês (en_GB) padrão, sem prefixo; português em /pt/.
cnet_seed_polylang(
	array(
		array( 'slug' => 'en', 'locale' => 'en_GB', 'name' => 'English',   'flag' => 'gb' ),
		array( 'slug' => 'pt', 'locale' => 'pt_BR', 'name' => 'Português', 'flag' => 'br' ),
	),
	array(
		'default_lang'  => 'en',
		'browser'       => 0,
		'hide_default'  => 1,
		'force_lang'    => 1,
		'rewrite'       => 1,
		'redirect_lang' => 0,
	)
);
