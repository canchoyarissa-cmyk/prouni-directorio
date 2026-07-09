<?php
/**
 * Plantilla de entrada individual (entradas de blog y CPT como
 * "Miembro del Directorio" cuando se visita su URL propia).
 *
 * Completa la jerarquía de plantillas estándar de WordPress: sin este
 * archivo, cualquier vista singular no-página caía en index.php
 * (pensado para listados), mostrando solo el extracto en vez del
 * contenido completo.
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	the_content();

	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile;

get_footer();
