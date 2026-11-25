<?php
/**
 * Section: Registration (#registration).
 *
 * Free event; registration goes through Even3. The header/hero "Register"
 * CTAs point to #registration, so this section must keep that id. Text in
 * both languages goes through hint_pick(); styles in
 * assets/css/sections/registration.css.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Event page on Even3 (registration form).
 *
 * @return string
 */
function hint_get_even3_url() {
	return 'https://www.even3.com.br/international-congress-of-human-centred-inclusive-new-technologies-hint-2027-791040';
}

/**
 * Output the Registration section.
 */
function hint_render_section_registration() {
	$even3_url = hint_get_even3_url();
	?>
	<section class="hint-section hint-section--registration" id="registration" aria-labelledby="registration-title">
		<div class="hint-section__inner hint-section__inner--split">
			<div class="hint-registration__intro">
				<?php
				hint_render_section_heading(
					hint_pick(
						array(
							'pt' => 'Inscrições',
							'en' => 'Registration',
						)
					),
					hint_pick(
						array(
							'pt' => 'Inscrições',
							'en' => 'Registration',
						)
					),
					'registration-title'
				);
				?>
				<p class="hint-section__lead">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'A participação no HINT 2027 é gratuita.',
								'en' => 'Attendance at HINT 2027 is free of charge.',
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
								'pt' => 'As inscrições estão abertas até 18/02/2027 e estão sujeitas ao número de vagas.',
								'en' => 'Registration is open until February 18, 2027, subject to seat availability.',
							)
						)
					);
					?>
				</p>
			</div>

			<div class="hint-registration__action">
				<p class="hint-registration__cta">
					<?php // Themed text button in both languages: no hotlinked Even3 image (external dependency, PT-only artwork). ?>
					<a class="hint-button hint-button--solid hint-even3" href="<?php echo esc_url( $even3_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php
						echo esc_html(
							hint_pick(
								array(
									'pt' => 'Inscreva-se no Even3',
									'en' => 'Register on Even3',
								)
							)
						);
						?>
						<span class="screen-reader-text">
							<?php
							echo esc_html(
								hint_pick(
									array(
										'pt' => '(abre em nova aba)',
										'en' => '(opens in a new tab)',
									)
								)
							);
							?>
						</span>
					</a>
				</p>
				<p class="hint-section__text hint-registration__access">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'No formulário de inscrição você pode informar recursos ou adaptações de acessibilidade de que precisa.',
								'en' => 'In the registration form you can tell us about any accessibility resources or adaptations you need.',
							)
						)
					);
					?>
					<a class="hint-registration__link" href="#accessibility">
						<?php
						echo esc_html(
							hint_pick(
								array(
									'pt' => 'Saiba mais sobre acessibilidade',
									'en' => 'Learn more about accessibility',
								)
							)
						);
						?>
					</a>
				</p>

				<div class="hint-registration__calendar">
					<h3 class="hint-registration__calendar-title">
						<?php
						echo esc_html(
							hint_pick(
								array(
									'pt' => 'Salvar na agenda',
									'en' => 'Add to calendar',
								)
							)
						);
						?>
					</h3>
					<p class="hint-section__text">
						<?php
						echo esc_html(
							hint_pick(
								array(
									'pt' => '25 de fevereiro de 2027, das 8h às 18h (horário de Brasília).',
									'en' => '25 February 2027, 8:00 am to 6:00 pm (Brasília time, UTC-3).',
								)
							)
						);
						?>
					</p>
					<ul class="hint-section__actions hint-section__actions--row hint-registration__calendar-links">
						<?php foreach ( hint_get_calendar_links() as $link ) : ?>
							<li>
								<a class="hint-button hint-button--outline" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['external'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
									<?php hint_render_calendar_icon( $link['icon'] ); ?>
									<?php echo esc_html( $link['label'] ); ?>
									<?php if ( $link['external'] ) : ?>
										<span class="screen-reader-text">
											<?php
											echo esc_html(
												hint_pick(
													array(
														'pt' => '(abre em nova aba)',
														'en' => '(opens in a new tab)',
													)
												)
											);
											?>
										</span>
									<?php endif; ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<?php
}
