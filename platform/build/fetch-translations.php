<?php
/**
 * Baixa os pacotes de idioma oficiais (core, plugins e temas do plugins.lock) para dentro da imagem.
 * A rede antiga os instalava em tempo de execução (`wp language core install`); com a imagem imutável
 * (DISALLOW_FILE_MODS) eles precisam vir no build.
 *
 * Uso (estágio final do Dockerfile): php fetch-translations.php <plugins.lock> <raiz do WordPress> <locales...>
 */

[ , $lock, $root ] = $argv;
$locales = array_slice( $argv, 3 );

require "{$root}/wp-includes/version.php"; // define $wp_version

function api( string $url ): array {
	$json = @file_get_contents( $url );
	if ( false === $json ) {
		fwrite( STDERR, "falha ao consultar {$url}\n" );
		exit( 1 );
	}
	return json_decode( $json, true )['translations'] ?? array();
}

function install( array $translations, array $locales, string $dest, string $label ): void {
	@mkdir( $dest, 0755, true );
	$found = array();
	foreach ( $translations as $t ) {
		if ( ! in_array( $t['language'], $locales, true ) ) {
			continue;
		}
		$zip_file = tempnam( sys_get_temp_dir(), 'l10n' );
		if ( false === @copy( $t['package'], $zip_file ) ) {
			fwrite( STDERR, "falha ao baixar {$t['package']}\n" );
			exit( 1 );
		}
		$zip = new ZipArchive();
		if ( true !== $zip->open( $zip_file ) || ! $zip->extractTo( $dest ) ) {
			fwrite( STDERR, "zip inválido: {$t['package']}\n" );
			exit( 1 );
		}
		$zip->close();
		unlink( $zip_file );
		$found[] = $t['language'];
	}
	$missing = array_diff( $locales, $found );
	echo "==> {$label}: " . implode( ' ', $found ) . ( $missing ? ' (sem pacote: ' . implode( ' ', $missing ) . ')' : '' ) . "\n";
}

$base = "{$root}/wp-content/languages";
install( api( "https://api.wordpress.org/translations/core/1.0/?version={$wp_version}" ), $locales, $base, "core {$wp_version}" );

foreach ( file( $lock, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES ) as $line ) {
	$parts = preg_split( '/\s+/', trim( $line ) );
	if ( '' === $parts[0] || '#' === $parts[0][0] || count( $parts ) < 3 ) {
		continue;
	}
	[ $type, $slug, $version ] = $parts;
	$kind = 'plugin' === $type ? 'plugins' : 'themes';
	install(
		api( "https://api.wordpress.org/translations/{$kind}/1.0/?slug={$slug}&version={$version}" ),
		$locales,
		"{$base}/{$kind}",
		"{$type} {$slug} {$version}"
	);
}
