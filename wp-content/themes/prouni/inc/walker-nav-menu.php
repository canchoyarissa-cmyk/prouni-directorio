<?php
/**
 * Walker de menú personalizado para el menú principal de la cabecera.
 *
 * El diseño original NO usa <ul><li> para el menú, sino enlaces <a>
 * y un <div class="dropdown"> sueltos como hijos flex directos de
 * <nav class="main-nav">. Este walker reproduce exactamente esa
 * estructura (y por tanto el CSS original no necesita tocarse) a
 * partir de un menú real de WordPress editable en Apariencia > Menús.
 * Cualquier ítem con hijos se comporta como el desplegable
 * "Membresía" del prototipo, no solo ese ítem en particular.
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ProUNI_Walker_Nav_Menu' ) ) {

	class ProUNI_Walker_Nav_Menu extends Walker_Nav_Menu {

		/**
		 * Abre el desplegable (equivalente a .dropdown-menu).
		 */
		public function start_lvl( &$output, $depth = 0, $args = null ) {
			$output .= '<div class="dropdown-menu">';
		}

		/**
		 * Cierra el desplegable.
		 */
		public function end_lvl( &$output, $depth = 0, $args = null ) {
			$output .= '</div>';
		}

		/**
		 * Abre cada elemento del menú.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
			$has_children = in_array( 'menu-item-has-children', $item->classes, true );
			$is_current   = $item->current || $item->current_item_ancestor;

			if ( 0 === $depth ) {
				if ( $has_children ) {
					// Se cierra en end_el() tras procesar start_lvl/end_lvl de sus hijos.
					$output .= '<div class="dropdown">';
				}

				$link_class   = array( 'nav-item' );
				$link_class[] = $is_current ? 'active' : '';
				$link_class   = implode( ' ', array_filter( $link_class ) );

				$output .= '<a class="' . esc_attr( $link_class ) . '" href="' . esc_url( $item->url ) . '">';
				$output .= esc_html( $item->title );
				$output .= $has_children ? '<span aria-hidden="true">⌄</span>' : '';
				$output .= '</a>';
			} else {
				// Ítems dentro de un .dropdown-menu.
				$output .= '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
			}
		}

		/**
		 * Cierra cada elemento del menú (solo los de nivel superior con hijos
		 * necesitan cerrar el <div class="dropdown"> abierto en start_el()).
		 */
		public function end_el( &$output, $item, $depth = 0, $args = null ) {
			$has_children = in_array( 'menu-item-has-children', $item->classes, true );
			if ( 0 === $depth && $has_children ) {
				$output .= '</div>';
			}
		}
	}
}

/**
 * Menú estático de respaldo (fallback_cb) que se muestra únicamente si
 * el administrador aún no ha creado el menú "Menú principal" en
 * Apariencia > Menús. Reproduce el contenido exacto del prototipo
 * original para que el sitio nunca se vea "vacío" antes de configurar
 * el menú real. Incluye el propio contenedor <nav class="main-nav">
 * porque wp_nav_menu() no lo añade cuando se usa fallback_cb.
 */
function prouni_fallback_menu() {
	?>
	<nav class="main-nav" aria-label="<?php esc_attr_e( 'Menú principal', 'prouni' ); ?>">
		<a href="#" class="nav-item"><?php esc_html_e( 'Inicio', 'prouni' ); ?></a>

		<div class="dropdown">
			<a href="#" class="nav-item"><?php esc_html_e( 'Acerca de', 'prouni' ); ?><span aria-hidden="true">⌄</span></a>
		</div>

		<div class="dropdown">
			<a href="#" class="nav-item"><?php esc_html_e( 'Actividades', 'prouni' ); ?><span aria-hidden="true">⌄</span></a>
		</div>

		<a href="#" class="nav-item"><?php esc_html_e( 'Publicaciones', 'prouni' ); ?></a>

		<div class="dropdown">
			<a href="#" class="nav-item active"><?php esc_html_e( 'Membresía', 'prouni' ); ?><span aria-hidden="true">⌄</span></a>
			<div class="dropdown-menu">
				<a href="#"><?php esc_html_e( 'Directorio', 'prouni' ); ?></a>
				<a href="#"><?php esc_html_e( 'Programa de Aliados', 'prouni' ); ?></a>
			</div>
		</div>

		<a href="#" class="nav-item"><?php esc_html_e( 'Balance Anual', 'prouni' ); ?></a>
	</nav>
	<?php
}
