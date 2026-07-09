<?php
/**
 * Carga de estilos y scripts del tema.
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Encola las hojas de estilo y los scripts del tema.
 * Usa filemtime() como cache-buster para que los navegadores
 * siempre sirvan la última versión tras cada despliegue.
 */
function prouni_enqueue_assets() {
	// Tipografías Google Fonts usadas por el diseño original (Roboto / Roboto Serif).
	wp_enqueue_style(
		'prouni-google-fonts',
		'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&family=Roboto+Serif:wght@500;600;700&display=swap',
		array(),
		null
	);

	// Hoja de estilos principal del directorio.
	$css_path = get_template_directory() . '/assets/css/directorio.css';
	wp_enqueue_style(
		'prouni-directorio',
		get_template_directory_uri() . '/assets/css/directorio.css',
		array(),
		file_exists( $css_path ) ? filemtime( $css_path ) : PROUNI_THEME_VERSION
	);

	// Hoja de estilos raíz del tema (cabecera obligatoria de WordPress).
	wp_enqueue_style( 'prouni-style', get_stylesheet_uri(), array( 'prouni-directorio' ), PROUNI_THEME_VERSION );

	// Comportamiento de pestañas, chips de filtro y buscador.
	$js_path = get_template_directory() . '/assets/js/directorio.js';
	wp_enqueue_script(
		'prouni-directorio',
		get_template_directory_uri() . '/assets/js/directorio.js',
		array(),
		file_exists( $js_path ) ? filemtime( $js_path ) : PROUNI_THEME_VERSION,
		true
	);

	if ( is_singular() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'prouni_enqueue_assets' );
