<?php
/**
 * Funciones de plantilla (template tags) para renderizar el Directorio
 * Institucional. Se usan desde page-directorio.php y mantienen las
 * mismas clases CSS del diseño original (premium-card, member-card,
 * company-logo, avatar, simple-row, etc.).
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Consulta los miembros de una categoría dentro de una pestaña (tipo).
 *
 * @param string $tipo_slug      Slug del término de tipo_directorio.
 * @param string $categoria_slug Slug del término de categoria_directorio.
 * @return WP_Query
 */
function prouni_query_miembros( $tipo_slug, $categoria_slug ) {
	return new WP_Query(
		array(
			'post_type'      => 'prouni_miembro',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'tax_query'      => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'tipo_directorio',
					'field'    => 'slug',
					'terms'    => $tipo_slug,
				),
				array(
					'taxonomy' => 'categoria_directorio',
					'field'    => 'slug',
					'terms'    => $categoria_slug,
				),
			),
		)
	);
}

/**
 * Genera iniciales a partir de un nombre/razón social, usadas como
 * respaldo del logo cuando la entrada no tiene imagen destacada.
 *
 * @param string $titulo Título de la entrada.
 * @return string
 */
function prouni_get_iniciales( $titulo ) {
	$palabras  = preg_split( '/\s+/', trim( $titulo ) );
	$iniciales = '';
	foreach ( array_slice( $palabras, 0, 2 ) as $palabra ) {
		$iniciales .= mb_strtoupper( mb_substr( $palabra, 0, 1 ) );
	}
	return $iniciales;
}

/**
 * Imprime el círculo de avatar (foto de la Biblioteca Multimedia o,
 * si no hay ninguna asignada, el fondo degradado del diseño original).
 *
 * @param int    $post_id ID de la entrada.
 * @param string $size    Tamaño de imagen registrado.
 */
function prouni_render_avatar( $post_id, $size = 'prouni-avatar' ) {
	if ( has_post_thumbnail( $post_id ) ) {
		echo '<div class="avatar">' . get_the_post_thumbnail( $post_id, $size, array( 'alt' => get_the_title( $post_id ) ) ) . '</div>';
	} else {
		echo '<div class="avatar"></div>';
	}
}

/**
 * Imprime el bloque .company-logo (imagen del logo desde la Biblioteca
 * Multimedia o, en su defecto, la sigla configurada / autogenerada).
 *
 * @param int    $post_id      ID de la entrada.
 * @param string $extra_estilo Estilo inline adicional (ej. "border-left:0").
 */
function prouni_render_company_logo( $post_id, $extra_estilo = '' ) {
	$sigla = get_post_meta( $post_id, '_prouni_sigla', true );
	if ( ! $sigla ) {
		$sigla = prouni_get_iniciales( get_the_title( $post_id ) );
	}

	$style = $extra_estilo ? ' style="' . esc_attr( $extra_estilo ) . '"' : '';

	echo '<div class="company-logo"' . $style . '>';
	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, 'prouni-logo', array( 'alt' => get_the_title( $post_id ) ) );
	} else {
		echo esc_html( $sigla );
	}
	echo '</div>';
}

/**
 * Tarjeta destacada (premium-card) para Asociados: avatar + datos + logo.
 *
 * @param WP_Post $post       Entrada del miembro.
 * @param string  $categoria  Slug de la categoría (para el filtro por chip).
 */
function prouni_render_card_premium_asociado( $post, $categoria ) {
	$cargo   = get_post_meta( $post->ID, '_prouni_cargo', true );
	$empresa = get_post_meta( $post->ID, '_prouni_empresa', true );
	$anio    = get_post_meta( $post->ID, '_prouni_anio_ingreso', true );
	?>
	<article class="premium-card filter-card" data-category="<?php echo esc_attr( $categoria ); ?>" data-name="<?php echo esc_attr( strtolower( $post->post_title ) ); ?>">
		<?php prouni_render_avatar( $post->ID, 'prouni-avatar-lg' ); ?>
		<div>
			<h3><?php echo esc_html( get_the_title( $post ) ); ?></h3>
			<?php if ( $cargo ) : ?><p><?php echo esc_html( $cargo ); ?></p><?php endif; ?>
			<?php if ( $empresa ) : ?><p><?php echo esc_html( $empresa ); ?></p><?php endif; ?>
			<p class="small-meta">▣ <?php esc_html_e( 'Ingreso a ProUNI', 'prouni' ); ?></p>
			<?php if ( $anio ) : ?><p class="year"><?php echo esc_html( $anio ); ?></p><?php endif; ?>
		</div>
		<?php prouni_render_company_logo( $post->ID ); ?>
	</article>
	<?php
}

/**
 * Tarjeta destacada (premium-card) para Aliados: logo + datos + logo.
 * La descripción larga usa el contenido/extracto de la entrada
 * (campo "editor" del CPT), editable con el editor de bloques normal.
 *
 * @param WP_Post $post      Entrada del aliado.
 * @param string  $categoria Slug de la categoría (para el filtro por chip).
 */
function prouni_render_card_premium_aliado( $post, $categoria ) {
	$sector = get_post_meta( $post->ID, '_prouni_cargo', true );
	?>
	<article class="premium-card filter-card" data-category="<?php echo esc_attr( $categoria ); ?>" data-name="<?php echo esc_attr( strtolower( $post->post_title ) ); ?>">
		<?php prouni_render_company_logo( $post->ID, 'border-left:0' ); ?>
		<div>
			<h3><?php echo esc_html( get_the_title( $post ) ); ?></h3>
			<?php if ( $sector ) : ?><p><?php echo esc_html( $sector ); ?></p><?php endif; ?>
			<?php if ( ! empty( $post->post_content ) ) : ?>
				<p><?php echo esc_html( wp_strip_all_tags( $post->post_content ) ); ?></p>
			<?php endif; ?>
		</div>
		<?php prouni_render_company_logo( $post->ID ); ?>
	</article>
	<?php
}

/**
 * Tarjeta estándar (member-card) usada en las cuadrículas de 4 columnas
 * (Honorarios, Estratégicos, etc.).
 *
 * @param WP_Post $post      Entrada del miembro.
 * @param string  $categoria Slug de la categoría (para el filtro por chip).
 */
function prouni_render_card_member( $post, $categoria ) {
	$cargo   = get_post_meta( $post->ID, '_prouni_cargo', true );
	$empresa = get_post_meta( $post->ID, '_prouni_empresa', true );
	?>
	<article class="member-card filter-card" data-category="<?php echo esc_attr( $categoria ); ?>" data-name="<?php echo esc_attr( strtolower( $post->post_title ) ); ?>">
		<?php prouni_render_avatar( $post->ID ); ?>
		<div>
			<h3><?php echo esc_html( get_the_title( $post ) ); ?></h3>
			<?php if ( $cargo ) : ?><p><?php echo esc_html( $cargo ); ?></p><?php endif; ?>
			<?php if ( $empresa ) : ?><p><?php echo esc_html( $empresa ); ?></p><?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * Fila simple con enlace "Ver todos" hacia el archivo de la taxonomía,
 * usada cuando una categoría todavía no tiene miembros cargados.
 *
 * @param string $categoria_slug Slug de la categoría.
 * @param string $etiqueta       Título visible de la sección.
 */
function prouni_render_simple_row( $categoria_slug, $etiqueta ) {
	$term = get_term_by( 'slug', $categoria_slug, 'categoria_directorio' );
	$link = $term && ! is_wp_error( $term ) ? get_term_link( $term ) : '#';
	?>
	<div class="simple-row section-title">
		<div class="title-left">
			<div class="icon-line">♙</div>
			<h2><?php echo esc_html( $etiqueta ); ?></h2>
		</div>
		<a class="view-all" href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'Ver todos', 'prouni' ); ?> ›</a>
	</div>
	<?php
}

/**
 * Renderiza el bloque completo de una categoría dentro de una pestaña:
 * decide automáticamente si mostrar la cuadrícula destacada (premium),
 * la cuadrícula estándar, o la fila simple con "Ver todos" cuando aún
 * no hay miembros publicados en esa categoría.
 *
 * @param string $tipo_slug       Slug del tipo (asociados|aliados).
 * @param string $categoria_slug  Slug de la categoría.
 * @param array  $config          Config de prouni_get_directorio_mapa().
 */
function prouni_render_seccion_categoria( $tipo_slug, $categoria_slug, $config ) {
	$query = prouni_query_miembros( $tipo_slug, $categoria_slug );

	if ( ! $query->have_posts() ) {
		prouni_render_simple_row( $categoria_slug, $config['label'] );
		return;
	}
	?>
	<div class="section-title">
		<div class="title-left">
			<div class="icon-line"><?php echo esc_html( prouni_get_directorio_mapa()[ $tipo_slug ]['icono'] ); ?></div>
			<h2><?php echo esc_html( $config['label'] ); ?></h2>
			<?php if ( ! empty( $config['destacado'] ) && ! empty( $config['nota'] ) ) : ?>
				<span class="highlight-note"><?php echo esc_html( $config['nota'] ); ?></span>
			<?php endif; ?>
		</div>
		<?php if ( empty( $config['destacado'] ) ) : ?>
			<?php $term = get_term_by( 'slug', $categoria_slug, 'categoria_directorio' ); ?>
			<a class="view-all" href="<?php echo esc_url( $term && ! is_wp_error( $term ) ? get_term_link( $term ) : '#' ); ?>"><?php esc_html_e( 'Ver todos', 'prouni' ); ?> ›</a>
		<?php endif; ?>
	</div>

	<div class="<?php echo ! empty( $config['destacado'] ) ? 'premium-grid' : 'standard-grid'; ?>">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			if ( ! empty( $config['destacado'] ) ) {
				if ( 'aliados' === $tipo_slug ) {
					prouni_render_card_premium_aliado( get_post(), $categoria_slug );
				} else {
					prouni_render_card_premium_asociado( get_post(), $categoria_slug );
				}
			} else {
				prouni_render_card_member( get_post(), $categoria_slug );
			}
		endwhile;
		wp_reset_postdata();
		?>
	</div>
	<?php
}
