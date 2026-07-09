# prouni-directorio

Sitio institucional de ProUNI (Patronato de la Universidad Nacional de
Ingeniería del Perú).

## ⚠️ Decisión vigente: NO tema propio — el sitio usa GeneratePress Child

El sitio real del cliente ya tiene un tema activo: **GeneratePress Child**.
El cliente decidió **no reemplazarlo**, así que el enfoque de
`wp-content/themes/prouni/` (tema WordPress completo, construido en una
etapa anterior de este proyecto) quedó **abandonado** — no se sigue
desarrollando y no debe presentarse como la solución a instalar. Esos
archivos se dejan en el repo solo como referencia/histórico; no se
eliminan sin instrucción explícita del cliente.

El Directorio Institucional se entrega en cambio como un **plugin
independiente** en `wp-content/plugins/prouni-directorio/`, con el
shortcode `[prouni_directorio]`. El cliente lo instala vía
Plugins → Añadir nueva → Subir plugin → Activar, crea su propia página
"Directorio" en GeneratePress/Elementor, y pega el shortcode dentro del
contenido (widget "Shortcode" de Elementor Free o bloque de shortcode
de Gutenberg). El header y footer los sigue manejando GeneratePress; el
plugin no los toca.

Reglas para cualquier componente nuevo que se agregue de aquí en
adelante, salvo que el cliente pida explícitamente volver a un tema
completo:
- Vive en `wp-content/plugins/<nombre>/`, nunca en `wp-content/themes/`.
- No crea ni modifica `header.php`, `footer.php`, `functions.php`,
  `style.css` de tema, `page.php`, `single.php` ni `index.php`.
- Todo su CSS va anidado bajo una clase contenedora propia (como
  `.prouni-directorio`) para no filtrarse al resto del sitio.
- Se expone como shortcode (o bloque de Gutenberg si se pide), nunca
  como plantilla de página que reemplace header/footer.

## Regla de fidelidad al diseño original (obligatoria)

El HTML renderizado debe mantenerse **lo más idéntico posible** al
prototipo HTML/CSS/JS original que dio origen a este componente. Al
modificar o extenderlo:

- No cambies clases CSS, estructura del DOM, jerarquía de elementos,
  espaciados ni breakpoints salvo que sea **estrictamente necesario**
  para que funcione como shortcode dentro de GeneratePress/Elementor
  (por ejemplo: anidar el CSS bajo `.prouni-directorio`, encolar
  assets, o envolver el contenido en el shortcode).
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

**Estado actual:** el componente en
`wp-content/plugins/prouni-directorio/directorio.css` todavía usa los
colores del prototipo HTML original (`--guinda:#8f0010`,
`--dorado:#e0aa00`, `--blanco:#fff`), y **así debe permanecer por
ahora** — la prioridad actual es la fidelidad 1:1 con ese HTML. **No
reemplaces estos valores todavía**, ni en el directorio ni copiando la
paleta antigua a páginas nuevas.

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

- `wp-content/plugins/prouni-directorio/` — componente activo (shortcode
  `[prouni_directorio]`): `directorio.php` + `directorio.css` +
  `directorio.js`.
- `wp-content/themes/prouni/` — tema completo de una etapa anterior,
  **abandonado** (ver decisión arriba). No desarrollar sobre esto salvo
  instrucción explícita.
- `prouni-directorio.html` (raíz del repo) — prototipo/experimento
  anterior no relacionado con la implementación actual.
