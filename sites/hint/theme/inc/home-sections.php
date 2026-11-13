<?php
/**
 * HINT front-page sections below the hero.
 *
 * Content follows technical-docs/HINT_2027_Site_Update_Brief.md. Anything the
 * organisation has not confirmed yet is shown as "Coming soon"; do not fill it
 * with placeholder values.
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Conference topics (three tracks from the Call for Papers).
 *
 * @return array<int,array{number:string,title:string,items:string[]}>
 */
function hint_get_topics() {
	return array(
		array(
			'number' => '01',
			'title'  => 'Inclusive Education, UDL & Technology',
			'items'  => array(
				'Inclusive Education and UDL',
				'UDL and Adaptive Digital Games',
				'Inclusive Digital Educational Games and Learning',
				'Artificial Intelligence, Inclusive Education and Learning',
				'Accessible Games and Assistive Technology',
				'Game-Based Learning Assessment',
				'Inclusive Pedagogical Practices Mediated by Technology',
				'Teacher Education for Inclusive Education and Technology',
			),
		),
		array(
			'number' => '02',
			'title'  => 'Assistive Technology, Accessibility & Neurodiversity',
			'items'  => array(
				'Assistive technologies',
				'Serious games for rehabilitation and/or learning',
				'Digital biomarkers for functional assessments',
				'Accessibility in healthcare and learning',
				'Novel digital tools for neurodivergence support',
			),
		),
		array(
			'number' => '03',
			'title'  => 'Digital Twins, Neurosymbolic AI & Knowledge Graphs',
			'items'  => array(
				'Knowledge Graph construction',
				'Data interoperability and data integration',
				'Digital twins',
				'Large Language Models using Knowledge Graphs',
				'Neurosymbolic AI applications',
				'Educational and Serious Games',
			),
		),
	);
}

/**
 * Venue address lines.
 *
 * @return string[]
 */
function hint_get_venue_lines() {
	return array(
		'Central Auditorium',
		'Rua Arlindo Béttio, 1000',
		'Ermelino Matarazzo',
		'São Paulo — SP, Brazil',
		'CEP 03828-000',
	);
}

/**
 * Map link built from the venue address (no embed, no API key).
 *
 * @return string
 */
function hint_get_venue_map_url() {
	return add_query_arg(
		array(
			'api'   => 1,
			'query' => 'EACH USP, Rua Arlindo Béttio, 1000, Ermelino Matarazzo, São Paulo - SP, 03828-000',
		),
		'https://www.google.com/maps/search/'
	);
}

/**
 * Abstract templates offered for download (files in assets/downloads/).
 *
 * @return array<int,array{file:string,label:string}>
 */
function hint_get_abstract_templates() {
	return array(
		array(
			'file'  => 'hint-2027-abstract-template.docx',
			'label' => __( 'Download template (.docx)', 'hint' ),
		),
		array(
			'file'  => 'hint-2027-abstract-template.tex',
			'label' => __( 'Download template (LaTeX)', 'hint' ),
		),
	);
}

/**
 * Section heading block.
 *
 * @param string $eyebrow Small label above the title.
 * @param string $title   Section title.
 * @param string $id      Heading id for aria-labelledby.
 */
function hint_render_section_heading( $eyebrow, $title, $id ) {
	?>
	<header class="hint-section__head">
		<p class="hint-section__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<h2 class="hint-section__title" id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></h2>
	</header>
	<?php
}

/**
 * About.
 */
function hint_render_section_about() {
	?>
	<section class="hint-section hint-section--about" id="about" aria-labelledby="about-title">
		<div class="hint-section__inner hint-section__inner--narrow">
			<?php hint_render_section_heading( __( 'About', 'hint' ), __( 'About HINT', 'hint' ), 'about-title' ); ?>
			<p class="hint-section__lead">
				<?php esc_html_e( 'HINT 2027 brings together researchers, educators, health professionals, designers and technology developers to explore inclusive technologies across education, healthcare and emerging digital environments.', 'hint' ); ?>
			</p>
			<p class="hint-section__text">
				<?php esc_html_e( 'The congress promotes dialogue between scientific knowledge, innovation and social responsibility, with particular attention to accessibility, participation, assistive technologies and inclusive digital experiences.', 'hint' ); ?>
			</p>
		</div>
	</section>
	<?php
}

/**
 * Topics.
 */
function hint_render_section_topics() {
	?>
	<section class="hint-section hint-section--topics" id="topics" aria-labelledby="topics-title">
		<div class="hint-section__inner">
			<?php hint_render_section_heading( __( 'Topics', 'hint' ), __( 'Conference Topics', 'hint' ), 'topics-title' ); ?>
			<p class="hint-section__lead hint-section__lead--topics">
				<?php esc_html_e( 'Topics include, but are not limited to, the tracks below. Contributions on other relevant themes are welcome.', 'hint' ); ?>
			</p>
			<div class="hint-topics">
				<?php foreach ( hint_get_topics() as $topic ) : ?>
					<article class="hint-topic">
						<p class="hint-topic__number"><?php echo esc_html( $topic['number'] ); ?></p>
						<h3 class="hint-topic__title"><?php echo esc_html( $topic['title'] ); ?></h3>
						<ul class="hint-topic__list">
							<?php foreach ( $topic['items'] as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Call for Papers.
 */
function hint_render_section_cfp() {
	$rows = array(
		__( 'Contribution format', 'hint' ) => array( __( 'Abstract paper — 1 page + references', 'hint' ), false ),
		__( 'Submission', 'hint' )          => array( __( '31 January 2027', 'hint' ), false ),
		__( 'Notification', 'hint' )        => array( __( '15 February 2027', 'hint' ), false ),
	);
	?>
	<section class="hint-section hint-section--cfp" id="call-for-papers" aria-labelledby="cfp-title">
		<div class="hint-section__inner hint-section__inner--split">
			<div>
				<?php hint_render_section_heading( __( 'Call for Papers', 'hint' ), __( 'Call for Papers', 'hint' ), 'cfp-title' ); ?>
				<p class="hint-section__lead">
					<?php esc_html_e( 'Researchers and practitioners are invited to submit contributions related to the conference topics.', 'hint' ); ?>
				</p>
				<p class="hint-section__text">
					<?php esc_html_e( 'Please prepare your abstract using the template below. It includes an example abstract.', 'hint' ); ?>
				</p>
				<p class="hint-section__actions">
					<?php hint_render_submit_cta( 'hint-button--solid' ); ?>
				</p>
				<p class="hint-section__note"><?php esc_html_e( 'Abstract template', 'hint' ); ?></p>
				<p class="hint-section__actions hint-section__actions--row">
					<?php foreach ( hint_get_abstract_templates() as $template ) : ?>
						<a class="hint-button hint-button--outline" href="<?php echo esc_url( hint_asset_url( 'downloads/' . $template['file'] ) ); ?>" download>
							<?php echo esc_html( $template['label'] ); ?>
						</a>
					<?php endforeach; ?>
				</p>
			</div>
			<dl class="hint-facts">
				<?php foreach ( $rows as $label => $row ) : ?>
					<div class="hint-facts__row">
						<dt><?php echo esc_html( $label ); ?></dt>
						<dd<?php echo $row[1] ? ' class="hint-facts__soon"' : ''; ?>><?php echo esc_html( $row[0] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
	</section>
	<?php
}

/**
 * Speaker slots for a session: optional keynote, N talks and a moderator.
 *
 * Every slot is a placeholder until the organisation confirms names. To
 * publish a speaker, fill `name` (and optionally `affiliation` / `photo`, a
 * URL) on the slot; the layout does not change.
 *
 * @param int  $talks   Number of talk slots.
 * @param bool $keynote Whether the session opens with a keynote.
 * @return array<int,array{role:string,name:string,affiliation:string,photo:string}>
 */
function hint_program_slots( $talks, $keynote = false ) {
	$slots = array();

	if ( $keynote ) {
		$slots[] = array(
			'role'        => __( 'Keynote speaker', 'hint' ),
			'name'        => '',
			'affiliation' => '',
			'photo'       => '',
		);
	}

	for ( $i = 1; $i <= $talks; $i++ ) {
		$slots[] = array(
			/* translators: %d: talk number within the session. */
			'role'        => sprintf( __( 'Talk %d', 'hint' ), $i ),
			'name'        => '',
			'affiliation' => '',
			'photo'       => '',
		);
	}

	$slots[] = array(
		'role'        => __( 'Moderator', 'hint' ),
		'name'        => '',
		'affiliation' => '',
		'photo'       => '',
	);

	return $slots;
}

/**
 * Program: Thursday 25 February 2027, morning and afternoon sessions.
 *
 * Times (except the opening) and speakers are still to be confirmed.
 *
 * @return array<int,array{label:string,sessions:array}>
 */
function hint_get_program() {
	return array(
		array(
			'label'    => __( 'Morning', 'hint' ),
			'sessions' => array(
				array(
					'number'   => '01',
					'title'    => __( 'Digital Literacy', 'hint' ),
					'subtitle' => __( 'Based on the Global Standards for Digital Intelligence (DQGS)', 'hint' ),
					'slots'    => hint_program_slots( 3 ),
				),
				array(
					'number'   => '02',
					'title'    => __( 'Assistive Technology & Inclusion in Health and Education', 'hint' ),
					'subtitle' => __( 'Emerging technology for neurodiversity', 'hint' ),
					'slots'    => hint_program_slots( 3 ),
				),
			),
		),
		array(
			'label'    => __( 'Afternoon', 'hint' ),
			'sessions' => array(
				array(
					'number'   => '03',
					'title'    => __( 'UDL — Universal Design for Learning', 'hint' ),
					'subtitle' => '',
					'slots'    => hint_program_slots( 3 ),
				),
				array(
					'number'   => '04',
					'title'    => __( 'Digital Twins', 'hint' ),
					'subtitle' => '',
					'slots'    => hint_program_slots( 3, true ),
				),
			),
		),
	);
}

/**
 * Generic avatar silhouette (inline, no image file).
 */
function hint_render_avatar_placeholder() {
	?>
	<svg class="hint-avatar__svg" viewBox="0 0 64 64" width="64" height="64" aria-hidden="true" focusable="false">
		<circle cx="32" cy="24" r="11" fill="currentColor" />
		<path d="M10 58c0-12.2 9.8-22 22-22s22 9.8 22 22H10Z" fill="currentColor" />
	</svg>
	<?php
}

/**
 * One speaker card (placeholder or confirmed).
 *
 * @param array{role:string,name:string,affiliation:string,photo:string} $slot Speaker slot.
 */
function hint_render_speaker( $slot ) {
	$confirmed = '' !== $slot['name'];
	?>
	<li class="hint-speaker<?php echo $confirmed ? '' : ' hint-speaker--tbd'; ?>">
		<span class="hint-avatar">
			<?php if ( $slot['photo'] ) : ?>
				<img src="<?php echo esc_url( $slot['photo'] ); ?>" alt="" width="64" height="64" loading="lazy" decoding="async" />
			<?php else : ?>
				<?php hint_render_avatar_placeholder(); ?>
			<?php endif; ?>
		</span>
		<span class="hint-speaker__text">
			<span class="hint-speaker__role"><?php echo esc_html( $slot['role'] ); ?></span>
			<span class="hint-speaker__name">
				<?php echo esc_html( $confirmed ? $slot['name'] : __( 'To be announced', 'hint' ) ); ?>
			</span>
			<?php if ( $slot['affiliation'] ) : ?>
				<span class="hint-speaker__affil"><?php echo esc_html( $slot['affiliation'] ); ?></span>
			<?php endif; ?>
		</span>
	</li>
	<?php
}

/**
 * Program.
 */
function hint_render_section_program() {
	?>
	<section class="hint-section hint-section--program" id="program" aria-labelledby="program-title">
		<div class="hint-section__inner">
			<?php hint_render_section_heading( __( 'Program', 'hint' ), __( 'Program', 'hint' ), 'program-title' ); ?>
			<p class="hint-section__lead">
				<?php esc_html_e( 'Thursday · 25 February 2027', 'hint' ); ?><br />
				<?php esc_html_e( 'São Paulo · EACH/USP', 'hint' ); ?>
			</p>

			<p class="hint-program__opening">
				<span class="hint-program__time"><?php esc_html_e( '9:00', 'hint' ); ?></span>
				<?php esc_html_e( 'Opening', 'hint' ); ?>
			</p>

			<?php foreach ( hint_get_program() as $block ) : ?>
				<div class="hint-program__block">
					<h3 class="hint-program__block-title"><?php echo esc_html( $block['label'] ); ?></h3>
					<div class="hint-program__sessions">
						<?php foreach ( $block['sessions'] as $session ) : ?>
							<article class="hint-session">
								<p class="hint-session__number"><?php echo esc_html( $session['number'] ); ?></p>
								<h4 class="hint-session__title"><?php echo esc_html( $session['title'] ); ?></h4>
								<?php if ( $session['subtitle'] ) : ?>
									<p class="hint-session__subtitle"><?php echo esc_html( $session['subtitle'] ); ?></p>
								<?php endif; ?>
								<ul class="hint-session__speakers">
									<?php array_map( 'hint_render_speaker', $session['slots'] ); ?>
								</ul>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>

			<p class="hint-section__soon"><?php esc_html_e( 'Times and speakers to be announced.', 'hint' ); ?></p>
		</div>
	</section>
	<?php
}

/**
 * Venue.
 */
function hint_render_section_venue() {
	?>
	<section class="hint-section hint-section--venue" id="venue" aria-labelledby="venue-title">
		<div class="hint-section__inner hint-section__inner--split">
			<div>
				<?php hint_render_section_heading( __( 'Venue', 'hint' ), __( 'Venue', 'hint' ), 'venue-title' ); ?>
				<p class="hint-section__actions">
					<a class="hint-button hint-button--outline" href="<?php echo esc_url( hint_get_venue_map_url() ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'How to get there', 'hint' ); ?>
					</a>
				</p>
			</div>
			<address class="hint-venue">
				<strong class="hint-venue__name"><?php esc_html_e( 'EACH — University of São Paulo', 'hint' ); ?></strong>
				<?php foreach ( hint_get_venue_lines() as $line ) : ?>
					<span><?php echo esc_html( $line ); ?></span>
				<?php endforeach; ?>
			</address>
		</div>
	</section>
	<?php
}

/**
 * Output every section below the hero.
 */
function hint_render_home_sections() {
	?>
	<main class="hint-home" id="content">
		<?php
		hint_render_section_about();
		hint_render_section_topics();
		hint_render_section_cfp();
		hint_render_section_program();
		hint_render_section_venue();
		?>
	</main>
	<?php
}
