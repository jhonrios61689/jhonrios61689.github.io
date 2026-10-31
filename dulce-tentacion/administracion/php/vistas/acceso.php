<main id="access" class="access">
  <section class="access-brand">
    <a class="brand" href="./">
      <img src="assets/img/logo.png" alt="Logo de Dulce Tentaci&oacute;n">
      <span>Dulce Tentaci&oacute;n</span>
    </a>
    <div class="access-message">
      <p class="eyebrow">PORTAL INTERNO INDEPENDIENTE</p>
      <h1>Administraci&oacute;n<br>y Empleados.</h1>
      <p>Plataforma interna conectada a <b>sistema_dulcetentacion</b> para gestionar inventario, ventas, clientes y equipo.</p>
      <img class="mascot" src="assets/img/mascota.png" alt="Mascota cupcake con caf&eacute;">
    </div>
    <small>Uso exclusivo del personal autorizado &middot; Dulce Tentaci&oacute;n</small>
  </section>

  <section class="access-form">
    <div class="login-card">
      <span class="badge">ACCESO POR ROL &middot; ADMINISTRADOR O EMPLEADO</span>
      <h2>Iniciar sesi&oacute;n</h2>
      <p>Ingresa tu correo y contrase&ntilde;a y presiona el bot&oacute;n correspondiente a tu rol (<b>Administrador</b> o <b>Empleado</b>).</p>

      <form id="login-form">
        <label for="login-email">Correo institucional</label>
        <input id="login-email" type="email" value="" placeholder="correo@dulcetentacion.com" autocomplete="username" required maxlength="120">

        <label for="login-password">Contrase&ntilde;a</label>
        <div class="password-wrap">
          <input id="login-password" type="password" value="" placeholder="Ingresa tu contrase&ntilde;a" autocomplete="current-password" required>
          <button type="button" id="show-password" aria-label="Mostrar u ocultar contrase&ntilde;a">Mostrar</button>
        </div>

        <div class="role-login-actions" style="display:grid;gap:10px;margin-top:16px">
          <button class="btn primary" type="submit" id="btn-login-admin" data-login-role="administrador" style="width:100%;justify-content:space-between">
            <span>Entrar como Administrador</span> <span>&rarr;</span>
          </button>
          <button class="btn mint" type="submit" id="btn-login-empleado" data-login-role="empleado" style="width:100%;justify-content:space-between">
            <span>Entrar como Empleado</span> <span>&rarr;</span>
          </button>
        </div>
      </form>

      <p id="login-feedback" class="feedback" role="alert" hidden></p>
    </div>
  </section>
</main>
