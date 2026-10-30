/**
 * ============================================================================
 * CAPA MODELO / ADAPTADOR HTTP · ADMINISTRACIÓN DULCE TENTACIÓN
 * Conectado en tiempo real con la base de datos MySQL `sistema_dulcetentacion`
 * a través de `php/api_admin.php` -> `app/controllers/AdminController.php`.
 * ============================================================================
 */
export const ROLE_OPTIONS = ['administrador', 'encargado', 'inventario', 'atencion'];

const isStaticHost = typeof window !== 'undefined' && (
  window.location.hostname.includes('github.io') ||
  window.location.protocol === 'file:'
);

// Fallback de demostración para entornos estáticos (GitHub Pages) sin servidor PHP/MySQL
const MOCK_STORAGE_KEY = 'dulcetentacion_admin_demo';
function getMockDb() {
  const stored = localStorage.getItem(MOCK_STORAGE_KEY);
  if (stored) {
    try { return JSON.parse(stored); } catch {}
  }
  const initial = {
    admin: {
      id: 'adm-1',
      fullName: 'Angela Andrea',
      nombre: 'Angela',
      apellido: 'Andrea',
      email: 'admin1@local.com',
      cargo: 'Gerente General',
      roleType: 'administrador'
    },
    empleado: {
      id: 'emp-1',
      fullName: 'Carlos Mendoza',
      nombre: 'Carlos',
      apellido: 'Mendoza',
      email: 'empleado1@local.com',
      cargo: 'Atención y Ventas',
      roleType: 'empleado'
    },
    productos: [
      { id: 'prod-1', codigo: 'POS-001', nombre: 'Cheesecake Frutos Rojos', categoria: 'postres', precio: 14500, stock: 18, estado: 'activo' },
      { id: 'prod-2', codigo: 'POS-002', nombre: 'Tarta de Chocolate Belga', categoria: 'postres', precio: 16000, stock: 12, estado: 'activo' },
      { id: 'prod-3', codigo: 'POS-003', nombre: 'Tiramisú Tradicional', categoria: 'postres', precio: 15000, stock: 9, estado: 'activo' },
      { id: 'prod-4', codigo: 'CAF-001', nombre: 'Café Espresso Origen', categoria: 'cafes', precio: 6500, stock: 45, estado: 'activo' },
      { id: 'prod-5', codigo: 'CAF-002', nombre: 'Cappuccino Vainilla', categoria: 'cafes', precio: 8900, stock: 30, estado: 'activo' }
    ],
    empleados: [
      { id: 'emp-1', nombre: 'Carlos Mendoza', cargo: 'Atención y Ventas', rol: 'atencion', email: 'carlos@dulcetentacion.com', estado: 'activo' },
      { id: 'emp-2', nombre: 'Mariana Gómez', cargo: 'Encargada de Turno', rol: 'encargado', email: 'mariana@dulcetentacion.com', estado: 'activo' }
    ],
    ventas: [
      { id: 'vta-101', cliente: 'Laura Restrepo', empleado: 'Carlos Mendoza', total: 29500, fecha: 'Hoy, 10:45 AM', estado: 'completado' },
      { id: 'vta-102', cliente: 'Andrés Felipe Mejía', empleado: 'Mariana Gómez', total: 15000, fecha: 'Hoy, 11:20 AM', estado: 'completado' }
    ],
    movimientos: [
      { id: 'mov-1', tipo: 'venta', producto: 'Cheesecake Frutos Rojos', cantidad: -2, motivo: 'Venta #VTA-101', fecha: 'Hoy, 10:45 AM', empleado: 'Carlos Mendoza' },
      { id: 'mov-2', tipo: 'ajuste', producto: 'Café Espresso Origen', cantidad: 10, motivo: 'Recepción insumo café', fecha: 'Hoy, 09:15 AM', empleado: 'Angela Andrea' }
    ]
  };
  localStorage.setItem(MOCK_STORAGE_KEY, JSON.stringify(initial));
  return initial;
}

function saveMockDb(db) {
  localStorage.setItem(MOCK_STORAGE_KEY, JSON.stringify(db));
}

async function request(url, options = {}) {
  try {
    const res = await fetch(url, {
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        ...(options.headers || {})
      },
      ...options
    });

    if (res.ok) {
      const data = await res.json().catch(() => null);
      if (data?.ok) return data;
      if (data?.message) throw new Error(data.message);
    }
  } catch (err) {
    if (!isStaticHost) throw err;
  }

  // Si estamos en entorno estático (GitHub Pages), procesar con Mock DB
  if (isStaticHost) {
    return handleStaticMock(url, options);
  }

  throw new Error('Error al comunicarse con la base de datos sistema_dulcetentacion.');
}

function handleStaticMock(url, options) {
  const db = getMockDb();
  const urlObj = new URL(url, window.location.href);
  const action = urlObj.searchParams.get('action');
  const body = options.body ? JSON.parse(options.body) : {};

  if (action === 'login') {
    const role = body.roleType || 'administrador';
    const user = role === 'administrador' ? db.admin : db.empleado;
    return { ok: true, user };
  }

  if (action === 'list') {
    const entity = urlObj.searchParams.get('entity');
    return { ok: true, items: db[entity] || [] };
  }

  if (action === 'get_profile') {
    return { ok: true, admin: db.admin };
  }

  if (action === 'change_password') {
    return { ok: true, message: 'Contraseña actualizada correctamente en la simulación estática.' };
  }

  if (action === 'save') {
    const entity = body.entity;
    const values = body.values || {};
    const list = db[entity] || [];
    const id = values.id || `${entity.slice(0,3)}-${Date.now()}`;
    const idx = list.findIndex(i => i.id === id);
    const item = { ...values, id };
    if (idx >= 0) list[idx] = item;
    else list.unshift(item);
    db[entity] = list;
    saveMockDb(db);
    return { ok: true, item };
  }

  if (action === 'delete') {
    const { entity, id } = body;
    if (db[entity]) {
      db[entity] = db[entity].filter(i => i.id !== id);
      saveMockDb(db);
    }
    return { ok: true };
  }

  if (action === 'adjust_stock') {
    const { productId, delta, reason, employeeName } = body;
    const prod = (db.productos || []).find(p => p.id === productId);
    if (prod) {
      prod.stock = Math.max(0, (prod.stock || 0) + Number(delta));
    }
    const movement = {
      id: `mov-${Date.now()}`,
      tipo: delta >= 0 ? 'entrada' : 'ajuste',
      producto: prod ? prod.nombre : 'Producto',
      cantidad: Number(delta),
      motivo: reason || 'Ajuste manual',
      fecha: 'Hace un momento',
      empleado: employeeName || 'Administrador'
    };
    db.movimientos = [movement, ...(db.movimientos || [])];
    saveMockDb(db);
    return { ok: true, movement };
  }

  return { ok: true };
}

export const adminApi = {
  async login({ email, password, roleType }) {
    const data = await request('php/api_admin.php?action=login', {
      method: 'POST',
      body: JSON.stringify({ email, password, roleType })
    });
    return data.user;
  },

  async list(entity) {
    const data = await request(`php/api_admin.php?action=list&entity=${encodeURIComponent(entity)}`);
    return data.items || [];
  },

  async save(entity, values) {
    const data = await request('php/api_admin.php?action=save', {
      method: 'POST',
      body: JSON.stringify({ entity, values })
    });
    return data.item;
  },

  async adjustStock({ productId, delta, reason, employeeName }) {
    const data = await request('php/api_admin.php?action=adjust_stock', {
      method: 'POST',
      body: JSON.stringify({ productId, delta, reason, employeeName })
    });
    return data.movement;
  },

  async remove(entity, id) {
    return request('php/api_admin.php?action=delete', {
      method: 'POST',
      body: JSON.stringify({ entity, id })
    });
  },

  async getProfile(adminId) {
    const data = await request(`php/api_admin.php?action=get_profile&id=${encodeURIComponent(adminId)}`);
    return data.admin;
  },

  async changePassword({ adminId, currentPassword, newPassword, confirmPassword }) {
    const data = await request('php/api_admin.php?action=change_password', {
      method: 'POST',
      body: JSON.stringify({ adminId, currentPassword, newPassword, confirmPassword })
    });
    return data;
  }
};
