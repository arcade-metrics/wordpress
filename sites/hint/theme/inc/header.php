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
 * @return array<int,array{title:string,url:string}>
 */
function hint_get_fallback_nav_items() {
	$items = array(
		'about'           => __( 'About', 'hint' ),
		'topics'          => __( 'Topics', 'hint' ),
		'call-for-papers' => __( 'Call for Papers', 'hint' ),
		'program'         => __( 'Program', 'hint' ),
		'venue'           => __( 'Venue', 'hint' ),
	);

	$nav = array();

	foreach ( $items as $anchor => $title ) {
		$nav[] = array(
			'title' => $title,
			'url'   => home_url( '/#' . $anchor ),
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
 * Output the Register call to action (placeholder until registration opens).
 *
 * @param string $class Extra button classes.
 */
function hint_render_register_cta( $class = 'hint-button--primary' ) {
	printf(
		'<a class="hint-button %s hint-header__cta" href="%s">%s</a>',
		esc_attr( $class ),
		esc_url( apply_filters( 'hint_register_url', hint_coming_soon_url( 'register' ) ) ),
		esc_html__( 'Register', 'hint' )
	);
}

/**
 * Output the Submit a Paper call to action (placeholder until submissions open).
 *
 * @param string $class Extra button classes.
 */
function hint_render_submit_cta( $class = 'hint-button--outline' ) {
	printf(
		'<a class="hint-button %s hint-header__cta" href="%s">%s</a>',
		esc_attr( $class ),
		esc_url( apply_filters( 'hint_submit_url', hint_coming_soon_url( 'submit' ) ) ),
		esc_html__( 'Submit a Paper', 'hint' )
	);
}

/**
 * Output both header calls to action.
 */
function hint_render_header_ctas() {
	hint_render_submit_cta( 'hint-button--outline' );
	hint_render_register_cta( 'hint-button--primary' );
}

/**
 * Map Polylang language slugs to HINT flag assets.
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
 * @param string $slug Polylang language slug (pt, en, es).
 * @return string
 */
function hint_flag_file_for_code( $slug ) {
	$map = hint_get_flag_file_map();

	return isset( $map[ $slug ] ) ? $map[ $slug ] : 'flag-br.svg';
}

/**
 * Fallback languages when Polylang is inactive.
 *
 * @return array<int,array{code:string,label:string,file:string,url:string,current:bool}>
 */
function hint_get_fallback_languages() {
	return array(
		array(
			'code'    => 'pt',
			'label'   => __( 'Português', 'hint' ),
			'file'    => 'flag-br.svg',
			'url'     => '',
			'current' => true,
		),
		array(
			'code'    => 'en',
			'label'   => __( 'English', 'hint' ),
			'file'    => 'flag-uk.svg',
			'url'     => '',
			'current' => false,
		),
		array(
			'code'    => 'es',
			'label'   => __( 'Español', 'hint' ),
			'file'    => 'flag-es.svg',
			'url'     => '',
			'current' => false,
		),
	);
}

/**
 * Languages for the flag switcher, current one first.
 *
 * Built from Polylang's data (same source as the `ciatec` theme's switcher).
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
 * Output the light/dark theme icon.
 *
 * Placeholder control: no dark palette is defined yet, so it carries no
 * behaviour and is exposed as disabled to assistive technology.
 */
function hint_render_theme_toggle() {
	?>
	<button
		type="button"
		class="hint-theme-toggle"
		aria-disabled="true"
		aria-label="<?php esc_attr_e( 'Light and dark theme', 'hint' ); ?>"
		title="<?php esc_attr_e( 'Light and dark theme', 'hint' ); ?>"
	>
		<svg class="hint-theme-toggle__icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
			<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z" />
		</svg>
	</button>
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

			<a class="hint-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
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
				hint_render_theme_toggle();
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
				hint_render_theme_toggle();
				?>
			</div>
		</div>
	</header>
	<?php
}
