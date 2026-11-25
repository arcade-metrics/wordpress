<?php
/**
 * HINT site header.
 *
 * Rendered through Hello Elementor's header template parts, which the child
 * theme overrides. Elementor Free has no Theme Builder, so the global header
 * lives here while pages stay in Elementor.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Navigation items used until a menu is assigned to the "Header" location.
 *
 * Anchors match the section ids rendered by inc/sections/<name>.php, in the
 * order of hint_get_home_sections().
 *
 * @return array<int,array{title:string,url:string}>
 */
function hint_get_fallback_nav_items() {
	$items = array(
		'about'         => array(
			'pt' => 'Sobre',
			'en' => 'About',
		),
		'program'       => array(
			'pt' => 'Programação',
			'en' => 'Programme',
		),
		'registration'  => array(
			'pt' => 'Inscrições',
			'en' => 'Registration',
		),
		'papers'        => array(
			'pt' => 'Trabalhos',
			'en' => 'Papers',
		),
		'venue'         => array(
			'pt' => 'Local',
			'en' => 'Venue',
		),
		'accessibility' => array(
			'pt' => 'Acessibilidade',
			'en' => 'Accessibility',
		),
	);

	$nav = array();

	foreach ( $items as $anchor => $title ) {
		$nav[] = array(
			'title' => hint_pick( $title ),
			'url'   => hint_home_url( $anchor ),
		);
	}

	return $nav;
}

/**
 * Output the navigation list.
 *
 * Uses the WordPress menu assigned to Hello Elementor's "Header" location
 * (menu-1) when available, otherwise the fallback items above.
 *
 * @param string $id Unique list id, so desktop and mobile markup stay valid.
 */
function hint_render_nav_list( $id ) {
	$menu = wp_nav_menu(
		array(
			'theme_location' => 'menu-1',
			'container'      => false,
			'menu_id'        => $id,
			'menu_class'     => 'hint-nav__list',
			'depth'          => 1,
			'fallback_cb'    => false,
			'echo'           => false,
		)
	);

	if ( $menu ) {
		echo $menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by wp_nav_menu.
		return;
	}

	printf( '<ul id="%s" class="hint-nav__list">', esc_attr( $id ) );

	foreach ( hint_get_fallback_nav_items() as $item ) {
		printf(
			'<li class="menu-item"><a href="%s">%s</a></li>',
			esc_url( $item['url'] ),
			esc_html( $item['title'] )
		);
	}

	echo '</ul>';
}

/**
 * Output the Register call to action (links to the Registration section).
 *
 * Also used by the hero, so the context class (`hint-header__cta`) is passed
 * by the caller instead of being built in.
 *
 * @param string $class Extra button classes.
 */
function hint_render_register_cta( $class = 'hint-button--primary' ) {
	printf(
		'<a class="hint-button %s" href="%s">%s</a>',
		esc_attr( $class ),
		esc_url( apply_filters( 'hint_register_url', hint_home_url( 'registration' ) ) ),
		esc_html__( 'Register', 'hint' )
	);
}

/**
 * Output the Submit a Paper call to action (links to the Papers section).
 *
 * @param string $class Extra button classes.
 */
function hint_render_submit_cta( $class = 'hint-button--outline' ) {
	printf(
		'<a class="hint-button %s" href="%s">%s</a>',
		esc_attr( $class ),
		esc_url( apply_filters( 'hint_submit_url', hint_home_url( 'papers' ) ) ),
		esc_html__( 'Submit a Paper', 'hint' )
	);
}

/**
 * Output both header calls to action (desktop actions and mobile menu).
 */
function hint_render_header_ctas() {
	hint_render_submit_cta( 'hint-button--outline hint-header__cta' );
	hint_render_register_cta( 'hint-button--primary hint-header__cta' );
}

/**
 * Map Polylang language slugs to HINT flag assets.
 *
 * The site has two languages (sites/hint/seed.php): `en` (default) and `pt`.
 * `es` is kept only so a future language gets its flag without code changes.
 *
 * @return array<string,string>
 */
function hint_get_flag_file_map() {
	return array(
		'pt' => 'flag-br.svg',
		'en' => 'flag-uk.svg',
		'es' => 'flag-es.svg',
	);
}

/**
 * Resolve a HINT flag filename for a Polylang language slug.
 *
 * @param string $slug Polylang language slug (pt, en).
 * @return string
 */
function hint_flag_file_for_code( $slug ) {
	$map = hint_get_flag_file_map();

	return isset( $map[ $slug ] ) ? $map[ $slug ] : 'flag-uk.svg';
}

/**
 * Fallback languages when Polylang is inactive: the same two as the seed,
 * current one (from hint_lang()) first, inert (no URLs).
 *
 * @return array<int,array{code:string,label:string,file:string,url:string,current:bool}>
 */
function hint_get_fallback_languages() {
	$current = hint_lang();

	$languages = array(
		array(
			'code'    => 'en',
			'label'   => __( 'English', 'hint' ),
			'file'    => 'flag-uk.svg',
			'url'     => '',
			'current' => 'en' === $current,
		),
		array(
			'code'    => 'pt',
			'label'   => __( 'Português', 'hint' ),
			'file'    => 'flag-br.svg',
			'url'     => '',
			'current' => 'pt' === $current,
		),
	);

	if ( 'pt' === $current ) {
		$languages = array_reverse( $languages );
	}

	return $languages;
}

/**
 * Languages for the flag switcher, current one first.
 *
 * Built from Polylang's data (same source as the `ciatec` theme's switcher):
 * only the languages configured there are listed (en and pt, from the seed).
 * Falls back to inert placeholders when Polylang is inactive.
 *
 * @return array<int,array{code:string,label:string,file:string,url:string,current:bool}>
 */
function hint_get_languages() {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return hint_get_fallback_languages();
	}

	$languages = pll_the_languages(
		array(
			'raw'           => 1,
			'hide_if_empty' => 0, // show every language even before it has content.
		)
	);

	if ( empty( $languages ) || ! is_array( $languages ) ) {
		return hint_get_fallback_languages();
	}

	$items = array();
	foreach ( $languages as $lang ) {
		$items[] = array(
			'code'    => $lang['slug'],
			'label'   => $lang['name'],
			'file'    => hint_flag_file_for_code( $lang['slug'] ),
			'url'     => $lang['url'],
			'current' => ! empty( $lang['current_lang'] ),
		);
	}

	$has_current = false;
	foreach ( $items as $item ) {
		if ( $item['current'] ) {
			$has_current = true;
			break;
		}
	}

	if ( ! $has_current && ! empty( $items ) ) {
		$items[0]['current'] = true;
	}

	usort(
		$items,
		static function ( $a, $b ) {
			if ( $a['current'] === $b['current'] ) {
				return 0;
			}
			return $a['current'] ? -1 : 1;
		}
	);

	return $items;
}

/**
 * Output a single flag image.
 *
 * @param array{code:string,label:string,file:string} $language Language entry.
 */
function hint_render_flag( $language ) {
	printf(
		'<img class="hint-lang__flag" src="%s" alt="%s" width="100" height="60" decoding="async" />',
		esc_url( hint_asset_url( 'flags/' . $language['file'] ) ),
		esc_attr( $language['label'] )
	);
}

/**
 * Output the language switcher: current flag as trigger, remaining flags in a
 * dropdown. Only the images are shown — no labels, no chevron.
 *
 * With Polylang active, options become links to the current page in that
 * language. Without it, options stay inert placeholders.
 *
 * @param string $id Unique id, since the switcher is rendered twice.
 */
function hint_render_language_flags( $id = 'hint-lang' ) {
	$languages = hint_get_languages();

	if ( empty( $languages ) ) {
		return;
	}

	$current = array_shift( $languages );
	$others  = $languages;
	?>
	<div class="hint-lang" data-hint-lang data-no-translation>
		<button
			type="button"
			class="hint-lang__trigger"
			aria-haspopup="true"
			aria-expanded="false"
			aria-controls="<?php echo esc_attr( $id . '-menu' ); ?>"
			aria-label="<?php echo esc_attr( sprintf( /* translators: %s: current language. */ __( 'Language: %s', 'hint' ), $current['label'] ) ); ?>"
		>
			<?php hint_render_flag( $current ); ?>
		</button>

		<?php if ( ! empty( $others ) ) : ?>
			<ul class="hint-lang__menu" id="<?php echo esc_attr( $id . '-menu' ); ?>" hidden>
				<?php foreach ( $others as $language ) : ?>
					<li>
						<?php if ( ! empty( $language['url'] ) ) : ?>
							<a
								class="hint-lang__option"
								href="<?php echo esc_url( $language['url'] ); ?>"
								hreflang="<?php echo esc_attr( $language['code'] ); ?>"
								aria-label="<?php echo esc_attr( $language['label'] ); ?>"
								title="<?php echo esc_attr( $language['label'] ); ?>"
							>
								<?php hint_render_flag( $language ); ?>
							</a>
						<?php else : ?>
							<button
								type="button"
								class="hint-lang__option"
								aria-disabled="true"
								aria-label="<?php echo esc_attr( $language['label'] ); ?>"
								title="<?php echo esc_attr( $language['label'] ); ?>"
							>
								<?php hint_render_flag( $language ); ?>
							</button>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Options offered by the accessibility panel.
 *
 * Values are the ones assets/js/a11y.js writes to <html> (data-theme and
 * data-text) and to localStorage; tokens.css styles each of them.
 *
 * @return array<string,array{label:string,attr:string,options:array<string,string>}>
 */
function hint_get_a11y_groups() {
	return array(
		'theme' => array(
			'label'   => __( 'Appearance', 'hint' ),
			'attr'    => 'theme',
			'options' => array(
				'light'    => __( 'Light', 'hint' ),
				'dark'     => __( 'Dark', 'hint' ),
				'contrast' => __( 'High contrast', 'hint' ),
			),
		),
		'text'  => array(
			'label'   => __( 'Text size', 'hint' ),
			'attr'    => 'text',
			'options' => array(
				'1' => __( 'Normal', 'hint' ),
				'2' => __( 'Large', 'hint' ),
				'3' => __( 'Extra large', 'hint' ),
			),
		),
	);
}

/**
 * Output the accessibility panel: a trigger with the universal access icon
 * and a popover with pressed-state buttons for appearance (light, dark, high
 * contrast) and text size, plus a reset.
 *
 * Replaces the former light/dark toggle. The panel is rendered twice (desktop
 * actions and mobile menu); state is shared through <html> attributes, and
 * aria-pressed is filled in by assets/js/a11y.js once the saved preference is
 * known. Without JS the panel stays closed and inert.
 *
 * It is a disclosure (aria-expanded + aria-controls), not a modal dialog: the
 * panel is a labelled group, focus is free to leave it (which closes it) and
 * Esc closes it returning focus to the trigger.
 *
 * @param string $id Unique id prefix, since the panel is rendered twice.
 */
function hint_render_a11y_panel( $id = 'hint-a11y' ) {
	$panel_id = $id . '-panel';
	$title_id = $id . '-title';
	?>
	<div class="hint-a11y" data-hint-a11y>
		<button
			type="button"
			class="hint-a11y__trigger"
			aria-expanded="false"
			aria-controls="<?php echo esc_attr( $panel_id ); ?>"
			aria-label="<?php esc_attr_e( 'Accessibility options', 'hint' ); ?>"
			title="<?php esc_attr_e( 'Accessibility options', 'hint' ); ?>"
		>
			<svg class="hint-a11y__icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
				<circle cx="12" cy="12" r="10" />
				<circle cx="12" cy="6.9" r="1.3" fill="currentColor" stroke="none" />
				<path d="M6.6 9.9c3.5 1 7.3 1 10.8 0" />
				<path d="M10.4 10.6v3.1l-1.7 4.8" />
				<path d="M13.6 10.6v3.1l1.7 4.8" />
				<path d="M10.4 13.7h3.2" />
			</svg>
		</button>

		<div
			class="hint-a11y__panel"
			id="<?php echo esc_attr( $panel_id ); ?>"
			role="group"
			aria-labelledby="<?php echo esc_attr( $title_id ); ?>"
			hidden
		>
			<p class="hint-a11y__title" id="<?php echo esc_attr( $title_id ); ?>"><?php esc_html_e( 'Accessibility', 'hint' ); ?></p>

			<?php foreach ( hint_get_a11y_groups() as $key => $group ) : ?>
				<?php $label_id = $id . '-' . $key . '-label'; ?>
				<div class="hint-a11y__group" role="group" aria-labelledby="<?php echo esc_attr( $label_id ); ?>">
					<p class="hint-a11y__label" id="<?php echo esc_attr( $label_id ); ?>"><?php echo esc_html( $group['label'] ); ?></p>
					<div class="hint-a11y__options">
						<?php foreach ( $group['options'] as $value => $label ) : ?>
							<button
								type="button"
								class="hint-a11y__option"
								data-a11y-<?php echo esc_attr( $group['attr'] ); ?>="<?php echo esc_attr( $value ); ?>"
								aria-pressed="false"
							><?php echo esc_html( $label ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>

			<button type="button" class="hint-a11y__reset" data-a11y-reset><?php esc_html_e( 'Reset', 'hint' ); ?></button>
		</div>
	</div>
	<?php
}

/**
 * Output the site header.
 */
function hint_render_header() {
	$logo = hint_asset_url( 'brand/LOGO-HINT-2027-dark.svg' );
	?>
	<header class="hint-header" role="banner">
		<div class="hint-header__inner">

			<a class="hint-header__logo" href="<?php echo esc_url( hint_home_url() ); ?>" rel="home">
				<img
					src="<?php echo esc_url( $logo ); ?>"
					alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					width="482"
					height="100"
					decoding="async"
				/>
			</a>

			<nav class="hint-header__nav hint-nav" aria-label="<?php esc_attr_e( 'Main menu', 'hint' ); ?>">
				<?php hint_render_nav_list( 'hint-menu-desktop' ); ?>
			</nav>

			<div class="hint-header__actions">
				<?php
				hint_render_language_flags( 'hint-lang-desktop' );
				hint_render_a11y_panel( 'hint-a11y-desktop' );
				hint_render_header_ctas();
				?>

				<button
					type="button"
					class="hint-header__toggle"
					aria-controls="hint-header-mobile"
					aria-expanded="false"
					aria-label="<?php esc_attr_e( 'Menu', 'hint' ); ?>"
				>
					<span class="hint-header__toggle-bars" aria-hidden="true"></span>
				</button>
			</div>

		</div>

		<div class="hint-header__mobile" id="hint-header-mobile" hidden>
			<nav class="hint-nav hint-nav--mobile" aria-label="<?php esc_attr_e( 'Mobile menu', 'hint' ); ?>">
				<?php hint_render_nav_list( 'hint-menu-mobile' ); ?>
			</nav>
			<div class="hint-header__mobile-ctas">
				<?php hint_render_header_ctas(); ?>
			</div>

			<div class="hint-header__mobile-utils">
				<?php
				hint_render_language_flags( 'hint-lang-mobile' );
				hint_render_a11y_panel( 'hint-a11y-mobile' );
				?>
			</div>
		</div>
	</header>
	<?php
}
