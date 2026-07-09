<?php
/**
 * Pie del sitio.
 *
 * El prototipo original no incluye una sección <footer> propia (la
 * página termina tras el <main class="directory">), así que este
 * archivo solo cierra el documento y engancha wp_footer() para que
 * scripts, widgets y plugins (incluido Elementor) puedan operar con
 * normalidad.
 *
 * @package ProUNI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php wp_footer(); ?>
</body>
</html>
