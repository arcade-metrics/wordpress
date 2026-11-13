<?php
/**
 * Contato (#contato): navy band, e-mail as the main element, no form. The logo ring is a cropped decoration.
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

$ring = ciatec_asset_url( 'brand/ciatec-icon.svg' );
?>
<section id="contato" class="ciatec-section ciatec-band" aria-labelledby="contact-title">
	<?php if ( $ring ) : ?>
		<img class="ciatec-ring ciatec-ring--right" src="<?php echo esc_url( $ring ); ?>" alt="" aria-hidden="true" width="159" height="159" decoding="async">
	<?php endif; ?>
	<div class="ciatec-container ciatec-split">
		<h2 id="contact-title" class="ciatec-split__title"><?php echo esc_html( ciatec_t( 'contact_title' ) ); ?></h2>
		<div class="ciatec-split__body">
			<p class="ciatec-lead"><?php echo esc_html( ciatec_t( 'contact_text' ) ); ?></p>
			<p><a class="ciatec-email" href="mailto:<?php echo esc_attr( CIATEC_EMAIL ); ?>"><?php echo esc_html( CIATEC_EMAIL ); ?></a></p>
			<ul class="ciatec-social" role="list">
				<?php foreach ( ciatec_social_links() as $slug => $link ) : ?>
					<li>
						<?php if ( $link[1] ) : ?>
							<a href="<?php echo esc_url( $link[1] ); ?>" aria-label="<?php echo esc_attr( sprintf( ciatec_t( 'social_label' ), $link[0] ) ); ?>" title="<?php echo esc_attr( $link[0] ); ?>"><?php ciatec_icon( $slug ); ?></a>
						<?php else : ?>
							<span aria-disabled="true" title="<?php echo esc_attr( sprintf( ciatec_t( 'social_soon' ), $link[0] ) ); ?>"><?php ciatec_icon( $slug ); ?><span class="screen-reader-text"><?php echo esc_html( sprintf( ciatec_t( 'social_soon' ), $link[0] ) ); ?></span></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
