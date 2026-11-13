<?php
/**
 * Overrides Hello Elementor's dynamic footer template part, which is the one
 * used while the "hello-theme-header-footer" experiment is active.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'hello_get_footer_display' ) && ! hello_get_footer_display() ) {
	return;
}

hint_render_footer();
