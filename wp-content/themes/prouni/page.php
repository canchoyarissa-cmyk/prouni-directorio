<?php
/**
 * Plantilla de página por defecto.
 *
 * Requisito de estándares de WordPress y de compatibilidad con
 * Elementor: sin un page.php que llame a the_content(), CUALQUIER
 * página que no use la plantilla "Directorio Institucional" (incluida
 * toda página construida con Elementor) caería en index.php, que solo
 * imprime the_excerpt() dentro de un listado de blog — Elementor
 * nunca llegaría a pintarse. Este archivo es el estándar mínimo que
 * evita ese problema y deja el contenido 100% libre para el editor de
 * bloques o Elementor.
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
endwhile;

get_footer();
