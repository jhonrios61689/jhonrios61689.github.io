# Dulce Tentación

Proyecto PHP local con el diseño aceptado de escritorio y celular.

## Organización

- `index.html`: entrada principal que ensambla la página.
- `php/layout/`: head.php, encabezado.php y pie.php.
- `php/secciones/`: portada.php, catalogo.php y marca.php.
- `php/componentes/`: producto.php, carrito.php e informacion.php.
- `css/styles.css`: diseño y adaptación responsive.
- `js/app.js`: catálogo y selección de productos.
- `assets/img/`: logo, fotografía y mascota.
- `assets/fonts/`: fuente de presentación local.
- `docs/`: estructura documentada y referencias.

## Ejecutar localmente

Se encontró PHP instalado con XAMPP. Desde PowerShell, abierto en la carpeta del proyecto, ejecutar:

```powershell
& 'C:/xampp/php/php.exe' -S 127.0.0.1:8000 -t .
```

Abrir http://127.0.0.1:8000 en el navegador. Mantener la terminal abierta; Ctrl+C detiene el servidor. El servidor integrado es solo para desarrollo local. index.php requiere un servidor PHP y no se abre con doble clic.

Como alternativa, colocar la carpeta completa en `C:/xampp/htdocs/dulce-tentacion`, iniciar Apache desde XAMPP y abrir http://localhost/dulce-tentacion/. Esta entrega permanece en su carpeta actual; no modifica la configuración de XAMPP.

## Alcance

No requiere Composer, npm ni base de datos. PHP organiza las vistas; JavaScript genera las tarjetas y gestiona el carrito local. No hay cuentas, panel administrativo ni procesamiento de pagos. Mi pedido permite preparar una solicitud por WhatsApp al número +57 311 361 7622 con productos, cantidades y los datos de ubicación escritos por el cliente. El cliente revisa y envía el mensaje en WhatsApp; el negocio confirma las condiciones. Catálogo, precios, ingredientes, contacto y condiciones comerciales siguen pendientes de datos definitivos.

La estructura se explica en [Estructura del proyecto](docs/ESTRUCTURA%20DEL%20PROYECTO.md). Las capturas conservan la presentación aceptada.

La fuente local Arial Rounded MT Bold reproduce la maqueta. Antes de publicar, confirmar su licencia web o utilizar Quicksand, recomendada en el manual.
