<?php
/**
 * Section: Organisers and supporters (#partners).
 *
 * Last section of the page: the logos of the organising and supporting
 * institutions (assets/brand/partners/<slug>.svg). Not a nav item. Text in
 * both languages goes through hint_pick(); styles in
 * assets/css/sections/partners.css.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Organising and supporting institutions.
 *
 * Each slug maps to assets/brand/partners/<slug>.svg. An empty url renders
 * the logo without a link.
 *
 * @return array<int,array{slug:string,url:string,alt:string}>
 */
function hint_get_partners() {
	return array(
		array(
			'slug' => 'each',
			'url'  => 'https://each.usp.br/',
			'alt'  => hint_pick(
				array(
					'pt' => 'EACH-USP, Escola de Artes, Ciências e Humanidades da Universidade de São Paulo',
					'en' => 'EACH-USP, School of Arts, Sciences and Humanities, University of São Paulo',
				)
			),
		),
		array(
			'slug' => 'ciatec',
			'url'  => 'https://ciatec.org/',
			'alt'  => hint_pick(
				array(
					'pt' => 'CIATec, Centro de Inclusão e Acessibilidade por meio da Tecnologia',
					'en' => 'CIATec, Centre for Inclusion and Accessibility through Technology',
				)
			),
		),
		array(
			'slug' => 'pater',
			'url'  => '',
			'alt'  => hint_pick(
				array(
					'pt' => 'PATER-USP, Grupo de Pesquisa e Aplicação Tecnológica em Reabilitação da Universidade de São Paulo',
					'en' => 'PATER-USP, Rehabilitation Technology Research and Application Group, University of São Paulo',
				)
			),
		),
		array(
			'slug' => 'fapesp',
			'url'  => 'https://fapesp.br/',
			'alt'  => hint_pick(
				array(
					'pt' => 'FAPESP, Fundação de Amparo à Pesquisa do Estado de São Paulo',
					'en' => 'FAPESP, São Paulo Research Foundation',
				)
			),
		),
	);
}

/**
 * Output a partner logo as inline SVG.
 *
 * Inline (not <img>) so the monochrome parts, drawn with currentColor in the
 * SVG files, follow the theme: black on light, white on dark and high
 * contrast (partners.css). The SVG is decorative; the wrapper carries the name.
 *
 * @param string $slug Partner slug (file name without .svg).
 */
function hint_render_partner_logo( $slug ) {
	$file = HINT_THEME_DIR . '/assets/brand/partners/' . sanitize_file_name( $slug ) . '.svg';

	if ( ! is_readable( $file ) ) {
		return;
	}

	$svg = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme asset.

	echo preg_replace( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted theme asset.
		'/<svg\b/',
		'<svg class="hint-partners__logo" aria-hidden="true" focusable="false"',
		$svg,
		1
	);
}

/**
 * Output the Organisers and supporters section.
 */
function hint_render_section_partners() {
	?>
	<section class="hint-section hint-section--partners" id="partners" aria-labelledby="partners-title">
		<div class="hint-section__inner">
			<h2 class="hint-partners__title" id="partners-title">
				<?php
				echo esc_html(
					hint_pick(
						array(
							'pt' => 'Organização e apoio',
							'en' => 'Organisers and supporters',
						)
					)
				);
				?>
			</h2>
			<ul class="hint-partners__logos">
				<?php foreach ( hint_get_partners() as $partner ) : ?>
					<li class="hint-partners__item hint-partners__item--<?php echo esc_attr( $partner['slug'] ); ?>">
						<?php if ( $partner['url'] ) : ?>
							<a class="hint-partners__link" href="<?php echo esc_url( $partner['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $partner['alt'] ); ?>">
								<?php hint_render_partner_logo( $partner['slug'] ); ?>
							</a>
						<?php else : ?>
							<span class="hint-partners__link" role="img" aria-label="<?php echo esc_attr( $partner['alt'] ); ?>">
								<?php hint_render_partner_logo( $partner['slug'] ); ?>
							</span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
}
