<?php
/**
 * Site footer: logo, institution, e-mail. Language is chosen in the header; this is a one-page
 * site, so there is no separate footer menu.
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;
?>
<footer id="site-footer" class="ciatec-footer">
	<div class="ciatec-footer__inner">
		<div class="ciatec-footer__id">
			<?php ciatec_footer_logo(); ?>
			<p><?php echo esc_html( ciatec_t( 'footer_inst' ) ); ?></p>
			<p><a href="mailto:<?php echo esc_attr( CIATEC_EMAIL ); ?>"><?php echo esc_html( CIATEC_EMAIL ); ?></a></p>
		</div>
	</div>
	<p class="ciatec-copyright">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
</footer>
