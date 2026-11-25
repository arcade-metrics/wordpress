<?php
/**
 * Section: Scientific Papers (#papers).
 *
 * Call for abstracts, deadlines, templates and presentation guidelines.
 * The header/hero "Submit a Paper" CTAs point to #papers, so this section
 * must keep that id. Text in both languages goes through hint_pick();
 * styles in assets/css/sections/papers.css.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Poster template (Canva).
 *
 * @return string
 */
function hint_get_poster_template_url() {
	return 'https://www.canva.com/templates/EAHSJ9nebZ4-navy-blue-and-white-modern-academic-research-poster/';
}

/**
 * Output the Papers section.
 */
function hint_render_section_papers() {
	/*
	 * Submission goes through the event page on Even3 for now: the organiser
	 * area link from the brief is not public. When Even3 provides a public
	 * submission link, replace the URL below (or hook the filter).
	 * hint_get_even3_url() is defined in inc/sections/registration.php.
	 */
	$submit_url = apply_filters( 'hint_papers_submit_url', hint_get_even3_url() );

	$deadlines = array(
		array(
			'label' => array(
				'pt' => 'Prazo para submissão de resumos',
				'en' => 'Abstract submission deadline',
			),
			'value' => array(
				'pt' => '31 de janeiro de 2027',
				'en' => '31 January 2027',
			),
		),
		array(
			'label' => array(
				'pt' => 'Notificação de aceite até',
				'en' => 'Notification of acceptance by',
			),
			'value' => array(
				'pt' => '15 de fevereiro de 2027',
				'en' => '15 February 2027',
			),
		),
	);

	$poster_specs = array(
		array(
			'label' => array(
				'pt' => 'Formato',
				'en' => 'Format',
			),
			'text'  => array(
				'pt' => 'A0 (84,1 cm × 118,9 cm), preferencialmente na orientação retrato.',
				'en' => 'A0 (84.1 cm × 118.9 cm), preferably in portrait orientation.',
			),
		),
		array(
			'label' => array(
				'pt' => 'Conteúdo',
				'en' => 'Content',
			),
			'text'  => array(
				'pt' => 'Título do trabalho, autores, instituições de vínculo, introdução, objetivos, metodologia, resultados, conclusão e referências, conforme a natureza do estudo.',
				'en' => 'Title, authors, affiliations, introduction, objectives, methods, results, conclusion and references, as appropriate to the nature of the study.',
			),
		),
		array(
			'label' => array(
				'pt' => 'Organização visual',
				'en' => 'Visual organisation',
			),
			'text'  => array(
				'pt' => 'Distribua as informações de forma clara, com textos objetivos e gráficos e imagens de boa qualidade.',
				'en' => 'Lay out the information clearly, favouring concise text and good-quality charts and images.',
			),
		),
		array(
			'label' => array(
				'pt' => 'Legibilidade',
				'en' => 'Legibility',
			),
			'text'  => array(
				'pt' => 'Use cores com bom contraste entre texto e fundo e evite fundos que dificultem a leitura.',
				'en' => 'Use colours with good contrast between text and background, and avoid backgrounds that make reading difficult.',
			),
		),
		array(
			'label' => array(
				'pt' => 'Imagens e gráficos',
				'en' => 'Images and charts',
			),
			'text'  => array(
				'pt' => 'Use arquivos de boa resolução e não amplie imagens pequenas, para não comprometer a qualidade da impressão.',
				'en' => 'Use high-resolution files and do not enlarge small images, so print quality is not compromised.',
			),
		),
		array(
			'label' => array(
				'pt' => 'Revisão final',
				'en' => 'Final check',
			),
			'text'  => array(
				'pt' => 'Antes de imprimir, confira o tamanho do arquivo, a orientação, as margens, o alinhamento dos elementos e se nenhum conteúdo está cortado.',
				'en' => 'Before printing, check the file size, orientation, margins, alignment of elements, and that no content is cut off.',
			),
		),
	);

	$new_tab = hint_pick(
		array(
			'pt' => '(abre em nova aba)',
			'en' => '(opens in a new tab)',
		)
	);
	?>
	<section class="hint-section hint-section--papers" id="papers" aria-labelledby="papers-title">
		<div class="hint-section__inner">
			<?php
			hint_render_section_heading(
				hint_pick(
					array(
						'pt' => 'Trabalhos',
						'en' => 'Papers',
					)
				),
				hint_pick(
					array(
						'pt' => 'Trabalhos científicos',
						'en' => 'Call for Papers',
					)
				),
				'papers-title'
			);
			?>

			<dl class="hint-papers__deadlines">
				<?php foreach ( $deadlines as $index => $deadline ) : ?>
					<div class="hint-papers__deadline<?php echo 0 === $index ? ' hint-papers__deadline--main' : ''; ?>">
						<dt><?php echo esc_html( hint_pick( $deadline['label'] ) ); ?></dt>
						<dd><?php echo esc_html( hint_pick( $deadline['value'] ) ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>

			<div class="hint-papers__call">
				<div class="hint-papers__call-text">
					<p class="hint-section__lead">
						<?php
						echo esc_html(
							hint_pick(
								array(
									'pt' => 'Envie seu resumo para apresentação no HINT 2027.',
									'en' => 'Submit your abstract for presentation at HINT 2027.',
								)
							)
						);
						?>
					</p>
					<p class="hint-section__text">
						<?php
						/*
						 * Only what the brief states: submission via Even3, the two
						 * deadlines and the templates in assets/downloads/. No length
						 * rule is published until the organisers confirm one.
						 */
						echo esc_html(
							hint_pick(
								array(
									'pt' => 'Prepare o resumo em um dos modelos abaixo e submeta pelo Even3.',
									'en' => 'Prepare your abstract in one of the templates below and submit it through Even3.',
								)
							)
						);
						?>
					</p>
					<ul class="hint-section__actions hint-section__actions--row hint-papers__buttons">
						<li>
							<a class="hint-button hint-button--solid" href="<?php echo esc_url( $submit_url ); ?>" target="_blank" rel="noopener noreferrer">
								<?php
								echo esc_html(
									hint_pick(
										array(
											'pt' => 'Submeter resumo',
											'en' => 'Submit an abstract',
										)
									)
								);
								?>
								<span class="screen-reader-text"><?php echo esc_html( $new_tab ); ?></span>
							</a>
						</li>
						<?php foreach ( hint_get_abstract_templates() as $template ) : ?>
							<li>
								<a class="hint-button hint-button--outline" href="<?php echo esc_url( hint_asset_url( 'downloads/' . $template['file'] ) ); ?>" download>
									<?php echo esc_html( $template['label'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

			</div>

			<?php // Thematic areas: always visible; the list is published when the organisers define it. ?>
			<div class="hint-papers__areas">
				<h3 class="hint-papers__subtitle">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'Áreas temáticas',
								'en' => 'Thematic areas',
							)
						)
					);
					?>
				</h3>
				<p class="hint-section__soon">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'Em breve',
								'en' => 'Coming soon',
							)
						)
					);
					?>
				</p>
			</div>

			<details class="hint-papers__details">
				<summary class="hint-papers__details-head">
					<span class="hint-papers__details-text">
						<span class="hint-papers__details-title">
							<?php
							echo esc_html(
								hint_pick(
									array(
										'pt' => 'Orientações para apresentação dos trabalhos',
										'en' => 'Presentation guidelines',
									)
								)
							);
							?>
						</span>
						<span class="hint-papers__details-hint">
							<?php
							echo esc_html(
								hint_pick(
									array(
										'pt' => 'Mais detalhes: formato da apresentação, especificações do pôster e preparação do arquivo.',
										'en' => 'More details: presentation format, poster specifications and file preparation.',
									)
								)
							);
							?>
						</span>
					</span>
				</summary>
				<div class="hint-papers__details-body">
				<p class="hint-section__text">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'Os trabalhos aprovados serão apresentados na modalidade oral, com apoio de pôster científico impresso, durante o horário de almoço do evento. Os apresentadores receberão certificado de apresentação oral.',
								'en' => 'Accepted papers will be presented orally, supported by a printed scientific poster, during the lunch break of the event. Presenters will receive an oral presentation certificate.',
							)
						)
					);
					?>
				</p>

				<h4 class="hint-papers__minor">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'Especificações do pôster',
								'en' => 'Poster specifications',
							)
						)
					);
					?>
				</h4>
				<ul class="hint-papers__specs">
					<?php foreach ( $poster_specs as $spec ) : ?>
						<li>
							<strong><?php echo esc_html( hint_pick( $spec['label'] ) ); ?></strong>
							<span><?php echo esc_html( hint_pick( $spec['text'] ) ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>

				<p class="hint-section__text">
					<strong>
						<?php
						echo esc_html(
							hint_pick(
								array(
									'pt' => 'Preparação do arquivo:',
									'en' => 'File preparation:',
								)
							)
						);
						?>
					</strong>
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'o pôster pode ser feito no Google Slides, no PowerPoint ou em outro programa, desde que respeite as dimensões indicadas. Ao finalizar, exporte em PDF para impressão e confira a visualização antes de enviar o arquivo à gráfica.',
								'en' => 'the poster can be made in Google Slides, PowerPoint or any other programme, as long as it respects the dimensions above. When finished, export it as a PDF for printing and check how it looks before sending the file to the print shop.',
							)
						)
					);
					?>
				</p>
				<p class="hint-section__text">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'Os apresentadores devem levar o pôster impresso e estar disponíveis no horário indicado pela organização para apresentar o trabalho e conversar com os participantes.',
								'en' => 'Presenters should bring the printed poster and be available at the time set by the organisers to present their work and talk with attendees.',
							)
						)
					);
					?>
				</p>
				<p class="hint-section__actions hint-papers__poster-link">
					<a class="hint-button hint-button--outline" href="<?php echo esc_url( hint_get_poster_template_url() ); ?>" target="_blank" rel="noopener noreferrer">
						<?php
						echo esc_html(
							hint_pick(
								array(
									'pt' => 'Modelo de pôster no Canva',
									'en' => 'Poster template on Canva',
								)
							)
						);
						?>
						<span class="screen-reader-text"><?php echo esc_html( $new_tab ); ?></span>
					</a>
				</p>
				</div>
			</details>
		</div>
	</section>
	<?php
}
