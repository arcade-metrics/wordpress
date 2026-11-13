<?php
/**
 * HINT site footer.
 *
 * Rendered through Hello Elementor's footer template parts, which the child
 * theme overrides. Elementor Free has no Theme Builder, so the global footer
 * lives here while pages stay in Elementor.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Contact email shown in the footer.
 *
 * @return string
 */
function hint_get_footer_contact_email() {
	return (string) apply_filters( 'hint_footer_contact_email', 'ciatec@usp.br' );
}

/**
 * Social links for the footer icon row.
 *
 * @return array<int,array{id:string,label:string,url:string}>
 */
function hint_get_footer_social_links() {
	$links = array(
		array(
			'id'    => 'facebook',
			'label' => 'Facebook',
			'url'   => '#',
		),
		array(
			'id'    => 'instagram',
			'label' => 'Instagram',
			'url'   => '#',
		),
		array(
			'id'    => 'youtube',
			'label' => 'YouTube',
			'url'   => '#',
		),
		array(
			'id'    => 'linkedin',
			'label' => 'LinkedIn',
			'url'   => '#',
		),
	);

	// Hide networks without an official URL yet.
	$links = array_values(
		array_filter(
			$links,
			static function ( $link ) {
				return '#' !== $link['url'];
			}
		)
	);

	return apply_filters( 'hint_footer_social_links', $links );
}

/**
 * Output an inline social icon (stroke, matches header controls).
 *
 * @param string $id Network id: facebook|instagram|youtube|linkedin.
 */
function hint_render_social_icon( $id ) {
	$common = 'class="hint-footer__social-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"';

	switch ( $id ) {
		case 'facebook':
			printf(
				'<svg %s><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3V2Z" /></svg>',
				$common // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attribute string.
			);
			break;

		case 'instagram':
			printf(
				'<svg %s><rect x="3" y="3" width="18" height="18" rx="5" /><circle cx="12" cy="12" r="4" /><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" /></svg>',
				$common // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			break;

		case 'youtube':
			printf(
				'<svg %s><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-2C18.88 4 12 4 12 4s-6.88 0-8.59.42a2.78 2.78 0 0 0-1.95 2A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 2C5.12 20 12 20 12 20s6.88 0 8.59-.42a2.78 2.78 0 0 0 1.95-2A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58Z" /><path d="m9.75 15.02 5.75-3.02-5.75-3.02v6.04Z" /></svg>',
				$common // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			break;

		case 'linkedin':
			printf(
				'<svg %s><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6Z" /><rect x="2" y="9" width="4" height="12" /><circle cx="4" cy="4" r="2" /></svg>',
				$common // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			break;
	}
}

/**
 * Output the social icon list.
 */
function hint_render_footer_social() {
	$links = hint_get_footer_social_links();

	if ( empty( $links ) ) {
		return;
	}
	?>
	<ul class="hint-footer__social">
		<?php foreach ( $links as $link ) : ?>
			<li>
				<a
					class="hint-footer__social-link"
					href="<?php echo esc_url( $link['url'] ); ?>"
					aria-label="<?php echo esc_attr( $link['label'] ); ?>"
					<?php if ( '#' !== $link['url'] ) : ?>
						target="_blank" rel="noopener noreferrer"
					<?php endif; ?>
				>
					<?php hint_render_social_icon( $link['id'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * Discreet WordPress admin / login entry (footer).
 */
function hint_render_footer_admin_link() {
	$url = is_user_logged_in() ? admin_url() : wp_login_url( admin_url() );
	?>
	<a
		class="hint-footer__admin"
		href="<?php echo esc_url( $url ); ?>"
		aria-label="<?php echo esc_attr__( 'WordPress admin', 'hint' ); ?>"
		title="<?php echo esc_attr__( 'Admin', 'hint' ); ?>"
	>
		<svg
			class="hint-footer__admin-icon"
			viewBox="0 0 24 24"
			width="14"
			height="14"
			fill="none"
			stroke="currentColor"
			stroke-width="1.75"
			stroke-linecap="round"
			stroke-linejoin="round"
			aria-hidden="true"
			focusable="false"
		>
			<rect x="5" y="11" width="14" height="10" rx="2" />
			<path d="M8 11V8a4 4 0 0 1 8 0v3" />
		</svg>
	</a>
	<?php
}

/**
 * Output the site footer.
 */
function hint_render_footer() {
	$email = hint_get_footer_contact_email();
	?>
	<footer class="hint-footer" role="contentinfo">
		<div class="hint-footer__inner">

			<div class="hint-footer__event">
				<strong class="hint-footer__event-name"><?php esc_html_e( 'HINT 2027', 'hint' ); ?></strong>
				<span><?php esc_html_e( 'International Congress of Human-Centred Inclusive New Technologies', 'hint' ); ?></span>
				<span><?php esc_html_e( 'EACH — University of São Paulo · São Paulo, Brazil', 'hint' ); ?></span>
				<span><?php esc_html_e( '25 February 2027', 'hint' ); ?></span>
				<p class="hint-footer__copy"><?php esc_html_e( '© HINT 2027 | CIATec. All rights reserved.', 'hint' ); ?></p>
			</div>

			<a class="hint-footer__email" href="<?php echo esc_url( 'mailto:' . $email ); ?>">
				<?php echo esc_html( $email ); ?>
			</a>

			<div class="hint-footer__end">
				<?php hint_render_footer_social(); ?>
				<?php hint_render_footer_admin_link(); ?>
			</div>

		</div>
	</footer>
	<?php
}
