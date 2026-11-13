<?php
/**
 * HINT front-page hero — full-bleed video with brand title.
 *
 * Theme infrastructure for the home start; remaining sections stay white /
 * Elementor until designed.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Official congress theme (kept in Portuguese, as published by the organisation).
 *
 * @return string
 */
function hint_get_event_theme() {
	return 'Sociedade Digital Inclusiva: Educação, Saúde e Tecnologias Emergentes em uma Perspectiva Multidisciplinar.';
}

/**
 * Output the home hero (video background + brand stack).
 */
function hint_render_home_hero() {
	$video = hint_asset_url( 'videos/video-3.mp4' );
	?>
	<section class="hint-hero" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<video
			class="hint-hero__video"
			autoplay
			muted
			loop
			playsinline
			preload="metadata"
			aria-hidden="true"
		>
			<source src="<?php echo esc_url( $video ); ?>" type="video/mp4" />
		</video>

		<div class="hint-hero__overlay" aria-hidden="true"></div>

		<div class="hint-hero__box">
			<p class="hint-hero__brand">HINT <span class="hint-hero__brand-year">2027</span></p>
			<p class="hint-hero__tagline">
				<?php
				echo esc_html__( 'International Congress of', 'hint' );
				echo '<br />';
				echo esc_html__( 'Human-Centred Inclusive New Technologies', 'hint' );
				?>
			</p>
			<p class="hint-hero__when">
				<?php esc_html_e( '25 February 2027', 'hint' ); ?>
				<span class="hint-hero__sep" aria-hidden="true">·</span>
				<?php esc_html_e( 'São Paulo, Brazil', 'hint' ); ?>
			</p>
			<p class="hint-hero__where"><?php esc_html_e( 'EACH — University of São Paulo', 'hint' ); ?></p>
			<p class="hint-hero__theme" lang="pt-BR">
				<?php echo esc_html( hint_get_event_theme() ); ?>
			</p>
			<p class="hint-hero__actions">
				<?php
				hint_render_register_cta( 'hint-button--solid' );
				hint_render_submit_cta( 'hint-button--ghost' );
				?>
			</p>
		</div>
	</section>
	<?php
}
