<?php
/**
 * Basic SEO: meta description, Open Graph and JSON-LD Organization on the home; favicon fallback.
 * The page <title> comes from WordPress (title-tag support in the parent theme).
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_head',
	function () {
		if ( ! has_site_icon() ) {
			$icon = ciatec_asset_url( 'brand/favicon.png' );
			if ( $icon ) {
				printf( '<link rel="icon" href="%s">' . "\n", esc_url( $icon ) );
			}
		}

		if ( ! is_front_page() ) {
			return;
		}

		$description = ciatec_t( 'meta_description' );
		$image       = ciatec_asset_url( 'brand/og-image.jpg' );
		$home        = ciatec_home_url();

		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
		echo '<meta property="og:type" content="website">' . "\n";
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $home ) );
		if ( $image ) {
			printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		}

		$org  = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Organization',
			'name'        => get_bloginfo( 'name' ),
			'url'         => $home,
			'email'       => CIATEC_EMAIL,
			'description' => $description,
		);
		$logo = ciatec_asset_url( 'brand/ciatec-logo-light.svg' );
		if ( $logo ) {
			$org['logo'] = $logo;
		}
		printf( '<script type="application/ld+json">%s</script>' . "\n", wp_json_encode( $org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded.
	}
);
