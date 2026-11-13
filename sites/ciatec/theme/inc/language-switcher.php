<?php
/**
 * Language switcher built from Polylang's data (flags only). Prints nothing when Polylang is inactive.
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the flag switcher.
 *
 * 'dropdown' (header): current flag as trigger, the others in a menu.
 * 'inline' (footer): every flag in a row, current one marked.
 *
 * @param string $variant 'dropdown' or 'inline'.
 */
function ciatec_language_switcher( $variant = 'dropdown' ) {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return;
	}

	$languages = pll_the_languages(
		array(
			'raw'           => 1,
			'hide_if_empty' => 0, // show every language even before it has content.
		)
	);
	if ( empty( $languages ) || ! is_array( $languages ) ) {
		return;
	}

	$flags   = array(
		'pt' => 'flag-br.svg',
		'en' => 'flag-uk.svg',
		'es' => 'flag-es.webp',
	);
	$current = null;
	$others  = array();
	foreach ( $languages as $lang ) {
		if ( ! empty( $lang['current_lang'] ) ) {
			$current = $lang;
		} else {
			$others[] = $lang;
		}
	}
	if ( ! $current ) {
		$current = array_shift( $others );
	}

	$flag = static function ( $lang ) use ( $flags ) {
		$file = isset( $flags[ $lang['slug'] ] ) ? $flags[ $lang['slug'] ] : '';
		$src  = $file ? ciatec_asset_url( 'flags/' . $file ) : '';
		if ( ! $src ) {
			return '<span class="ciatec-lang__code" aria-hidden="true">' . esc_html( strtoupper( $lang['slug'] ) ) . '</span>';
		}
		return '<img class="ciatec-lang__flag" src="' . esc_url( $src ) . '" alt="" width="100" height="60" decoding="async">';
	};

	$option = static function ( $lang, $is_current = false ) use ( $flag ) {
		printf(
			'<a class="ciatec-lang__option" href="%1$s" lang="%2$s" hreflang="%2$s" aria-label="%3$s" title="%3$s"%4$s>%5$s</a>',
			esc_url( $lang['url'] ),
			esc_attr( $lang['locale'] ),
			esc_attr( $lang['name'] ),
			$is_current ? ' aria-current="true"' : '',
			$flag( $lang ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in $flag().
		);
	};

	if ( 'inline' === $variant ) {
		?>
		<nav class="ciatec-lang ciatec-lang--inline" aria-label="<?php echo esc_attr( ciatec_t( 'lang_label' ) ); ?>">
			<ul>
				<?php foreach ( $languages as $lang ) : ?>
					<li><?php $option( $lang, ! empty( $lang['current_lang'] ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<?php
		return;
	}
	?>
	<div class="ciatec-lang" data-ciatec-lang>
		<button
			type="button"
			class="ciatec-lang__trigger"
			aria-haspopup="true"
			aria-expanded="false"
			aria-controls="ciatec-lang-menu"
			aria-label="<?php echo esc_attr( ciatec_t( 'lang_label' ) . ': ' . $current['name'] ); ?>"
		>
			<?php echo $flag( $current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in $flag(). ?>
		</button>
		<ul class="ciatec-lang__menu" id="ciatec-lang-menu" hidden>
			<?php foreach ( $others as $lang ) : ?>
				<li><?php $option( $lang ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}
