<?php
/**
 * Plugin Name: Rede CIATec — versão no ar
 * Description: Cabeçalho X-Release com a tag da imagem (WP_RELEASE, ex.: sha-abc1234). Usado para conferir um deploy.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'send_headers',
	static function () {
		$release = getenv( 'WP_RELEASE' );
		if ( $release && preg_match( '/^[A-Za-z0-9._-]{1,64}$/', $release ) ) {
			header( 'X-Release: ' . $release );
		}
	}
);
