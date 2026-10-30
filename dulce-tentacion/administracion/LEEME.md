# Administración de Dulce Tentación

Interfaz PHP independiente para entregar al desarrollador de base de datos. Incluye acceso, resumen, productos y stock, empleados y accesos, clientes, movimientos y propuesta de roles.

## Abrir localmente

Copiar la carpeta `administracion` junto al index.php de la tienda. Con XAMPP, iniciar Apache y abrir `http://localhost/dulce-tentacion/administracion/`. Si la carpeta del proyecto tiene otro nombre, ajustar esa parte de la dirección.

También se puede ejecutar desde esta carpeta con `C:/xampp/php/php.exe -S 127.0.0.1:8001 -t .` y abrir http://127.0.0.1:8001. PHP solo ensambla las vistas; no requiere MySQL, Composer ni npm para revisar la interfaz.

En la pantalla de acceso, elegir **Explorar demostración**. El formulario de ingreso presenta el estado de integración y no valida credenciales. No ingresar contraseñas reales.

## Entrega al desarrollador

Consultar [Integración](docs/INTEGRACION.md). La conexión se concentra en `js/api.js`; las vistas consumen sus métodos sin acceder directamente al almacenamiento. Los registros de ejemplo viven en memoria y se reinician al recargar. No se publican ni se guardan en una base de datos.

El módulo usa rutas propias y no modifica el index, carrito, CSS o JavaScript de la tienda. La fuente local replica la presentación aprobada; revisar su licencia web antes de publicar o usar Quicksand como alternativa del manual.
