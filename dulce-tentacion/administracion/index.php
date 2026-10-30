<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Panel de Administración y Empleados · Dulce Tentación</title>
  <link rel="icon" href="assets/img/logo.png">
  <link rel="stylesheet" href="css/admin.css?v=10">
  <script type="module" src="js/app.js?v=10"></script>
</head>
<body>
  <?php require __DIR__ . '/php/vistas/acceso.php'; ?>
  <?php require __DIR__ . '/php/vistas/panel.php'; ?>
  <?php require __DIR__ . '/php/componentes/editor.php'; ?>
  <?php require __DIR__ . '/php/componentes/perfil.php'; ?>
  <div id="notification" role="status" aria-live="polite"></div>
</body>
</html>
