<?php
/**
 * Helpers shared by the front-page sections.
 *
 * Loaded before inc/sections/<name>.php. Section files own their content;
 * what lives here is reused by more than one of them: the heading block,
 * the abstract templates (papers), the venue map link (venue, footer) and
 * the "add to calendar" links (registration).
 *
 * @package HINT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Section heading block (eyebrow + h2).
 *
 * @param string $eyebrow Small label above the title.
 * @param string $title   Section title.
 * @param string $id      Heading id, referenced by the section's aria-labelledby.
 */
function hint_render_section_heading( $eyebrow, $title, $id ) {
	?>
	<header class="hint-section__head">
		<?php if ( '' !== $eyebrow ) : ?>
			<p class="hint-section__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<?php endif; ?>
		<h2 class="hint-section__title" id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></h2>
	</header>
	<?php
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
 * "Add to calendar" links for the event day (25 Feb 2027, 08:00–18:00 BRT).
 *
 * Google Calendar gets a pre-filled link; every other calendar (Apple on
 * iPhone and Mac, Outlook, Android apps) opens assets/downloads/hint-2027.ics.
 * Times are UTC in both (São Paulo is UTC-3, no daylight saving time): keep
 * the two in sync when the programme changes.
 *
 * @return array<int,array{icon:string,label:string,url:string,external:bool}>
 */
function hint_get_calendar_links() {
	$google = add_query_arg(
		array(
			'action'   => 'TEMPLATE',
			'text'     => rawurlencode( 'HINT 2027 – International Congress of Human-Centred Inclusive New Technologies' ),
			'dates'    => '20270225T110000Z/20270225T210000Z',
			'location' => rawurlencode( 'EACH-USP, Auditório Vermelho, Rua Arlindo Béttio, 1000, Ermelino Matarazzo, São Paulo - SP' ),
			'details'  => rawurlencode( 'https://hint.ciatec.org/' ),
		),
		'https://calendar.google.com/calendar/render'
	);

	return array(
		array(
			'icon'     => 'google',
			'label'    => 'Google Calendar',
			'url'      => $google,
			'external' => true,
		),
		array(
			'icon'     => 'apple',
			'label'    => hint_pick(
				array(
					'pt' => 'Apple, Outlook e outros (.ics)',
					'en' => 'Apple, Outlook and others (.ics)',
				)
			),
			'url'      => hint_asset_url( 'downloads/hint-2027.ics' ),
			'external' => false,
		),
	);
}

/**
 * Output the brand glyph of a calendar link (decorative, single colour).
 *
 * Drawn with currentColor so it follows the button text in every theme.
 *
 * @param string $icon google|apple.
 */
function hint_render_calendar_icon( $icon ) {
	$paths = array(
		'google' => 'M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z',
		'apple'  => 'M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.559-1.701',
	);

	if ( ! isset( $paths[ $icon ] ) ) {
		return;
	}

	printf(
		'<svg class="hint-calendar-icon" viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true" focusable="false"><path d="%s"/></svg>',
		esc_attr( $paths[ $icon ] )
	);
}
