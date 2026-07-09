# prouni-directorio

Sitio institucional de ProUNI (Patronato de la Universidad Nacional de
Ingeniería del Perú), implementado como tema nativo de WordPress en
`wp-content/themes/prouni/`.

## Regla de fidelidad al diseño original (obligatoria)

El HTML renderizado debe mantenerse **lo más idéntico posible** al
prototipo HTML/CSS/JS original que dio origen al tema. Al modificar o
extender este tema:

- No cambies clases CSS, estructura del DOM, jerarquía de elementos,
  espaciados ni breakpoints salvo que sea **estrictamente necesario**
  para que funcione en WordPress (por ejemplo: dividir en
  header.php/footer.php, encolar assets, o convertir contenido fijo en
  contenido editable vía CPT/campos).
- No cambies textos, iconos, glifos ni comportamiento visual existente
  sin necesidad técnica real.
- Si detectas una oportunidad de mejora (funcionalidad, UX, SEO,
  accesibilidad, etc.) que no sea estrictamente necesaria para la
  conversión a WordPress, **preséntala primero como recomendación** y
  espera aprobación antes de implementarla. No la apliques de forma
  automática.
- Antes de abrir un Pull Request, muestra el resultado final (lista de
  archivos, estructura, diffs relevantes) para revisión.

## Estructura

- `wp-content/themes/prouni/` — tema activo.
- `prouni-directorio.html` (raíz del repo) — prototipo/experimento
  anterior no relacionado con el tema actual; no forma parte de la
  implementación en curso.
