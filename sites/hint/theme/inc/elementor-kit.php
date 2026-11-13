<?php
/**
 * Elementor Site Settings — HINT design system.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Design system version — bump to re-apply kit settings.
 */
const HINT_DESIGN_VERSION = '1.5.5';

/**
 * Size helper for Elementor slider/dimensions controls.
 *
 * @param float|int $size Size value.
 * @param string    $unit Unit.
 * @return array{unit:string,size:float|int,sizes:array}
 */
function hint_size( $size, $unit = 'px' ) {
	return array(
		'unit'  => $unit,
		'size'  => $size,
		'sizes' => array(),
	);
}

/**
 * Linked dimensions helper.
 *
 * @param float|int $value Value for all sides.
 * @param string    $unit  Unit.
 * @return array
 */
function hint_dimensions( $value, $unit = 'px' ) {
	return array(
		'unit'     => $unit,
		'top'      => (string) $value,
		'right'    => (string) $value,
		'bottom'   => (string) $value,
		'left'     => (string) $value,
		'isLinked' => true,
	);
}

/**
 * Build typography control payload.
 *
 * @param string      $family Font family.
 * @param string      $weight Font weight.
 * @param float|int   $size   Desktop size.
 * @param float|int|null $mobile_size Mobile size.
 * @param float|int|null $line_height Unitless line height.
 * @return array
 */
function hint_typography( $family, $weight, $size, $mobile_size = null, $line_height = null ) {
	$settings = array(
		'typography'  => 'custom',
		'font_family' => $family,
		'font_weight' => (string) $weight,
		'font_size'   => hint_size( $size ),
	);

	if ( null !== $mobile_size ) {
		$settings['font_size_mobile'] = hint_size( $mobile_size );
	}

	if ( null !== $line_height ) {
		$settings['line_height'] = hint_size( $line_height, 'em' );
	}

	return $settings;
}

/**
 * Prefix typography group keys (e.g. body_typography_*).
 *
 * @param string $prefix Control name prefix.
 * @param array  $typo   Typography values from hint_typography().
 * @return array
 */
function hint_prefix_typography( $prefix, array $typo ) {
	$out = array();

	foreach ( $typo as $key => $value ) {
		$out[ $prefix . '_' . $key ] = $value;
	}

	return $out;
}

/**
 * HINT Elementor kit settings.
 *
 * @return array
 */
function hint_get_elementor_kit_settings() {
	$space_grotesk = 'Space Grotesk';
	$inter         = 'Inter';

	// The four system slots are what widgets fall back to: Heading/Text/Icon
	// widgets use Primary, and the Button widget uses Accent.
	$system_typography = array(
		array_merge(
			array(
				'_id'   => 'primary',
				'title' => 'Headings',
			),
			hint_prefix_typography(
				'typography',
				hint_typography( $space_grotesk, '700', 64, 42, 1.1 )
			)
		),
		array_merge(
			array(
				'_id'   => 'secondary',
				'title' => 'Subheadings',
			),
			hint_prefix_typography(
				'typography',
				hint_typography( $space_grotesk, '600', 48, 36, 1.15 )
			)
		),
		array_merge(
			array(
				'_id'   => 'text',
				'title' => 'Body',
			),
			hint_prefix_typography(
				'typography',
				hint_typography( $inter, '400', 18, 16, 1.6 )
			)
		),
		array_merge(
			array(
				'_id'   => 'accent',
				'title' => 'Buttons',
			),
			hint_prefix_typography(
				'typography',
				hint_typography( $inter, '600', 16, 16, 1.2 )
			)
		),
	);

	$custom_typography = array(
		array_merge(
			array(
				'_id'   => 'h1',
				'title' => 'H1',
			),
			hint_prefix_typography(
				'typography',
				hint_typography( $space_grotesk, '700', 64, 42, 1.1 )
			)
		),
		array_merge(
			array(
				'_id'   => 'h2',
				'title' => 'H2',
			),
			hint_prefix_typography(
				'typography',
				hint_typography( $space_grotesk, '700', 48, 36, 1.15 )
			)
		),
		array_merge(
			array(
				'_id'   => 'h3',
				'title' => 'H3',
			),
			hint_prefix_typography(
				'typography',
				hint_typography( $space_grotesk, '600', 32, 28, 1.2 )
			)
		),
		array_merge(
			array(
				'_id'   => 'h4',
				'title' => 'H4',
			),
			hint_prefix_typography(
				'typography',
				hint_typography( $space_grotesk, '600', 24, 24, 1.25 )
			)
		),
		array_merge(
			array(
				'_id'   => 'body',
				'title' => 'Body',
			),
			hint_prefix_typography(
				'typography',
				hint_typography( $inter, '400', 18, 16, 1.6 )
			)
		),
		array_merge(
			array(
				'_id'   => 'small',
				'title' => 'Small',
			),
			hint_prefix_typography(
				'typography',
				hint_typography( $inter, '400', 14, 14, 1.5 )
			)
		),
	);

	$settings = array(
		// Site identity.
		'site_name'        => 'HINT - International Congress of Human-centred Inclusive New Technologies',
		'site_description' => 'International Congress of Human-centred Inclusive New Technologies',

		// Global Colors. Widgets fall back to these system slots — Heading and
		// Text use Primary, the Button widget uses Accent — and those defaults
		// win over Theme Style, so they are set to the values the design expects.
		'system_colors'    => array(
			array(
				'_id'   => 'primary',
				'title' => 'Text',
				'color' => '#111111',
			),
			array(
				'_id'   => 'secondary',
				'title' => 'Secondary',
				'color' => '#666663',
			),
			array(
				'_id'   => 'text',
				'title' => 'Body Text',
				'color' => '#111111',
			),
			array(
				'_id'   => 'accent',
				'title' => 'Buttons',
				'color' => '#03e8fc',
			),
		),
		// Custom ids must not collide with the system ids above, otherwise
		// they overwrite the --e-global-color-* variables.
		'custom_colors'    => array(
			array(
				'_id'   => 'hint_blue',
				'title' => 'HINT Blue',
				'color' => '#03e8fc',
			),
			array(
				'_id'   => 'hint_blue_dark',
				'title' => 'HINT Ink',
				'color' => '#111111',
			),
			array(
				'_id'   => 'hint_blue_on_dark',
				'title' => 'HINT Blue On Dark',
				'color' => '#03e8fc',
			),
			array(
				'_id'   => 'hint_hover',
				'title' => 'HINT Hover',
				'color' => '#f5119e',
			),
			array(
				'_id'   => 'background',
				'title' => 'Background',
				'color' => '#F7F6F2',
			),
			array(
				'_id'   => 'border',
				'title' => 'Border',
				'color' => '#D9D9D4',
			),
		),

		// Global Fonts.
		'system_typography'  => $system_typography,
		'custom_typography'  => $custom_typography,
		'default_generic_fonts' => 'Sans-serif',

		// Background.
		'body_background_background' => 'classic',
		'body_background_color'      => '#F7F6F2',
		'mobile_browser_background'  => '#F7F6F2',

		// Layout — spacing scale (gaps use 24px from 8/16/24…).
		'container_width'            => hint_size( 1140 ),
		'container_padding'          => hint_dimensions( 24 ),
		'space_between_widgets'      => array(
			'row'    => '24',
			'column' => '24',
			'unit'   => 'px',
			'isLinked' => true,
		),
		'default_page_template'      => 'elementor_header_footer',

		// Theme Style — Body + Headings.
		'body_color' => '#111111',
		'paragraph_spacing' => hint_size( 24 ),
		'link_normal_color' => '#03e8fc',
		'link_hover_color'  => '#f5119e',

		'h1_color' => '#111111',
		'h2_color' => '#111111',
		'h3_color' => '#111111',
		'h4_color' => '#111111',
		'h5_color' => '#111111',
		'h6_color' => '#111111',

		// Theme Style — Buttons (primary).
		'button_text_color'                   => '#111111',
		'button_background_background'        => 'classic',
		'button_background_color'             => '#03e8fc',
		'button_border_border'                => 'solid',
		'button_border_width'                 => hint_dimensions( 0 ),
		'button_border_color'                 => '#03e8fc',
		'button_border_radius'                => hint_dimensions( 4 ),
		'button_hover_text_color'             => '#FFFFFF',
		'button_hover_background_background'  => 'classic',
		'button_hover_background_color'       => '#f5119e',
		'button_hover_border_color'           => '#f5119e',
		'button_padding'                      => array(
			'unit'     => 'px',
			'top'      => '16',
			'right'    => '32',
			'bottom'   => '16',
			'left'     => '32',
			'isLinked' => false,
		),
	);

	$settings = array_merge(
		$settings,
		hint_prefix_typography( 'body_typography', hint_typography( $inter, '400', 18, 16, 1.6 ) ),
		hint_prefix_typography( 'h1_typography', hint_typography( $space_grotesk, '700', 64, 42, 1.1 ) ),
		hint_prefix_typography( 'h2_typography', hint_typography( $space_grotesk, '700', 48, 36, 1.15 ) ),
		hint_prefix_typography( 'h3_typography', hint_typography( $space_grotesk, '600', 32, 28, 1.2 ) ),
		hint_prefix_typography( 'h4_typography', hint_typography( $space_grotesk, '600', 24, 24, 1.25 ) ),
		hint_prefix_typography( 'h5_typography', hint_typography( $space_grotesk, '500', 20, 18, 1.3 ) ),
		hint_prefix_typography( 'h6_typography', hint_typography( $space_grotesk, '500', 18, 16, 1.35 ) ),
		hint_prefix_typography( 'button_typography', hint_typography( $inter, '600', 16, 16, 1.2 ) )
	);

	return $settings;
}

/**
 * Elementor plugin-level defaults (Settings + Performance tabs).
 *
 * @return array<string,string>
 */
function hint_get_elementor_options() {
	return array(
		// Global Colors / Fonts must stay active for the design system.
		'elementor_disable_color_schemes'      => '',
		'elementor_disable_typography_schemes' => '',

		// Google Fonts — required for Space Grotesk / Inter.
		'elementor_google_font'                => '1',
		'elementor_font_display'               => 'swap',
		'elementor_local_google_fonts'         => '1',

		// Performance.
		'elementor_css_print_method'           => 'external',
		'elementor_optimized_image_loading'    => '1',
		'elementor_optimized_gutenberg_loading' => '1',
		'elementor_lazy_load_background_images' => '1',
	);
}

/**
 * Apply Elementor plugin defaults.
 */
function hint_apply_elementor_options() {
	foreach ( hint_get_elementor_options() as $option => $value ) {
		update_option( $option, $value );
	}
}

/**
 * Apply HINT design system to the active Elementor kit.
 *
 * @return bool True on success.
 */
function hint_apply_elementor_kit() {
	if ( ! did_action( 'elementor/loaded' ) && ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}

	$plugin = \Elementor\Plugin::$instance;

	if ( ! isset( $plugin->kits_manager ) ) {
		return false;
	}

	$kit = $plugin->kits_manager->get_active_kit();

	if ( ! $kit || ! $kit->get_id() ) {
		return false;
	}

	$settings = hint_get_elementor_kit_settings();
	$kit_id   = (int) $kit->get_main_id();

	// CLI / bootstrap contexts often have no authenticated user;
	// Elementor requires edit capability to save kit settings.
	if ( ! current_user_can( 'edit_post', $kit_id ) ) {
		$admins = get_users(
			array(
				'role'   => 'administrator',
				'number' => 1,
				'fields' => array( 'ID' ),
			)
		);

		if ( empty( $admins ) ) {
			return false;
		}

		wp_set_current_user( (int) $admins[0]->ID );
	}

	// update_settings() merges recursively, which leaves stale repeater rows
	// behind when the palette shrinks. Wipe first so the kit mirrors the code.
	delete_post_meta( $kit_id, \Elementor\Core\Base\Document::PAGE_META_KEY );

	$kit->update_settings( $settings );

	hint_apply_elementor_options();

	// Regenerate kit CSS.
	if ( isset( $plugin->files_manager ) ) {
		$plugin->files_manager->clear_cache();
	}

	update_option( 'hint_design_version', HINT_DESIGN_VERSION );

	return true;
}

/**
 * Re-apply kit when design version changes.
 */
function hint_maybe_apply_elementor_kit() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( get_option( 'hint_design_version' ) === HINT_DESIGN_VERSION ) {
		return;
	}

	hint_apply_elementor_kit();
}
add_action( 'admin_init', 'hint_maybe_apply_elementor_kit' );
add_action( 'after_switch_theme', 'hint_apply_elementor_kit' );
