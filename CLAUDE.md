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

## Identidad visual oficial de PROUNI

Paleta institucional oficial (confirmada por el cliente):

- Guinda principal: `#660000`
- Dorado: `#C9A84C`
- Blanco de fondo: `#F9F8F6`

**Estado actual:** el tema en `wp-content/themes/prouni/` (plantilla del
Directorio) todavía usa los colores del prototipo HTML original
(`--guinda:#8f0010`, `--dorado:#e0aa00`, `--blanco:#fff` en
`assets/css/directorio.css`), y **así debe permanecer por ahora** — la
prioridad actual es la fidelidad 1:1 con ese HTML. **No reemplaces estos
valores todavía**, ni en el directorio ni copiando la paleta antigua a
páginas nuevas.

Esta paleta oficial es la que se debe usar en:
- Cualquier página o componente **nuevo** que se construya de aquí en
  adelante y no tenga ya un diseño HTML de referencia con otros colores.
- La migración global de colores que se hará **al final**, cuando todo
  el sitio esté migrado a WordPress: en ese momento se reemplazarán los
  colores antiguos (`#8f0010` / `#e0aa00` / `#fff`) por esta paleta
  oficial en todo el sitio a la vez, verificando consistencia visual
  completa antes de darla por terminada. No hacer ese reemplazo de
  forma parcial ni adelantada sin instrucción explícita.

## Estructura

- `wp-content/themes/prouni/` — tema activo.
- `prouni-directorio.html` (raíz del repo) — prototipo/experimento
  anterior no relacionado con el tema actual; no forma parte de la
  implementación en curso.
