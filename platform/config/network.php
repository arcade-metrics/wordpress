<?php
/**
 * Configuração da rede, incluída pelo wp-config.php gerado pela imagem (WORDPRESS_CONFIG_EXTRA).
 * Versionada no repositório (ADR 0002 e 0004). Não editar no servidor.
 *
 * Variáveis de ambiente:
 *   WP_DOMAIN_CURRENT_SITE  domínio principal da rede (ciatec.localhost | ciatec.org)
 *   WP_ENVIRONMENT_TYPE     local | production
 *   WP_NETWORK_READY        "0" só durante a instalação inicial da rede (scripts/setup-local.sh)
 */

// HTTPS terminado no Nginx do host.
if ( ( $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '' ) === 'https' ) {
	$_SERVER['HTTPS'] = 'on';
}

// Multisite em modo subdomínio. Desligado só enquanto as tabelas da rede ainda não existem.
if ( getenv( 'WP_NETWORK_READY' ) !== '0' ) {
	define( 'WP_ALLOW_MULTISITE', true );
	define( 'MULTISITE', true );
	define( 'SUBDOMAIN_INSTALL', true );
	define( 'DOMAIN_CURRENT_SITE', getenv( 'WP_DOMAIN_CURRENT_SITE' ) ?: 'ciatec.localhost' );
	define( 'PATH_CURRENT_SITE', '/' );
	define( 'SITE_ID_CURRENT_SITE', 1 );
	define( 'BLOG_ID_CURRENT_SITE', 1 );
	// Cookie por host: funciona para subdomínios e para domínios próprios mapeados.
	define( 'COOKIE_DOMAIN', '' );
}

define( 'WP_ENVIRONMENT_TYPE', getenv( 'WP_ENVIRONMENT_TYPE' ) ?: 'production' );

// Imagem imutável: nada é instalado ou atualizado pelo painel.
define( 'DISALLOW_FILE_MODS', true );
define( 'DISALLOW_FILE_EDIT', true );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );

define( 'WP_POST_REVISIONS', 5 );
define( 'WP_MEMORY_LIMIT', '96M' );
define( 'WP_MAX_MEMORY_LIMIT', '128M' );

// Polylang: sem cookie de idioma, para o cache do Nginx funcionar (spec 04).
define( 'PLL_COOKIE', false );
