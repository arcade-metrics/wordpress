<?php
/**
 * "Coming soon" placeholder page.
 *
 * Register and Submit a Paper are not open yet. Their buttons lead to a virtual
 * page served by the theme at /coming-soon/ (no WordPress page, no rewrite
 * flush, nothing in the database). When the real flows exist, point the
 * `hint_register_url` / `hint_submit_url` filters at them and this page can go.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Copy for each placeholder topic.
 *
 * @return array<string,array{title:string,text:string}>
 */
function hint_get_coming_soon_topics() {
	return array(
		'register' => array(
			'title' => __( 'Registration', 'hint' ),
			'text'  => __( 'Registration is not open yet. Fees, categories and dates will be announced on this website.', 'hint' ),
		),
		'submit'   => array(
			'title' => __( 'Paper submission', 'hint' ),
			'text'  => __( 'The submission system is not open yet. Deadlines and instructions will be announced on this website.', 'hint' ),
		),
	);
}

/**
 * URL of the placeholder page.
 *
 * Pretty (/coming-soon/) when permalinks are enabled; otherwise a query
 * string, so it also works on installs without rewrite rules.
 *
 * @param string $topic register|submit.
 * @return string
 */
function hint_coming_soon_url( $topic = '' ) {
	if ( get_option( 'permalink_structure' ) ) {
		$url = home_url( '/coming-soon/' );

		return $topic ? add_query_arg( 'for', $topic, $url ) : $url;
	}

	return add_query_arg( 'hint_soon', $topic ? $topic : '1', home_url( '/' ) );
}

/**
 * Whether the current request is for the placeholder page.
 *
 * @return bool
 */
function hint_is_coming_soon() {
	static $is = null;

	if ( null === $is ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['hint_soon'] ) ) {
			$is = true;

			return $is;
		}

		if ( ! get_option( 'permalink_structure' ) ) {
			$is = false;

			return $is;
		}

		$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
		$target  = wp_parse_url( hint_coming_soon_url(), PHP_URL_PATH );
		$is      = is_string( $request ) && untrailingslashit( $request ) === untrailingslashit( $target );
	}

	return $is;
}

/**
 * Serve the placeholder page before WordPress resolves a 404.
 */
function hint_serve_coming_soon() {
	if ( is_admin() || ! hint_is_coming_soon() ) {
		return;
	}

	global $wp_query;

	$wp_query->is_404 = false;
	status_header( 200 );
	nocache_headers();
	add_filter( 'wp_robots', 'wp_robots_no_robots' );
	add_filter(
		'pre_get_document_title',
		static function () {
			return __( 'Coming soon', 'hint' ) . ' — ' . get_bloginfo( 'name' );
		}
	);

	get_header();
	hint_render_coming_soon();
	get_footer();
	exit;
}
add_action( 'template_redirect', 'hint_serve_coming_soon', 1 );

/**
 * Output the placeholder page body.
 */
function hint_render_coming_soon() {
	$topics = hint_get_coming_soon_topics();
	$param  = isset( $_GET['hint_soon'] ) ? $_GET['hint_soon'] : ( isset( $_GET['for'] ) ? $_GET['for'] : '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$key    = sanitize_key( wp_unslash( $param ) );
	$topic  = isset( $topics[ $key ] ) ? $topics[ $key ] : null;
	$email  = hint_get_footer_contact_email();
	?>
	<main class="hint-soon" id="content">
		<div class="hint-soon__box">
			<p class="hint-soon__eyebrow">
				<?php echo esc_html( $topic ? $topic['title'] : __( 'HINT 2027', 'hint' ) ); ?>
			</p>
			<h1 class="hint-soon__title"><?php esc_html_e( 'Coming soon', 'hint' ); ?></h1>
			<p class="hint-soon__text">
				<?php echo esc_html( $topic ? $topic['text'] : __( 'This page is not available yet. More information will be announced on this website.', 'hint' ) ); ?>
			</p>
			<p class="hint-soon__meta">
				<?php esc_html_e( '25 February 2027 · São Paulo, Brazil', 'hint' ); ?>
			</p>
			<div class="hint-soon__actions">
				<a class="hint-button hint-button--solid" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php esc_html_e( 'Back to home', 'hint' ); ?>
				</a>
				<a class="hint-button hint-button--outline" href="<?php echo esc_url( 'mailto:' . $email ); ?>">
					<?php esc_html_e( 'Contact us', 'hint' ); ?>
				</a>
			</div>
		</div>
	</main>
	<?php
}
