<?php
/**
 * Front page — video hero, content sections, then footer.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

hint_render_home_hero();
hint_render_home_sections();

get_footer();
