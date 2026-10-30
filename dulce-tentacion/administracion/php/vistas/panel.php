<div id="panel" class="panel" hidden>
  <aside class="sidebar">
    <a class="brand" href="./">
      <img src="assets/img/logo.png" alt="">
      <span>Dulce<br>Tentación</span>
    </a>
    <small class="sidebar-label" id="sidebar-role-label">ADMINISTRACIÓN</small>
    <nav aria-label="Secciones del sistema interno">
      <button data-view="overview" class="active">▦ <span>Resumen</span></button>
      <button data-view="products">▧ <span>Productos y stock</span></button>
      <button data-view="employees" id="nav-employees">♧ <span>Empleados y accesos</span></button>
      <button data-view="clients">◉ <span>Clientes</span></button>
      <button data-view="movements">⇄ <span>Movimientos y ventas</span></button>
      <button data-view="roles">◇ <span>Roles y permisos</span></button>
    </nav>
    <div class="sidebar-bottom">
      <span class="dot"></span> Base de datos activa
      <p>Conectado a <b>sistema_dulcetentacion</b>.</p>
      <button id="logout">← Cerrar sesión</button>
    </div>
  </aside>

  <div class="workspace">
    <header class="topbar">
      <button class="mobile-toggle" id="toggle-menu" aria-label="Abrir menú" aria-expanded="false">☰</button>
      <span>Portal interno <b>/ Dulce Tentación</b></span>
      <button class="user-pill" id="user-profile-pill" type="button" aria-haspopup="dialog" title="Ver mis datos y cambiar contraseña">
        <span>AA</span>
        <div>Angela Andrea<small>Gerente General</small></div>
      </button>
    </header>

    <main class="content">
      <div class="demo-strip">
        <b id="strip-role-badge">Sesión Administrativa</b>
        <span>Conectado en tiempo real con MySQL · sistema_dulcetentacion</span>
      </div>

      <div class="page-heading">
        <div>
          <p class="eyebrow" id="page-kicker">GESTIÓN INTERNA DEL NEGOCIO</p>
          <h1 id="page-title">Resumen</h1>
          <p id="page-description"></p>
        </div>
        <button id="create-record" class="btn primary" hidden>+ Nuevo registro</button>
      </div>

      <section id="view-content" aria-label="Contenido de administración y empleados"></section>
    </main>

    <footer class="admin-footer">Analisis y Diseño de Soluciones Web 12 2026 todos los derechos reservados</footer>
  </div>
</div>
