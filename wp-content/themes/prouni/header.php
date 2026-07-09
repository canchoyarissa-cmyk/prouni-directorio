<?php
/**
 * Cabecera del sitio: barra superior + header con logo, menú principal
 * y buscador. Mantiene el mismo marcado y clases CSS del diseño
 * original (top-bar, site-header, logo-area, main-nav, header-search).
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="top-bar">
	<a href="mailto:<?php echo esc_attr( get_theme_mod( 'prouni_top_bar_email', 'contacto@prouni.org.pe' ) ); ?>">
		<?php esc_html_e( 'Correo', 'prouni' ); ?> <span class="mail-icon" aria-hidden="true">✉</span>
	</a>
</div>

<header class="site-header">
	<div class="logo-area">
		<?php if ( has_custom_logo() ) : ?>
			<div class="logo-mark"><?php the_custom_logo(); ?></div>
		<?php else : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-mark" style="text-decoration:none;">
				<?php bloginfo( 'name' ); ?>
			</a>
		<?php endif; ?>
		<div class="logo-divider"></div>
		<div class="logo-text">
			<?php
			$descripcion = get_bloginfo( 'description' );
			echo esc_html( $descripcion ? $descripcion : __( 'Patronato de la Universidad Nacional de Ingeniería', 'prouni' ) );
			?>
		</div>
	</div>

	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => 'nav',
			'container_class' => 'main-nav',
			'items_wrap'     => '%3$s',
			'walker'         => new ProUNI_Walker_Nav_Menu(),
			'fallback_cb'    => 'prouni_fallback_menu',
		)
	);
	?>

	<form class="header-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<span aria-hidden="true">⌕</span>
		<label class="screen-reader-text" for="header-search-input"><?php esc_html_e( 'Buscar en el sitio', 'prouni' ); ?></label>
		<input type="search" id="header-search-input" name="s" placeholder="<?php esc_attr_e( 'Buscar...', 'prouni' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
	</form>
</header>
