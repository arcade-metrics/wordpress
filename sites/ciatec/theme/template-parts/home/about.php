<?php
/**
 * Quem Somos (#quem-somos): intro, mission as a statement, logo ring as a faint decoration on the left edge, three pillars as ruled columns.
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

$ring = ciatec_asset_url( 'brand/ciatec-icon.svg' );
?>
<section id="quem-somos" class="ciatec-section ciatec-has-ring" aria-labelledby="about-title">
	<?php if ( $ring ) : ?>
		<img class="ciatec-ring ciatec-ring--left" src="<?php echo esc_url( $ring ); ?>" alt="" aria-hidden="true" width="159" height="159" decoding="async">
	<?php endif; ?>
	<div class="ciatec-container">
		<div class="ciatec-split">
			<h2 id="about-title" class="ciatec-split__title"><?php echo esc_html( ciatec_t( 'about_title' ) ); ?></h2>
			<div class="ciatec-split__body">
				<p class="ciatec-lead"><?php echo esc_html( ciatec_t( 'about_text' ) ); ?></p>
			</div>
		</div>

		<div class="ciatec-split">
			<h3 class="ciatec-split__title ciatec-label"><?php echo esc_html( ciatec_t( 'mission_label' ) ); ?></h3>
			<p class="ciatec-split__body ciatec-statement"><?php echo esc_html( ciatec_t( 'mission_text' ) ); ?></p>
		</div>

		<ul class="ciatec-pillars" role="list">
			<?php for ( $n = 1; $n <= 3; $n++ ) : ?>
				<li>
					<h3><?php echo esc_html( ciatec_t( "pillar_{$n}_title" ) ); ?></h3>
					<p><?php echo esc_html( ciatec_t( "pillar_{$n}_text" ) ); ?></p>
				</li>
			<?php endfor; ?>
		</ul>
	</div>
</section>
