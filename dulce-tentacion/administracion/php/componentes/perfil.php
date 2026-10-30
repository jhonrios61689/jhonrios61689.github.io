<dialog id="profile-dialog" aria-labelledby="profile-title">
  <header>
    <div>
      <p class="eyebrow">CUENTA DE ADMINISTRADOR</p>
      <h2 id="profile-title">Mis datos y seguridad</h2>
    </div>
    <button type="button" id="close-profile" aria-label="Cerrar ventana de perfil">×</button>
  </header>

  <div class="admin-data-card">
    <div class="profile-header-row">
      <div class="profile-avatar-large" id="profile-avatar">AA</div>
      <div class="profile-info">
        <h3 id="profile-fullname">Angela Andrea</h3>
        <span class="badge mint" id="profile-badge">Administrador</span>
      </div>
    </div>

    <div class="admin-fields-grid">
      <div class="admin-field">
        <small>ID Administrador</small>
        <strong id="profile-id">ADM-001</strong>
      </div>
      <div class="admin-field">
        <small>Cargo institucional</small>
        <strong id="profile-role">Gerente General</strong>
      </div>
      <div class="admin-field full">
        <small>Correo institucional</small>
        <strong id="profile-email">admin1@local.com</strong>
      </div>
      <div class="admin-field full">
        <small>Privilegios del sistema</small>
        <span>Acceso total · Gestión de personal, inventario y ventas</span>
      </div>
    </div>
  </div>

  <section class="admin-security-section">
    <div class="security-heading">
      <h3>Cambiar contraseña</h3>
      <p>Ingresa tu clave actual y define la nueva contraseña para tu cuenta.</p>
    </div>

    <!-- Mensaje de confirmacion destacado y visible -->
    <div id="password-feedback-banner" class="feedback success" style="display:none; font-size:13px; padding:12px 16px; margin-bottom:14px; border:2px solid #2e7d32; background:#e8f5e9; color:#1b5e20; border-radius:10px; font-weight:bold;">
      ✔ ¡Contraseña actualizada con éxito en la base de datos!
    </div>

    <form id="change-password-form">
      <label for="pwd-current">Contraseña actual</label>
      <div class="password-wrap">
        <input id="pwd-current" name="currentPassword" type="password" required placeholder="Tu contraseña actual" autocomplete="current-password">
        <button type="button" class="btn-toggle-pwd" data-target="pwd-current">Mostrar</button>
      </div>

      <label for="pwd-new">Nueva contraseña</label>
      <div class="password-wrap">
        <input id="pwd-new" name="newPassword" type="password" required minlength="4" placeholder="Mínimo 4 caracteres" autocomplete="new-password">
        <button type="button" class="btn-toggle-pwd" data-target="pwd-new">Mostrar</button>
      </div>

      <label for="pwd-confirm">Confirmar nueva contraseña</label>
      <div class="password-wrap">
        <input id="pwd-confirm" name="confirmPassword" type="password" required minlength="4" placeholder="Repite la nueva contraseña" autocomplete="new-password">
        <button type="button" class="btn-toggle-pwd" data-target="pwd-confirm">Mostrar</button>
      </div>

      <p id="password-feedback" class="feedback" role="alert" hidden></p>

      <div class="dialog-actions">
        <button type="button" class="btn" id="cancel-profile">Cerrar</button>
        <button type="submit" class="btn primary" id="btn-save-password">Actualizar contraseña</button>
      </div>
    </form>
  </section>
</dialog>

