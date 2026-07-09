<?php
/**
 * Arranque del tema ProUNI.
 *
 * Este archivo solo define constantes y carga los módulos del tema
 * desde /inc. Toda la lógica vive dividida por responsabilidad:
 *
 * - inc/setup.php             Soportes del tema, menús, tamaños de imagen, logo.
 * - inc/enqueue.php           Carga de CSS/JS (assets/css, assets/js).
 * - inc/walker-nav-menu.php   Walker del menú principal + menú de respaldo.
 * - inc/cpt-directorio.php    CPT y taxonomías del Directorio Institucional.
 * - inc/metabox-hero.php      Campo editable del subtítulo del Hero.
 * - inc/metabox-directorio.php Campos editables de cada miembro del directorio.
 * - inc/template-tags.php     Funciones de render usadas en page-directorio.php.
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PROUNI_THEME_VERSION', '1.0.0' );
define( 'PROUNI_THEME_DIR', get_template_directory() );
define( 'PROUNI_THEME_URI', get_template_directory_uri() );

require PROUNI_THEME_DIR . '/inc/setup.php';
require PROUNI_THEME_DIR . '/inc/enqueue.php';
require PROUNI_THEME_DIR . '/inc/walker-nav-menu.php';
require PROUNI_THEME_DIR . '/inc/cpt-directorio.php';
require PROUNI_THEME_DIR . '/inc/metabox-hero.php';
require PROUNI_THEME_DIR . '/inc/metabox-directorio.php';
require PROUNI_THEME_DIR . '/inc/template-tags.php';
