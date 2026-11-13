<?php
/**
 * Projetos (#projetos): five fronts as a ruled list next to the section title.
 *
 * @package ciatec
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="projetos" class="ciatec-section ciatec-section--tint" aria-labelledby="projects-title">
	<div class="ciatec-container ciatec-split ciatec-split--sticky">
		<h2 id="projects-title" class="ciatec-split__title"><?php echo esc_html( ciatec_t( 'projects_title' ) ); ?></h2>
		<ul class="ciatec-split__body ciatec-projects" role="list">
			<?php for ( $n = 1; $n <= 5; $n++ ) : ?>
				<li>
					<h3><?php echo esc_html( ciatec_t( "project_{$n}_title" ) ); ?></h3>
					<p><?php echo esc_html( ciatec_t( "project_{$n}_text" ) ); ?></p>
				</li>
			<?php endfor; ?>
		</ul>
	</div>
</section>
