<?php
/**
 * Section: About the Congress (#about).
 *
 * Official theme, the three presentation paragraphs (event proposal, 2030
 * Agenda / SDG 10, SDG 4, 9 and 11). Text in both languages goes through hint_pick();
 * styles in assets/css/sections/about.css.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output the About section.
 */
function hint_render_section_about() {
	$paragraphs = array(
		hint_pick(
			array(
				'pt' => 'Esse evento propõe um espaço de integração entre conhecimento científico, inovação e responsabilidade social, apresentando recursos de tecnologia assistiva e outras soluções tecnológicas voltadas à promoção da autonomia, acessibilidade e qualidade de vida. A proposta busca aproximar os participantes de tecnologias que possam contribuir para a superação de barreiras físicas, funcionais, comunicacionais e sociais, estimulando uma perspectiva de inclusão baseada nas potencialidades e na participação ativa do indivíduo.',
				'en' => "The congress offers a space where scientific knowledge, innovation and social responsibility meet, showcasing assistive technology and other technological solutions that promote autonomy, accessibility and quality of life. It aims to bring participants closer to technologies that can help overcome physical, functional, communication and social barriers, encouraging an approach to inclusion grounded in each person's strengths and active participation.",
			)
		),
		hint_pick(
			array(
				'pt' => 'A proposta está alinhada à Agenda 2030 para o Desenvolvimento Sustentável, especialmente ao ODS 10 – Redução das Desigualdades, que orienta a promoção da inclusão social, econômica e política de todas as pessoas, independentemente de idade ou deficiência. Nesse contexto, a tecnologia é compreendida como instrumento capaz de reduzir desigualdades e ampliar oportunidades de participação social.',
				'en' => 'The proposal is aligned with the 2030 Agenda for Sustainable Development, especially SDG 10 – Reduced Inequalities, which calls for the social, economic and political inclusion of all people, regardless of age or disability. In this context, technology is understood as a means of reducing inequalities and widening opportunities for social participation.',
			)
		),
		hint_pick(
			array(
				'pt' => 'O evento também se relaciona ao ODS 4 – Educação de Qualidade, ao promover acesso ao conhecimento e desenvolvimento de competências relacionadas ao uso de tecnologias; ao ODS 9 – Indústria, Inovação e Infraestrutura, ao incentivar a divulgação e utilização de soluções inovadoras e tecnologias assistivas; e ao ODS 11 – Cidades e Comunidades Sustentáveis, ao abordar recursos que favoreçam ambientes mais acessíveis, inclusivos e seguros para pessoas idosas e pessoas com deficiência.',
				'en' => 'The event also relates to SDG 4 – Quality Education, by promoting access to knowledge and the development of technology-related skills; to SDG 9 – Industry, Innovation and Infrastructure, by encouraging the dissemination and use of innovative solutions and assistive technologies; and to SDG 11 – Sustainable Cities and Communities, by addressing resources that make environments more accessible, inclusive and safe for older people and people with disabilities.',
			)
		),
	);
	?>
	<section class="hint-section hint-section--about" id="about" aria-labelledby="about-title">
		<div class="hint-section__inner">
			<?php
			hint_render_section_heading(
				hint_pick(
					array(
						'pt' => 'Sobre',
						'en' => 'About',
					)
				),
				hint_pick(
					array(
						'pt' => 'Sobre o congresso',
						'en' => 'About the Congress',
					)
				),
				'about-title'
			);
			?>
			<div class="hint-about__body">
				<p class="hint-section__lead"><?php echo esc_html( hint_get_event_theme() ); ?></p>
				<div class="hint-about__copy">
					<?php foreach ( $paragraphs as $paragraph ) : ?>
						<p class="hint-section__text"><?php echo esc_html( $paragraph ); ?></p>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}
