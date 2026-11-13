<?php
/**
 * HINT theme functions — child theme of Hello Elementor.
 *
 * Provides visual infrastructure only. Pages are built in Elementor.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HINT_THEME_VERSION', '1.9.0' );
define( 'HINT_THEME_DIR', get_stylesheet_directory() );
define( 'HINT_THEME_URI', get_stylesheet_directory_uri() );

require_once HINT_THEME_DIR . '/inc/elementor-kit.php';
require_once HINT_THEME_DIR . '/inc/header.php';
require_once HINT_THEME_DIR . '/inc/footer.php';
require_once HINT_THEME_DIR . '/inc/home-hero.php';
require_once HINT_THEME_DIR . '/inc/home-sections.php';
require_once HINT_THEME_DIR . '/inc/coming-soon.php';

/**
 * Enqueue HINT design-system styles after Hello Elementor.
 *
 * Load order:
 * 1. hello-elementor reset / theme
 * 2. hint tokens
 * 3. hint base
 * 4. hint components
 * 5. hint header
 * 6. hint footer
 * 7. hint home / sections (front page and placeholder page)
 */
function hint_enqueue_assets() {
	$css_uri = HINT_THEME_URI . '/assets/css';
	$parent  = array( 'hello-elementor', 'hello-elementor-theme-style' );

	wp_enqueue_style(
		'hint-tokens',
		$css_uri . '/tokens.css',
		$parent,
		HINT_THEME_VERSION
	);

	wp_enqueue_style(
		'hint-base',
		$css_uri . '/base.css',
		array( 'hint-tokens' ),
		HINT_THEME_VERSION
	);

	wp_enqueue_style(
		'hint-components',
		$css_uri . '/components.css',
		array( 'hint-base' ),
		HINT_THEME_VERSION
	);

	wp_enqueue_style(
		'hint-header',
		$css_uri . '/header.css',
		array( 'hint-components' ),
		HINT_THEME_VERSION
	);

	wp_enqueue_style(
		'hint-footer',
		$css_uri . '/footer.css',
		array( 'hint-header' ),
		HINT_THEME_VERSION
	);

	if ( is_front_page() ) {
		wp_enqueue_style(
			'hint-home',
			$css_uri . '/home.css',
			array( 'hint-header' ),
			HINT_THEME_VERSION
		);
	}

	if ( is_front_page() || hint_is_coming_soon() ) {
		wp_enqueue_style(
			'hint-sections',
			$css_uri . '/sections.css',
			array( 'hint-header' ),
			HINT_THEME_VERSION
		);
	}

	wp_enqueue_script(
		'hint-header',
		HINT_THEME_URI . '/assets/js/header.js',
		array(),
		HINT_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'hint_enqueue_assets', 20 );

/**
 * Body classes for fixed header and front-page hero layout.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function hint_body_classes( $classes ) {
	$classes[] = 'hint-fixed-header';

	if ( is_front_page() ) {
		$classes[] = 'hint-front';
	}

	if ( hint_is_coming_soon() ) {
		$classes[] = 'hint-coming-soon';
	}

	return $classes;
}
add_filter( 'body_class', 'hint_body_classes' );

/**
 * Also load design tokens inside the Elementor editor preview.
 */
function hint_enqueue_editor_assets() {
	wp_enqueue_style(
		'hint-tokens-editor',
		HINT_THEME_URI . '/assets/css/tokens.css',
		array(),
		HINT_THEME_VERSION
	);

	wp_enqueue_style(
		'hint-components-editor',
		HINT_THEME_URI . '/assets/css/components.css',
		array( 'hint-tokens-editor' ),
		HINT_THEME_VERSION
	);
}
add_action( 'elementor/editor/after_enqueue_styles', 'hint_enqueue_editor_assets' );
add_action( 'elementor/preview/enqueue_styles', 'hint_enqueue_editor_assets' );

/**
 * Expose favicon from theme assets without writing to the database.
 * Site Icon in Customizer still takes precedence when set.
 */
function hint_favicon() {
	if ( has_site_icon() ) {
		return;
	}

	$png = hint_asset_url( 'brand/favicom.png' );

	printf(
		'<link rel="icon" href="%s" type="image/png" />' . "\n",
		esc_url( $png )
	);
	printf(
		'<link rel="apple-touch-icon" href="%s" />' . "\n",
		esc_url( $png )
	);
	printf(
		'<link rel="shortcut icon" href="%s" type="image/png" />' . "\n",
		esc_url( $png )
	);
}
add_action( 'wp_head', 'hint_favicon', 2 );
add_action( 'admin_head', 'hint_favicon', 2 );

/**
 * Public helpers for asset URLs (usable later in Elementor HTML widgets).
 *
 * @param string $relative Path under assets/, e.g. images/logo-hint-2027.svg.
 * @return string
 */
function hint_asset_url( $relative ) {
	return trailingslashit( HINT_THEME_URI . '/assets' ) . ltrim( $relative, '/' );
}
