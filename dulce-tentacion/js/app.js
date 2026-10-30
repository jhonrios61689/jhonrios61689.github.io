'use strict';

/**
 * ============================================================================
 * ARQUITECTURA MVC EN CLIENTE · DULCE TENTACIÓN
 * ----------------------------------------------------------------------------
 * 1. Model      : Estado de productos, carrito, sesion y comunicacion con API PHP.
 * 2. View       : Renderizado del DOM, modales, carrito, formato COP y alertas.
 * 3. Controller : Enrutamiento, eventos de interfaz y flujo de compra/login.
 * ============================================================================
 */

const $ = (selector) => document.querySelector(selector);

/* ============================================================================
 * 1. CAPA MODELO (Model)
 * ============================================================================ */
const products = [
  // --- Postres (14 productos) ---
  {
    id: 'cupcake',
    name: 'Cupcake de fresa',
    category: 'postres',
    subtitle: 'Un toque dulce para tu pausa',
    description: 'Una propuesta de cupcake con cobertura rosa y fresas. Un pequeño antojo para acompañar ese momento para ti.',
    imageClass: 'cupcake',
    price: 6500,
    stock: 40
  },
  {
    id: 'vaso',
    name: 'Postre en vaso',
    category: 'postres',
    subtitle: 'Capas de sabor para disfrutar',
    description: 'Una propuesta de postre en capas con fresas, servido en vaso. Un momento dulce para disfrutar a tu ritmo.',
    imageClass: 'vaso',
    price: 11000,
    stock: 30
  },
  {
    id: 'cupcake-vainilla',
    name: 'Cupcake de vainilla',
    category: 'postres',
    subtitle: 'Cobertura suave y aroma delicado',
    description: 'Bizcocho esponjoso de vainilla coronado con crema batida artesanal y un toque frutal.',
    image: 'assets/img/Cupcake de vainilla..jpeg',
    imageClass: 'cupcake',
    price: 5500,
    stock: 50
  },
  {
    id: 'cheesecake-vaso',
    name: 'Cheesecake en vaso',
    category: 'postres',
    subtitle: 'Cremosidad con frutos rojos',
    description: 'Base de galleta dorada, crema suave de queso y reducción artesanal de fresas frescas.',
    image: 'assets/img/cheesecake en vaso.jpeg',
    imageClass: 'vaso',
    price: 12500,
    stock: 50
  },
  {
    id: 'tartaleta-fresa',
    name: 'Tartaleta de fresa',
    category: 'postres',
    subtitle: 'Base crujiente y fruta fresca',
    description: 'Masa sablé horneada al día con crema pastelera ligera y fresas seleccionadas.',
    image: 'assets/img/tartaleta de fresa.jpeg',
    imageClass: 'cupcake',
    price: 11500,
    stock: 25
  },
  {
    id: 'mousse-frutos',
    name: 'Mousse de fresa',
    category: 'postres',
    subtitle: 'Textura ligera en cada cucharada',
    description: 'Capas aireadas de mousse de fresa natural con trozos de fruta y crema chantilly.',
    image: 'assets/img/Mousse de fresa.jpeg',
    imageClass: 'vaso',
    price: 9500,
    stock: 30
  },
  {
    id: 'red-velvet',
    name: 'Cupcake Red Velvet',
    category: 'postres',
    subtitle: 'Suavidad con notas de cacao',
    description: 'Clásico bizcocho aterciopelado con frosting cremoso y acabado delicado para consentirte.',
    image: 'assets/img/Cupcake Red Velvet.jpeg',
    imageClass: 'cupcake',
    price: 6500,
    stock: 40
  },
  {
    id: 'parfait-cremoso',
    name: 'Parfait de fresa',
    category: 'postres',
    subtitle: 'Equilibrio dulce y refrescante',
    description: 'Combinación en vaso de crema suave, granola crujiente y compota casera de frutos rojos.',
    image: 'assets/img/Parfait de fresa.jpeg',
    imageClass: 'vaso',
    price: 13000,
    stock: 20
  },
  {
    id: 'tiramisu-vaso',
    name: 'Tiramisú en vaso',
    category: 'postres',
    subtitle: 'Capas suaves con toque de cacao',
    description: 'Postre cremoso en capas con bizcocho suave, crema mascarpone y un sutil aroma de café y cacao.',
    image: 'assets/img/Tiramisú en vaso.jpeg',
    imageClass: 'vaso',
    price: 14500,
    stock: 15
  },
  {
    id: 'panna-cotta',
    name: 'Panna cotta de fresa',
    category: 'postres',
    subtitle: 'Textura sedosa y coulis frutal',
    description: 'Postre italiano de crema suave con vainilla natural, bañado en salsa artesanal de fresas frescas.',
    image: 'assets/img/Panna cotta de fresa.jpeg',
    imageClass: 'vaso',
    price: 12000,
    stock: 20
  },
  {
    id: 'cupcake-frambuesa',
    name: 'Cupcake de frambuesa',
    category: 'postres',
    subtitle: 'Dulzura frutal en cada bocado',
    description: 'Ponqué esponjoso relleno de compota de frambuesa y decorado con crema batida suave.',
    image: 'assets/img/Cupcake de frambuesa.jpeg',
    imageClass: 'cupcake',
    price: 6500,
    stock: 40
  },
  {
    id: 'tres-leches-vaso',
    name: 'Tres leches con fresa',
    category: 'postres',
    subtitle: 'Tradición húmeda y cremosa',
    description: 'Bizcocho bañado en mezcla de tres leches con crema chantilly y fresas naturales en capas.',
    imageClass: 'vaso',
    price: 10500,
    stock: 25
  },
  {
    id: 'cupcake-limon',
    name: 'Cupcake de merengue',
    category: 'postres',
    subtitle: 'Toque suave y cobertura delicada',
    description: 'Bizcocho aromático coronado con merengue cremoso artesanal y notas dulces.',
    image: 'assets/img/Cupcake de merengue.jpeg',
    imageClass: 'cupcake',
    price: 6000,
    stock: 45
  },
  {
    id: 'carlota-frutos',
    name: 'Carlota de frutos rojos',
    category: 'postres',
    subtitle: 'Capas de galleta y crema dulce',
    description: 'Delicadas capas de crema suave, galleta artesanal de vainilla y selección de frutos rojos.',
    image: 'assets/img/carlota de frutos rojos.jpeg',
    imageClass: 'vaso',
    price: 14000,
    stock: 30
  },

  // --- Cafés (6 productos agrupados al final) ---
  {
    id: 'cafe',
    name: 'Cappuccino',
    category: 'cafes',
    subtitle: 'El compañero de tu postre',
    description: 'Una propuesta de café con espuma y un toque cálido para acompañar tu postre favorito.',
    image: 'assets/img/Cappuccino.jpeg',
    imageClass: 'cafe',
    price: 8000,
    stock: 100
  },
  {
    id: 'latte-vainilla',
    name: 'Latte de vainilla',
    category: 'cafes',
    subtitle: 'Suave, cremoso y aromático',
    description: 'Espresso equilibrado con leche vaporizada y un sutil perfume de vainilla para tu descanso.',
    image: 'assets/img/Lattle de vainilla.jpeg',
    imageClass: 'cafe',
    price: 8500,
    stock: 100
  },
  {
    id: 'mocaccino',
    name: 'Mocaccino artesanal',
    category: 'cafes',
    subtitle: 'Encuentro entre café y cacao',
    description: 'Café espresso combinado con notas de chocolate dulce y espuma de leche sedosa.',
    image: 'assets/img/Mocaccino artesanal.jpeg',
    imageClass: 'cafe',
    price: 9500,
    stock: 100
  },
  {
    id: 'caramel-latte',
    name: 'Latte caramelo',
    category: 'cafes',
    subtitle: 'Un toque cálido y acaramelado',
    description: 'Bebida de café con leche texturizada y notas dulces de caramelo suave.',
    image: 'assets/img/Latte caramelo.jpeg',
    imageClass: 'cafe',
    price: 9000,
    stock: 100
  },
  {
    id: 'cafe-americano',
    name: 'Café americano',
    category: 'cafes',
    subtitle: 'Aroma intenso para tu pausa',
    description: 'Taza de café suave y aromático de tueste medio, ideal para equilibrar el dulzor de tu postre.',
    image: 'assets/img/Café americano.jpeg',
    imageClass: 'cafe',
    price: 5000,
    stock: 100
  },
  {
    id: 'affogato-dulce',
    name: 'Café bombón',
    category: 'cafes',
    subtitle: 'Dulzura cremosa en cada sorbo',
    description: 'Combinación armónica de espresso caliente con una capa dulce y sedosa de leche condensada.',
    image: 'assets/img/Café bombón.jpeg',
    imageClass: 'cafe',
    price: 7500,
    stock: 100
  }
];

const info = {
  contacto: [
    'Hablemos de tu próxima pausa',
    'Puedes preparar tu solicitud desde Mi pedido y abrir WhatsApp al +57 311 361 7622. Los horarios y perfiles sociales se incorporarán cuando el negocio confirme sus datos.'
  ],
  condiciones: [
    'Antes de hacer un pedido',
    'Los precios y disponibilidad están sincronizados con la base de datos de Dulce Tentación. Al finalizar tu pedido se registra tu solicitud y puedes enviarla por WhatsApp para coordinar la entrega y el método de pago.'
  ],
  privacidad: [
    'Tu privacidad',
    'Tus datos de cuenta y pedidos se gestionan en el sistema local de Dulce Tentación (sistema_dulcetentacion) para agilizar tus compras y preparar tu solicitud por WhatsApp.'
  ]
};

const whatsappNumber = '573113617622';
let cart = {};
let users = [];
let currentUser = null;
let pendingCheckout = false;
let toastTimer;
let productObserver;

// Carga inicial del estado persistido en el navegador
try {
  const savedCart = JSON.parse(localStorage.getItem('dulce-cart-v1') || '{}');
  for (const p of products) {
    const q = savedCart?.[p.id];
    if (Number.isInteger(q) && q > 0) {
      cart[p.id] = Math.min(q, 99);
    }
  }
} catch {}

try {
  users = JSON.parse(localStorage.getItem('dulce-users-v1') || '[]');
  if (!Array.isArray(users)) users = [];
} catch {
  users = [];
}

try {
  const savedSession = JSON.parse(localStorage.getItem('dulce-session-v1') || 'null');
  if (savedSession && savedSession.email) {
    currentUser = savedSession;
  }
} catch {
  currentUser = null;
}

function save() {
  try {
    localStorage.setItem('dulce-cart-v1', JSON.stringify(cart));
  } catch {}
  const totalCount = Object.values(cart).reduce((a, b) => a + b, 0);
  document.querySelectorAll('.cart-count').forEach((el) => {
    el.textContent = totalCount;
  });
}

function cartTotal() {
  return products
    .filter((p) => cart[p.id])
    .reduce((sum, p) => sum + (Number(p.price) || 0) * cart[p.id], 0);
}

function orderDetails() {
  return {
    name: $('#order-name').value.trim() || (currentUser?.name || ''),
    method: $('#order-method').value,
    location: $('#order-location').value.trim(),
    notes: $('#order-notes').value.trim()
  };
}

function summary(details = orderDetails(), idVenta = null) {
  const selected = products.filter((p) => cart[p.id]);
  const methods = {
    confirmar: 'Por acordar con el negocio',
    recogida: 'Prefiero recogerlo',
    domicilio: 'Prefiero domicilio'
  };

  const header = idVenta
    ? `Hola, Dulce Tentación. Quisiera confirmar mi pedido #${idVenta}:`
    : 'Hola, Dulce Tentación. Quisiera solicitar este pedido:';

  const lines = [
    header,
    '',
    ...selected.map((p) => `${cart[p.id]} × ${p.name} (${formatPrice((Number(p.price) || 0) * cart[p.id])})`),
    '',
    `Total de unidades: ${selected.reduce((n, p) => n + cart[p.id], 0)}`,
    `Total estimado: ${formatPrice(cartTotal())}`
  ];

  if (details.name) lines.push(`Cliente: ${details.name}`);
  if (currentUser?.email) lines.push(`Correo: ${currentUser.email}`);
  if (currentUser?.phone) lines.push(`Teléfono: ${currentUser.phone}`);
  lines.push(`Entrega: ${methods[details.method] || methods.confirmar}`);
  if (details.location) lines.push(`Ubicación: ${details.location}`);
  if (details.notes) lines.push(`Indicaciones: ${details.notes}`);
  lines.push('', 'Por favor, confirmen disponibilidad, cobertura, costo de entrega y forma de pago.');

  return lines.join('\n');
}

function whatsappUrl(message) {
  return `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
}

async function loadDatabaseProducts() {
  try {
    const res = await fetch('php/api.php?action=productos', {
      headers: { Accept: 'application/json' }
    });
    if (!res.ok) return;
    const data = await res.json();
    if (!data?.ok || !Array.isArray(data.productos)) return;

    for (const row of data.productos) {
      const targetName = String(row.nombre || '').trim().toLowerCase();
      const match =
        products.find((p) => p.name.toLowerCase() === targetName && !p.dbId) ||
        products.find((p) => p.name.toLowerCase() === targetName);

      if (match && !match.dbId) {
        match.dbId = Number(row.id_producto);
        match.price = Number(row.precio);
        match.stock = Number(row.stock);
      }
    }

    const section = location.hash.slice(1) || 'inicio';
    const category = ['postres', 'cafes'].includes(section) ? section : 'all';
    renderProducts(category);
  } catch {}
}

async function registrarPedidoEnBD(details) {
  if (!currentUser?.id_cliente) return null;
  const selected = products.filter((p) => cart[p.id]);
  const items = selected.map((p) => ({
    id_producto: p.dbId || null,
    nombre: p.name,
    cantidad: cart[p.id],
    precio: p.price || 0
  }));

  try {
    const res = await fetch('php/api.php?action=pedido', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json'
      },
      body: JSON.stringify({
        id_cliente: currentUser.id_cliente,
        address: details.location,
        items
      })
    });
    const data = await res.json();
    if (data?.ok) {
      loadDatabaseProducts();
      return data.id_venta || null;
    }
  } catch {}
  return null;
}

/* ============================================================================
 * 2. CAPA VISTA (View)
 * ============================================================================ */
const copFormat = new Intl.NumberFormat('es-CO', {
  style: 'currency',
  currency: 'COP',
  maximumFractionDigits: 0
});

function formatPrice(val) {
  return Number.isFinite(Number(val)) && Number(val) > 0
    ? copFormat.format(Number(val))
    : 'Precio por confirmar';
}

function esc(str = '') {
  return String(str).replace(/[&<>"']/g, (m) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
  }[m]));
}

function picture(p) {
  const cls = p.image ? 'product-image' : `product-image ${p.imageClass}`;
  const src = p.image ? encodeURI(p.image) : 'assets/img/postres-cafe.png';
  return `<img class="${cls}" src="${src}" alt="${p.name}, imagen ilustrativa" width="240" height="240" loading="lazy">`;
}

function observeProducts() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  if (!CSS.supports('(animation-timeline: view()) and (animation-range: entry)')) {
    if (productObserver) productObserver.disconnect();
    productObserver = new IntersectionObserver(
      (entries) => {
        for (const entry of entries) {
          const r = entry.intersectionRatio;
          entry.target.style.opacity = String(0.15 + r * 0.85);
          entry.target.style.transform = `translateY(${(1 - r) * 24}px) scale(${0.95 + r * 0.05})`;
        }
      },
      { threshold: Array.from({ length: 101 }, (_, i) => i / 100) }
    );
    document.querySelectorAll('.product-card').forEach((el) => productObserver.observe(el));
  }
}

function renderProducts(category = 'all') {
  const filtered = products.filter((p) => category === 'all' || p.category === category);

  $('#products').innerHTML = filtered
    .map(
      (p) => `
      <article class="product-card">
        <div class="product-frame">${picture(p)}</div>
        <div class="product-card-copy">
          <h3>${p.name}</h3>
          <p>${p.subtitle}</p>
          <p class="product-price-tag">${formatPrice(p.price)}</p>
          <button class="button" data-product="${p.id}" aria-label="Ver producto ${p.name}">Ver producto</button>
        </div>
      </article>`
    )
    .join('');

  $('.category-context').hidden = category === 'all';
  $('#category-label').textContent = category === 'cafes' ? 'Nuestros cafés' : 'Nuestros postres';
  $('#catalog-title').textContent =
    category === 'all'
      ? '¿Qué se te antoja hoy?'
      : category === 'cafes'
      ? 'Café que acompaña.'
      : 'Un antojo dulce.';

  observeProducts();
}

function showProduct(id) {
  const p = products.find((item) => item.id === id);
  if (!p) return;

  const maxStock = Number.isFinite(p.stock) && p.stock > 0 ? Math.min(99, p.stock) : 99;
  const stockLabel = Number.isFinite(p.stock) ? ` · Disponible: ${p.stock} uds.` : '';

  $('.product-detail').innerHTML = `
    ${picture(p)}
    <div class="detail-copy">
      <p class="eyebrow">${p.category === 'postres' ? 'Un momento dulce' : 'Una pausa con café'}</p>
      <h2 id="product-title">${p.name}</h2>
      <p>${p.description}</p>
      <p class="price-label">${formatPrice(p.price)}${stockLabel}</p>
      <div class="quantity">
        <label for="quantity">Cantidad</label>
        <input id="quantity" type="number" min="1" max="${maxStock}" step="1" value="1" inputmode="numeric">
      </div>
      <button class="button primary" id="add-product" data-id="${id}">Añadir a mi pedido</button>
      <p class="detail-note">Producto sincronizado con la base de datos de Dulce Tentación.</p>
    </div>
  `;

  $('#product-dialog').showModal();
}

function updateAuthUI() {
  const btn = $('#auth-toggle');
  const lbl = $('#auth-toggle-label');
  if (!btn || !lbl) return;

  if (currentUser) {
    btn.classList.add('is-logged');
    lbl.textContent = `Hola, ${currentUser.name.split(' ')[0]}`;

    if ($('#order-name') && !$('#order-name').value.trim()) {
      $('#order-name').value = currentUser.name;
    }
    if (currentUser.address && $('#order-location') && !$('#order-location').value.trim()) {
      $('#order-location').value = currentUser.address;
    }

    const initials =
      currentUser.name
        .trim()
        .split(/\s+/)
        .slice(0, 2)
        .map((w) => w[0]?.toUpperCase() || '')
        .join('') || 'DT';

    if ($('#account-avatar')) $('#account-avatar').textContent = initials;
    if ($('#account-name')) $('#account-name').textContent = currentUser.name;
    if ($('#account-email')) $('#account-email').textContent = currentUser.email;
    if ($('#account-phone')) {
      $('#account-phone').hidden = !currentUser.phone;
      $('#account-phone').textContent = currentUser.phone ? `WhatsApp / Tel: ${currentUser.phone}` : '';
    }
  } else {
    btn.classList.remove('is-logged');
    lbl.textContent = 'Ingresar';
  }

  const submitBtn = $('#whatsapp-form .whatsapp-button');
  if (submitBtn) {
    submitBtn.textContent = currentUser
      ? 'Confirmar y enviar pedido por WhatsApp'
      : 'Registrarme y finalizar pedido por WhatsApp';
  }
}

function switchAuthTab(tab = 'login', fromCheckout = pendingCheckout) {
  const isReg = tab === 'register';

  document.querySelectorAll('.auth-tab').forEach((t) => {
    const active = t.dataset.authTab === tab;
    t.classList.toggle('active', active);
    t.setAttribute('aria-selected', String(active));
  });

  $('#login-form').hidden = isReg;
  $('#register-form').hidden = !isReg;
  $('#login-error').hidden = true;
  $('#register-error').hidden = true;
  $('#auth-checkout-notice').hidden = !fromCheckout;

  $('#auth-eyebrow').textContent = fromCheckout ? 'Paso final para tu compra' : 'Acceso de clientes';
  $('#auth-title').textContent = isReg ? 'Crea tu cuenta con tu correo' : 'Inicia sesión con tu correo';
  $('#register-submit-btn').innerHTML = fromCheckout
    ? 'Registrarme y finalizar compra <span aria-hidden="true">→</span>'
    : 'Crear mi cuenta <span aria-hidden="true">→</span>';
  $('#login-submit-btn').innerHTML = fromCheckout
    ? 'Iniciar sesión y finalizar compra <span aria-hidden="true">→</span>'
    : 'Iniciar sesión con mi correo <span aria-hidden="true">→</span>';
}

function openAuth(defaultTab = 'login', fromCheckout = false) {
  pendingCheckout = fromCheckout;

  if (currentUser && !fromCheckout) {
    $('#auth-forms-box').hidden = true;
    $('#auth-checkout-notice').hidden = true;
    $('#account-view').hidden = false;
    updateAuthUI();
  } else {
    $('#account-view').hidden = true;
    $('#auth-forms-box').hidden = false;
    switchAuthTab(defaultTab, fromCheckout);

    if (fromCheckout && $('#order-name')?.value.trim() && !$('#register-name').value.trim()) {
      $('#register-name').value = $('#order-name').value.trim();
    }
  }

  if ($('#cart-dialog')?.open) $('#cart-dialog').close();
  $('#auth-dialog').showModal();
}

function renderCart() {
  const selected = products.filter((p) => cart[p.id]);
  $('#whatsapp-form').hidden = !selected.length;
  updateAuthUI();

  if (!selected.length) {
    $('#cart-content').innerHTML = `
      <div class="empty-cart">
        <img src="assets/img/logo.png" alt="Dulce Tentación">
        <h3>Tu próxima pausa empieza aquí.</h3>
        <p>Aún no has añadido productos.</p>
        <button class="button primary" data-browse>Explorar postres</button>
      </div>`;
    return;
  }

  const authBox = currentUser
    ? `<div class="cart-auth-card is-logged">
        <div>
          <h4>✓ Sesión activa: ${esc(currentUser.name)}</h4>
          <p>${esc(currentUser.email)}</p>
        </div>
        <button type="button" class="auth-link-btn" data-open-auth="account">Mi cuenta</button>
      </div>`
    : `<div class="cart-auth-card">
        <h4>💌 Regístrate para finalizar tu compra</h4>
        <p>Inicia sesión o regístrate con tu correo electrónico para confirmar y enviar tu pedido.</p>
        <div class="cart-auth-actions">
          <button type="button" class="button primary" data-auth-checkout="register">Crear cuenta con correo</button>
          <button type="button" class="button secondary" data-auth-checkout="login">Ya tengo cuenta</button>
        </div>
      </div>`;

  const linesHtml = selected
    .map(
      (p) => `
      <article class="cart-line">
        ${picture(p)}
        <div>
          <h3>${p.name}</h3>
          <p>${formatPrice(p.price)} c/u · Subtotal: <strong>${formatPrice((Number(p.price) || 0) * cart[p.id])}</strong></p>
          <div class="stepper">
            <button data-change="-1" data-id="${p.id}" aria-label="Reducir cantidad de ${p.name}">−</button>
            <span aria-label="Cantidad">${cart[p.id]}</span>
            <button data-change="1" data-id="${p.id}" aria-label="Aumentar cantidad de ${p.name}" ${cart[p.id] >= 99 ? 'disabled' : ''}>+</button>
            <button class="remove" data-remove="${p.id}" aria-label="Eliminar ${p.name}">Eliminar</button>
          </div>
        </div>
      </article>`
    )
    .join('');

  $('#cart-content').innerHTML = `
    ${linesHtml}
    <div class="cart-summary">
      <strong>Total del pedido: ${formatPrice(cartTotal())}</strong>
      <p>Al solicitar tu pedido se registra en el sistema de Dulce Tentación y se abre WhatsApp para coordinar la entrega y el pago.</p>
    </div>
    ${authBox}
    <div class="cart-actions">
      <button class="button primary" id="view-summary">Ver resumen de mi selección</button>
      <button class="button secondary" data-browse>Seguir explorando</button>
    </div>
    <div class="summary-preview" hidden></div>
  `;
}

function openCart() {
  renderCart();
  $('#cart-dialog').showModal();
}

function toast(msg) {
  $('#toast').textContent = msg;
  $('#toast').classList.add('visible');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => $('#toast').classList.remove('visible'), 2800);
}

function closeMenu() {
  $('#menu').classList.remove('open');
  $('.menu-toggle').setAttribute('aria-expanded', 'false');
  $('.menu-toggle').setAttribute('aria-label', 'Abrir menú');
}

/* ============================================================================
 * 3. CAPA CONTROLADOR (Controller)
 * ============================================================================ */
function route() {
  const section = location.hash.slice(1) || 'inicio';
  const category = ['postres', 'cafes'].includes(section) ? section : 'all';

  renderProducts(category);

  document.querySelectorAll('[data-nav]').forEach((a) => {
    if (a.dataset.nav === section) {
      a.setAttribute('aria-current', 'page');
    } else {
      a.removeAttribute('aria-current');
    }
  });

  closeMenu();

  if (category !== 'all') {
    $('#catalogo').scrollIntoView({ behavior: 'smooth' });
  } else if (section === 'inicio') {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
}

async function procesarEnvioPedido() {
  const details = orderDetails();
  if (details.method === 'domicilio' && !details.location) {
    openCart();
    $('#order-location').setCustomValidity('Escribe el municipio, barrio y dirección o referencia.');
    $('#order-location').reportValidity();
    return false;
  }

  $('#order-location').setCustomValidity('');
  const idVenta = await registrarPedidoEnBD(details);
  window.open(whatsappUrl(summary(details, idVenta)), '_blank', 'noopener,noreferrer');
  return idVenta;
}

async function completeAuth(user, welcomeMsg) {
  currentUser = {
    id_cliente: user.id_cliente || null,
    name: user.name,
    email: user.email,
    phone: user.phone || '',
    address: user.address || ''
  };

  try {
    localStorage.setItem('dulce-session-v1', JSON.stringify(currentUser));
  } catch {}

  updateAuthUI();
  $('#auth-dialog').close();

  if (pendingCheckout && products.some((p) => cart[p.id])) {
    pendingCheckout = false;
    const details = orderDetails();
    if (details.method === 'domicilio' && !details.location) {
      openCart();
      $('#order-location').setCustomValidity('Escribe el municipio, barrio y dirección o referencia.');
      $('#order-location').reportValidity();
      toast(`${welcomeMsg} Indica tu dirección de entrega para continuar.`);
      return;
    }
    const idVenta = await procesarEnvioPedido();
    openCart();
    toast(
      idVenta
        ? `${welcomeMsg} Pedido #${idVenta} registrado en la base de datos.`
        : `${welcomeMsg} Abriendo WhatsApp con tu pedido.`
    );
  } else {
    pendingCheckout = false;
    toast(welcomeMsg);
  }
}

// Delegacion global de clics en la interfaz
document.addEventListener('click', (e) => {
  const infoTrigger = e.target.closest('[data-info]');
  if (infoTrigger) {
    e.preventDefault();
    const data = info[infoTrigger.dataset.info];
    $('#info-title').textContent = data[0];
    $('#info-content').textContent = data[1];
    $('#info-dialog').showModal();
    return;
  }

  const b = e.target.closest('button');
  if (!b) return;

  if (b.matches('.menu-toggle')) {
    const open = $('#menu').classList.toggle('open');
    b.setAttribute('aria-expanded', String(open));
    b.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
  }
  if (b.matches('.cart-toggle')) openCart();
  if (b.id === 'auth-toggle' || b.hasAttribute('data-open-auth')) openAuth('login', false);
  if (b.hasAttribute('data-auth-checkout')) openAuth(b.dataset.authCheckout || 'register', true);
  if (b.hasAttribute('data-auth-tab')) switchAuthTab(b.dataset.authTab, pendingCheckout);
  if (b.hasAttribute('data-open-cart')) {
    $('#auth-dialog').close();
    openCart();
  }
  if (b.id === 'logout-button') {
    currentUser = null;
    try {
      localStorage.removeItem('dulce-session-v1');
    } catch {}
    updateAuthUI();
    $('#auth-dialog').close();
    toast('Has cerrado tu sesión.');
  }
  if (b.hasAttribute('data-toggle-pass')) {
    const input = document.getElementById(b.dataset.togglePass);
    if (input) {
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      b.setAttribute('aria-pressed', String(show));
      b.textContent = show ? 'Ocultar' : 'Ver';
    }
  }
  if (b.hasAttribute('data-forgot-password')) {
    const email = $('#login-email').value.trim();
    if (!email) {
      $('#login-error').hidden = false;
      $('#login-error').textContent = 'Escribe tu correo electrónico arriba para ayudarte a recuperar tu acceso.';
      $('#login-email').focus();
      return;
    }
    switchAuthTab('register', pendingCheckout);
    $('#register-email').value = email;
    toast('Puedes actualizar tu contraseña registrando tu correo.');
  }
  if (b.hasAttribute('data-product')) showProduct(b.dataset.product);
  if (b.hasAttribute('data-close')) b.closest('dialog').close();

  if (b.id === 'add-product') {
    const input = $('#quantity');
    const q = Number(input.value);
    if (!input.reportValidity() || !Number.isInteger(q)) return;
    const id = b.dataset.id;
    if ((cart[id] || 0) + q > 99) {
      toast('Puedes seleccionar hasta 99 unidades por producto.');
      return;
    }
    cart[id] = (cart[id] || 0) + q;
    save();
    $('#product-dialog').close();
    toast('Añadido a tu pedido');
  }

  if (b.dataset.change) {
    const id = b.dataset.id;
    cart[id] = Math.max(0, Math.min(99, (cart[id] || 0) + Number(b.dataset.change)));
    if (!cart[id]) delete cart[id];
    save();
    renderCart();
    ($(`[data-change="${b.dataset.change}"][data-id="${id}"]:not(:disabled)`) || $('#cart-dialog .close-button')).focus();
  }

  if (b.dataset.remove) {
    delete cart[b.dataset.remove];
    save();
    renderCart();
    $('#cart-dialog .close-button').focus();
  }

  if (b.hasAttribute('data-browse')) {
    $('#cart-dialog').close();
    location.hash = 'postres';
    route();
  }

  if (b.id === 'view-summary') {
    const el = $('.summary-preview');
    el.hidden = false;
    el.textContent = summary();
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
});

// Cierre de dialogos al hacer clic fuera del contenedor
document.querySelectorAll('dialog').forEach((d) =>
  d.addEventListener('click', (e) => {
    if (e.target !== d) return;
    const r = d.getBoundingClientRect();
    if (e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom) {
      d.close();
    }
  })
);

// Enlaces de navegacion por ancla
document.addEventListener('click', (e) => {
  const a = e.target.closest('a[href^="#"]');
  if (a && a.hash === location.hash) {
    route();
    if (a.hash === '#marca') {
      $('#marca').scrollIntoView({ behavior: 'smooth' });
    }
  }
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeMenu();
});

// Envio del formulario de inicio de sesion (Controlador -> API AuthController::login)
$('#login-form')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  $('#login-error').hidden = true;
  const email = $('#login-email').value.trim().toLowerCase();
  const password = $('#current-password').value;
  if (!$('#login-form').reportValidity()) return;

  try {
    const res = await fetch('php/api.php?action=login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json'
      },
      body: JSON.stringify({ email, password })
    });
    const data = await res.json();
    if (data?.ok && data.user) {
      $('#login-form').reset();
      await completeAuth(data.user, `¡Bienvenido de nuevo, ${data.user.name.split(' ')[0]}!`);
      return;
    }
    if (data?.message) {
      $('#login-error').hidden = false;
      $('#login-error').textContent = data.message;
      return;
    }
  } catch {}

  const found = users.find((u) => u.email.toLowerCase() === email);
  if (!found) {
    $('#login-error').hidden = false;
    $('#login-error').textContent = 'No encontramos una cuenta con este correo. ¡Regístrate en segundos en la pestaña Crear cuenta!';
    return;
  }
  if (found.password !== password) {
    $('#login-error').hidden = false;
    $('#login-error').textContent = 'La contraseña ingresada no coincide. Verifica e inténtalo de nuevo.';
    return;
  }
  $('#login-form').reset();
  await completeAuth(found, `¡Bienvenido de nuevo, ${found.name.split(' ')[0]}!`);
});

// Envio del formulario de registro (Controlador -> API AuthController::register)
$('#register-form')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  $('#register-error').hidden = true;
  if (!$('#register-form').reportValidity()) return;

  const name = $('#register-name').value.trim();
  const email = $('#register-email').value.trim().toLowerCase();
  const phone = $('#register-phone').value.trim();
  const password = $('#new-password').value;
  const address = $('#order-location')?.value.trim() || '';

  if (name.length < 2) {
    $('#register-error').hidden = false;
    $('#register-error').textContent = 'Por favor, escribe tu nombre para personalizar tu pedido.';
    return;
  }

  try {
    const res = await fetch('php/api.php?action=register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json'
      },
      body: JSON.stringify({ name, email, phone, address, password })
    });
    const data = await res.json();
    if (data?.ok && data.user) {
      $('#register-form').reset();
      await completeAuth(data.user, `¡Cuenta registrada en el sistema, ${data.user.name.split(' ')[0]}!`);
      return;
    }
    if (data?.message) {
      $('#register-error').hidden = false;
      $('#register-error').textContent = data.message;
      return;
    }
  } catch {}

  const existingIdx = users.findIndex((u) => u.email.toLowerCase() === email);
  const newUser = { name, email, phone, password };
  if (existingIdx >= 0) users[existingIdx] = newUser;
  else users.push(newUser);

  try {
    localStorage.setItem('dulce-users-v1', JSON.stringify(users));
  } catch {}

  $('#register-form').reset();
  await completeAuth(newUser, `¡Cuenta creada con éxito, ${name.split(' ')[0]}!`);
});

// Cambio de modalidad de entrega y envio del pedido (Controlador -> API PedidoController::crear)
$('#order-method').addEventListener('change', () => {
  const required = $('#order-method').value === 'domicilio';
  $('#order-location').setCustomValidity('');
  $('#order-location').required = required;
  $('#location-optional').textContent = required ? '(necesario para domicilio)' : '(opcional)';
});

$('#whatsapp-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  if (!products.some((p) => cart[p.id])) {
    toast('Añade un producto antes de solicitar tu pedido.');
    return;
  }
  const details = orderDetails();
  if (details.method === 'domicilio' && !details.location) {
    $('#order-location').setCustomValidity('Escribe el municipio, barrio y dirección o referencia.');
    $('#order-location').reportValidity();
    return;
  }
  $('#order-location').setCustomValidity('');

  if (!currentUser) {
    openAuth('register', true);
    toast('Regístrate o inicia sesión con tu correo para finalizar tu compra.');
    return;
  }

  const idVenta = await procesarEnvioPedido();
  if (idVenta) toast(`Pedido #${idVenta} guardado en la base de datos.`);
});

$('#order-location').addEventListener('input', () => $('#order-location').setCustomValidity(''));

// Inicializacion de la aplicacion
window.addEventListener('hashchange', route);
save();
updateAuthUI();
route();
loadDatabaseProducts();
