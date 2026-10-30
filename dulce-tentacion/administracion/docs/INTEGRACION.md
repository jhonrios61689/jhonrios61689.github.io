# Integración del módulo administrativo

## Estado de entrega

Interfaz independiente con datos ficticios y adaptador asíncrono de demostración. El formulario de acceso no autentica, y Explorar demostración abre todas las pantallas para revisión. Antes de publicar se deben conectar autenticación, autorización y persistencia. No utilizar la visibilidad de botones como control de acceso.

Los flujos propuestos deben validarse con la propietaria y el equipo. El inventario se expresa en unidades vendibles de producto; no calcula ingredientes, recetas ni consumos de materia prima.

## Organización y DOM

- `index.php`: documento y carga de recursos propios.
- `php/vistas/acceso.php`: vista de acceso y entrada a demostración.
- `php/vistas/panel.php`: navegación, cabecera y contenedor del panel.
- `php/componentes/editor.php`: diálogo reutilizable de edición.
- `css/admin.css`: estilos y adaptación móvil.
- `js/app.js`: presentación, navegación, formularios y estado visible.
- `js/api.js`: único adaptador de acceso a datos; reemplazar aquí la demostración por solicitudes al servidor.
- `assets/`: imágenes y fuente locales, sin dependencia de las rutas de la tienda.

El DOM tiene dos contenedores principales: access y panel. Dentro de panel, view-content recibe cada vista. editor es el diálogo de edición, y notification anuncia resultados. Los valores introducidos se escapan al generar tablas y se validan en el adaptador. Repetir validación en el servidor.

## Métodos consumidos por la interfaz

Todos retornan Promise. Los errores deben rechazarse con Error y un mensaje comprensible. El adaptador debe convertir el formato del servidor al siguiente contrato para evitar cambiar las vistas.

| Método | Argumentos | Resultado |
|---|---|---|
| list | entity: products, employees, clients o movements | Array completo de registros para esta demostración |
| save | entity, objeto con campos del registro e id opcional | Registro creado o actualizado |
| adjustStock | productId, delta entero con signo, reason | Movimiento generado y stock actualizado en servidor |
| login | Reservado para la integración | Sesión autenticada; conectar también el formulario de acceso |

La interfaz actual obtiene la lista completa y filtra en navegador. Si el volumen exige paginación y búsqueda en servidor, ampliar list y la barra de búsqueda de app.js. No devolver directorios completos de clientes sin evaluar necesidad y permisos.

## Campos

| Entidad | Campos |
|---|---|
| Producto | id string, name string, category Postres o Cafés, sku string único, price número COP o null, stock entero, minStock entero, status activo o inactivo |
| Empleado | id string, name string, email string único, role administrador/encargado/inventario/atencion, status activo o inactivo |
| Cliente | id string, name string, email opcional, phone opcional, status activo o inactivo |
| Movimiento | id string, productId string, productName string, delta entero con signo, before entero, after entero, reason string, createdAt fecha ISO 8601 |

price null significa por confirmar, nunca cero. Stock y mínimo tienen rango de demostración 0 a 999999. Una salida no puede producir stock negativo. La edición de un producto no cambia su stock: usar adjustStock, con motivo obligatorio. El stock inicial de un nuevo producto genera un movimiento de entrada. Los ejemplos iniciales son una fotografía de inventario de demostración y no tienen movimientos históricos.

## Propuesta de rutas para el servidor

Estas rutas se documentan como contrato orientativo; no existen endpoints operativos en esta entrega.

| Ruta propuesta | Operación |
|---|---|
| POST /api/admin/session | Autenticar y establecer sesión |
| GET /api/admin/session | Consultar persona autenticada y permisos |
| DELETE /api/admin/session | Cerrar sesión en servidor |
| GET /api/admin/products | Listar productos |
| POST /api/admin/products | Crear producto |
| PATCH /api/admin/products/{id} | Editar metadatos y estado |
| POST /api/admin/products/{id}/stock-movements | Entrada o salida transaccional |
| GET /api/admin/stock-movements | Listar movimientos autorizados |
| GET, POST /api/admin/employees | Listar o crear empleados |
| PATCH /api/admin/employees/{id} | Cambiar datos, rol o estado |
| GET, POST /api/admin/clients | Listar o crear registros de cliente |
| PATCH /api/admin/clients/{id} | Editar datos o estado |

Propuesta de respuesta: JSON con data para resultados; error con code, message y fields opcional para validación. Adaptar al contrato de métodos anterior. Usar 401 para sesión ausente, 403 para falta de permiso, 409 para conflictos de concurrencia o duplicados y 422 para campos inválidos.

## Pasos para la conexión

1. Definir permisos y esquema con el responsable de datos. No usar nombre ni correo como clave primaria. Enlazar movimiento con producto y con la persona autenticada que lo ejecuta; el usuario responsable y la fecha se asignan en el servidor.
2. Sustituir las colecciones de api.js por llamadas HTTP a las rutas del servicio. Los archivos PHP de vista pueden conservarse.
3. Conectar el envío de login-form con el proveedor o endpoint de autenticación. Retirar o deshabilitar Explorar demostración en producción. Comprobar la sesión al cargar y conectar logout con el cierre de sesión del servidor. La función login de api.js es solo un punto reservado; actualmente el formulario muestra un aviso y no la invoca.
4. Guardar la sesión en cookie segura HttpOnly con configuración adecuada, usar HTTPS en publicación y protección CSRF para cambios con sesión basada en cookie. No guardar contraseñas ni tokens en JavaScript o localStorage. Definir recuperación, verificación, límites de intentos y altas por invitación según el modelo elegido.
5. Aplicar autorización en cada endpoint. El rol de ejemplo mostrado en el panel no limita operaciones por sí mismo. La suspensión de un empleado debe bloquear nuevas sesiones y revocar las existentes.
6. Realizar stock y movimiento en una misma transacción con control de concurrencia. El servidor recalcula before/after y valida la salida. No aceptar stock final ni identidad del operador como valores confiables del navegador. Prevenir duplicados por reintentos con una clave de operación.
7. Al pasar a datos reales, añadir avisos de carga, cancelación y tratamiento de sesión vencida y errores de conexión. Conservar el formulario si falla el guardado y evitar operaciones duplicadas. La demostración no prueba esas condiciones de red.

## Roles propuestos

| Función | Administrador | Encargado | Inventario | Atención |
|---|---|---|---|---|
| Empleados y permisos | Sí | No | No | No |
| Consultar productos | Sí | Sí | Sí | Sí |
| Editar productos y stock | Sí | Sí | Sí | No |
| Consultar movimientos | Sí | Sí | Sí | No |
| Consultar y editar clientes | Sí | Sí | No | Sí |

Clientes es una agenda de registros en este prototipo. Las cuentas de acceso para clientes, sus permisos y recuperación de contraseña requieren un flujo adicional del portal de clientes. Marcar un registro de cliente como inactivo no suspende una cuenta real hasta que el desarrollador defina y conecte esa relación.

## Pruebas para la integración real

- Credenciales inválidas, sesión válida, vencimiento y cierre de sesión.
- Peticiones directas sin permiso, aunque el botón no sea visible.
- Correo y SKU duplicados, campos inválidos y stock insuficiente.
- Dos personas ajustando el mismo producto a la vez, reintentos y auditoría.
- Alta, edición y suspensión; conservación de cambios después de recargar.
- Fallo de red durante guardado, mensajes comprensibles y posibilidad de reintentar.
- Navegación por teclado, tamaños móvil y escritorio, y formularios sin desbordamiento.

## Validación de esta entrega

Se revisó la sintaxis PHP y JavaScript, las rutas y los identificadores del documento. En el adaptador se probaron copias aisladas de datos, rechazo de stock insuficiente, movimiento de entrada/salida, alta de producto, SKU duplicado y suspensión de ejemplo. En navegador se comprobó la entrada a demostración, la vista de productos, el formulario de stock y la aparición del movimiento, además del resumen en escritorio y celular. La conexión real, las cuentas y los permisos de servidor quedan pendientes.
