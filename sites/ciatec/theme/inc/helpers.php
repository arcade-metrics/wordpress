<?php
/**
 * Asset helpers: brand files, inline SVG icons.
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL of a file under assets/, or '' when the file is not there yet (placeholders).
 *
 * @param string $relative Path relative to assets/, e.g. 'brand/logo-ciatec-light.svg'.
 */
function ciatec_asset_url( $relative ) {
	$relative = ltrim( $relative, '/' );
	$path     = get_stylesheet_directory() . '/assets/' . $relative;
	return is_readable( $path ) ? get_stylesheet_directory_uri() . '/assets/' . $relative : '';
}

/**
 * Link to the home of the current language.
 */
function ciatec_home_url() {
	return function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
}

/**
 * Full logo file URL for the current language (tagline is part of the image).
 * Polylang slug => file suffix; PT-BR is the fallback. Returns '' while the file is missing.
 *
 * @param string $variant 'dark' (light text, for dark backgrounds) or 'light' (dark text, for light backgrounds).
 */
function ciatec_logo_url( $variant = 'dark' ) {
	$suffixes = array(
		'pt' => 'ptbr',
		'en' => 'en-uk',
		'es' => 'es',
	);
	$slug     = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : 'pt';
	$suffix   = isset( $suffixes[ $slug ] ) ? $suffixes[ $slug ] : 'ptbr';
	$url      = ciatec_asset_url( "brand/ciatec-logo-{$variant}-{$suffix}.svg" );
	return $url ? $url : ciatec_asset_url( "brand/ciatec-logo-{$variant}-ptbr.svg" );
}

/**
 * Header brand: logo without tagline (dark variant, the header is always dark); site name as text until the file exists.
 */
function ciatec_logo() {
	$src  = ciatec_asset_url( 'brand/ciatec-logo-dark.svg' );
	$name = get_bloginfo( 'name' );
	?>
	<a class="ciatec-logo" href="<?php echo esc_url( ciatec_home_url() ); ?>" rel="home">
		<?php if ( $src ) : ?>
			<img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $name ); ?>" width="154" height="48" decoding="async">
		<?php else : ?>
			<span class="ciatec-logo__text"><?php echo esc_html( $name ); ?></span>
		<?php endif; ?>
	</a>
	<?php
}

/**
 * Light/dark switch. Both icons are in the markup; CSS shows the one that applies. header.js wires it up.
 */
function ciatec_theme_toggle() {
	$label = ciatec_t( 'theme_dark' );
	?>
	<button type="button" class="ciatec-theme-toggle" aria-pressed="false" aria-label="<?php echo esc_attr( $label ); ?>" title="<?php echo esc_attr( $label ); ?>">
		<span class="ciatec-theme-toggle__moon"><?php ciatec_icon( 'moon' ); ?></span>
		<span class="ciatec-theme-toggle__sun"><?php ciatec_icon( 'sun' ); ?></span>
	</button>
	<?php
}

/**
 * Footer brand: full logo with tagline in the current language; falls back to the site name as text.
 */
function ciatec_footer_logo() {
	$src  = ciatec_logo_url( 'dark' );
	$name = get_bloginfo( 'name' );
	if ( $src ) {
		printf( '<img class="ciatec-footer__logo" src="%s" alt="%s" height="120">', esc_url( $src ), esc_attr( $name ) );
	} else {
		printf( '<p class="ciatec-footer__name">%s</p>', esc_html( $name ) );
	}
}

/**
 * Inline a Lucide icon (assets/icons/<name>.svg) as a decorative SVG.
 *
 * @param string $name Icon file name without extension.
 */
function ciatec_icon( $name ) {
	$file = get_stylesheet_directory() . '/assets/icons/' . sanitize_file_name( $name ) . '.svg';
	if ( ! is_readable( $file ) ) {
		return;
	}
	$svg = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
	$svg = preg_replace( '/<!--.*?-->/s', '', $svg );
	// Strip size/class from the root <svg> only; inner shapes (rect width/height) must stay.
	$svg = preg_replace_callback(
		'/<svg\s[^>]*>/',
		static function ( $m ) {
			$tag = preg_replace( '/\s(width|height|class)="[^"]*"/', '', $m[0] );
			return str_replace( '<svg', '<svg class="ciatec-icon" aria-hidden="true" focusable="false"', $tag );
		},
		$svg,
		1
	);
	echo trim( $svg ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- bundled theme asset.
}

/**
 * Apply the saved (or system) color scheme before first paint, so the page does not flash the wrong theme.
 */
add_action(
	'wp_head',
	function () {
		?>
<script>
( function () {
	var theme = 'light';
	try {
		theme = localStorage.getItem( 'ciatec-theme' ) || ( window.matchMedia( '(prefers-color-scheme: dark)' ).matches ? 'dark' : 'light' );
	} catch ( e ) {}
	document.documentElement.setAttribute( 'data-theme', theme );
} )();
</script>
		<?php
	},
	1
);
