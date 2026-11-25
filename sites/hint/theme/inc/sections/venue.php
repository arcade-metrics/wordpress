<?php
/**
 * Section: Getting there and where to stay (#venue).
 *
 * Four blocks: the venue (address + embedded map), "Plan your arrival"
 * (one <details> per way of arriving), "Where to stay" (two regions with
 * search links, no hotel is named or endorsed) and "Discover São Paulo"
 * (official tourism sources). Transport details are to be reviewed by the
 * organisation before publication (spec 21).
 *
 * Text in both languages goes through hint_pick(); styles in
 * assets/css/sections/venue.css.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Embedded map URL for the venue (Google Maps embed, no API key).
 *
 * @return string
 */
function hint_get_venue_embed_url() {
	return add_query_arg(
		array(
			'q'      => rawurlencode( 'EACH USP, Rua Arlindo Béttio, 1000, São Paulo' ),
			'z'      => 14,
			'hl'     => 'pt' === hint_lang() ? 'pt-BR' : 'en',
			'output' => 'embed',
		),
		'https://www.google.com/maps'
	);
}

/**
 * Ways of arriving, one per <details> panel.
 *
 * @return array<int,array{icon:string,title:string,summary:string,paragraphs:string[],items:string[],links:array<int,array{label:string,url:string}>}>
 */
function hint_get_venue_arrival() {
	$panels = array(
		array(
			'icon'       => 'train',
			'title'      => array(
				'pt' => 'Trem e metrô',
				'en' => 'Train and metro',
			),
			'summary'    => array(
				'pt' => 'A estação USP Leste fica na entrada do campus.',
				'en' => 'USP Leste station is at the campus entrance.',
			),
			'paragraphs' => array(
				array(
					'pt' => 'A estação USP Leste, da Linha 12-Safira da CPTM, dá acesso direto ao campus, sem precisar de ônibus.',
					'en' => 'USP Leste station, on CPTM Line 12-Sapphire (Linha 12-Safira), gives direct access to the campus, with no bus needed.',
				),
			),
			'items'      => array(
				array(
					'pt' => 'Do metrô Tatuapé (Linha 3-Vermelha): Linha 12-Safira até USP Leste.',
					'en' => 'From Tatuapé metro (Line 3-Red): Line 12-Sapphire to USP Leste.',
				),
				array(
					'pt' => 'Do metrô Brás (Linha 3-Vermelha): Linha 12-Safira até USP Leste.',
					'en' => 'From Brás metro (Line 3-Red): Line 12-Sapphire to USP Leste.',
				),
				array(
					'pt' => 'Da estação da Luz: siga até o Brás e faça a transferência para a Linha 12-Safira.',
					'en' => 'From Luz station: go to Brás and change to Line 12-Sapphire.',
				),
			),
			'links'      => array(
				array(
					'label' => array(
						'pt' => 'Mapa e horários da CPTM',
						'en' => 'CPTM map and timetables',
					),
					'url'   => 'https://www.cptm.sp.gov.br/',
				),
			),
		),
		array(
			'icon'       => 'plane',
			'title'      => array(
				'pt' => 'Avião',
				'en' => 'By air',
			),
			'summary'    => array(
				'pt' => 'Aeroportos de Guarulhos e Congonhas.',
				'en' => 'Guarulhos and Congonhas airports.',
			),
			'paragraphs' => array(
				array(
					'pt' => 'Aeroporto de Guarulhos (GRU): pegue a Linha 13-Jade até Engenheiro Goulart e faça a transferência para a Linha 12-Safira; USP Leste é a estação seguinte. A estação do aeroporto fica fora dos terminais, e um ônibus gratuito faz a ligação.',
					'en' => 'Guarulhos Airport (GRU): take Line 13-Jade to Engenheiro Goulart and change to Line 12-Sapphire; USP Leste is the next station. The airport station is outside the terminals, and a free shuttle bus links them.',
				),
				array(
					'pt' => 'Aeroporto de Congonhas (CGH): o trajeto mais direto é de táxi ou aplicativo. Por transporte público, siga até o metrô Tatuapé ou Brás e pegue a Linha 12-Safira.',
					'en' => 'Congonhas Airport (CGH): a taxi or ride-hailing app is the most direct option. By public transport, go to Tatuapé or Brás metro and take Line 12-Sapphire.',
				),
			),
			'items'      => array(),
			'links'      => array(
				array(
					'label' => array(
						'pt' => 'Trem do Aeroporto de Guarulhos',
						'en' => 'Guarulhos Airport train',
					),
					'url'   => 'https://www.gru.com.br/pt/passageiro/como-chegar-sair/trem/',
				),
			),
		),
		array(
			'icon'       => 'bus',
			'title'      => array(
				'pt' => 'Ônibus',
				'en' => 'Bus',
			),
			'summary'    => array(
				'pt' => 'Linhas municipais que atendem a região.',
				'en' => 'City bus lines serving the area.',
			),
			'paragraphs' => array(
				array(
					'pt' => 'Linhas municipais que passam pela região do campus. Confira itinerários e horários antes de sair.',
					'en' => 'City bus lines that serve the campus area. Check routes and timetables before you travel.',
				),
			),
			'items'      => array(
				array(
					'pt' => '2762: Metrô Tatuapé / Ermelino Matarazzo',
					'en' => '2762: Tatuapé metro / Ermelino Matarazzo',
				),
				array(
					'pt' => '2735-10: Jardim Keralux',
					'en' => '2735-10: Jardim Keralux',
				),
				array(
					'pt' => '2076: Terminal Penha / Jardim das Oliveiras',
					'en' => '2076: Penha Terminal / Jardim das Oliveiras',
				),
				array(
					'pt' => '1178: Praça do Correio / São Miguel',
					'en' => '1178: Praça do Correio / São Miguel',
				),
			),
			'links'      => array(
				array(
					'label' => array(
						'pt' => 'Consultar linhas na SPTrans',
						'en' => 'Look up lines at SPTrans',
					),
					'url'   => 'https://www.sptrans.com.br/',
				),
			),
		),
		array(
			'icon'       => 'car',
			'title'      => array(
				'pt' => 'Carro e aplicativo',
				'en' => 'Car and ride-hailing',
			),
			'summary'    => array(
				'pt' => 'Endereço para o GPS e para aplicativos.',
				'en' => 'Address for GPS and ride-hailing apps.',
			),
			'paragraphs' => array(
				array(
					'pt' => 'Use como destino: EACH-USP, Rua Arlindo Béttio, 1000, Ermelino Matarazzo.',
					'en' => 'Set your destination to: EACH-USP, Rua Arlindo Béttio, 1000, Ermelino Matarazzo.',
				),
				array(
					'pt' => 'As orientações de estacionamento serão divulgadas em breve.',
					'en' => 'Parking guidance will be published soon.',
				),
			),
			'items'      => array(),
			'links'      => array(
				array(
					'label' => array(
						'pt' => 'Como chegar, no site da EACH',
						'en' => 'Getting there, on the EACH website',
					),
					'url'   => 'https://www5.each.usp.br/como-chegar/',
				),
			),
		),
	);

	return array_map( 'hint_pick_deep', $panels );
}

/**
 * Regions suggested for accommodation, with neutral search links.
 *
 * No hotel is named: the links open a search for the region on each platform,
 * pre-filled with the night before and the night of the event (24–26 Feb 2027).
 *
 * @return array<int,array{title:string,tag:string,text:string,links:array<int,array{label:string,url:string}>}>
 */
function hint_get_venue_stay() {
	$regions = array(
		array(
			'title' => 'Tatuapé',
			'tag'   => array(
				'pt' => 'Mais prático',
				'en' => 'Most convenient',
			),
			'text'  => array(
				'pt' => 'Bairro com metrô (Linha 3-Vermelha) e trem direto para a USP Leste pela Linha 12-Safira, além de shopping, restaurantes e serviços.',
				'en' => 'A neighbourhood with metro (Line 3-Red) and a direct train to USP Leste on Line 12-Sapphire, plus a shopping centre, restaurants and services.',
			),
			'links' => array(
				array(
					'label' => array(
						'pt' => 'Buscar no Booking',
						'en' => 'Search on Booking',
					),
					'url'   => 'https://www.booking.com/searchresults.html?ss=Tatuap%C3%A9%2C+S%C3%A3o+Paulo&checkin=2027-02-24&checkout=2027-02-26',
				),
				array(
					'label' => array(
						'pt' => 'Buscar no Airbnb',
						'en' => 'Search on Airbnb',
					),
					'url'   => 'https://www.airbnb.com/s/Tatuap%C3%A9--S%C3%A3o-Paulo--Brazil/homes?checkin=2027-02-24&checkout=2027-02-26',
				),
			),
		),
		array(
			'title' => 'Guarulhos',
			'tag'   => array(
				'pt' => 'Para quem chega de avião',
				'en' => 'If you arrive by air',
			),
			'text'  => array(
				'pt' => 'Perto do aeroporto internacional. A Linha 13-Jade leva até Engenheiro Goulart, a uma estação da USP Leste. Alguns hotéis exigem deslocamento até a estação.',
				'en' => 'Close to the international airport. Line 13-Jade runs to Engenheiro Goulart, one station from USP Leste. Some hotels require a transfer to the station.',
			),
			'links' => array(
				array(
					'label' => array(
						'pt' => 'Buscar no Booking',
						'en' => 'Search on Booking',
					),
					'url'   => 'https://www.booking.com/searchresults.html?ss=Guarulhos%2C+S%C3%A3o+Paulo&checkin=2027-02-24&checkout=2027-02-26',
				),
				array(
					'label' => array(
						'pt' => 'Buscar no Airbnb',
						'en' => 'Search on Airbnb',
					),
					'url'   => 'https://www.airbnb.com/s/Guarulhos--S%C3%A3o-Paulo--Brazil/homes?checkin=2027-02-24&checkout=2027-02-26',
				),
			),
		),
	);

	return array_map( 'hint_pick_deep', $regions );
}

/**
 * Official tourism sources for "Discover São Paulo".
 *
 * @return array<int,array{icon:string,label:string,text:string,url:string}>
 */
function hint_get_venue_city_links() {
	$links = array(
		array(
			'icon'  => 'landmark',
			'label' => 'Visite São Paulo',
			'text'  => array(
				'pt' => 'Turismo, gastronomia, cultura e programação.',
				'en' => 'Tourism, food, culture and events.',
			),
			'url'   => 'https://visitesaopaulo.com/',
		),
		array(
			'icon'  => 'route',
			'label' => 'Vai de Roteiro',
			'text'  => array(
				'pt' => 'Passeios guiados gratuitos da Prefeitura de São Paulo.',
				'en' => 'Free guided tours run by the City of São Paulo.',
			),
			'url'   => 'https://cidadedesaopaulo.com/vaideroteiro/',
		),
		array(
			'icon'  => 'metro',
			'label' => 'Turismetrô',
			'text'  => array(
				'pt' => 'Atrações próximas às estações de metrô.',
				'en' => 'Attractions close to metro stations.',
			),
			'url'   => 'https://www.metro.sp.gov.br/sua-viagem/turismetro/',
		),
		array(
			'icon'  => 'globe',
			'label' => 'Visit Brasil',
			'text'  => array(
				'pt' => 'Guia geral do país e da cidade.',
				'en' => 'A general guide to the country and the city.',
			),
			'url'   => 'https://www.visitbrasil.com/',
		),
	);

	return array_map( 'hint_pick_deep', $links );
}

/**
 * Resolve every bilingual array( 'pt' => ..., 'en' => ... ) inside a structure.
 *
 * @param mixed $value Nested data.
 * @return mixed
 */
function hint_pick_deep( $value ) {
	if ( ! is_array( $value ) ) {
		return $value;
	}

	if ( isset( $value['pt'], $value['en'] ) ) {
		return hint_pick( $value );
	}

	return array_map( 'hint_pick_deep', $value );
}

/**
 * Output an external link that opens in a new tab, announced as such.
 *
 * @param string $url   Destination.
 * @param string $label Visible text.
 * @param string $class CSS class(es).
 */
function hint_render_venue_link( $url, $label, $class = 'hint-venue__link' ) {
	?>
	<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
		<?php echo esc_html( $label ); ?>
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
	<?php
}

/**
 * Output a decorative line icon (arrival panels and city links).
 *
 * @param string $icon train|plane|bus|car|landmark|route|metro|globe.
 */
function hint_render_venue_icon( $icon ) {
	$paths = array(
		'train' => '<rect x="5" y="3" width="14" height="14" rx="3"/><path d="M5 11h14M9 21l1.5-4M15 21l-1.5-4M9 14.5h.01M15 14.5h.01"/>',
		'plane' => '<path d="M10.5 13.5 3 11l1.5-1.5 7 .5L16 5.5a2.1 2.1 0 0 1 3 3L14.5 13l.5 7-1.5 1.5-3-8Z"/>',
		'bus'   => '<rect x="4" y="4" width="16" height="13" rx="2.5"/><path d="M4 11h16M7.5 17v2.5M16.5 17v2.5M8 14h.01M16 14h.01"/>',
		'car'   => '<path d="M5 11 6.5 6.5A2 2 0 0 1 8.4 5h7.2a2 2 0 0 1 1.9 1.5L19 11M4 11h16a1 1 0 0 1 1 1v5H3v-5a1 1 0 0 1 1-1ZM6 17v2M18 17v2M7 14h.01M17 14h.01"/>',
		'landmark' => '<path d="M3 21h18M5 21V10M9.5 21V10M14.5 21V10M19 21V10M3 10h18L12 3 3 10Z"/>',
		'route'    => '<circle cx="6" cy="18" r="2.5"/><circle cx="18" cy="6" r="2.5"/><path d="M8.5 18H15a3.5 3.5 0 0 0 0-7H9a3.5 3.5 0 0 1 0-7h6.5"/>',
		'metro'    => '<circle cx="12" cy="12" r="9"/><path d="M7.5 16V8.5l4.5 5 4.5-5V16"/>',
		'globe'    => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/>',
	);

	if ( ! isset( $paths[ $icon ] ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup defined above.
	echo '<svg class="hint-venue__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $icon ] . '</svg>';
}

/**
 * Output the Venue section.
 */
function hint_render_section_venue() {
	$map_title = hint_pick(
		array(
			'pt' => 'Mapa: EACH-USP, Rua Arlindo Béttio, 1000, São Paulo',
			'en' => 'Map: EACH-USP, Rua Arlindo Béttio, 1000, São Paulo',
		)
	);
	?>
	<section class="hint-section hint-section--venue" id="venue" aria-labelledby="venue-title">
		<div class="hint-section__inner">
			<?php
			hint_render_section_heading(
				hint_pick(
					array(
						'pt' => 'Local',
						'en' => 'Venue',
					)
				),
				hint_pick(
					array(
						'pt' => 'Como chegar e onde ficar',
						'en' => 'Getting there and where to stay',
					)
				),
				'venue-title'
			);
			?>

			<div class="hint-venue__top">
				<div class="hint-venue__place">
					<address class="hint-venue__address">
						<p class="hint-venue__name">
							<?php
							echo esc_html(
								hint_pick(
									array(
										'pt' => 'EACH — Universidade de São Paulo',
										'en' => 'EACH — University of São Paulo',
									)
								)
							);
							?>
						</p>
						<p class="hint-venue__room">
							<?php
							echo esc_html(
								hint_pick(
									array(
										'pt' => 'Auditório Vermelho',
										'en' => 'Red Auditorium',
									)
								)
							);
							?>
						</p>
						<p class="hint-section__text">
							Rua Arlindo Béttio, 1000<br>
							Ermelino Matarazzo<br>
							<?php
							echo esc_html(
								hint_pick(
									array(
										'pt' => 'São Paulo — SP, Brasil',
										'en' => 'São Paulo — SP, Brazil',
									)
								)
							);
							?>
							<br>
							CEP 03828-000
						</p>
					</address>

					<p class="hint-venue__highlight">
						<?php
						echo esc_html(
							hint_pick(
								array(
									'pt' => 'A estação USP Leste (Linha 12-Safira da CPTM) fica na entrada do campus.',
									'en' => 'USP Leste station (CPTM Line 12-Sapphire) is at the campus entrance.',
								)
							)
						);
						?>
					</p>

					<p class="hint-section__actions">
						<?php
						hint_render_venue_link(
							hint_get_venue_map_url(),
							hint_pick(
								array(
									'pt' => 'Abrir no Google Maps',
									'en' => 'Open in Google Maps',
								)
							),
							'hint-button hint-button--solid'
						);
						?>
					</p>
				</div>

				<div class="hint-venue__map">
					<iframe
						src="<?php echo esc_url( hint_get_venue_embed_url() ); ?>"
						title="<?php echo esc_attr( $map_title ); ?>"
						loading="lazy"
						referrerpolicy="no-referrer-when-downgrade"
						allowfullscreen
					></iframe>
				</div>
			</div>

			<div class="hint-venue__block">
				<h3 class="hint-venue__subtitle">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'Planeje sua chegada',
								'en' => 'Plan your arrival',
							)
						)
					);
					?>
				</h3>
				<div class="hint-venue__arrival">
					<?php foreach ( hint_get_venue_arrival() as $panel ) : ?>
						<details class="hint-venue__panel">
							<summary class="hint-venue__panel-head">
								<?php hint_render_venue_icon( $panel['icon'] ); ?>
								<span class="hint-venue__panel-text">
									<span class="hint-venue__panel-title"><?php echo esc_html( $panel['title'] ); ?></span>
									<span class="hint-venue__panel-summary"><?php echo esc_html( $panel['summary'] ); ?></span>
								</span>
							</summary>
							<div class="hint-venue__panel-body">
								<?php foreach ( $panel['paragraphs'] as $paragraph ) : ?>
									<p class="hint-section__text"><?php echo esc_html( $paragraph ); ?></p>
								<?php endforeach; ?>
								<?php if ( $panel['items'] ) : ?>
									<ul class="hint-venue__list">
										<?php foreach ( $panel['items'] as $item ) : ?>
											<li><?php echo esc_html( $item ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
								<?php foreach ( $panel['links'] as $link ) : ?>
									<p class="hint-venue__more"><?php hint_render_venue_link( $link['url'], $link['label'] ); ?></p>
								<?php endforeach; ?>
							</div>
						</details>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="hint-venue__block">
				<h3 class="hint-venue__subtitle">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'Onde ficar',
								'en' => 'Where to stay',
							)
						)
					);
					?>
				</h3>
				<p class="hint-section__text hint-venue__intro">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'Duas regiões facilitam o deslocamento até o campus. Os links abrem uma busca por hospedagem em cada uma, já com as datas de 24 a 26 de fevereiro de 2027.',
								'en' => 'Two areas make it easy to reach the campus. The links open an accommodation search for each one, pre-filled with 24 to 26 February 2027.',
							)
						)
					);
					?>
				</p>
				<div class="hint-venue__stay">
					<?php foreach ( hint_get_venue_stay() as $region ) : ?>
						<div class="hint-venue__region">
							<p class="hint-venue__tag"><?php echo esc_html( $region['tag'] ); ?></p>
							<h4 class="hint-venue__region-title"><?php echo esc_html( $region['title'] ); ?></h4>
							<p class="hint-section__text"><?php echo esc_html( $region['text'] ); ?></p>
							<p class="hint-section__actions hint-section__actions--row">
								<?php
								foreach ( $region['links'] as $link ) {
									hint_render_venue_link( $link['url'], $link['label'] . ': ' . $region['title'], 'hint-button hint-button--outline' );
								}
								?>
							</p>
						</div>
					<?php endforeach; ?>
				</div>
				<p class="hint-venue__disclaimer">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'O HINT não tem parceria com hotéis nem com as plataformas de busca. Confira localização, preços e avaliações antes de reservar.',
								'en' => 'HINT has no partnership with hotels or with the search platforms. Check location, prices and reviews before booking.',
							)
						)
					);
					?>
				</p>
			</div>

			<div class="hint-venue__block">
				<h3 class="hint-venue__subtitle">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'Conheça São Paulo',
								'en' => 'Discover São Paulo',
							)
						)
					);
					?>
				</h3>
				<p class="hint-section__text hint-venue__intro">
					<?php
					echo esc_html(
						hint_pick(
							array(
								'pt' => 'Sua visita ao HINT também pode ser uma oportunidade para conhecer São Paulo. Explore atrações culturais, parques, gastronomia e experiências que fazem da cidade um dos destinos mais interessantes do Brasil.',
								'en' => "Your visit to HINT can also be a chance to get to know São Paulo. Explore the cultural attractions, parks, food and experiences that make the city one of Brazil's most interesting destinations.",
							)
						)
					);
					?>
				</p>
				<ul class="hint-venue__city">
					<?php foreach ( hint_get_venue_city_links() as $link ) : ?>
						<li class="hint-venue__city-item">
							<span class="hint-venue__city-image"><?php hint_render_venue_icon( $link['icon'] ); ?></span>
							<span class="hint-venue__city-body">
								<?php hint_render_venue_link( $link['url'], $link['label'], 'hint-venue__city-link' ); ?>
								<span class="hint-venue__city-text"><?php echo esc_html( $link['text'] ); ?></span>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>
	<?php
}
