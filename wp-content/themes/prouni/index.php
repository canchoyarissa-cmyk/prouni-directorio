<?php
/**
 * Plantilla de respaldo general (index.php).
 *
 * WordPress exige este archivo como mínimo indispensable para que el
 * tema sea válido. El diseño original (directorio_prouni_v2_filtros.html)
 * solo define la vista del Directorio Institucional -implementada en
 * page-directorio.php-, así que este archivo cubre cualquier otra
 * vista (inicio por defecto, entradas de blog, 404, etc.) con un
 * listado simple que respeta la tipografía y colores del tema.
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main class="directory">
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'simple-row' ); ?> id="post-<?php the_ID(); ?>">
				<div class="section-title">
					<div class="title-left">
						<h2><a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a></h2>
					</div>
				</div>
				<div><?php the_excerpt(); ?></div>
			</article>
			<?php
		endwhile;

		the_posts_pagination();
	else :
		?>
		<p><?php esc_html_e( 'No se encontró contenido.', 'prouni' ); ?></p>
		<?php
	endif;
	?>
</main>
<?php
get_footer();
