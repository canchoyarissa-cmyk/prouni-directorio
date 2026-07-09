<?php
/**
 * Plugin Name:       ProUNI - Directorio Institucional
 * Plugin URI:        https://prouni.org.pe
 * Description:       Shortcode [prouni_directorio] con el Directorio de Asociados y Aliados de ProUNI. No modifica el tema activo: se inserta como contenido dentro de cualquier página (compatible con GeneratePress y Elementor Free, vía el widget "Shortcode" o un bloque de shortcode).
 * Version:           1.0.0
 * Author:            ProUNI
 * Text Domain:       prouni-directorio
 *
 * Fidelidad de diseño: todo el marcado, clases CSS y contenido replican
 * 1:1 el prototipo HTML original (directorio_prouni_v2_filtros.html).
 * No se cambia texto, estructura ni comportamiento visual salvo lo
 * estrictamente necesario para funcionar como shortcode de WordPress
 * (ver CLAUDE.md en la raíz del repositorio para el detalle de qué se
 * mantuvo igual y qué se revirtió a pedido del cliente).
 *
 * @package ProUNI_Directorio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PROUNI_DIRECTORIO_VERSION', '1.0.0' );
define( 'PROUNI_DIRECTORIO_URL', plugin_dir_url( __FILE__ ) );

/**
 * Encola el CSS/JS del componente en el front-end.
 *
 * Se encola sin condición (en vez de detectar el shortcode con
 * has_shortcode()) porque el contenido de páginas construidas con
 * Elementor no vive en post_content de forma fiable, así que esa
 * detección fallaría justo en el caso de uso principal de este
 * componente. El costo es mínimo: un solo CSS (~8 KB) y un solo JS
 * (~3 KB), y ambos están completamente ámbito-limitados a
 * ".prouni-directorio", así que no afectan nada más del sitio aunque
 * se carguen en páginas donde el shortcode no aparece.
 */
function prouni_directorio_assets() {
	wp_enqueue_style(
		'prouni-directorio-fonts',
		'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&family=Roboto+Serif:wght@500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'prouni-directorio',
		PROUNI_DIRECTORIO_URL . 'directorio.css',
		array(),
		PROUNI_DIRECTORIO_VERSION
	);

	wp_enqueue_script(
		'prouni-directorio',
		PROUNI_DIRECTORIO_URL . 'directorio.js',
		array(),
		PROUNI_DIRECTORIO_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'prouni_directorio_assets' );

/**
 * Datos del directorio (pestañas, categorías y miembros).
 *
 * Este componente no crea ningún Custom Post Type ni tabla propia -eso
 * exigiría registrar código en el functions.php del tema, algo que se
 * pidió evitar-, así que el contenido se edita directamente en este
 * array. Para editar un miembro existente o añadir uno nuevo, copia un
 * bloque 'miembros' => [ ... ] y cambia los valores.
 *
 * 'imagen' acepta la URL de una imagen de tu Biblioteca Multimedia
 * (Media > Añadir nueva > clic derecho en la imagen > "Copiar enlace
 * de la imagen"). Si se deja vacío, se usa el círculo degradado o la
 * sigla de texto del diseño original.
 *
 * Se expone con un filtro (prouni_directorio_datos) para que, si más
 * adelante se quiere hacer editable desde wp-admin, pueda sobrescribirse
 * desde otro plugin o desde el functions.php del tema sin tocar este
 * archivo.
 *
 * @return array
 */
function prouni_directorio_datos() {
	$datos = array(
		'asociados' => array(
			'label'      => __( 'Asociados', 'prouni-directorio' ),
			'icono'      => '⌾',
			'categorias' => array(
				'fundadores'     => array(
					'label'     => __( 'Asociados Fundadores', 'prouni-directorio' ),
					'chip'      => __( 'Fundadores', 'prouni-directorio' ),
					'destacado' => true,
					'nota'      => __( '★ Los más destacados', 'prouni-directorio' ),
					'miembros'  => array(
						array(
							'nombre'  => 'Juan Pérez Rodríguez',
							'cargo'   => 'Director General',
							'empresa' => 'Empresa Constructora ABC',
							'anio'    => '2026',
							'sigla'   => 'ABC',
							'imagen'  => '',
						),
						array(
							'nombre'  => 'María del Carmen López',
							'cargo'   => 'Gerente Corporativa',
							'empresa' => 'Industrias del Acero S.A.',
							'anio'    => '2021',
							'sigla'   => 'IDAC',
							'imagen'  => '',
						),
					),
				),
				'honorarios'     => array(
					'label'     => __( 'Asociados Honorarios', 'prouni-directorio' ),
					'chip'      => __( 'Honorarios', 'prouni-directorio' ),
					'destacado' => false,
					'miembros'  => array(
						array(
							'nombre'  => 'Jorge Luis Alarcón',
							'cargo'   => 'Ingeniería Civil',
							'empresa' => 'Promoción UNI 1988',
							'imagen'  => '',
						),
						array(
							'nombre'  => 'Ana María Sánchez',
							'cargo'   => 'Ingeniería Industrial',
							'empresa' => 'Promoción UNI 1995',
							'imagen'  => '',
						),
						array(
							'nombre'  => 'Luis Enrique Flores',
							'cargo'   => 'Ingeniería Mecánica',
							'empresa' => 'Promoción UNI 1993',
							'imagen'  => '',
						),
						array(
							'nombre'  => 'Ricardo Gonzales',
							'cargo'   => 'Ingeniería Eléctrica',
							'empresa' => 'Promoción UNI 2000',
							'imagen'  => '',
						),
					),
				),
				// Igual que en el HTML original: esta sección siempre es una
				// fila estática "Ver todos", nunca una cuadrícula de tarjetas.
				'patrocinadores' => array(
					'label'         => __( 'Patrocinadores', 'prouni-directorio' ),
					'chip'          => __( 'Patrocinadores', 'prouni-directorio' ),
					'destacado'     => false,
					'forzar_simple' => true,
					'simple_glyph'  => '⌄',
				),
				'colaboradores'  => array(
					'label'         => __( 'Colaboradores', 'prouni-directorio' ),
					'chip'          => __( 'Colaboradores', 'prouni-directorio' ),
					'destacado'     => false,
					'forzar_simple' => true,
					'simple_glyph'  => '⌄',
				),
			),
		),
		'aliados'   => array(
			'label'      => __( 'Aliados', 'prouni-directorio' ),
			'icono'      => '▥',
			'categorias' => array(
				'institucionales' => array(
					'label'     => __( 'Aliados Institucionales', 'prouni-directorio' ),
					'chip'      => __( 'Institucionales', 'prouni-directorio' ),
					'destacado' => true,
					'nota'      => __( '★ Los más destacados', 'prouni-directorio' ),
					'miembros'  => array(
						array(
							'nombre'      => 'Constructora ABC',
							'cargo'       => 'Infraestructura y construcción',
							'descripcion' => 'Aliado institucional comprometido con el desarrollo de la ingeniería nacional.',
							'sigla'       => 'ABC',
							'imagen'      => '',
						),
						array(
							'nombre'      => 'Industrias del Acero S.A.',
							'cargo'       => 'Industria y manufactura',
							'descripcion' => 'Empresa aliada para fortalecer vínculos entre universidad, empresa y egresados.',
							'sigla'       => 'IDAC',
							'imagen'      => '',
						),
					),
				),
				// El HTML original solo define el chip de filtro para estas tres
				// categorías: no existe ninguna sección/cuadrícula para ellas.
				'estrategicos'    => array(
					'label'              => __( 'Aliados Estratégicos', 'prouni-directorio' ),
					'chip'               => __( 'Estratégicos', 'prouni-directorio' ),
					'destacado'          => false,
					'renderizar_seccion' => false,
				),
				'delegados'       => array(
					'label'              => __( 'Delegados', 'prouni-directorio' ),
					'chip'               => __( 'Delegados', 'prouni-directorio' ),
					'destacado'          => false,
					'renderizar_seccion' => false,
				),
				'amigos'          => array(
					'label'              => __( 'Amigos', 'prouni-directorio' ),
					'chip'               => __( 'Amigos', 'prouni-directorio' ),
					'destacado'          => false,
					'renderizar_seccion' => false,
				),
			),
		),
	);

	/**
	 * Filtra los datos del directorio (pestañas/categorías/miembros).
	 *
	 * @param array $datos Estructura completa descrita arriba.
	 */
	return apply_filters( 'prouni_directorio_datos', $datos );
}

/**
 * Genera iniciales a partir de un nombre/razón social, usadas como
 * respaldo del logo cuando el miembro no tiene 'imagen'.
 *
 * @param string $nombre Nombre completo o razón social.
 * @return string
 */
function prouni_directorio_iniciales( $nombre ) {
	$palabras  = preg_split( '/\s+/', trim( $nombre ) );
	$iniciales = '';
	foreach ( array_slice( $palabras, 0, 2 ) as $palabra ) {
		$iniciales .= mb_strtoupper( mb_substr( $palabra, 0, 1 ) );
	}
	return $iniciales;
}

/**
 * Imprime el círculo de avatar (imagen del miembro o, si no hay
 * ninguna, el fondo degradado del diseño original).
 *
 * @param array $miembro Datos del miembro.
 */
function prouni_directorio_avatar( $miembro ) {
	if ( ! empty( $miembro['imagen'] ) ) {
		echo '<div class="avatar"><img src="' . esc_url( $miembro['imagen'] ) . '" alt="' . esc_attr( $miembro['nombre'] ) . '" loading="lazy" /></div>';
	} else {
		echo '<div class="avatar"></div>';
	}
}

/**
 * Imprime el bloque .company-logo (imagen del logo o, en su defecto,
 * la sigla configurada / autogenerada a partir del nombre).
 *
 * @param array  $miembro      Datos del miembro.
 * @param string $extra_estilo Estilo inline adicional (ej. "border-left:0").
 */
function prouni_directorio_logo( $miembro, $extra_estilo = '' ) {
	$sigla = ! empty( $miembro['sigla'] ) ? $miembro['sigla'] : prouni_directorio_iniciales( $miembro['nombre'] );
	$style = $extra_estilo ? ' style="' . esc_attr( $extra_estilo ) . '"' : '';

	echo '<div class="company-logo"' . $style . '>';
	if ( ! empty( $miembro['imagen'] ) ) {
		echo '<img src="' . esc_url( $miembro['imagen'] ) . '" alt="' . esc_attr( $miembro['nombre'] ) . '" loading="lazy" />';
	} else {
		// El diseño original antepone un icono "▮" a la sigla en el
		// company-logo principal (no en la variante compacta con
		// border-left:0).
		if ( ! $extra_estilo ) {
			echo '<span aria-hidden="true">▮</span>';
		}
		echo esc_html( $sigla );
	}
	echo '</div>';
}

/**
 * Tarjeta destacada (premium-card) para Asociados: avatar + datos + logo.
 *
 * @param array  $miembro   Datos del miembro.
 * @param string $categoria Slug de la categoría (para el filtro por chip).
 */
function prouni_directorio_card_premium_asociado( $miembro, $categoria ) {
	?>
	<article class="premium-card filter-card" data-category="<?php echo esc_attr( $categoria ); ?>" data-name="<?php echo esc_attr( strtolower( $miembro['nombre'] ) ); ?>">
		<?php prouni_directorio_avatar( $miembro ); ?>
		<div>
			<h3><?php echo esc_html( $miembro['nombre'] ); ?></h3>
			<?php if ( ! empty( $miembro['cargo'] ) ) : ?><p><?php echo esc_html( $miembro['cargo'] ); ?></p><?php endif; ?>
			<?php if ( ! empty( $miembro['empresa'] ) ) : ?><p><?php echo esc_html( $miembro['empresa'] ); ?></p><?php endif; ?>
			<p class="small-meta"><span aria-hidden="true">▣</span> <?php esc_html_e( 'Ingreso a ProUNI', 'prouni-directorio' ); ?></p>
			<?php if ( ! empty( $miembro['anio'] ) ) : ?><p class="year"><?php echo esc_html( $miembro['anio'] ); ?></p><?php endif; ?>
		</div>
		<?php prouni_directorio_logo( $miembro ); ?>
	</article>
	<?php
}

/**
 * Tarjeta destacada (premium-card) para Aliados: logo + datos + logo.
 *
 * @param array  $miembro   Datos del aliado.
 * @param string $categoria Slug de la categoría (para el filtro por chip).
 */
function prouni_directorio_card_premium_aliado( $miembro, $categoria ) {
	?>
	<article class="premium-card filter-card" data-category="<?php echo esc_attr( $categoria ); ?>" data-name="<?php echo esc_attr( strtolower( $miembro['nombre'] ) ); ?>">
		<?php prouni_directorio_logo( $miembro, 'border-left:0' ); ?>
		<div>
			<h3><?php echo esc_html( $miembro['nombre'] ); ?></h3>
			<?php if ( ! empty( $miembro['cargo'] ) ) : ?><p><?php echo esc_html( $miembro['cargo'] ); ?></p><?php endif; ?>
			<?php if ( ! empty( $miembro['descripcion'] ) ) : ?><p><?php echo esc_html( $miembro['descripcion'] ); ?></p><?php endif; ?>
		</div>
		<?php prouni_directorio_logo( $miembro ); ?>
	</article>
	<?php
}

/**
 * Tarjeta estándar (member-card) usada en las cuadrículas de 4 columnas
 * (Honorarios).
 *
 * @param array  $miembro   Datos del miembro.
 * @param string $categoria Slug de la categoría (para el filtro por chip).
 */
function prouni_directorio_card_member( $miembro, $categoria ) {
	?>
	<article class="member-card filter-card" data-category="<?php echo esc_attr( $categoria ); ?>" data-name="<?php echo esc_attr( strtolower( $miembro['nombre'] ) ); ?>">
		<?php prouni_directorio_avatar( $miembro ); ?>
		<div>
			<h3><?php echo esc_html( $miembro['nombre'] ); ?></h3>
			<?php if ( ! empty( $miembro['cargo'] ) ) : ?><p><?php echo esc_html( $miembro['cargo'] ); ?></p><?php endif; ?>
			<?php if ( ! empty( $miembro['empresa'] ) ) : ?><p><?php echo esc_html( $miembro['empresa'] ); ?></p><?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * Fila simple con enlace "Ver todos", usada cuando una categoría no
 * tiene miembros cargados (o siempre, para Patrocinadores/Colaboradores,
 * tal como en el HTML original).
 *
 * @param string $etiqueta Título visible de la sección.
 * @param string $sufijo   Texto/glifo tras "Ver todos".
 */
function prouni_directorio_simple_row( $etiqueta, $sufijo = ' ›' ) {
	?>
	<div class="simple-row section-title">
		<div class="title-left">
			<div class="icon-line" aria-hidden="true">♙</div>
			<h2><?php echo esc_html( $etiqueta ); ?></h2>
		</div>
		<a class="view-all" href="#"><?php esc_html_e( 'Ver todos', 'prouni-directorio' ); ?><?php echo esc_html( $sufijo ); ?></a>
	</div>
	<?php
}

/**
 * Renderiza el bloque completo de una categoría dentro de una pestaña.
 *
 * @param string $tipo_slug Slug del tipo (asociados|aliados).
 * @param array  $tipo      Config del tipo (icono, etc.).
 * @param string $cat_slug  Slug de la categoría.
 * @param array  $config    Config de la categoría (label, chip, destacado, miembros...).
 */
function prouni_directorio_render_seccion( $tipo_slug, $tipo, $cat_slug, $config ) {
	if ( isset( $config['renderizar_seccion'] ) && false === $config['renderizar_seccion'] ) {
		return;
	}

	if ( ! empty( $config['forzar_simple'] ) ) {
		prouni_directorio_simple_row( $config['label'], $config['simple_glyph'] ?? ' ›' );
		return;
	}

	$miembros = $config['miembros'] ?? array();

	if ( empty( $miembros ) ) {
		prouni_directorio_simple_row( $config['label'] );
		return;
	}
	?>
	<div class="section-title">
		<div class="title-left">
			<div class="icon-line" aria-hidden="true"><?php echo esc_html( $tipo['icono'] ); ?></div>
			<h2><?php echo esc_html( $config['label'] ); ?></h2>
			<?php if ( ! empty( $config['destacado'] ) && ! empty( $config['nota'] ) ) : ?>
				<span class="highlight-note"><?php echo esc_html( $config['nota'] ); ?></span>
			<?php endif; ?>
		</div>
		<?php if ( empty( $config['destacado'] ) ) : ?>
			<a class="view-all" href="#"><?php esc_html_e( 'Ver todos', 'prouni-directorio' ); ?> ›</a>
		<?php endif; ?>
	</div>

	<div class="<?php echo ! empty( $config['destacado'] ) ? 'premium-grid' : 'standard-grid'; ?>">
		<?php foreach ( $miembros as $miembro ) : ?>
			<?php
			if ( ! empty( $config['destacado'] ) ) {
				if ( 'aliados' === $tipo_slug ) {
					prouni_directorio_card_premium_aliado( $miembro, $cat_slug );
				} else {
					prouni_directorio_card_premium_asociado( $miembro, $cat_slug );
				}
			} else {
				prouni_directorio_card_member( $miembro, $cat_slug );
			}
			?>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Callback del shortcode [prouni_directorio].
 *
 * Atributos:
 * - titulo: título del Hero (por defecto "Directorio Institucional").
 * - subtitulo: párrafo del Hero.
 *
 * @param array $atts Atributos del shortcode.
 * @return string
 */
function prouni_directorio_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'titulo'    => __( 'Directorio Institucional', 'prouni-directorio' ),
			'subtitulo' => __( 'Conoce a los profesionales, empresas e instituciones que forman parte de nuestra comunidad y que impulsan el futuro de la ingeniería en el Perú.', 'prouni-directorio' ),
		),
		$atts,
		'prouni_directorio'
	);

	$mapa = prouni_directorio_datos();

	ob_start();
	?>
	<div class="prouni-directorio">
		<section class="hero">
			<div class="hero-inner">
				<h1><?php echo esc_html( $atts['titulo'] ); ?></h1>
				<p><?php echo esc_html( $atts['subtitulo'] ); ?></p>
				<div class="gold-line"></div>
			</div>
		</section>

		<main class="directory">
			<div class="switch-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Tipo de directorio', 'prouni-directorio' ); ?>">
				<?php $primero = true; ?>
				<?php foreach ( $mapa as $tipo_slug => $tipo_config ) : ?>
					<button
						class="<?php echo esc_attr( $primero ? 'active' : '' ); ?>"
						data-tab="<?php echo esc_attr( $tipo_slug ); ?>"
						role="tab"
						id="tab-<?php echo esc_attr( $tipo_slug ); ?>"
						aria-selected="<?php echo esc_attr( $primero ? 'true' : 'false' ); ?>"
						aria-controls="<?php echo esc_attr( $tipo_slug ); ?>"
					>
						<span aria-hidden="true"><?php echo esc_html( $tipo_config['icono'] ); ?></span> &nbsp; <?php echo esc_html( $tipo_config['label'] ); ?>
					</button>
					<?php $primero = false; ?>
				<?php endforeach; ?>
			</div>

			<?php $primero = true; ?>
			<?php foreach ( $mapa as $tipo_slug => $tipo_config ) : ?>
				<div
					class="tab-panel <?php echo esc_attr( $primero ? 'active' : '' ); ?>"
					id="<?php echo esc_attr( $tipo_slug ); ?>"
					role="tabpanel"
					aria-labelledby="tab-<?php echo esc_attr( $tipo_slug ); ?>"
				>
					<div class="controls">
						<div class="chips" role="group" aria-label="<?php esc_attr_e( 'Filtrar por categoría', 'prouni-directorio' ); ?>">
							<button class="chip active" data-filter="all" aria-pressed="true"><?php esc_html_e( 'Todos', 'prouni-directorio' ); ?></button>
							<?php foreach ( $tipo_config['categorias'] as $cat_slug => $cat_config ) : ?>
								<button class="chip" data-filter="<?php echo esc_attr( $cat_slug ); ?>" aria-pressed="false"><?php echo esc_html( $cat_config['chip'] ); ?></button>
							<?php endforeach; ?>
						</div>
						<div class="search-box">
							<span aria-hidden="true">⌕</span>
							<label class="screen-reader-text" for="prouni-search-<?php echo esc_attr( $tipo_slug ); ?>">
								<?php echo esc_html( 'aliados' === $tipo_slug ? __( 'Buscar institución', 'prouni-directorio' ) : __( 'Buscar por nombre', 'prouni-directorio' ) ); ?>
							</label>
							<input type="text" id="prouni-search-<?php echo esc_attr( $tipo_slug ); ?>" data-role="name-search" placeholder="<?php echo esc_attr( 'aliados' === $tipo_slug ? __( 'Buscar institución...', 'prouni-directorio' ) : __( 'Buscar por nombre...', 'prouni-directorio' ) ); ?>" />
						</div>
					</div>

					<?php foreach ( $tipo_config['categorias'] as $cat_slug => $cat_config ) : ?>
						<?php prouni_directorio_render_seccion( $tipo_slug, $tipo_config, $cat_slug, $cat_config ); ?>
					<?php endforeach; ?>

					<p class="no-results-msg"><?php esc_html_e( 'No se encontraron resultados con esos criterios.', 'prouni-directorio' ); ?></p>
				</div>
				<?php $primero = false; ?>
			<?php endforeach; ?>
		</main>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'prouni_directorio', 'prouni_directorio_shortcode' );
