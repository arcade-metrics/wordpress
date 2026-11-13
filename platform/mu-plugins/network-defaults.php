<?php
/**
 * Plugin Name: Rede CIATec — padrões
 * Description: Ajustes de segurança e limpeza válidos para todos os sites da rede. Versionado em platform/mu-plugins.
 */

defined( 'ABSPATH' ) || exit;

// Sem XML-RPC (nenhum site usa).
add_filter( 'xmlrpc_enabled', '__return_false' );

// Não expor a versão do WordPress.
remove_action( 'wp_head', 'wp_generator' );

// Sem enumeração de usuários por ?author=N.
add_action(
	'template_redirect',
	static function () {
		if ( ! is_admin() && isset( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
);

// Sem endpoints REST de usuários para visitantes.
add_filter(
	'rest_endpoints',
	static function ( $endpoints ) {
		if ( ! is_user_logged_in() ) {
			unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
		return $endpoints;
	}
);
