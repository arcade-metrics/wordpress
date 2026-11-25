<?php
/**
 * HINT front-page sections below the hero (orchestration only).
 *
 * Each section lives in inc/sections/<name>.php and exposes
 * hint_render_section_<name>(). Shared helpers are in inc/sections/shared.php.
 * To add or reorder a section, change the list in hint_get_home_sections().
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once HINT_THEME_DIR . '/inc/sections/shared.php';

/**
 * Section slugs in display order (also the anchor ids and nav targets).
 *
 * @return string[]
 */
function hint_get_home_sections() {
	return array(
		'about',
		'program',
		'registration',
		'papers',
		'venue',
		'accessibility',
		'partners',
	);
}

foreach ( hint_get_home_sections() as $hint_section ) {
	require_once HINT_THEME_DIR . '/inc/sections/' . $hint_section . '.php';
}
unset( $hint_section );

/**
 * Output every section below the hero, in order.
 */
function hint_render_home_sections() {
	?>
	<main class="hint-home" id="content">
		<?php
		foreach ( hint_get_home_sections() as $section ) {
			$renderer = 'hint_render_section_' . $section;

			if ( function_exists( $renderer ) ) {
				call_user_func( $renderer );
			}
		}
		?>
	</main>
	<?php
}
