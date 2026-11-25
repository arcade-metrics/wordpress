<?php
/**
 * Section: Accessibility and Inclusion (#accessibility).
 *
 * The congress's accessibility commitment, how to request resources or
 * adaptations (registration form) and accessible parking. Text in both
 * languages goes through hint_pick(); styles in assets/css/sections/accessibility.css.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output the Accessibility section.
 */
function hint_render_section_accessibility() {
	$intro = array(
		hint_pick(
			array(
				'pt' => 'O International Congress of Human-Centred Inclusive New Technologies – HINT 2027, organizado pelo CIATec – Centro de Inclusão e Acessibilidade por meio da Tecnologia, busca promover um ambiente inclusivo, acolhedor e centrado nas pessoas.',
				'en' => 'The International Congress of Human-Centred Inclusive New Technologies – HINT 2027, organised by CIATec – Centre for Inclusion and Accessibility through Technology, aims to foster an inclusive, welcoming and people-centred environment.',
			)
		),
		hint_pick(
			array(
				'pt' => 'Reconhecemos a importância de identificar e reduzir barreiras que possam dificultar a participação e o acesso ao conhecimento. Por isso, buscamos considerar as diferentes necessidades dos participantes no planejamento do evento, contemplando aspectos físicos, comunicacionais e digitais.',
				'en' => "We recognise the importance of identifying and reducing barriers that can hinder participation and access to knowledge. For this reason, we seek to take participants' different needs into account when planning the event, covering physical, communication and digital aspects.",
			)
		),
	);

	$question = hint_pick(
		array(
			'pt' => 'Necessita de algum recurso ou adaptação de acessibilidade para participar?',
			'en' => 'Do you need any accessibility resource or adaptation to take part?',
		)
	);

	$form = hint_pick(
		array(
			'pt' => 'No formulário de inscrição, você poderá informar quais recursos ou adaptações são necessários para sua participação. Essas informações auxiliarão a comissão organizadora no planejamento das medidas de acessibilidade e na avaliação das possibilidades de atendimento.',
			'en' => 'In the registration form you can tell us which resources or adaptations you need to take part. This information will help the organising committee plan accessibility measures and assess what can be provided.',
		)
	);

	$parking = hint_pick(
		array(
			'pt' => 'Há estacionamento próximo ao auditório, com vagas especiais destinadas a pessoas que necessitam desse recurso, facilitando o acesso ao local do evento.',
			'en' => 'There is parking close to the auditorium, with special spaces reserved for people who need them, making it easier to reach the venue.',
		)
	);

	$closing = hint_pick(
		array(
			'pt' => 'O HINT 2027 acredita que a ciência, a tecnologia e a inovação devem contribuir para uma sociedade mais acessível, inclusiva e centrada nas necessidades humanas.',
			'en' => 'HINT 2027 believes that science, technology and innovation should contribute to a society that is more accessible, more inclusive and centred on human needs.',
		)
	);
	?>
	<section class="hint-section hint-section--accessibility" id="accessibility" aria-labelledby="accessibility-title">
		<div class="hint-section__inner">
			<?php
			hint_render_section_heading(
				hint_pick(
					array(
						'pt' => 'Acessibilidade',
						'en' => 'Accessibility',
					)
				),
				hint_pick(
					array(
						'pt' => 'Acessibilidade e inclusão',
						'en' => 'Accessibility and Inclusion',
					)
				),
				'accessibility-title'
			);
			?>
			<div class="hint-a11y-section__grid">
				<div class="hint-a11y-section__main">
					<?php foreach ( $intro as $paragraph ) : ?>
						<p class="hint-section__text"><?php echo esc_html( $paragraph ); ?></p>
					<?php endforeach; ?>

					<h3 class="hint-a11y-section__question"><?php echo esc_html( $question ); ?></h3>
					<p class="hint-section__text"><?php echo esc_html( $form ); ?></p>
					<p class="hint-section__actions">
						<a class="hint-button hint-button--solid" href="#registration">
							<?php
							echo esc_html(
								hint_pick(
									array(
										'pt' => 'Ir para a inscrição',
										'en' => 'Go to registration',
									)
								)
							);
							?>
						</a>
					</p>
				</div>

				<div class="hint-a11y-section__aside">
					<div class="hint-a11y-section__card">
						<h3 class="hint-a11y-section__card-title">
							<?php
							echo esc_html(
								hint_pick(
									array(
										'pt' => 'Estacionamento',
										'en' => 'Parking',
									)
								)
							);
							?>
						</h3>
						<p class="hint-section__text"><?php echo esc_html( $parking ); ?></p>
					</div>
				</div>
			</div>

			<p class="hint-a11y-section__closing"><?php echo esc_html( $closing ); ?></p>
		</div>
	</section>
	<?php
}
