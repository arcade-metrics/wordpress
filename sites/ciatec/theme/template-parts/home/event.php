<?php
/**
 * HINT 2027 banner (between Projetos and Contato). The whole banner links to the congress site in a new window.
 * The text lives inside the image, so the alt text repeats it. Phones get a crop of the text area only.
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

$wide   = ciatec_asset_url( 'images/hint-2027-2069.webp' );
$medium = ciatec_asset_url( 'images/hint-2027-1200.webp' );
$mobile = ciatec_asset_url( 'images/hint-2027-mobile.webp' );

if ( ! $wide ) {
	return;
}
?>
<aside class="ciatec-section ciatec-event" aria-label="<?php echo esc_attr( ciatec_t( 'hint_region' ) ); ?>">
	<div class="ciatec-container">
		<a class="ciatec-event__link" href="https://hint.ciatec.org/" target="_blank" rel="noopener">
			<picture>
				<?php if ( $mobile ) : ?>
					<source media="(max-width: 767px)" srcset="<?php echo esc_url( $mobile ); ?>" width="780" height="570">
				<?php endif; ?>
				<img
					src="<?php echo esc_url( $wide ); ?>"
					srcset="<?php echo esc_url( $medium ); ?> 1200w, <?php echo esc_url( $wide ); ?> 2069w"
					sizes="(max-width: 1200px) 100vw, 1104px"
					width="2069"
					height="760"
					alt="<?php echo esc_attr( ciatec_t( 'hint_alt' ) ); ?>"
					loading="lazy"
					decoding="async"
				>
			</picture>
		</a>
	</div>
</aside>
