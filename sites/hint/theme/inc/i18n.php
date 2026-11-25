<?php
/**
 * HINT i18n helpers.
 *
 * The site is bilingual (Polylang: `en` default without prefix, `pt` under
 * /pt/). Two mechanisms coexist:
 *
 * 1. Interface strings use __( 'English source', 'hint' ) and are translated
 *    by languages/pt_BR.l10n.php (WordPress 6.5+ PHP translation format).
 * 2. Content that is authored in both languages at once (section titles,
 *    editorial copy) is passed to hint_pick() as array( 'pt' => ..., 'en' => ... ).
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load the theme translations from languages/<locale>.l10n.php (or .mo).
 *
 * Runs on after_setup_theme so Polylang has already set the request locale.
 */
function hint_load_textdomain() {
	load_theme_textdomain( 'hint', HINT_THEME_DIR . '/languages' );
}
add_action( 'after_setup_theme', 'hint_load_textdomain' );

/**
 * Current site language: 'pt' or 'en'.
 *
 * Uses Polylang when active; otherwise falls back to the WordPress locale.
 * Anything that is not Portuguese resolves to 'en', the default language.
 *
 * @return string 'pt'|'en'
 */
function hint_lang() {
	if ( function_exists( 'pll_current_language' ) ) {
		$slug = pll_current_language( 'slug' );

		if ( 'pt' === $slug || 'en' === $slug ) {
			return $slug;
		}
	}

	$locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();

	return 0 === strpos( (string) $locale, 'pt' ) ? 'pt' : 'en';
}

/**
 * Home URL in the current language, optionally with an in-page anchor.
 *
 * Polylang only rewrites home_url() in a few core contexts, so theme links
 * built with home_url() would drop the /pt/ prefix and send the visitor back
 * to English. pll_home_url() returns the home of the current language.
 *
 * @param string $anchor Optional section id, without '#'.
 * @return string
 */
function hint_home_url( $anchor = '' ) {
	$url = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );

	return $anchor ? $url . '#' . $anchor : $url;
}

/**
 * Pick the current-language value from a bilingual array.
 *
 * Accepts a plain string (returned as is) or array( 'pt' => ..., 'en' => ... ).
 * Falls back to 'en', then to the first available value, then to ''.
 *
 * @param string|array<string,mixed> $value Bilingual value.
 * @return mixed
 */
function hint_pick( $value ) {
	if ( ! is_array( $value ) ) {
		return $value;
	}

	$lang = hint_lang();

	if ( isset( $value[ $lang ] ) && '' !== $value[ $lang ] ) {
		return $value[ $lang ];
	}

	if ( isset( $value['en'] ) && '' !== $value['en'] ) {
		return $value['en'];
	}

	foreach ( $value as $fallback ) {
		if ( '' !== $fallback && null !== $fallback ) {
			return $fallback;
		}
	}

	return '';
}
