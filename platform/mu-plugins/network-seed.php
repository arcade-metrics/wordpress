<?php
/**
 * Plugin Name: Rede CIATec — helpers de seed
 * Description: Funções usadas por sites/<site>/seed.php (via `wp eval-file`). Não registra hooks.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Atualiza opções do site atual, só quando o valor muda (idempotente).
 *
 * @param array<string,mixed> $options
 */
function cnet_seed_options( array $options ): void {
	foreach ( $options as $name => $value ) {
		if ( ! cnet_same( get_option( $name ), $value ) ) {
			update_option( $name, $value );
			cnet_log( "opção {$name} atualizada" );
		}
	}
}

/**
 * Estrutura de links permanentes, com flush só quando muda.
 */
function cnet_seed_permalinks( string $structure ): void {
	global $wp_rewrite;
	if ( get_option( 'permalink_structure' ) !== $structure ) {
		$wp_rewrite->set_permalink_structure( $structure );
		cnet_log( "permalinks: {$structure}" );
	}
	flush_rewrite_rules( false );
}

/**
 * Cria os idiomas do Polylang que faltam e define o padrão.
 * A API interna do Polylang mudou entre versões; o helper tenta as conhecidas. Validar na F1 (spec 02).
 *
 * @param array<int,array{slug:string,locale:string,name:string,flag:string}> $languages Primeiro = padrão.
 * @param array<string,mixed> $settings Opções do Polylang a forçar (ex.: browser => 0).
 */
function cnet_seed_polylang( array $languages, array $settings = array() ): void {
	if ( ! function_exists( 'PLL' ) || ! PLL() ) {
		cnet_fail( 'Polylang não está ativo neste site.' );
	}
	$model = PLL()->model;

	$list = ( isset( $model->languages ) && method_exists( $model->languages, 'get_list' ) )
		? $model->languages->get_list()
		: $model->get_languages_list();
	$existing = wp_list_pluck( $list, 'slug' );

	foreach ( array_values( $languages ) as $order => $lang ) {
		if ( in_array( $lang['slug'], $existing, true ) ) {
			continue;
		}
		$args = array(
			'name'       => $lang['name'],
			'slug'       => $lang['slug'],
			'locale'     => $lang['locale'],
			'rtl'        => false,
			'term_group' => $order,
			'flag'       => $lang['flag'],
		);
		if ( isset( $model->languages ) && method_exists( $model->languages, 'add' ) ) {
			$result = $model->languages->add( $args );
		} elseif ( method_exists( $model, 'add_language' ) ) {
			$result = $model->add_language( $args );
		} else {
			$admin  = new PLL_Admin_Model( PLL()->options );
			$result = $admin->add_language( $args );
		}
		if ( is_wp_error( $result ) ) {
			cnet_fail( "Polylang: falha ao criar {$lang['slug']}: " . $result->get_error_message() );
		}
		cnet_log( "Polylang: idioma {$lang['slug']} criado" );
	}

	$wanted = array_merge( array( 'default_lang' => $languages[0]['slug'] ), $settings );

	// Idioma padrão pela API própria quando existe (como o antigo setup-polylang.sh).
	if ( isset( $model->languages ) && method_exists( $model->languages, 'update_default' ) ) {
		$current = $model->languages->get_default();
		if ( ! $current || $current->slug !== $wanted['default_lang'] ) {
			$model->languages->update_default( $wanted['default_lang'] );
			cnet_log( "Polylang: idioma padrão {$wanted['default_lang']}" );
		}
		unset( $wanted['default_lang'] );
	}
	cnet_polylang_set_options( $wanted );

	// O Polylang guarda a lista de idiomas (com as URLs de cada um) em cache: calculada antes de o idioma padrão
	// ser definido, ela fazia "/" redirecionar para "/pt/". Limpar e regerar as regras é barato e idempotente.
	if ( isset( $model->languages ) && method_exists( $model->languages, 'clean_cache' ) ) {
		$model->languages->clean_cache();
	} elseif ( method_exists( $model, 'clean_languages_cache' ) ) {
		$model->clean_languages_cache();
	}
	delete_transient( 'pll_languages_list' );
	flush_rewrite_rules( false );
}

/**
 * Grava traduções de strings do Polylang (Idiomas > Traduções), só quando mudam.
 * Mesmo mecanismo do antigo scripts/setup-polylang-strings.sh (PLL_MO).
 *
 * @param array<string,string>               $sources      chave => texto original (idioma padrão).
 * @param array<string,array<string,string>> $translations slug do idioma => (chave => tradução).
 */
function cnet_seed_polylang_strings( array $sources, array $translations ): void {
	foreach ( $translations as $slug => $strings ) {
		$language = PLL()->model->get_language( $slug );
		if ( ! $language ) {
			cnet_fail( "Polylang: idioma {$slug} não existe (rode cnet_seed_polylang antes)." );
		}
		$mo = new PLL_MO();
		$mo->import_from_db( $language );

		$changed = 0;
		foreach ( $strings as $key => $translated ) {
			if ( ! isset( $sources[ $key ] ) ) {
				cnet_fail( "Polylang: chave desconhecida em {$slug}: {$key}" );
			}
			if ( $mo->translate( $sources[ $key ] ) === $translated ) {
				continue;
			}
			$mo->add_entry( $mo->make_entry( $sources[ $key ], $translated ) );
			++$changed;
		}
		if ( $changed ) {
			$mo->export_to_db( $language );
			cnet_log( "Polylang: {$changed} strings traduzidas para {$slug}" );
		}
	}
}

/**
 * Grava opções do Polylang pela API dele, quando existir.
 *
 * @param array<string,mixed> $wanted
 */
function cnet_polylang_set_options( array $wanted ): void {
	$pll = PLL()->options;

	// Polylang >= 3.7: opções num objeto que se regrava no `shutdown` e bloqueia update_option direto.
	if ( is_object( $pll ) && method_exists( $pll, 'set' ) ) {
		$changed = false;
		foreach ( $wanted as $key => $value ) {
			$current = method_exists( $pll, 'get' ) ? $pll->get( $key ) : $pll[ $key ];
			if ( cnet_same( $current, $value ) ) {
				continue;
			}
			$error = $pll->set( $key, $value );
			if ( $error->has_errors() ) {
				cnet_fail( "Polylang: opção {$key}: " . $error->get_error_message() );
			}
			$changed = true;
		}
		if ( $changed ) {
			$pll->save();
			cnet_log( 'Polylang: opções atualizadas' );
		}
		return;
	}

	// Polylang antigo: opções num array simples.
	$options = get_option( 'polylang', array() );
	$changed = false;
	foreach ( $wanted as $key => $value ) {
		if ( ! isset( $options[ $key ] ) || ! cnet_same( $options[ $key ], $value ) ) {
			$options[ $key ] = $value;
			$changed         = true;
		}
	}
	if ( $changed ) {
		update_option( 'polylang', $options );
		cnet_log( 'Polylang: opções atualizadas' );
	}
}

/**
 * Compara valores como o banco os guarda: escalares viram texto ("0" == 0, "1" == true).
 */
function cnet_same( $stored, $wanted ): bool {
	if ( is_scalar( $stored ) && is_scalar( $wanted ) ) {
		$norm = static function ( $v ): string {
			return (string) ( is_bool( $v ) ? (int) $v : $v );
		};
		return $norm( $stored ) === $norm( $wanted );
	}
	return $stored === $wanted;
}

function cnet_log( string $message ): void {
	if ( class_exists( 'WP_CLI' ) ) {
		WP_CLI::log( '  ' . $message );
	}
}

function cnet_fail( string $message ): void {
	if ( class_exists( 'WP_CLI' ) ) {
		WP_CLI::error( $message );
	}
	wp_die( esc_html( $message ) );
}
