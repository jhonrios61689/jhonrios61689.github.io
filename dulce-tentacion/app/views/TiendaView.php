<?php
declare(strict_types=1);

/**
 * CAPA VISTA: TiendaView
 * Ensambla y renderiza las plantillas modulares de la tienda (Layout, Secciones y Componentes).
 */
class TiendaView
{
    public static function render(): void
    {
        $baseDir = dirname(__DIR__, 2) . '/php';
        ?>
<!doctype html>
<html lang="es">
<?php require $baseDir . '/layout/head.php'; ?>
<body>
  <a class="skip-link" href="#contenido">Ir al contenido</a>
  <?php require $baseDir . '/layout/encabezado.php'; ?>
  <main id="contenido">
    <?php require $baseDir . '/secciones/portada.php'; ?>
    <?php require $baseDir . '/secciones/catalogo.php'; ?>
    <?php require $baseDir . '/secciones/marca.php'; ?>
  </main>
  <?php require $baseDir . '/layout/pie.php'; ?>
  <?php require $baseDir . '/componentes/producto.php'; ?>
  <?php require $baseDir . '/componentes/carrito.php'; ?>
  <?php require $baseDir . '/componentes/informacion.php'; ?>
  <?php require $baseDir . '/componentes/login.php'; ?>
  <div id="toast" role="status" aria-live="polite"></div>
</body>
</html>
        <?php
    }
}

