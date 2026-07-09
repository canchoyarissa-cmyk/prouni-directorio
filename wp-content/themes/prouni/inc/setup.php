<?php
/**
 * Configuración base del tema: soporte de características, menús,
 * tamaños de imagen y el ajuste del Personalizador para el correo
 * de la barra superior.
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ancho de referencia para oEmbeds e imágenes insertadas en el contenido
// (coincide con el max-width de .directory en el diseño original).
// Buena práctica estándar de temas, sin efecto visual por sí sola.
if ( ! isset( $content_width ) ) {
	$content_width = 1400;
}

/**
 * Registra soportes del tema, menús y tamaños de imagen usados por
 * el directorio (avatares y logos de empresa/institución).
 */
function prouni_theme_setup() {
	load_theme_textdomain( 'prouni', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );

	// Compatibilidad expresa con Elementor (no restringe anchos ni contenedores).
	add_theme_support( 'responsive-embeds' );

	register_nav_menus(
		array(
			'primary' => __( 'Menú principal (cabecera)', 'prouni' ),
		)
	);

	add_image_size( 'prouni-avatar', 200, 200, true );
	add_image_size( 'prouni-avatar-lg', 300, 300, true );
	add_image_size( 'prouni-logo', 240, 120, false );

	// Permite reemplazar el logotipo de texto ("ProUNI") por una imagen
	// desde la Biblioteca Multimedia en Apariencia > Personalizar > Identidad.
	// Si no se sube ninguna imagen, la cabecera muestra el logotipo de texto
	// original tal como en el diseño de referencia.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 48,
			'width'       => 160,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
}
add_action( 'after_setup_theme', 'prouni_theme_setup' );

/**
 * Ajuste del Personalizador para editar el correo de la barra superior
 * sin tocar código (Apariencia > Personalizar > Barra superior).
 */
function prouni_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'prouni_top_bar',
		array(
			'title'    => __( 'Barra superior', 'prouni' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'prouni_top_bar_email',
		array(
			'default'           => 'contacto@prouni.org.pe',
			'sanitize_callback' => 'sanitize_email',
		)
	);

	$wp_customize->add_control(
		'prouni_top_bar_email',
		array(
			'label'   => __( 'Correo de contacto', 'prouni' ),
			'section' => 'prouni_top_bar',
			'type'    => 'email',
		)
	);
}
add_action( 'customize_register', 'prouni_customize_register' );
