<?php
declare(strict_types=1);

/**
 * API INDEPENDIENTE DEL PORTAL DE ADMINISTRACIÓN Y EMPLEADOS
 * Conectada directamente con la base de datos MySQL `sistema_dulcetentacion`.
 */
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexion.php';

function responderAdmin(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function detectarCategoriaProducto(string $nombre): string
{
    $n = mb_strtolower($nombre, 'UTF-8');
    $palabrasCafe = ['cappuccino', 'latte', 'lattle', 'mocaccino', 'café', 'cafe', 'espresso', 'americano', 'bombón', 'bombon'];
    foreach ($palabrasCafe as $kw) {
        if (mb_strpos($n, $kw) !== false) {
            return 'Cafés';
        }
    }
    return 'Postres';
}

$action = $_GET['action'] ?? '';
$entity = $_GET['entity'] ?? '';
$raw    = file_get_contents('php://input');
$body   = json_decode($raw ?: '{}', true);
if (!is_array($body) || empty($body)) {
    $body = $_POST;
}

try {
    $db = obtenerConexionAdmin();

    // Asegurar tabla de movimientos de stock para auditoria
    $db->exec('CREATE TABLE IF NOT EXISTS movimientos_stock (
        id_movimiento INT NOT NULL AUTO_INCREMENT,
        id_producto INT NOT NULL,
        nombre_producto VARCHAR(100) NOT NULL,
        delta INT NOT NULL,
        stock_antes INT NOT NULL,
        stock_despues INT NOT NULL,
        motivo VARCHAR(300) NOT NULL,
        responsable VARCHAR(120) DEFAULT \'Juan Villamil\',
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_movimiento),
        KEY id_producto (id_producto)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

    $colsMov = $db->query("SHOW COLUMNS FROM movimientos_stock LIKE 'responsable'")->fetchAll();
    if (empty($colsMov)) {
        $db->exec("ALTER TABLE movimientos_stock ADD COLUMN responsable VARCHAR(120) DEFAULT 'Juan Villamil' AFTER motivo");
    }

    // 1. AUTENTICACIÓN SEPARADA POR ROL (ADMINISTRADOR VS EMPLEADO)
    if ($action === 'login') {
        $email    = trim((string)($body['email'] ?? ''));
        $password = (string)($body['password'] ?? '');
        $roleType = strtolower(trim((string)($body['roleType'] ?? '')));

        if ($email === '' || $password === '') {
            responderAdmin(['ok' => false, 'message' => 'Ingresa tu correo y contraseña.'], 400);
        }

        if (!in_array($roleType, ['administrador', 'empleado'], true)) {
            responderAdmin(['ok' => false, 'message' => 'Selecciona si deseas iniciar sesión desde el botón de Administrador o de Empleado.'], 400);
        }

        // Consultar en `administradores`
        $stmtAdm = $db->prepare('SELECT id_admin, nombre, apellido, email, cargo, contrasena FROM administradores WHERE LOWER(email) = LOWER(:email) LIMIT 1');
        $stmtAdm->execute([':email' => $email]);
        $adm = $stmtAdm->fetch();

        // Consultar en `empleados`
        $stmtEmp = $db->prepare('SELECT id_empleado, nombre, apellido, email, contrasena FROM empleados WHERE LOWER(email) = LOWER(:email) LIMIT 1');
        $stmtEmp->execute([':email' => $email]);
        $emp = $stmtEmp->fetch();

        // Si intenta entrar desde el botón de ADMINISTRADOR
        if ($roleType === 'administrador') {
            if ($adm) {
                $guardada = (string)$adm['contrasena'];
                if (hash_equals($guardada, $password) || password_verify($password, $guardada)) {
                    responderAdmin([
                        'ok'   => true,
                        'user' => [
                            'id'       => 'adm-' . $adm['id_admin'],
                            'name'     => trim($adm['nombre'] . ' ' . $adm['apellido']),
                            'email'    => $adm['email'],
                            'role'     => $adm['cargo'] ?: 'Administrador',
                            'roleType' => 'administrador',
                        ],
                    ]);
                }
                responderAdmin(['ok' => false, 'message' => 'Contraseña incorrecta para la cuenta de Administrador.'], 401);
            }

            if ($emp) {
                responderAdmin([
                    'ok'      => false,
                    'message' => 'Acceso denegado: esta es una cuenta de Empleado. Solo puedes iniciarla desde el botón de Empleado.',
                ], 403);
            }

            responderAdmin(['ok' => false, 'message' => 'No existe una cuenta de Administrador con ese correo en sistema_dulcetentacion.'], 404);
        }

        // Si intenta entrar desde el botón de EMPLEADO
        if ($roleType === 'empleado') {
            if ($emp) {
                $guardada = (string)$emp['contrasena'];
                if (hash_equals($guardada, $password) || password_verify($password, $guardada)) {
                    responderAdmin([
                        'ok'   => true,
                        'user' => [
                            'id'       => 'emp-' . $emp['id_empleado'],
                            'name'     => trim($emp['nombre'] . ' ' . $emp['apellido']),
                            'email'    => $emp['email'],
                            'role'     => 'Empleado Operativo',
                            'roleType' => 'empleado',
                        ],
                    ]);
                }
                responderAdmin(['ok' => false, 'message' => 'Contraseña incorrecta para la cuenta de Empleado.'], 401);
            }

            if ($adm) {
                responderAdmin([
                    'ok'      => false,
                    'message' => 'Acceso denegado: esta es una cuenta de Administrador. Solo puedes iniciarla desde el botón de Administrador.',
                ], 403);
            }

            responderAdmin(['ok' => false, 'message' => 'No existe una cuenta de Empleado con ese correo en sistema_dulcetentacion.'], 404);
        }
    }

    // 2. LISTADO DE ENTIDADES DESDE `sistema_dulcetentacion`
    if ($action === 'list') {
        if ($entity === 'products') {
            $rows = $db->query('SELECT id_producto, nombre, precio, stock FROM productos ORDER BY id_producto ASC')->fetchAll();
            $items = [];
            foreach ($rows as $r) {
                $id = (int)$r['id_producto'];
                $items[] = [
                    'id'       => (string)$id,
                    'name'     => $r['nombre'],
                    'category' => detectarCategoriaProducto($r['nombre']),
                    'sku'      => sprintf('DT-%03d', $id),
                    'price'    => $r['precio'] !== null ? (float)$r['precio'] : null,
                    'stock'    => (int)$r['stock'],
                    'minStock' => 15,
                    'status'   => 'activo',
                ];
            }
            responderAdmin(['ok' => true, 'items' => $items]);
        }

        if ($entity === 'employees') {
            $items = [];
            $admins = $db->query('SELECT id_admin, nombre, apellido, email, cargo FROM administradores ORDER BY id_admin ASC')->fetchAll();
            foreach ($admins as $a) {
                $items[] = [
                    'id'     => 'adm-' . $a['id_admin'],
                    'name'   => trim($a['nombre'] . ' ' . $a['apellido']) . ($a['cargo'] ? ' (' . $a['cargo'] . ')' : ''),
                    'email'  => $a['email'],
                    'role'   => 'administrador',
                    'status' => 'activo',
                ];
            }

            $emps = $db->query('SELECT id_empleado, nombre, apellido, email FROM empleados ORDER BY id_empleado ASC')->fetchAll();
            foreach ($emps as $idx => $e) {
                $items[] = [
                    'id'     => 'emp-' . $e['id_empleado'],
                    'name'   => trim($e['nombre'] . ' ' . $e['apellido']),
                    'email'  => $e['email'],
                    'role'   => $idx % 2 === 0 ? 'inventario' : 'atencion',
                    'status' => 'activo',
                ];
            }
            responderAdmin(['ok' => true, 'items' => $items]);
        }

        if ($entity === 'clients') {
            $rows = $db->query('SELECT id_cliente, nombre, apellido, telefono, email, direccion FROM clientes ORDER BY id_cliente ASC')->fetchAll();
            $items = [];
            foreach ($rows as $c) {
                $apellido = $c['apellido'] === 'Cliente' ? '' : $c['apellido'];
                $items[] = [
                    'id'      => (string)$c['id_cliente'],
                    'name'    => trim($c['nombre'] . ' ' . $apellido),
                    'email'   => $c['email'] ?? '',
                    'phone'   => $c['telefono'] ?? '',
                    'address' => $c['direccion'] ?? '',
                    'status'  => 'activo',
                ];
            }
            responderAdmin(['ok' => true, 'items' => $items]);
        }

        if ($entity === 'movements') {
            $items = [];
            $movs = $db->query('SELECT id_movimiento, id_producto, nombre_producto, delta, stock_antes, stock_despues, motivo, responsable, fecha FROM movimientos_stock ORDER BY id_movimiento DESC')->fetchAll();
            foreach ($movs as $m) {
                $items[] = [
                    'id'           => 'mov-' . $m['id_movimiento'],
                    'productId'    => (string)$m['id_producto'],
                    'productName'  => $m['nombre_producto'],
                    'delta'        => (int)$m['delta'],
                    'before'       => (int)$m['stock_antes'],
                    'after'        => (int)$m['stock_despues'],
                    'employeeName' => !empty($m['responsable']) ? $m['responsable'] : 'Juan Villamil',
                    'reason'       => $m['motivo'],
                    'createdAt'    => date('c', strtotime((string)$m['fecha'])),
                ];
            }

            $sqlVentas = 'SELECT d.id_detalle, d.id_venta, d.id_producto, d.cantidad, d.precio_unitario,
                                 v.fecha, p.nombre AS nombre_producto, p.stock AS stock_actual,
                                 c.nombre AS cliente_nombre, c.apellido AS cliente_apellido,
                                 e.nombre AS empleado_nombre, e.apellido AS empleado_apellido
                          FROM detalles_venta d
                          INNER JOIN ventas v ON v.id_venta = d.id_venta
                          LEFT JOIN productos p ON p.id_producto = d.id_producto
                          LEFT JOIN clientes c ON c.id_cliente = v.id_cliente
                          LEFT JOIN empleados e ON e.id_empleado = v.id_empleado
                          ORDER BY v.fecha DESC, d.id_detalle DESC';
            $ventasRows = $db->query($sqlVentas)->fetchAll();
            foreach ($ventasRows as $vr) {
                $cant        = (int)$vr['cantidad'];
                $stockActual = (int)($vr['stock_actual'] ?? 0);
                $clienteNom  = trim(($vr['cliente_nombre'] ?? '') . ' ' . (($vr['cliente_apellido'] ?? '') === 'Cliente' ? '' : ($vr['cliente_apellido'] ?? '')));
                $empleadoNom = trim(($vr['empleado_nombre'] ?? '') . ' ' . ($vr['empleado_apellido'] ?? '')) ?: 'Juan Villamil';
                $motivo      = 'Venta #' . $vr['id_venta'] . ($clienteNom ? ' · Cliente: ' . $clienteNom : '');

                $items[] = [
                    'id'           => 'venta-' . $vr['id_detalle'],
                    'productId'    => (string)$vr['id_producto'],
                    'productName'  => $vr['nombre_producto'] ?? ('Producto #' . $vr['id_producto']),
                    'delta'        => -$cant,
                    'before'       => $stockActual + $cant,
                    'after'        => $stockActual,
                    'employeeName' => $empleadoNom,
                    'reason'       => $motivo,
                    'createdAt'    => date('c', strtotime((string)$vr['fecha'])),
                ];
            }
            responderAdmin(['ok' => true, 'items' => $items]);
        }

        responderAdmin(['ok' => false, 'message' => 'Entidad desconocida.'], 400);
    }

    // 3. CREAR O EDITAR REGISTROS
    if ($action === 'save') {
        $targetEntity = (string)($body['entity'] ?? '');
        $values       = $body['values'] ?? [];

        if (!is_array($values)) {
            responderAdmin(['ok' => false, 'message' => 'Datos inválidos.'], 400);
        }

        $name = trim((string)($values['name'] ?? ''));
        if ($name === '') {
            responderAdmin(['ok' => false, 'message' => 'Escribe el nombre del registro.'], 422);
        }

        if ($targetEntity === 'products') {
            $id    = !empty($values['id']) ? (int)$values['id'] : 0;
            $price = isset($values['price']) && $values['price'] !== '' && $values['price'] !== null ? (float)$values['price'] : 0.0;
            $stock = isset($values['stock']) ? max(0, (int)$values['stock']) : 0;

            if ($id > 0) {
                $stmt = $db->prepare('UPDATE productos SET nombre = :nombre, precio = :precio WHERE id_producto = :id');
                $stmt->execute([':nombre' => $name, ':precio' => $price, ':id' => $id]);
            } else {
                $stmt = $db->prepare('INSERT INTO productos (nombre, precio, stock) VALUES (:nombre, :precio, :stock)');
                $stmt->execute([':nombre' => $name, ':precio' => $price, ':stock' => $stock]);
                $id = (int)$db->lastInsertId();

                if ($stock > 0) {
                    $stmtMov = $db->prepare('INSERT INTO movimientos_stock (id_producto, nombre_producto, delta, stock_antes, stock_despues, motivo) VALUES (:id, :nombre, :delta, 0, :despues, :motivo)');
                    $stmtMov->execute([
                        ':id'      => $id,
                        ':nombre'  => $name,
                        ':delta'   => $stock,
                        ':despues' => $stock,
                        ':motivo'  => 'Stock inicial registrado en sistema',
                    ]);
                }
            }

            responderAdmin([
                'ok'   => true,
                'item' => [
                    'id'       => (string)$id,
                    'name'     => $name,
                    'category' => $values['category'] ?? detectarCategoriaProducto($name),
                    'sku'      => sprintf('DT-%03d', $id),
                    'price'    => $price,
                    'stock'    => $stock,
                    'minStock' => (int)($values['minStock'] ?? 15),
                    'status'   => $values['status'] ?? 'activo',
                ],
            ]);
        }

        if ($targetEntity === 'employees') {
            $rawId = (string)($values['id'] ?? '');
            $email = trim((string)($values['email'] ?? ''));
            $role  = (string)($values['role'] ?? 'inventario');

            if ($email === '') {
                responderAdmin(['ok' => false, 'message' => 'El correo electrónico es obligatorio.'], 422);
            }

            $cleanName = trim((string)preg_replace('/\s*\([^)]*\)\s*$/', '', $name));
            $partes    = preg_split('/\s+/', $cleanName, 2);
            $nombre    = mb_substr($partes[0] ?? $cleanName, 0, 50);
            $apellido  = mb_substr($partes[1] ?? 'Equipo', 0, 50);

            if (str_starts_with($rawId, 'adm-') || ($rawId === '' && $role === 'administrador')) {
                $idAdmin = str_starts_with($rawId, 'adm-') ? (int)substr($rawId, 4) : 0;
                if ($idAdmin > 0) {
                    $stmt = $db->prepare('UPDATE administradores SET nombre = :nombre, apellido = :apellido, email = :email WHERE id_admin = :id');
                    $stmt->execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':id' => $idAdmin]);
                } else {
                    $stmt = $db->prepare('INSERT INTO administradores (nombre, apellido, email, cargo, contrasena) VALUES (:nombre, :apellido, :email, :cargo, :pass)');
                    $stmt->execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':cargo' => 'Administrador', ':pass' => 'admin_pass']);
                    $idAdmin = (int)$db->lastInsertId();
                }
                $finalId = 'adm-' . $idAdmin;
            } else {
                $idEmp = str_starts_with($rawId, 'emp-') ? (int)substr($rawId, 4) : (int)$rawId;
                if ($idEmp > 0) {
                    $stmt = $db->prepare('UPDATE empleados SET nombre = :nombre, apellido = :apellido, email = :email WHERE id_empleado = :id');
                    $stmt->execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':id' => $idEmp]);
                } else {
                    $stmt = $db->prepare('INSERT INTO empleados (nombre, apellido, email, contrasena) VALUES (:nombre, :apellido, :email, :pass)');
                    $stmt->execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':pass' => 'emp_pass']);
                    $idEmp = (int)$db->lastInsertId();
                }
                $finalId = 'emp-' . $idEmp;
            }

            responderAdmin([
                'ok'   => true,
                'item' => [
                    'id'     => $finalId,
                    'name'   => trim($nombre . ' ' . $apellido),
                    'email'  => $email,
                    'role'   => $role,
                    'status' => $values['status'] ?? 'activo',
                ],
            ]);
        }

        if ($targetEntity === 'clients') {
            $id       = !empty($values['id']) ? (int)$values['id'] : 0;
            $email    = trim((string)($values['email'] ?? ''));
            $phone    = trim((string)($values['phone'] ?? ''));
            $partes   = preg_split('/\s+/', $name, 2);
            $nombre   = mb_substr($partes[0] ?? $name, 0, 50);
            $apellido = mb_substr($partes[1] ?? 'Cliente', 0, 50);

            if ($id > 0) {
                $stmt = $db->prepare('UPDATE clientes SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono WHERE id_cliente = :id');
                $stmt->execute([
                    ':nombre'   => $nombre,
                    ':apellido' => $apellido,
                    ':email'    => $email !== '' ? $email : ('cliente' . $id . '@dulcetentacion.local'),
                    ':telefono' => $phone !== '' ? $phone : null,
                    ':id'       => $id,
                ]);
            } else {
                $stmt = $db->prepare('INSERT INTO clientes (nombre, apellido, telefono, email, contrasena) VALUES (:nombre, :apellido, :telefono, :email, :pass)');
                $stmt->execute([
                    ':nombre'   => $nombre,
                    ':apellido' => $apellido,
                    ':telefono' => $phone !== '' ? $phone : null,
                    ':email'    => $email !== '' ? $email : ('cliente' . time() . '@dulcetentacion.local'),
                    ':pass'     => 'cliente_123',
                ]);
                $id = (int)$db->lastInsertId();
            }

            responderAdmin([
                'ok'   => true,
                'item' => [
                    'id'     => (string)$id,
                    'name'   => trim($nombre . ' ' . ($apellido === 'Cliente' && count($partes) < 2 ? '' : $apellido)),
                    'email'  => $email,
                    'phone'  => $phone,
                    'status' => $values['status'] ?? 'activo',
                ],
            ]);
        }

        responderAdmin(['ok' => false, 'message' => 'Entidad no editable.'], 400);
    }

    // 4. AJUSTAR STOCK
    if ($action === 'adjust_stock') {
        $productId    = (int)($body['productId'] ?? 0);
        $delta        = (int)($body['delta'] ?? 0);
        $reason       = trim((string)($body['reason'] ?? ''));
        $employeeName = trim((string)($body['employeeName'] ?? '')) ?: 'Juan Villamil';

        if ($productId <= 0 || $delta === 0 || $reason === '') {
            responderAdmin(['ok' => false, 'message' => 'Indica el producto, una cantidad distinta de cero y el motivo del ajuste.'], 422);
        }

        $db->beginTransaction();
        $stmt = $db->prepare('SELECT id_producto, nombre, stock FROM productos WHERE id_producto = :id FOR UPDATE');
        $stmt->execute([':id' => $productId]);
        $prod = $stmt->fetch();

        if (!$prod) {
            $db->rollBack();
            responderAdmin(['ok' => false, 'message' => 'Producto no encontrado.'], 404);
        }

        $before = (int)$prod['stock'];
        $after  = $before + $delta;
        if ($after < 0) {
            $db->rollBack();
            responderAdmin(['ok' => false, 'message' => 'El movimiento deja el stock en negativo (stock actual: ' . $before . ').'], 422);
        }

        $stmtUpd = $db->prepare('UPDATE productos SET stock = :stock WHERE id_producto = :id');
        $stmtUpd->execute([':stock' => $after, ':id' => $productId]);

        $stmtMov = $db->prepare('INSERT INTO movimientos_stock (id_producto, nombre_producto, delta, stock_antes, stock_despues, motivo, responsable) VALUES (:id_prod, :nombre, :delta, :antes, :despues, :motivo, :responsable)');
        $stmtMov->execute([
            ':id_prod'     => $productId,
            ':nombre'      => $prod['nombre'],
            ':delta'       => $delta,
            ':antes'       => $before,
            ':despues'     => $after,
            ':motivo'      => mb_substr($reason, 0, 300),
            ':responsable' => mb_substr($employeeName, 0, 120),
        ]);
        $idMov = (int)$db->lastInsertId();

        $db->commit();

        responderAdmin([
            'ok'       => true,
            'movement' => [
                'id'           => 'mov-' . $idMov,
                'productId'    => (string)$productId,
                'productName'  => $prod['nombre'],
                'delta'        => $delta,
                'before'       => $before,
                'after'        => $after,
                'employeeName' => $employeeName,
                'reason'       => $reason,
                'createdAt'    => date('c'),
            ],
        ]);
    }

    // 5. BORRAR REGISTROS DE `sistema_dulcetentacion`
    if ($action === 'delete') {
        $targetEntity = (string)($body['entity'] ?? '');
        $rawId        = trim((string)($body['id'] ?? ''));

        if ($targetEntity === '' || $rawId === '') {
            responderAdmin(['ok' => false, 'message' => 'Falta identificar el registro a borrar.'], 400);
        }

        if ($targetEntity === 'products') {
            $id = (int)$rawId;
            if ($id <= 0) {
                responderAdmin(['ok' => false, 'message' => 'ID de producto inválido.'], 400);
            }
            $db->beginTransaction();
            $db->prepare('DELETE FROM movimientos_stock WHERE id_producto = :id')->execute([':id' => $id]);
            $db->prepare('DELETE FROM detalles_venta WHERE id_producto = :id')->execute([':id' => $id]);
            $db->prepare('DELETE FROM productos WHERE id_producto = :id')->execute([':id' => $id]);
            $db->commit();
            responderAdmin(['ok' => true]);
        }

        if ($targetEntity === 'employees') {
            if (str_starts_with($rawId, 'adm-')) {
                $id = (int)substr($rawId, 4);
                $db->prepare('DELETE FROM administradores WHERE id_admin = :id')->execute([':id' => $id]);
            } else {
                $id = str_starts_with($rawId, 'emp-') ? (int)substr($rawId, 4) : (int)$rawId;
                $db->prepare('DELETE FROM empleados WHERE id_empleado = :id')->execute([':id' => $id]);
            }
            responderAdmin(['ok' => true]);
        }

        if ($targetEntity === 'clients') {
            $id = (int)$rawId;
            if ($id <= 0) {
                responderAdmin(['ok' => false, 'message' => 'ID de cliente inválido.'], 400);
            }
            $db->beginTransaction();
            $db->prepare('DELETE FROM detalles_venta WHERE id_venta IN (SELECT id_venta FROM ventas WHERE id_cliente = :id)')->execute([':id' => $id]);
            $db->prepare('DELETE FROM ventas WHERE id_cliente = :id')->execute([':id' => $id]);
            $db->prepare('DELETE FROM clientes WHERE id_cliente = :id')->execute([':id' => $id]);
            $db->commit();
            responderAdmin(['ok' => true]);
        }

        responderAdmin(['ok' => false, 'message' => 'Entidad no válida para eliminar.'], 400);
    }

    // 6. OBTENER DATOS DEL PERFIL DEL ADMINISTRADOR
    if ($action === 'get_profile') {
        $rawId = trim((string)($body['id'] ?? ($_GET['id'] ?? '')));
        $id = str_starts_with($rawId, 'adm-') ? (int)substr($rawId, 4) : (int)$rawId;

        if ($id <= 0) {
            responderAdmin(['ok' => false, 'message' => 'ID de administrador inválido.'], 400);
        }

        $stmt = $db->prepare('SELECT id_admin, nombre, apellido, email, cargo FROM administradores WHERE id_admin = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $adm = $stmt->fetch();

        if (!$adm) {
            responderAdmin(['ok' => false, 'message' => 'No se encontró la cuenta de administrador.'], 404);
        }

        responderAdmin([
            'ok' => true,
            'admin' => [
                'id'       => 'adm-' . $adm['id_admin'],
                'fullName' => trim($adm['nombre'] . ' ' . $adm['apellido']),
                'nombre'   => $adm['nombre'],
                'apellido' => $adm['apellido'],
                'email'    => $adm['email'],
                'cargo'    => $adm['cargo'] ?: 'Administrador',
                'roleType' => 'administrador',
            ],
        ]);
    }

    // 7. CAMBIAR CONTRASEÑA DE ADMINISTRADOR
    if ($action === 'change_password') {
        $rawId       = trim((string)($body['adminId'] ?? ''));
        $currentPass = (string)($body['currentPassword'] ?? '');
        $newPass     = (string)($body['newPassword'] ?? '');
        $confirmPass = (string)($body['confirmPassword'] ?? '');

        $id = str_starts_with($rawId, 'adm-') ? (int)substr($rawId, 4) : (int)$rawId;
        if ($id <= 0) {
            responderAdmin(['ok' => false, 'message' => 'Solo los administradores pueden cambiar su contraseña desde este panel.'], 403);
        }

        if ($currentPass === '' || $newPass === '') {
            responderAdmin(['ok' => false, 'message' => 'Debes ingresar tu contraseña actual y la nueva contraseña.'], 422);
        }

        if (mb_strlen($newPass) < 4) {
            responderAdmin(['ok' => false, 'message' => 'La nueva contraseña debe tener al menos 4 caracteres.'], 422);
        }

        if ($newPass !== $confirmPass) {
            responderAdmin(['ok' => false, 'message' => 'La confirmación de la nueva contraseña no coincide.'], 422);
        }

        $stmt = $db->prepare('SELECT id_admin, contrasena FROM administradores WHERE id_admin = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $adm = $stmt->fetch();

        if (!$adm) {
            responderAdmin(['ok' => false, 'message' => 'Cuenta de administrador no encontrada.'], 404);
        }

        $guardada = (string)$adm['contrasena'];
        if (!hash_equals($guardada, $currentPass) && !password_verify($currentPass, $guardada)) {
            responderAdmin(['ok' => false, 'message' => 'La contraseña actual ingresada es incorrecta.'], 401);
        }

        // Actualizar contraseña
        $stmtUpd = $db->prepare('UPDATE administradores SET contrasena = :new_pass WHERE id_admin = :id');
        $stmtUpd->execute([':new_pass' => $newPass, ':id' => $id]);

        responderAdmin([
            'ok'      => true,
            'message' => 'Contraseña actualizada con éxito en sistema_dulcetentacion.',
        ]);
    }

    responderAdmin(['ok' => false, 'message' => 'Acción no reconocida.'], 400);
} catch (Throwable $e) {
    if (isset($db) && $db instanceof PDO && $db->inTransaction()) {
        $db->rollBack();
    }
    responderAdmin(['ok' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()], 500);
}
