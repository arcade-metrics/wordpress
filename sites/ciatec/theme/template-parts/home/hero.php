<?php
/**
 * Hero (#inicio). Background video from assets/videos/hero.{webm,mp4} (flipped horizontally, 10% already cropped top and bottom),
 * poster assets/images/hero-poster.webp. Without the files it is a plain navy block.
 *
 * The video does not autoplay from markup: hero-video.js starts it only when the visitor allows motion
 * (no prefers-reduced-motion, no Save-Data, not a phone), and the pause button is always available (WCAG 2.2.2).
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

$mp4    = ciatec_asset_url( 'videos/hero.mp4' );
$webm   = ciatec_asset_url( 'videos/hero.webm' );
$poster = ciatec_asset_url( 'images/hero-poster.webp' );
$has_video = $mp4 || $webm;
?>
<section id="inicio" class="ciatec-hero<?php echo $has_video ? ' has-video' : ''; ?>" aria-labelledby="hero-title">
	<?php if ( $has_video ) : ?>
		<video
			class="ciatec-hero__video"
			muted
			loop
			playsinline
			preload="none"
			aria-hidden="true"
			tabindex="-1"
			<?php echo $poster ? 'poster="' . esc_url( $poster ) . '"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
		>
			<?php if ( $webm ) : ?>
				<source src="<?php echo esc_url( $webm ); ?>" type="video/webm">
			<?php endif; ?>
			<?php if ( $mp4 ) : ?>
				<source src="<?php echo esc_url( $mp4 ); ?>" type="video/mp4">
			<?php endif; ?>
		</video>
	<?php endif; ?>
	<div class="ciatec-hero__overlay" aria-hidden="true"></div>

	<div class="ciatec-container ciatec-hero__inner">
		<div class="ciatec-hero__content">
			<h1 id="hero-title"><?php echo esc_html( ciatec_t( 'hero_title' ) ); ?></h1>
			<p class="ciatec-hero__lead"><?php echo esc_html( ciatec_t( 'hero_subtitle' ) ); ?></p>
		</div>
	</div>

	<?php if ( $has_video ) : ?>
		<button
			type="button"
			class="ciatec-hero__control"
			hidden
			data-label-pause="<?php echo esc_attr( ciatec_t( 'video_pause' ) ); ?>"
			data-label-play="<?php echo esc_attr( ciatec_t( 'video_play' ) ); ?>"
		>
			<span class="ciatec-hero__control-pause"><?php ciatec_icon( 'pause' ); ?></span>
			<span class="ciatec-hero__control-play"><?php ciatec_icon( 'play' ); ?></span>
			<span class="screen-reader-text"></span>
		</button>
	<?php endif; ?>
</section>
