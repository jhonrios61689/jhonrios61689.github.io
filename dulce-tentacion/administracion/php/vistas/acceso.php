<main id="access" class="access">
  <section class="access-brand">
    <a class="brand" href="./">
      <img src="assets/img/logo.png" alt="Logo de Dulce Tentaci&oacute;n">
      <span>Dulce Tentaci&oacute;n</span>
    </a>
    <div class="access-message">
      <p class="eyebrow">PORTAL ADMINISTRATIVO</p>
      <h1>Panel del<br>Administrador.</h1>
      <p>Plataforma interna conectada en tiempo real a <b>sistema_dulcetentacion</b> para la gesti&oacute;n integral de inventario, ventas, clientes y personal.</p>
      <img class="mascot" src="assets/img/mascota.png" alt="Mascota cupcake con caf&eacute;">
    </div>
    <small>Uso exclusivo de la Administraci&oacute;n &middot; Dulce Tentaci&oacute;n</small>
  </section>

  <section class="access-form">
    <div class="login-card">
      <span class="badge" id="role-badge">ACCESO DEL ADMINISTRADOR</span>
      <h2 id="role-login-title">Iniciar sesi&oacute;n como Administrador</h2>
      <p id="role-login-desc">Ingresa tu correo institucional y contrase&ntilde;a autorizada en la base de datos MySQL.</p>

      <form id="login-form">
        <label for="login-email" id="label-login-email">Correo del Administrador</label>
        <input id="login-email" type="email" value="" placeholder="admin1@local.com" autocomplete="username" required maxlength="120">

        <label for="login-password">Contrase&ntilde;a</label>
        <div class="password-wrap">
          <input id="login-password" type="password" value="" placeholder="Ingresa tu contrase&ntilde;a" autocomplete="current-password" required>
          <button type="button" id="show-password" aria-label="Mostrar u ocultar contrase&ntilde;a">Mostrar</button>
        </div>

        <div style="margin-top:16px">
          <button class="btn primary" type="submit" id="btn-login-submit" data-login-role="administrador" style="width:100%; justify-content:center; gap:8px; padding:12px; font-size:15px; font-weight:600;">
            <span id="btn-login-text">Entrar como Administrador</span> <span>&rarr;</span>
          </button>
        </div>

        <div class="role-switch-container" style="margin-top:16px; padding-top:14px; border-top:1px dashed #e8d7cf; text-align:center; font-size:13px; color:#6b574f;">
          <span id="role-switch-text">&iquest;Eres empleado operativo?</span>
          <button type="button" id="btn-switch-role" style="background:none; border:none; color:#c85a7a; font-weight:700; cursor:pointer; text-decoration:underline; margin-left:4px; font-size:13px;">Acceso para Empleado</button>
        </div>
      </form>

      <p id="login-feedback" class="feedback" role="alert" hidden></p>

      <p class="help" id="role-help-text">Valida exclusivamente en la tabla <code>administradores</code> de MySQL (ejemplo: <b>admin1@local.com</b> / <b>pass123</b>).</p>
    </div>
  </section>
</main>
