import { adminApi, ROLE_OPTIONS } from './api.js?v=9';

/**
 * ============================================================================
 * CAPA VISTA Y CONTROLADOR · PORTAL INDEPENDIENTE DE ADMINISTRACIÓN Y EMPLEADOS
 * Conectado con las tablas `administradores`, `empleados`, `productos`,
 * `clientes`, `ventas` y `detalles_venta` de `sistema_dulcetentacion`.
 * ============================================================================
 */
const $ = (s) => document.querySelector(s);
const esc = (x) =>
  String(x ?? '').replace(/[&<>"']/g, (c) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
  }[c]));

const labels = {
  overview: ['Resumen operativo', 'Estado en tiempo real del inventario, clientes y ventas en sistema_dulcetentacion.'],
  products: ['Productos y stock', 'Catálogo conectado a la tabla productos y control de unidades disponibles.'],
  employees: ['Empleados y accesos', 'Gestión de las tablas administradores y empleados (exclusivo para Administradores).'],
  clients: ['Directorio de clientes', 'Consulta y actualización de la tabla clientes.'],
  movements: ['Movimientos y ventas', 'Historial de ventas (detalles_venta) y ajustes de inventario.'],
  roles: ['Roles y permisos', 'Matriz de permisos entre Administradores y Empleados.']
};

const roleNames = {
  administrador: 'Administrador',
  encargado: 'Encargado',
  inventario: 'Inventario',
  atencion: 'Atención al cliente'
};

let view = 'overview';
let records = [];
let editing = null;
let mode = 'record';
let filter = '';
let timer;
let activeUser = null;

function isEmployeeSession() {
  return activeUser?.roleType === 'empleado';
}

function notice(t) {
  $('#notification').textContent = t;
  $('#notification').classList.add('show');
  clearTimeout(timer);
  timer = setTimeout(() => $('#notification').classList.remove('show'), 3500);
}

const money = (x) =>
  x === null
    ? 'Por confirmar'
    : new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
      }).format(x);

const status = (x) => `<span class="status ${x}">${x === 'activo' ? 'Activo' : 'Inactivo'}</span>`;
const empty = (t) => `<div class="empty"><span>◇</span><h3>${t}</h3><p>Los registros aparecerán aquí cuando los agregues.</p></div>`;

function table(headers, rows) {
  return `<div class="table-scroll"><table><thead><tr>${headers.map((h) => `<th scope="col">${h}</th>`).join('')}</tr></thead><tbody>${rows.join('')}</tbody></table></div>`;
}

async function overview() {
  const [p, e, c, m] = await Promise.all(['products', 'employees', 'clients', 'movements'].map((x) => adminApi.list(x)));
  const low = p.filter((x) => x.status === 'activo' && x.stock <= x.minStock);

  const actionBtn = isEmployeeSession()
    ? `<button class="btn" data-open-view="products">Gestionar inventario →</button>`
    : `<button class="btn" data-open-view="employees">Gestionar equipo →</button>`;

  return `
    <div class="stats">
      ${[
        ['Productos', p.length, 'En tabla productos'],
        ['Unidades disponibles', p.filter((x) => x.status === 'activo').reduce((n, x) => n + x.stock, 0), 'Suma del stock activo'],
        ['Equipo interno', e.filter((x) => x.status === 'activo').length, 'Administradores y empleados'],
        ['Clientes', c.length, 'En tabla clientes']
      ]
        .map(([a, b, d]) => `<article><span>${a}</span><strong>${b}</strong><small>${d}</small></article>`)
        .join('')}
    </div>
    <div class="dashboard-grid">
      <section class="card">
        <div class="card-title">
          <h2>Atención al inventario</h2>
          <button data-open-view="products">Ver productos →</button>
        </div>
        ${
          low.length
            ? low
                .map(
                  (x) => `<div class="stock-alert"><div><b>${esc(x.name)}</b><small>Stock mínimo sugerido: ${x.minStock} unidades</small></div><span>${x.stock} disponibles</span></div>`
                )
                .join('')
            : empty('Todo el inventario con buen stock')
        }
      </section>
      <section class="welcome-card">
        <img src="assets/img/mascota.png" alt="Mascota Dulce Tentación">
        <h2>Todo empieza con<br>un buen detalle.</h2>
        <p>Sesión activa como <b>${esc(activeUser?.name || 'Personal')}</b> (${esc(activeUser?.role || 'Administración')}).</p>
        ${actionBtn}
      </section>
    </div>
    <section class="card">
      <div class="card-title">
        <h2>Actividad reciente (Ventas y Stock)</h2>
        <button data-open-view="movements">Ver movimientos →</button>
      </div>
      ${m.length ? movementTable(m.slice(0, 8)) : empty('Aún no hay movimientos')}
    </section>`;
}

function movementTable(items) {
  return table(
    ['Producto', 'Movimiento', 'Antes → después', 'Empleado', 'Detalle / Venta', 'Fecha'],
    items.map(
      (x) => `
      <tr>
        <td><b>${esc(x.productName)}</b></td>
        <td><span class="${x.delta > 0 ? 'positive' : 'negative'}">${x.delta > 0 ? '+' : ''}${x.delta}</span></td>
        <td>${x.before} → ${x.after}</td>
        <td><b>${esc(x.employeeName || 'Juan Villamil')}</b></td>
        <td>${esc(x.reason)}</td>
        <td>${esc(new Date(x.createdAt).toLocaleString('es-CO'))}</td>
      </tr>`
    )
  );
}

function roles() {
  return `
    <section class="card">
      <h2>Permisos por rol en el portal independiente</h2>
      <p class="subtle">Distribución de responsabilidades entre las tablas administradores y empleados de sistema_dulcetentacion.</p>
      ${table(
        ['Acción', 'Administrador', 'Empleado (Inventario / Atención)'],
        [
          ['Gestionar empleados y administradores', 'Sí', 'No'],
          ['Consultar productos y stock', 'Sí', 'Sí'],
          ['Crear y editar productos', 'Sí', 'Sí'],
          ['Ajustar stock y consultar ventas/movimientos', 'Sí', 'Sí'],
          ['Consultar y editar clientes', 'Sí', 'Sí']
        ].map((row) => `<tr>${row.map((x, i) => (i ? `<td>${x}</td>` : `<th scope="row">${x}</th>`)).join('')}</tr>`)
      )}
    </section>`;
}

function drawList() {
  const items = records.filter((x) => Object.values(x).join(' ').toLowerCase().includes(filter.toLowerCase()));
  let html;

  if (!items.length) {
    html = empty('No se encontraron registros');
  } else if (view === 'movements') {
    html = movementTable(items);
  } else {
    const headers =
      view === 'products'
        ? ['Producto', 'SKU', 'Precio COP', 'Stock / mínimo', 'Estado', 'Acciones']
        : view === 'employees'
        ? ['Nombre', 'Correo', 'Rol', 'Acceso', 'Acciones']
        : ['Nombre', 'Correo', 'Teléfono', 'Estado', 'Acciones'];

    html = table(
      headers,
      items.map((x) => {
        const actions = `
          <button class="table-action" data-edit="${esc(x.id)}">Editar<span class="sr-only"> ${esc(x.name)}</span></button>
          <button class="table-action danger" data-delete="${esc(x.id)}" data-delete-name="${esc(x.name)}">Borrar<span class="sr-only"> ${esc(x.name)}</span></button>`;
        const first = `<td><b>${esc(x.name)}</b>${view === 'products' ? `<small>${esc(x.category)}</small>` : ''}</td>`;

        return `<tr>
          ${first}
          ${
            view === 'products'
              ? `<td>${esc(x.sku)}</td><td>${money(x.price)}</td><td><span class="${x.stock <= x.minStock ? 'low-stock' : ''}">${x.stock}</span> / ${x.minStock}</td>`
              : view === 'employees'
              ? `<td>${esc(x.email)}</td><td>${esc(roleNames[x.role] || x.role)}</td>`
              : `<td>${esc(x.email) || '—'}</td><td>${esc(x.phone) || '—'}</td>`
          }
          <td>${status(x.status)}</td>
          <td class="actions">
            ${actions}
            ${view === 'products' ? `<button class="table-action" data-stock="${esc(x.id)}" ${x.status === 'inactivo' ? 'disabled' : ''}>Ajustar stock</button>` : ''}
          </td>
        </tr>`;
      })
    );
  }

  $('#list-results').innerHTML = html;
  $('#result-count').textContent = `${items.length} ${items.length === 1 ? 'registro' : 'registros'}`;
}

async function render(next = view) {
  if (next === 'employees' && isEmployeeSession()) {
    notice('La sección de Empleados y accesos está reservada para el perfil de Administrador.');
    next = 'overview';
  }

  view = next;
  filter = '';
  const [title, desc] = labels[view];
  $('#page-title').textContent = title;
  $('#page-description').textContent = desc;
  $('#create-record').hidden = !['products', 'employees', 'clients'].includes(view);
  $('#create-record').textContent =
    view === 'products' ? '+ Nuevo producto' : view === 'employees' ? '+ Nuevo integrante' : '+ Nuevo cliente';

  document.querySelectorAll('[data-view]').forEach((b) => {
    b.classList.toggle('active', b.dataset.view === view);
    b.setAttribute('aria-current', b.dataset.view === view ? 'page' : 'false');
  });

  $('.sidebar').classList.remove('open');
  $('#toggle-menu').setAttribute('aria-expanded', 'false');

  if (view === 'overview') {
    $('#view-content').innerHTML = await overview();
  } else if (view === 'roles') {
    $('#view-content').innerHTML = roles();
  } else {
    records = await adminApi.list(view);
    $('#view-content').innerHTML = `
      <section class="card">
        <div class="list-toolbar">
          <label for="record-search">Buscar en esta sección</label>
          <input id="record-search" type="search" placeholder="Nombre, correo o código…">
          <span id="result-count"></span>
        </div>
        <div id="list-results"></div>
      </section>`;
    drawList();
  }
}

function field(label, name, type = 'text', value = '', extra = '') {
  return `<label for="field-${name}">${label}</label><input id="field-${name}" name="${name}" type="${type}" value="${esc(value)}" ${extra}>`;
}

function select(label, name, options, value) {
  return `<label for="field-${name}">${label}</label><select id="field-${name}" name="${name}">${options
    .map(([v, t]) => `<option value="${v}" ${v === value ? 'selected' : ''}>${t}</option>`)
    .join('')}</select>`;
}

function openEditor(id, stock = false) {
  editing = id ? records.find((x) => x.id === id) : null;
  mode = stock ? 'stock' : 'record';
  $('#editor-error').hidden = true;
  $('#editor-title').textContent = stock ? 'Ajustar stock en BD' : editing ? 'Editar registro' : 'Nuevo registro';

  let fields = '';
  if (stock) {
    fields =
      `<div class="current-stock"><strong>${esc(editing.name)}</strong><p>Stock actual en BD: ${editing.stock} unidades</p></div>` +
      select('Tipo de movimiento', 'direction', [['entrada', 'Entrada (+ sumar)'], ['salida', 'Salida (− descontar)']], 'entrada') +
      field('Cantidad de unidades', 'quantity', 'number', 1, 'min="1" max="999999" step="1" required') +
      field('Motivo del ajuste', 'reason', 'text', '', 'required maxlength="300"');
  } else {
    fields = field('Nombre', 'name', 'text', editing?.name || '', 'required maxlength="120"');
    if (view === 'products') {
      fields +=
        field('Código SKU', 'sku', 'text', editing?.sku || 'DT-NUEVO', 'required maxlength="40"') +
        select('Categoría', 'category', [['Postres', 'Postres'], ['Cafés', 'Cafés']], editing?.category || 'Postres') +
        field('Precio en COP', 'price', 'number', editing?.price ?? '', 'min="0" max="999999999" step="1" required') +
        (!editing
          ? field('Stock inicial', 'stock', 'number', 20, 'min="0" max="999999" step="1" required')
          : '<p class="subtle">Utiliza «Ajustar stock» para registrar entradas o salidas con su motivo en la base de datos.</p>') +
        field('Stock mínimo sugerido', 'minStock', 'number', editing?.minStock ?? 15, 'min="0" max="999999" step="1" required');
    } else {
      fields += field(
        view === 'employees' ? 'Correo de acceso' : 'Correo electrónico',
        'email',
        'email',
        editing?.email || '',
        `maxlength="120" required`
      );
      if (view === 'employees') {
        fields += select(
          'Rol en el sistema',
          'role',
          ROLE_OPTIONS.map((x) => [x, roleNames[x]]),
          editing?.role || 'inventario'
        );
      } else {
        fields += field('Teléfono', 'phone', 'tel', editing?.phone || '', 'maxlength="30"');
      }
    }
    fields += select(
      view === 'employees' ? 'Estado de acceso' : 'Estado',
      'status',
      [['activo', 'Activo'], ['inactivo', 'Inactivo']],
      editing?.status || 'activo'
    );
  }

  $('#editor-fields').innerHTML = fields;
  $('#editor').showModal();
}

function enterPanel(user) {
  activeUser = user;
  const initials =
    user.name
      .trim()
      .split(/\s+/)
      .slice(0, 2)
      .map((w) => w[0]?.toUpperCase() || '')
      .join('') || 'DT';

  const pillSpan = $('.user-pill > span');
  const pillDiv = $('.user-pill > div');
  if (pillSpan) pillSpan.textContent = initials;
  if (pillDiv) pillDiv.innerHTML = `<b>${esc(user.name)}</b><small>${esc(user.role)}</small>`;

  const pillBtn = $('#user-profile-pill');
  if (pillBtn) {
    pillBtn.setAttribute('data-role', user.roleType);
    pillBtn.title = isEmployeeSession()
      ? 'Sesión de Empleado (sin acceso a datos administrativos)'
      : 'Clic para ver mis datos de administrador y cambiar contraseña';
  }

  if ($('#sidebar-role-label')) {
    $('#sidebar-role-label').textContent = isEmployeeSession() ? 'PANEL DE EMPLEADO' : 'PANEL ADMINISTRATIVO';
  }
  if ($('#strip-role-badge')) {
    $('#strip-role-badge').textContent = isEmployeeSession()
      ? `Sesión de Empleado · ${user.name}`
      : `Sesión de Administrador · ${user.name}`;
  }
  if ($('#nav-employees')) {
    $('#nav-employees').hidden = isEmployeeSession();
  }

  $('#access').hidden = true;
  $('#panel').hidden = false;
  render('overview').then(() => {
    $('#page-title').tabIndex = -1;
    $('#page-title').focus();
  });
}

$('#record-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const values = Object.fromEntries(new FormData(e.target));
  try {
    if (mode === 'stock') {
      await adminApi.adjustStock({
        productId: editing.id,
        delta: Number(values.quantity) * (values.direction === 'salida' ? -1 : 1),
        reason: values.reason,
        employeeName: activeUser?.name || 'Juan Villamil'
      });
    } else {
      if (editing) values.id = editing.id;
      if (view === 'products') {
        values.price = values.price === '' ? null : Number(values.price);
        values.stock = Number(values.stock ?? editing?.stock ?? 0);
        values.minStock = Number(values.minStock ?? 15);
      }
      await adminApi.save(view, values);
    }
    $('#editor').close();
    await render();
    notice('Cambios guardados en la base de datos sistema_dulcetentacion.');
  } catch (error) {
    $('#editor-error').textContent = error.message;
    $('#editor-error').hidden = false;
  }
});

let selectedLoginRole = 'administrador';

document.querySelectorAll('[data-login-role]').forEach((btn) => {
  btn.addEventListener('click', () => {
    selectedLoginRole = btn.dataset.loginRole;
  });
});

$('#login-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  $('#login-feedback').hidden = true;
  const email = $('#login-email').value.trim();
  const password = $('#login-password').value;
  const roleType = e.submitter?.dataset.loginRole || selectedLoginRole;
  try {
    const user = await adminApi.login({ email, password, roleType });
    $('#login-form').reset();
    enterPanel(user);
    notice(`Sesión iniciada como ${user.roleType === 'empleado' ? 'Empleado' : 'Administrador'}: ${user.name}.`);
  } catch (error) {
    $('#login-feedback').textContent = error.message;
    $('#login-feedback').hidden = false;
  }
});

$('#show-password').addEventListener('click', () => {
  const input = $('#login-password');
  input.type = input.type === 'password' ? 'text' : 'password';
  $('#show-password').textContent = input.type === 'password' ? 'Mostrar' : 'Ocultar';
});

$('#logout').addEventListener('click', () => {
  activeUser = null;
  selectedLoginRole = 'administrador';
  $('#panel').hidden = true;
  $('#access').hidden = false;
  $('#login-feedback').hidden = true;
  $('#login-email').value = '';
  $('#login-password').value = '';
  $('#login-email').focus();
});

$('#toggle-menu').addEventListener('click', () => {
  const open = $('.sidebar').classList.toggle('open');
  $('#toggle-menu').setAttribute('aria-expanded', String(open));
});

document.addEventListener('click', async (e) => {
  const b = e.target.closest('button');
  if (!b) return;
  try {
    if (b.dataset.view || b.dataset.openView) await render(b.dataset.view || b.dataset.openView);
    if (b.id === 'create-record') openEditor();
    if (b.dataset.edit) openEditor(b.dataset.edit);
    if (b.dataset.stock) openEditor(b.dataset.stock, true);
    if (b.dataset.delete) {
      const nombre = b.dataset.deleteName || 'este registro';
      if (confirm(`¿Estás seguro de que deseas borrar "${nombre}" de la base de datos?`)) {
        await adminApi.remove(view, b.dataset.delete);
        await render();
        notice(`"${nombre}" fue eliminado de sistema_dulcetentacion.`);
      }
    }
    if (['close-editor', 'cancel-editor'].includes(b.id)) $('#editor').close();
  } catch (error) {
    notice(error.message);
  }
});

document.addEventListener('input', (e) => {
  if (e.target.id === 'record-search') {
    filter = e.target.value;
    drawList();
  }
});

async function openProfileModal() {
  if (isEmployeeSession() || !activeUser) {
    notice('Los datos de cuenta y cambio de clave son exclusivos del Administrador.');
    return;
  }

  try {
    const admin = await adminApi.getProfile(activeUser.id);
    const initials =
      admin.fullName
        .trim()
        .split(/\s+/)
        .slice(0, 2)
        .map((w) => w[0]?.toUpperCase() || '')
        .join('') || 'AD';

    $('#profile-avatar').textContent = initials;
    $('#profile-fullname').textContent = admin.fullName;
    $('#profile-badge').textContent = `Administrador · ${admin.cargo}`;
    $('#profile-id').textContent = admin.id.toUpperCase();
    $('#profile-role').textContent = admin.cargo;
    $('#profile-email').textContent = admin.email;

    $('#pwd-current').value = '';
    $('#pwd-new').value = '';
    $('#pwd-confirm').value = '';
    $('#password-feedback').hidden = true;
    const banner = $('#password-feedback-banner');
    if (banner) banner.style.display = 'none';

    $('#profile-dialog').showModal();
  } catch (error) {
    notice(error.message);
  }
}

$('#user-profile-pill')?.addEventListener('click', () => {
  if (isEmployeeSession()) {
    notice('Tu rol actual es Empleado. La gestión de perfil y cambio de clave es exclusiva de Administradores.');
    return;
  }
  openProfileModal();
});

$('#close-profile')?.addEventListener('click', () => $('#profile-dialog').close());
$('#cancel-profile')?.addEventListener('click', () => $('#profile-dialog').close());

document.querySelectorAll('.btn-toggle-pwd').forEach((btn) => {
  btn.addEventListener('click', () => {
    const targetId = btn.dataset.target;
    const input = document.getElementById(targetId);
    if (!input) return;
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    btn.textContent = isPassword ? 'Ocultar' : 'Mostrar';
  });
});

$('#change-password-form')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  const submitBtn = $('#btn-save-password');
  $('#password-feedback').className = 'feedback';
  $('#password-feedback').hidden = true;

  const currentPassword = $('#pwd-current').value;
  const newPassword = $('#pwd-new').value;
  const confirmPassword = $('#pwd-confirm').value;

  if (newPassword !== confirmPassword) {
    $('#password-feedback').textContent = 'La confirmación de la nueva contraseña no coincide.';
    $('#password-feedback').hidden = false;
    return;
  }

  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.textContent = 'Guardando en base de datos...';
  }

  try {
    const res = await adminApi.changePassword({
      adminId: activeUser.id,
      currentPassword,
      newPassword,
      confirmPassword
    });

    $('#pwd-current').value = '';
    $('#pwd-new').value = '';
    $('#pwd-confirm').value = '';

    // Mostrar confirmación visual destacada en el banner superior e inferior
    const banner = $('#password-feedback-banner');
    if (banner) {
      banner.style.display = 'block';
      banner.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    $('#password-feedback').className = 'feedback success';
    $('#password-feedback').textContent = '✔ ¡Contraseña actualizada con éxito en la base de datos!';
    $('#password-feedback').hidden = false;

    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.textContent = '✔ Contraseña cambiada';
      setTimeout(() => {
        submitBtn.textContent = 'Actualizar contraseña';
      }, 4000);
    }
  } catch (error) {
    $('#password-feedback').className = 'feedback';
    $('#password-feedback').textContent = error.message;
    $('#password-feedback').hidden = false;
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Actualizar contraseña';
    }
  }
});
