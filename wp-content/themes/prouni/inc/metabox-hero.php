<?php
/**
 * Campo editable para el subtítulo del Hero de la plantilla
 * "Directorio Institucional" (page-directorio.php).
 *
 * Se guarda aparte del contenido principal de la página para que el
 * editor de bloques / Elementor pueda seguir usando el contenido
 * principal libremente sin chocar con el texto del hero.
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Añade el metabox solo cuando la página usa la plantilla del directorio.
 * Se filtra aquí mismo (no dentro del callback de render) para no dejar
 * una caja vacía en todas las demás páginas del sitio.
 *
 * @param string  $post_type Post type de la pantalla de edición actual.
 * @param WP_Post $post      Página actual (puede no existir aún al crear una nueva).
 */
function prouni_add_metabox_hero( $post_type, $post ) {
	if ( 'page' !== $post_type || ! ( $post instanceof WP_Post ) ) {
		return;
	}

	if ( 'page-directorio.php' !== get_page_template_slug( $post->ID ) ) {
		return;
	}

	add_meta_box(
		'prouni_hero_datos',
		__( 'Directorio: texto del Hero', 'prouni' ),
		'prouni_render_metabox_hero',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'prouni_add_metabox_hero', 10, 2 );

/**
 * Pinta el campo del subtítulo del Hero.
 *
 * @param WP_Post $post Página actual.
 */
function prouni_render_metabox_hero( $post ) {
	wp_nonce_field( 'prouni_guardar_hero', 'prouni_hero_nonce' );
	$subtitulo = get_post_meta( $post->ID, '_prouni_hero_subtitulo', true );
	?>
	<p>
		<label for="prouni_hero_subtitulo"><strong><?php esc_html_e( 'Subtítulo (párrafo bajo el título del Hero)', 'prouni' ); ?></strong></label><br>
		<textarea id="prouni_hero_subtitulo" name="prouni_hero_subtitulo" class="widefat" rows="3"><?php echo esc_textarea( $subtitulo ); ?></textarea>
		<span class="description"><?php esc_html_e( 'El título del Hero es el título de esta página. El contenido principal de abajo queda libre para el editor de bloques o Elementor.', 'prouni' ); ?></span>
	</p>
	<?php
}

/**
 * Guarda el campo del hero.
 *
 * @param int $post_id ID de la página.
 */
function prouni_guardar_metabox_hero( $post_id ) {
	if ( ! isset( $_POST['prouni_hero_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['prouni_hero_nonce'] ), 'prouni_guardar_hero' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['prouni_hero_subtitulo'] ) ) {
		update_post_meta( $post_id, '_prouni_hero_subtitulo', sanitize_textarea_field( wp_unslash( $_POST['prouni_hero_subtitulo'] ) ) );
	}
}
add_action( 'save_post_page', 'prouni_guardar_metabox_hero' );
