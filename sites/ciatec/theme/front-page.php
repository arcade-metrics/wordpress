<?php
/**
 * One-page home: hero, about, projects, HINT banner, contact. Copy lives in inc/strings.php (Polylang).
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="content" class="ciatec-home">
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/about' );
	get_template_part( 'template-parts/home/projects' );
	get_template_part( 'template-parts/home/event' );
	get_template_part( 'template-parts/home/contact' );
	?>
</main>
<?php
get_footer();
