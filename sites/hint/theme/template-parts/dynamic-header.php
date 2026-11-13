<?php
/**
 * Overrides Hello Elementor's dynamic header template part, which is the one
 * used while the "hello-theme-header-footer" experiment is active.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'hello_get_header_display' ) && ! hello_get_header_display() ) {
	return;
}

hint_render_header();
