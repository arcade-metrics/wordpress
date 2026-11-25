<?php
/**
 * HINT theme functions — child theme of Hello Elementor.
 *
 * The site is a one-page landing rendered in PHP (front-page.php: hero in
 * inc/home-hero.php, sections in inc/sections/*.php, header and footer in
 * inc/), bilingual through Polylang (inc/i18n.php), with the accessibility
 * panel and themes in assets/css/tokens.css + assets/js/a11y.js. Elementor
 * only supplies the global kit (inc/elementor-kit.php).
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HINT_THEME_VERSION', '1.15.0' );
define( 'HINT_THEME_DIR', get_stylesheet_directory() );
define( 'HINT_THEME_URI', get_stylesheet_directory_uri() );

require_once HINT_THEME_DIR . '/inc/i18n.php';
require_once HINT_THEME_DIR . '/inc/elementor-kit.php';
require_once HINT_THEME_DIR . '/inc/header.php';
require_once HINT_THEME_DIR . '/inc/footer.php';
require_once HINT_THEME_DIR . '/inc/home-hero.php';
require_once HINT_THEME_DIR . '/inc/home-sections.php'; // loads inc/sections/*.php.

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
 * 7. hint home (hero) and sections (shared), then one file per section
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

	if ( is_front_page() ) {
		wp_enqueue_style(
			'hint-sections',
			$css_uri . '/sections.css',
			array( 'hint-header' ),
			HINT_THEME_VERSION
		);

		foreach ( hint_get_home_sections() as $section ) {
			wp_enqueue_style(
				'hint-section-' . $section,
				$css_uri . '/sections/' . $section . '.css',
				array( 'hint-sections' ),
				HINT_THEME_VERSION
			);
		}
	}

	wp_enqueue_script(
		'hint-header',
		HINT_THEME_URI . '/assets/js/header.js',
		array(),
		HINT_THEME_VERSION,
		true
	);

	// Accessibility panel (appearance, text size) and hero video control.
	wp_enqueue_script(
		'hint-a11y',
		HINT_THEME_URI . '/assets/js/a11y.js',
		array(),
		HINT_THEME_VERSION,
		true
	);

	// VLibras (Brazilian Sign Language) official widget, front-end only.
	// Deferred so it never blocks parsing; WordPress (>= 6.4) runs the inline
	// init after a deferred script has loaded.
	if ( hint_show_vlibras() ) {
		wp_enqueue_script(
			'hint-vlibras',
			'https://vlibras.gov.br/app/vlibras-plugin.js',
			array(),
			null, // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- external, unversioned.
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
		wp_add_inline_script( 'hint-vlibras', "new window.VLibras.Widget('https://vlibras.gov.br/app');" );
	}
}
add_action( 'wp_enqueue_scripts', 'hint_enqueue_assets', 20 );

/**
 * Apply the saved accessibility preference before the first paint.
 *
 * Mirrors the logic in assets/js/a11y.js: a choice saved in localStorage
 * (`hint-a11y`) wins; otherwise the system preferences decide
 * (prefers-contrast: more → contrast, prefers-color-scheme: dark → dark).
 * Printed at wp_head priority 0, before the stylesheets, so there is no flash.
 */
function hint_print_a11y_boot_script() {
	$script = "(function(){try{var d=document.documentElement,s={},m=window.matchMedia;try{s=JSON.parse(localStorage.getItem('hint-a11y')||'{}')||{}}catch(e){s={}}var t=['light','dark','contrast'].indexOf(s.theme)!==-1?s.theme:'';var x=['1','2','3'].indexOf(s.text)!==-1?s.text:'1';if(!t){t=(m&&m('(prefers-contrast: more)').matches)?'contrast':((m&&m('(prefers-color-scheme: dark)').matches)?'dark':'light')}d.setAttribute('data-theme',t);d.setAttribute('data-text',x)}catch(e){}})();";

	if ( function_exists( 'wp_print_inline_script_tag' ) ) {
		wp_print_inline_script_tag( $script, array( 'id' => 'hint-a11y-boot' ) );
		return;
	}

	echo '<script id="hint-a11y-boot">' . $script . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static script.
}
add_action( 'wp_head', 'hint_print_a11y_boot_script', 0 );

/**
 * Whether to load the VLibras widget: public front-end only, never in the
 * admin, the Elementor editor or its preview, and never for feeds/embeds.
 *
 * @return bool
 */
function hint_show_vlibras() {
	if ( is_admin() || is_feed() || is_embed() ) {
		return false;
	}

	if ( isset( $_GET['elementor-preview'] ) || isset( $_GET['elementor_library'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only context check.
		return false;
	}

	return (bool) apply_filters( 'hint_show_vlibras', true );
}

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
