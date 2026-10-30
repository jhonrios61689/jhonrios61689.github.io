<main id="access" class="access">
  <section class="access-brand">
    <a class="brand" href="./">
      <img src="assets/img/logo.png" alt="">
      <span>Dulce Tentación</span>
    </a>
    <div class="access-message">
      <p class="eyebrow">PORTAL INTERNO INDEPENDIENTE</p>
      <h1>Administración<br>y Empleados.</h1>
      <p>Plataforma interna conectada a <b>sistema_dulcetentacion</b> para gestionar inventario, ventas, clientes y equipo.</p>
      <img class="mascot" src="assets/img/mascota.png" alt="Mascota cupcake con café">
    </div>
    <small>Uso exclusivo del personal autorizado · Dulce Tentación</small>
  </section>

  <section class="access-form">
    <div class="login-card">
      <span class="badge">ACCESO POR ROL · ADMINISTRADOR O EMPLEADO</span>
      <h2>Iniciar sesión</h2>
      <p>Ingresa tu correo y contraseña y presiona el botón correspondiente a tu rol (<b>Administrador</b> o <b>Empleado</b>).</p>

      <form id="login-form">
        <label for="login-email">Correo institucional</label>
        <input id="login-email" type="email" value="" placeholder="correo@dulcetentacion.com" autocomplete="username" required maxlength="120">

        <label for="login-password">Contraseña</label>
        <div class="password-wrap">
          <input id="login-password" type="password" value="" placeholder="Ingresa tu contraseña" autocomplete="current-password" required>
          <button type="button" id="show-password" aria-label="Mostrar contraseña">Mostrar</button>
        </div>

        <div class="role-login-actions" style="display:grid;gap:10px;margin-top:14px">
          <button class="btn primary" type="submit" id="btn-login-admin" data-login-role="administrador" style="width:100%;justify-content:space-between">
            <span>Entrar como Administrador</span> <span>→</span>
          </button>
          <button class="btn mint" type="submit" id="btn-login-empleado" data-login-role="empleado" style="width:100%;justify-content:space-between">
            <span>Entrar como Empleado</span> <span>→</span>
          </button>
        </div>
      </form>

      <p id="login-feedback" class="feedback" role="alert" hidden></p>

      <p class="help">Cada botón valida exclusivamente su tabla correspondiente en MySQL (<code>administradores</code> o <code>empleados</code>).</p>
    </div>
  </section>
</main>
