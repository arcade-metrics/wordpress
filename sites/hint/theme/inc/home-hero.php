<?php
/**
 * HINT front-page hero — full-bleed video with brand title.
 *
 * Interface strings are English sources translated by languages/pt_BR.l10n.php;
 * the congress theme is bilingual content picked by hint_pick().
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Official congress theme, in the current language.
 *
 * Portuguese is the text published by the organisation; English is the
 * site's translation of it.
 *
 * @return string
 */
function hint_get_event_theme() {
	return hint_pick(
		array(
			'pt' => 'Sociedade Digital Inclusiva: Educação, Saúde e Tecnologias Emergentes em uma Perspectiva Multidisciplinar.',
			'en' => 'Inclusive Digital Society: Education, Health and Emerging Technologies from a Multidisciplinary Perspective',
		)
	);
}

/**
 * Output the pause/play toggle for the background video (WCAG 2.2.2).
 *
 * A toggle button: the label stays constant and aria-pressed="true" means the
 * video is paused. assets/js/a11y.js drives it and starts the video paused
 * when the visitor prefers reduced motion; in high contrast the video is
 * hidden and so is this control (home.css).
 */
function hint_render_hero_video_toggle() {
	?>
	<button
		type="button"
		class="hint-hero__play"
		aria-pressed="false"
		aria-label="<?php esc_attr_e( 'Pause background video', 'hint' ); ?>"
		title="<?php esc_attr_e( 'Pause background video', 'hint' ); ?>"
	>
		<svg class="hint-hero__play-icon hint-hero__play-icon--pause" viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true" focusable="false">
			<rect x="6" y="4" width="4" height="16" rx="1" />
			<rect x="14" y="4" width="4" height="16" rx="1" />
		</svg>
		<svg class="hint-hero__play-icon hint-hero__play-icon--play" viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true" focusable="false">
			<path d="M8 5.5v13a1 1 0 0 0 1.5.87l11-6.5a1 1 0 0 0 0-1.74l-11-6.5A1 1 0 0 0 8 5.5Z" />
		</svg>
	</button>
	<?php
}

/**
 * Output the home hero (video background + brand stack).
 *
 * The brand is the page's only <h1>; the sections start at <h2>.
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

		<?php hint_render_hero_video_toggle(); ?>

		<div class="hint-hero__box">
			<h1 class="hint-hero__brand">HINT <span class="hint-hero__brand-year">2027</span></h1>
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
			<p class="hint-hero__theme">
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
