<?php
/**
 * Template Name: Directorio Institucional
 * Template Post Type: page
 *
 * Plantilla de página para /directorio: Hero + pestañas Asociados/Aliados
 * con chips de filtro por categoría y buscador por nombre. El listado de
 * miembros se alimenta del CPT "prouni_miembro" (ver inc/cpt-directorio.php),
 * de modo que todo el contenido es editable desde el escritorio de
 * WordPress sin tocar esta plantilla.
 *
 * Compatibilidad con Elementor: si esta página se construye con Elementor
 * (Editar con Elementor), se respeta ese contenido y no se fuerza el
 * marcado del directorio, para no interferir con el plugin.
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$usa_elementor = false;
if ( did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' ) && get_the_ID() ) {
	$elementor_doc = \Elementor\Plugin::$instance->documents->get( get_the_ID() );
	$usa_elementor = $elementor_doc && $elementor_doc->is_built_with_elementor();
}

if ( $usa_elementor ) {
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	get_footer();
	return;
}

$mapa = prouni_get_directorio_mapa();
$hero_subtitulo = get_post_meta( get_the_ID(), '_prouni_hero_subtitulo', true );
if ( ! $hero_subtitulo ) {
	$hero_subtitulo = __( 'Conoce a los profesionales, empresas e instituciones que forman parte de nuestra comunidad y que impulsan el futuro de la ingeniería en el Perú.', 'prouni' );
}
?>

<section class="hero">
	<div class="hero-inner">
		<?php while ( have_posts() ) : the_post(); ?>
			<h1><?php the_title(); ?></h1>
		<?php endwhile; ?>
		<p><?php echo esc_html( $hero_subtitulo ); ?></p>
		<div class="gold-line"></div>
	</div>
</section>

<main class="directory">
	<div class="switch-tabs">
		<?php $primero = true; ?>
		<?php foreach ( $mapa as $tipo_slug => $tipo_config ) : ?>
			<button class="<?php echo $primero ? 'active' : ''; ?>" data-tab="<?php echo esc_attr( $tipo_slug ); ?>">
				<?php echo esc_html( $tipo_config['icono'] ); ?> &nbsp; <?php echo esc_html( $tipo_config['label'] ); ?>
			</button>
			<?php $primero = false; ?>
		<?php endforeach; ?>
	</div>

	<?php $primero = true; ?>
	<?php foreach ( $mapa as $tipo_slug => $tipo_config ) : ?>
		<div class="tab-panel <?php echo $primero ? 'active' : ''; ?>" id="<?php echo esc_attr( $tipo_slug ); ?>">

			<div class="controls">
				<div class="chips">
					<button class="chip active" data-filter="all"><?php esc_html_e( 'Todos', 'prouni' ); ?></button>
					<?php foreach ( $tipo_config['categorias'] as $cat_slug => $cat_config ) : ?>
						<button class="chip" data-filter="<?php echo esc_attr( $cat_slug ); ?>"><?php echo esc_html( $cat_config['chip'] ); ?></button>
					<?php endforeach; ?>
				</div>
				<div class="search-box">
					⌕ <input type="text" data-role="name-search" placeholder="<?php echo esc_attr( 'aliados' === $tipo_slug ? __( 'Buscar institución...', 'prouni' ) : __( 'Buscar por nombre...', 'prouni' ) ); ?>" />
				</div>
			</div>

			<?php foreach ( $tipo_config['categorias'] as $cat_slug => $cat_config ) : ?>
				<?php prouni_render_seccion_categoria( $tipo_slug, $cat_slug, $cat_config ); ?>
			<?php endforeach; ?>

			<p class="no-results-msg"><?php esc_html_e( 'No se encontraron resultados con esos criterios.', 'prouni' ); ?></p>
		</div>
		<?php $primero = false; ?>
	<?php endforeach; ?>
</main>

<?php get_footer(); ?>
