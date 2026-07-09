<?php
/**
 * Custom Post Type y taxonomías del Directorio Institucional.
 *
 * Cada tarjeta del directorio (asociado o aliado) es una entrada del
 * CPT "prouni_miembro", clasificada por dos taxonomías:
 * - tipo_directorio: a qué pestaña pertenece (Asociados / Aliados).
 * - categoria_directorio: el chip de filtro (Fundadores, Honorarios,
 *   Patrocinadores, Colaboradores, Institucionales, Estratégicos,
 *   Delegados, Amigos).
 *
 * Esto permite editar todo el contenido del directorio desde el
 * escritorio de WordPress sin tocar código, y usar la Biblioteca
 * Multimedia para las fotos/logos (imagen destacada de cada entrada).
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra el CPT "prouni_miembro".
 */
function prouni_register_cpt_miembro() {
	$labels = array(
		'name'               => __( 'Miembros del Directorio', 'prouni' ),
		'singular_name'      => __( 'Miembro del Directorio', 'prouni' ),
		'add_new_item'       => __( 'Añadir nuevo miembro', 'prouni' ),
		'edit_item'          => __( 'Editar miembro', 'prouni' ),
		'new_item'           => __( 'Nuevo miembro', 'prouni' ),
		'view_item'          => __( 'Ver miembro', 'prouni' ),
		'search_items'       => __( 'Buscar miembros', 'prouni' ),
		'not_found'          => __( 'No se encontraron miembros del directorio.', 'prouni' ),
		'menu_name'          => __( 'Directorio', 'prouni' ),
		'featured_image'     => __( 'Foto / Logo', 'prouni' ),
		'set_featured_image' => __( 'Elegir foto o logo desde la Biblioteca Multimedia', 'prouni' ),
	);

	register_post_type(
		'prouni_miembro',
		array(
			'labels'        => $labels,
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-groups',
			'menu_position' => 20,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'has_archive'   => false,
			'rewrite'       => array( 'slug' => 'directorio-miembro' ),
		)
	);
}
add_action( 'init', 'prouni_register_cpt_miembro' );

/**
 * Registra las taxonomías tipo_directorio y categoria_directorio.
 */
function prouni_register_taxonomias_directorio() {
	register_taxonomy(
		'tipo_directorio',
		'prouni_miembro',
		array(
			'labels'            => array(
				'name'          => __( 'Tipo de directorio', 'prouni' ),
				'singular_name' => __( 'Tipo de directorio', 'prouni' ),
			),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'directorio-tipo' ),
		)
	);

	register_taxonomy(
		'categoria_directorio',
		'prouni_miembro',
		array(
			'labels'            => array(
				'name'          => __( 'Categoría del directorio', 'prouni' ),
				'singular_name' => __( 'Categoría del directorio', 'prouni' ),
			),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'directorio-categoria' ),
		)
	);
}
add_action( 'init', 'prouni_register_taxonomias_directorio' );

/**
 * Crea los términos por defecto (pestañas y chips del diseño original)
 * una sola vez, al activar el tema, para que el administrador solo
 * tenga que asignarlos a cada miembro en vez de crearlos a mano.
 */
function prouni_seed_taxonomy_terms() {
	$tipos = array(
		'asociados' => __( 'Asociados', 'prouni' ),
		'aliados'   => __( 'Aliados', 'prouni' ),
	);
	foreach ( $tipos as $slug => $nombre ) {
		if ( ! term_exists( $slug, 'tipo_directorio' ) ) {
			wp_insert_term( $nombre, 'tipo_directorio', array( 'slug' => $slug ) );
		}
	}

	$categorias = array(
		'fundadores'      => __( 'Fundadores', 'prouni' ),
		'honorarios'      => __( 'Honorarios', 'prouni' ),
		'patrocinadores'  => __( 'Patrocinadores', 'prouni' ),
		'colaboradores'   => __( 'Colaboradores', 'prouni' ),
		'institucionales' => __( 'Institucionales', 'prouni' ),
		'estrategicos'    => __( 'Estratégicos', 'prouni' ),
		'delegados'       => __( 'Delegados', 'prouni' ),
		'amigos'          => __( 'Amigos', 'prouni' ),
	);
	foreach ( $categorias as $slug => $nombre ) {
		if ( ! term_exists( $slug, 'categoria_directorio' ) ) {
			wp_insert_term( $nombre, 'categoria_directorio', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'after_switch_theme', 'prouni_register_cpt_miembro' );
add_action( 'after_switch_theme', 'prouni_register_taxonomias_directorio' );
add_action( 'after_switch_theme', 'prouni_seed_taxonomy_terms' );
add_action( 'after_switch_theme', 'flush_rewrite_rules' );

/**
 * Mapa de qué categorías corresponden a cada pestaña y cuáles se
 * muestran en formato "destacado" (premium-grid) vs formato estándar
 * (standard-grid), replicando el diseño original.
 *
 * @return array
 */
function prouni_get_directorio_mapa() {
	return array(
		'asociados' => array(
			'label'      => __( 'Asociados', 'prouni' ),
			'icono'      => '⌾',
			'categorias' => array(
				'fundadores'     => array(
					'label'     => __( 'Asociados Fundadores', 'prouni' ),
					'chip'      => __( 'Fundadores', 'prouni' ),
					'destacado' => true,
					'nota'      => __( '★ Los más destacados', 'prouni' ),
				),
				'honorarios'     => array(
					'label'     => __( 'Asociados Honorarios', 'prouni' ),
					'chip'      => __( 'Honorarios', 'prouni' ),
					'destacado' => false,
				),
				'patrocinadores' => array(
					'label'         => __( 'Patrocinadores', 'prouni' ),
					'chip'          => __( 'Patrocinadores', 'prouni' ),
					'destacado'     => false,
					// Igual que en el HTML original: esta sección siempre es una
					// fila estática "Ver todos", nunca una cuadrícula de tarjetas,
					// incluso si ya existen miembros cargados en esta categoría.
					'forzar_simple' => true,
					'simple_glyph'  => '⌄',
				),
				'colaboradores'  => array(
					'label'         => __( 'Colaboradores', 'prouni' ),
					'chip'          => __( 'Colaboradores', 'prouni' ),
					'destacado'     => false,
					'forzar_simple' => true,
					'simple_glyph'  => '⌄',
				),
			),
		),
		'aliados'   => array(
			'label'      => __( 'Aliados', 'prouni' ),
			'icono'      => '▥',
			'categorias' => array(
				'institucionales' => array(
					'label'     => __( 'Aliados Institucionales', 'prouni' ),
					'chip'      => __( 'Institucionales', 'prouni' ),
					'destacado' => true,
					'nota'      => __( '★ Los más destacados', 'prouni' ),
				),
				// El HTML original solo define el chip de filtro para estas tres
				// categorías: no existe ninguna sección/cuadrícula para ellas en
				// la pestaña Aliados. Se mantiene así (renderizar_seccion=false)
				// para no añadir estructura que no estaba en el diseño original.
				'estrategicos' => array(
					'label'              => __( 'Aliados Estratégicos', 'prouni' ),
					'chip'               => __( 'Estratégicos', 'prouni' ),
					'destacado'          => false,
					'renderizar_seccion' => false,
				),
				'delegados'    => array(
					'label'              => __( 'Delegados', 'prouni' ),
					'chip'               => __( 'Delegados', 'prouni' ),
					'destacado'          => false,
					'renderizar_seccion' => false,
				),
				'amigos'       => array(
					'label'              => __( 'Amigos', 'prouni' ),
					'chip'               => __( 'Amigos', 'prouni' ),
					'destacado'          => false,
					'renderizar_seccion' => false,
				),
			),
		),
	);
}
