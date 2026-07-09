<?php
/**
 * Metabox de datos adicionales para cada Miembro del Directorio.
 *
 * Guarda los campos que el diseño original mostraba como texto fijo
 * (cargo, empresa, año de ingreso, sigla del logo) como post meta,
 * editables desde el propio formulario de la entrada.
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra el metabox en la pantalla de edición de "prouni_miembro".
 */
function prouni_add_metabox_miembro() {
	add_meta_box(
		'prouni_miembro_datos',
		__( 'Datos del directorio', 'prouni' ),
		'prouni_render_metabox_miembro',
		'prouni_miembro',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'prouni_add_metabox_miembro' );

/**
 * Pinta los campos del metabox.
 *
 * @param WP_Post $post Entrada actual.
 */
function prouni_render_metabox_miembro( $post ) {
	wp_nonce_field( 'prouni_guardar_miembro', 'prouni_miembro_nonce' );

	$cargo   = get_post_meta( $post->ID, '_prouni_cargo', true );
	$empresa = get_post_meta( $post->ID, '_prouni_empresa', true );
	$anio    = get_post_meta( $post->ID, '_prouni_anio_ingreso', true );
	$sigla   = get_post_meta( $post->ID, '_prouni_sigla', true );
	$web     = get_post_meta( $post->ID, '_prouni_sitio_web', true );
	?>
	<p>
		<label for="prouni_cargo"><strong><?php esc_html_e( 'Cargo o especialidad', 'prouni' ); ?></strong></label><br>
		<input type="text" id="prouni_cargo" name="prouni_cargo" class="widefat" value="<?php echo esc_attr( $cargo ); ?>" placeholder="<?php esc_attr_e( 'Ej. Director General / Ingeniería Civil', 'prouni' ); ?>">
	</p>
	<p>
		<label for="prouni_empresa"><strong><?php esc_html_e( 'Empresa, institución o promoción', 'prouni' ); ?></strong></label><br>
		<input type="text" id="prouni_empresa" name="prouni_empresa" class="widefat" value="<?php echo esc_attr( $empresa ); ?>" placeholder="<?php esc_attr_e( 'Ej. Empresa Constructora ABC / Promoción UNI 1988', 'prouni' ); ?>">
	</p>
	<p>
		<label for="prouni_anio_ingreso"><strong><?php esc_html_e( 'Año de ingreso a ProUNI', 'prouni' ); ?></strong></label><br>
		<input type="text" id="prouni_anio_ingreso" name="prouni_anio_ingreso" value="<?php echo esc_attr( $anio ); ?>" placeholder="<?php esc_attr_e( 'Ej. 2021', 'prouni' ); ?>">
		<span class="description"><?php esc_html_e( 'Solo aplica a Asociados. Déjalo vacío para Aliados.', 'prouni' ); ?></span>
	</p>
	<p>
		<label for="prouni_sigla"><strong><?php esc_html_e( 'Sigla del logo (respaldo si no hay imagen)', 'prouni' ); ?></strong></label><br>
		<input type="text" id="prouni_sigla" name="prouni_sigla" value="<?php echo esc_attr( $sigla ); ?>" maxlength="6" placeholder="<?php esc_attr_e( 'Ej. ABC', 'prouni' ); ?>">
		<span class="description"><?php esc_html_e( 'Se muestra solo si no se asigna una foto/logo en "Foto / Logo".', 'prouni' ); ?></span>
	</p>
	<p>
		<label for="prouni_sitio_web"><strong><?php esc_html_e( 'Sitio web (solo Aliados)', 'prouni' ); ?></strong></label><br>
		<input type="url" id="prouni_sitio_web" name="prouni_sitio_web" class="widefat" value="<?php echo esc_attr( $web ); ?>" placeholder="https://">
	</p>
	<?php
}

/**
 * Guarda los campos del metabox validando el nonce y capacidades.
 *
 * @param int $post_id ID de la entrada.
 */
function prouni_guardar_metabox_miembro( $post_id ) {
	if ( ! isset( $_POST['prouni_miembro_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['prouni_miembro_nonce'] ), 'prouni_guardar_miembro' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$campos = array(
		'prouni_cargo'         => '_prouni_cargo',
		'prouni_empresa'       => '_prouni_empresa',
		'prouni_anio_ingreso'  => '_prouni_anio_ingreso',
		'prouni_sigla'         => '_prouni_sigla',
	);

	foreach ( $campos as $campo_form => $meta_key ) {
		if ( isset( $_POST[ $campo_form ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $campo_form ] ) ) );
		}
	}

	if ( isset( $_POST['prouni_sitio_web'] ) ) {
		update_post_meta( $post_id, '_prouni_sitio_web', esc_url_raw( wp_unslash( $_POST['prouni_sitio_web'] ) ) );
	}
}
add_action( 'save_post_prouni_miembro', 'prouni_guardar_metabox_miembro' );
