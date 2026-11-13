<?php
/**
 * Site header: logo, main nav, language flags and theme switch.
 *
 * One-page site: the nav is always the anchor list below, not a WordPress menu. This guarantees
 * the header matches the design regardless of whatever may be saved under Appearance > Menus
 * (e.g. an older menu from before this redesign).
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

$home  = trailingslashit( ciatec_home_url() );
$items = array(
	'inicio'     => 'nav_home',
	'quem-somos' => 'nav_about',
	'projetos'   => 'nav_projects',
	'contato'    => 'nav_contact',
);
?>
<header id="site-header" class="ciatec-header">
	<div class="ciatec-header__inner">
		<?php ciatec_logo(); ?>

		<button type="button" class="ciatec-header__toggle" aria-expanded="false" aria-controls="ciatec-nav" hidden>
			<span class="ciatec-header__toggle-bars" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php echo esc_html( ciatec_t( 'menu_toggle' ) ); ?></span>
		</button>

		<div id="ciatec-nav" class="ciatec-header__panel">
			<nav class="ciatec-nav" aria-label="<?php echo esc_attr( ciatec_t( 'menu_toggle' ) ); ?>">
				<ul class="menu">
					<?php foreach ( $items as $anchor => $key ) : ?>
						<li><a href="<?php echo esc_url( $home . '#' . $anchor ); ?>"><?php echo esc_html( ciatec_t( $key ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
			<div class="ciatec-header__utils">
				<?php
				ciatec_language_switcher();
				ciatec_theme_toggle();
				?>
			</div>
		</div>
	</div>
</header>
