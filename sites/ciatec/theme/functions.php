<?php
/**
 * CIATec child theme (parent: hello-elementor).
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

define( 'CIATEC_THEME_VERSION', '0.7.0' );

require_once get_stylesheet_directory() . '/inc/helpers.php';
require_once get_stylesheet_directory() . '/inc/strings.php';
require_once get_stylesheet_directory() . '/inc/seo.php';
require_once get_stylesheet_directory() . '/inc/language-switcher.php';

add_action(
	'wp_enqueue_scripts',
	function () {
		$uri = get_stylesheet_directory_uri() . '/assets';

		// Load order: parent, tokens, base, components, header/footer, home.
		$styles = array(
			'ciatec-style'         => array( get_stylesheet_uri(), array( 'hello-elementor' ) ),
			'ciatec-tokens'        => array( $uri . '/css/tokens.css', array( 'ciatec-style' ) ),
			'ciatec-base'          => array( $uri . '/css/base.css', array( 'ciatec-tokens' ) ),
			'ciatec-components'    => array( $uri . '/css/components.css', array( 'ciatec-base' ) ),
			'ciatec-header-footer' => array( $uri . '/css/header-footer.css', array( 'ciatec-components' ) ),
		);
		if ( is_front_page() ) {
			$styles['ciatec-home'] = array( $uri . '/css/home.css', array( 'ciatec-header-footer' ) );
		}
		foreach ( $styles as $handle => $style ) {
			wp_enqueue_style( $handle, $style[0], $style[1], CIATEC_THEME_VERSION );
		}

		if ( is_front_page() ) {
			wp_enqueue_script(
				'ciatec-hero-video',
				$uri . '/js/hero-video.js',
				array(),
				CIATEC_THEME_VERSION,
				array(
					'in_footer' => true,
					'strategy'  => 'defer',
				)
			);
		}

		wp_enqueue_script(
			'ciatec-header',
			$uri . '/js/header.js',
			array(),
			CIATEC_THEME_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
);
