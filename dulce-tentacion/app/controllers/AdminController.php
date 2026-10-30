<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

/**
 * CAPA CONTROLADOR / SERVICIO MVC: AdminController
 * Conecta el panel de administracion (`administracion/`) con la base de datos `sistema_dulcetentacion`.
 */
class AdminController
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
        $this->asegurarTablaMovimientos();
    }

    /**
     * Crea la tabla auxiliar `movimientos_stock` si no existe para persistir ajustes de inventario.
     */
    private function asegurarTablaMovimientos(): void
    {
        $sql = 'CREATE TABLE IF NOT EXISTS movimientos_stock (
            id_movimiento INT NOT NULL AUTO_INCREMENT,
            id_producto INT NOT NULL,
            nombre_producto VARCHAR(100) NOT NULL,
            delta INT NOT NULL,
            stock_antes INT NOT NULL,
            stock_despues INT NOT NULL,
            motivo VARCHAR(300) NOT NULL,
            fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id_movimiento),
            KEY id_producto (id_producto)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
        $this->db->exec($sql);
    }

    /**
     * Determina si un nombre de producto corresponde a la categoria Cafes o Postres.
     */
    private function detectarCategoria(string $nombre): string
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

    /**
     * POST ?action=login
     * Autentica separando estrictamente cuentas de `administradores` y `empleados` en `sistema_dulcetentacion`.
     */
    public function login(array $body): array
    {
        $email    = trim((string)($body['email'] ?? ''));
        $password = (string)($body['password'] ?? '');
        $roleType = strtolower(trim((string)($body['roleType'] ?? '')));

        if ($email === '' || $password === '') {
            return ['status' => 400, 'body' => ['ok' => false, 'message' => 'Ingresa tu correo y contraseña.']];
        }

        if (!in_array($roleType, ['administrador', 'empleado'], true)) {
            return ['status' => 400, 'body' => ['ok' => false, 'message' => 'Selecciona si deseas iniciar sesión desde el botón de Administrador o de Empleado.']];
        }

        // 1. Buscar en tabla `administradores`
        $stmtAdm = $this->db->prepare('SELECT id_admin, nombre, apellido, email, cargo, contrasena FROM administradores WHERE LOWER(email) = LOWER(:email) LIMIT 1');
        $stmtAdm->execute([':email' => $email]);
        $adm = $stmtAdm->fetch();

        // 2. Buscar en tabla `empleados`
        $stmtEmp = $this->db->prepare('SELECT id_empleado, nombre, apellido, email, contrasena FROM empleados WHERE LOWER(email) = LOWER(:email) LIMIT 1');
        $stmtEmp->execute([':email' => $email]);
        $emp = $stmtEmp->fetch();

        if ($roleType === 'administrador') {
            if ($adm) {
                $guardada = (string)$adm['contrasena'];
                if (hash_equals($guardada, $password) || password_verify($password, $guardada)) {
                    return [
                        'status' => 200,
                        'body'   => [
                            'ok'   => true,
                            'user' => [
                                'id'       => 'adm-' . $adm['id_admin'],
                                'name'     => trim($adm['nombre'] . ' ' . $adm['apellido']),
                                'email'    => $adm['email'],
                                'role'     => $adm['cargo'] ?: 'Administrador',
                                'roleType' => 'administrador',
                            ],
                        ],
                    ];
                }
                return ['status' => 401, 'body' => ['ok' => false, 'message' => 'Contraseña incorrecta para la cuenta de Administrador.']];
            }

            if ($emp) {
                return ['status' => 403, 'body' => ['ok' => false, 'message' => 'Acceso denegado: esta es una cuenta de Empleado. Solo puedes iniciarla desde el botón de Empleado.']];
            }

            return ['status' => 404, 'body' => ['ok' => false, 'message' => 'No existe una cuenta de Administrador con ese correo en sistema_dulcetentacion.']];
        }

        if ($emp) {
            $guardada = (string)$emp['contrasena'];
            if (hash_equals($guardada, $password) || password_verify($password, $guardada)) {
                return [
                    'status' => 200,
                    'body'   => [
                        'ok'   => true,
                        'user' => [
                            'id'       => 'emp-' . $emp['id_empleado'],
                            'name'     => trim($emp['nombre'] . ' ' . $emp['apellido']),
                            'email'    => $emp['email'],
                            'role'     => 'Empleado Operativo',
                            'roleType' => 'empleado',
                        ],
                    ],
                ];
            }
            return ['status' => 401, 'body' => ['ok' => false, 'message' => 'Contraseña incorrecta para la cuenta de Empleado.']];
        }

        if ($adm) {
            return ['status' => 403, 'body' => ['ok' => false, 'message' => 'Acceso denegado: esta es una cuenta de Administrador. Solo puedes iniciarla desde el botón de Administrador.']];
        }

        return ['status' => 404, 'body' => ['ok' => false, 'message' => 'No existe una cuenta de Empleado con ese correo en sistema_dulcetentacion.']];
    }

    /**
     * GET ?action=list&entity=products|employees|clients|movements
     */
    public function listar(string $entity): array
    {
        if ($entity === 'products') {
            $rows = $this->db->query('SELECT id_producto, nombre, precio, stock FROM productos ORDER BY id_producto ASC')->fetchAll();
            $items = [];
            foreach ($rows as $r) {
                $id = (int)$r['id_producto'];
                $items[] = [
                    'id'       => (string)$id,
                    'name'     => $r['nombre'],
                    'category' => $this->detectarCategoria($r['nombre']),
                    'sku'      => sprintf('DT-%03d', $id),
                    'price'    => $r['precio'] !== null ? (float)$r['precio'] : null,
                    'stock'    => (int)$r['stock'],
                    'minStock' => 15,
                    'status'   => 'activo',
                ];
            }
            return ['status' => 200, 'body' => ['ok' => true, 'items' => $items]];
        }

        if ($entity === 'employees') {
            $items = [];
            $admins = $this->db->query('SELECT id_admin, nombre, apellido, email, cargo FROM administradores ORDER BY id_admin ASC')->fetchAll();
            foreach ($admins as $a) {
                $items[] = [
                    'id'     => 'adm-' . $a['id_admin'],
                    'name'   => trim($a['nombre'] . ' ' . $a['apellido']) . ($a['cargo'] ? ' (' . $a['cargo'] . ')' : ''),
                    'email'  => $a['email'],
                    'role'   => 'administrador',
                    'status' => 'activo',
                ];
            }

            $emps = $this->db->query('SELECT id_empleado, nombre, apellido, email FROM empleados ORDER BY id_empleado ASC')->fetchAll();
            foreach ($emps as $idx => $e) {
                $items[] = [
                    'id'     => 'emp-' . $e['id_empleado'],
                    'name'   => trim($e['nombre'] . ' ' . $e['apellido']),
                    'email'  => $e['email'],
                    'role'   => $idx % 2 === 0 ? 'inventario' : 'atencion',
                    'status' => 'activo',
                ];
            }
            return ['status' => 200, 'body' => ['ok' => true, 'items' => $items]];
        }

        if ($entity === 'clients') {
            $rows = $this->db->query('SELECT id_cliente, nombre, apellido, telefono, email, direccion FROM clientes ORDER BY id_cliente ASC')->fetchAll();
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
            return ['status' => 200, 'body' => ['ok' => true, 'items' => $items]];
        }

        if ($entity === 'movements') {
            $items = [];

            // 1. Movimientos manuales en `movimientos_stock`
            $movs = $this->db->query('SELECT id_movimiento, id_producto, nombre_producto, delta, stock_antes, stock_despues, motivo, fecha FROM movimientos_stock ORDER BY id_movimiento DESC')->fetchAll();
            foreach ($movs as $m) {
                $items[] = [
                    'id'          => 'mov-' . $m['id_movimiento'],
                    'productId'   => (string)$m['id_producto'],
                    'productName' => $m['nombre_producto'],
                    'delta'       => (int)$m['delta'],
                    'before'      => (int)$m['stock_antes'],
                    'after'       => (int)$m['stock_despues'],
                    'reason'      => $m['motivo'],
                    'createdAt'   => date('c', strtotime((string)$m['fecha'])),
                ];
            }

            // 2. Ventas registradas en `ventas` y `detalles_venta`
            $sqlVentas = 'SELECT d.id_detalle, d.id_venta, d.id_producto, d.cantidad, d.precio_unitario,
                                 v.fecha, p.nombre AS nombre_producto, p.stock AS stock_actual,
                                 c.nombre AS cliente_nombre, c.apellido AS cliente_apellido
                          FROM detalles_venta d
                          INNER JOIN ventas v ON v.id_venta = d.id_venta
                          LEFT JOIN productos p ON p.id_producto = d.id_producto
                          LEFT JOIN clientes c ON c.id_cliente = v.id_cliente
                          ORDER BY v.fecha DESC, d.id_detalle DESC';
            $ventasRows = $this->db->query($sqlVentas)->fetchAll();
            foreach ($ventasRows as $vr) {
                $cant        = (int)$vr['cantidad'];
                $stockActual = (int)($vr['stock_actual'] ?? 0);
                $clienteNom  = trim(($vr['cliente_nombre'] ?? '') . ' ' . (($vr['cliente_apellido'] ?? '') === 'Cliente' ? '' : ($vr['cliente_apellido'] ?? '')));
                $motivo      = 'Venta #' . $vr['id_venta'] . ($clienteNom ? ' · Cliente: ' . $clienteNom : '');

                $items[] = [
                    'id'          => 'venta-' . $vr['id_detalle'],
                    'productId'   => (string)$vr['id_producto'],
                    'productName' => $vr['nombre_producto'] ?? ('Producto #' . $vr['id_producto']),
                    'delta'       => -$cant,
                    'before'      => $stockActual + $cant,
                    'after'       => $stockActual,
                    'reason'      => $motivo,
                    'createdAt'   => date('c', strtotime((string)$vr['fecha'])),
                ];
            }

            return ['status' => 200, 'body' => ['ok' => true, 'items' => $items]];
        }

        return ['status' => 400, 'body' => ['ok' => false, 'message' => 'Entidad desconocida.']];
    }

    /**
     * POST ?action=save
     * Crea o edita registros en `productos`, `empleados`/`administradores` o `clientes`.
     */
    public function guardar(array $body): array
    {
        $entity = (string)($body['entity'] ?? '');
        $values = $body['values'] ?? [];

        if (!is_array($values)) {
            return ['status' => 400, 'body' => ['ok' => false, 'message' => 'Datos inválidos.']];
        }

        $name = trim((string)($values['name'] ?? ''));
        if ($name === '') {
            return ['status' => 422, 'body' => ['ok' => false, 'message' => 'Escribe el nombre del registro.']];
        }

        if ($entity === 'products') {
            $id    = !empty($values['id']) ? (int)$values['id'] : 0;
            $price = isset($values['price']) && $values['price'] !== '' && $values['price'] !== null ? (float)$values['price'] : 0.0;
            $stock = isset($values['stock']) ? max(0, (int)$values['stock']) : 0;

            if ($id > 0) {
                $stmt = $this->db->prepare('UPDATE productos SET nombre = :nombre, precio = :precio WHERE id_producto = :id');
                $stmt->execute([
                    ':nombre' => $name,
                    ':precio' => $price,
                    ':id'     => $id,
                ]);
            } else {
                $stmt = $this->db->prepare('INSERT INTO productos (nombre, precio, stock) VALUES (:nombre, :precio, :stock)');
                $stmt->execute([
                    ':nombre' => $name,
                    ':precio' => $price,
                    ':stock'  => $stock,
                ]);
                $id = (int)$this->db->lastInsertId();

                if ($stock > 0) {
                    $stmtMov = $this->db->prepare('INSERT INTO movimientos_stock (id_producto, nombre_producto, delta, stock_antes, stock_despues, motivo) VALUES (:id, :nombre, :delta, 0, :despues, :motivo)');
                    $stmtMov->execute([
                        ':id'      => $id,
                        ':nombre'  => $name,
                        ':delta'   => $stock,
                        ':despues' => $stock,
                        ':motivo'  => 'Stock inicial registrado en sistema',
                    ]);
                }
            }

            return [
                'status' => 200,
                'body'   => [
                    'ok'   => true,
                    'item' => [
                        'id'       => (string)$id,
                        'name'     => $name,
                        'category' => $values['category'] ?? $this->detectarCategoria($name),
                        'sku'      => sprintf('DT-%03d', $id),
                        'price'    => $price,
                        'stock'    => $stock,
                        'minStock' => (int)($values['minStock'] ?? 15),
                        'status'   => $values['status'] ?? 'activo',
                    ],
                ],
            ];
        }

        if ($entity === 'employees') {
            $rawId = (string)($values['id'] ?? '');
            $email = trim((string)($values['email'] ?? ''));
            $role  = (string)($values['role'] ?? 'inventario');

            if ($email === '') {
                return ['status' => 422, 'body' => ['ok' => false, 'message' => 'El correo electrónico es obligatorio.']];
            }

            // Limpiar cargo entre parentesis si viene del listado de administradores
            $cleanName = trim((string)preg_replace('/\s*\([^)]*\)\s*$/', '', $name));
            $partes    = preg_split('/\s+/', $cleanName, 2);
            $nombre    = mb_substr($partes[0] ?? $cleanName, 0, 50);
            $apellido  = mb_substr($partes[1] ?? 'Equipo', 0, 50);

            if (str_starts_with($rawId, 'adm-') || ($rawId === '' && $role === 'administrador')) {
                $idAdmin = str_starts_with($rawId, 'adm-') ? (int)substr($rawId, 4) : 0;
                if ($idAdmin > 0) {
                    $stmt = $this->db->prepare('UPDATE administradores SET nombre = :nombre, apellido = :apellido, email = :email WHERE id_admin = :id');
                    $stmt->execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':id' => $idAdmin]);
                } else {
                    $stmt = $this->db->prepare('INSERT INTO administradores (nombre, apellido, email, cargo, contrasena) VALUES (:nombre, :apellido, :email, :cargo, :pass)');
                    $stmt->execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':cargo' => 'Administrador', ':pass' => 'admin_pass']);
                    $idAdmin = (int)$this->db->lastInsertId();
                }
                $finalId = 'adm-' . $idAdmin;
            } else {
                $idEmp = str_starts_with($rawId, 'emp-') ? (int)substr($rawId, 4) : (int)$rawId;
                if ($idEmp > 0) {
                    $stmt = $this->db->prepare('UPDATE empleados SET nombre = :nombre, apellido = :apellido, email = :email WHERE id_empleado = :id');
                    $stmt->execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':id' => $idEmp]);
                } else {
                    $stmt = $this->db->prepare('INSERT INTO empleados (nombre, apellido, email, contrasena) VALUES (:nombre, :apellido, :email, :pass)');
                    $stmt->execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':pass' => 'emp_pass']);
                    $idEmp = (int)$this->db->lastInsertId();
                }
                $finalId = 'emp-' . $idEmp;
            }

            return [
                'status' => 200,
                'body'   => [
                    'ok'   => true,
                    'item' => [
                        'id'     => $finalId,
                        'name'   => trim($nombre . ' ' . $apellido),
                        'email'  => $email,
                        'role'   => $role,
                        'status' => $values['status'] ?? 'activo',
                    ],
                ],
            ];
        }

        if ($entity === 'clients') {
            $id       = !empty($values['id']) ? (int)$values['id'] : 0;
            $email    = trim((string)($values['email'] ?? ''));
            $phone    = trim((string)($values['phone'] ?? ''));
            $partes   = preg_split('/\s+/', $name, 2);
            $nombre   = mb_substr($partes[0] ?? $name, 0, 50);
            $apellido = mb_substr($partes[1] ?? 'Cliente', 0, 50);

            if ($id > 0) {
                $stmt = $this->db->prepare('UPDATE clientes SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono WHERE id_cliente = :id');
                $stmt->execute([
                    ':nombre'   => $nombre,
                    ':apellido' => $apellido,
                    ':email'    => $email !== '' ? $email : ('cliente' . $id . '@dulcetentacion.local'),
                    ':telefono' => $phone !== '' ? $phone : null,
                    ':id'       => $id,
                ]);
            } else {
                $stmt = $this->db->prepare('INSERT INTO clientes (nombre, apellido, telefono, email, contrasena) VALUES (:nombre, :apellido, :telefono, :email, :pass)');
                $stmt->execute([
                    ':nombre'   => $nombre,
                    ':apellido' => $apellido,
                    ':telefono' => $phone !== '' ? $phone : null,
                    ':email'    => $email !== '' ? $email : ('cliente' . time() . '@dulcetentacion.local'),
                    ':pass'     => 'cliente_123',
                ]);
                $id = (int)$this->db->lastInsertId();
            }

            return [
                'status' => 200,
                'body'   => [
                    'ok'   => true,
                    'item' => [
                        'id'     => (string)$id,
                        'name'   => trim($nombre . ' ' . ($apellido === 'Cliente' && count($partes) < 2 ? '' : $apellido)),
                        'email'  => $email,
                        'phone'  => $phone,
                        'status' => $values['status'] ?? 'activo',
                    ],
                ],
            ];
        }

        return ['status' => 400, 'body' => ['ok' => false, 'message' => 'Entidad no editable.']];
    }

    /**
     * POST ?action=adjust_stock
     * Ajusta el stock en `productos` y registra la trazabilidad en `movimientos_stock`.
     */
    public function ajustarStock(array $body): array
    {
        $productId = (int)($body['productId'] ?? 0);
        $delta     = (int)($body['delta'] ?? 0);
        $reason    = trim((string)($body['reason'] ?? ''));

        if ($productId <= 0 || $delta === 0 || $reason === '') {
            return ['status' => 422, 'body' => ['ok' => false, 'message' => 'Indica el producto, una cantidad distinta de cero y el motivo del ajuste.']];
        }

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('SELECT id_producto, nombre, stock FROM productos WHERE id_producto = :id FOR UPDATE');
            $stmt->execute([':id' => $productId]);
            $prod = $stmt->fetch();

            if (!$prod) {
                $this->db->rollBack();
                return ['status' => 404, 'body' => ['ok' => false, 'message' => 'Producto no encontrado en la base de datos.']];
            }

            $before = (int)$prod['stock'];
            $after  = $before + $delta;
            if ($after < 0) {
                $this->db->rollBack();
                return ['status' => 422, 'body' => ['ok' => false, 'message' => 'El movimiento deja el stock en negativo (stock actual: ' . $before . ').']];
            }

            $stmtUpd = $this->db->prepare('UPDATE productos SET stock = :stock WHERE id_producto = :id');
            $stmtUpd->execute([':stock' => $after, ':id' => $productId]);

            $stmtMov = $this->db->prepare(
                'INSERT INTO movimientos_stock (id_producto, nombre_producto, delta, stock_antes, stock_despues, motivo)
                 VALUES (:id_prod, :nombre, :delta, :antes, :despues, :motivo)'
            );
            $stmtMov->execute([
                ':id_prod' => $productId,
                ':nombre'  => $prod['nombre'],
                ':delta'   => $delta,
                ':antes'   => $before,
                ':despues' => $after,
                ':motivo'  => mb_substr($reason, 0, 300),
            ]);
            $idMov = (int)$this->db->lastInsertId();

            $this->db->commit();

            return [
                'status' => 200,
                'body'   => [
                    'ok'       => true,
                    'movement' => [
                        'id'          => 'mov-' . $idMov,
                        'productId'   => (string)$productId,
                        'productName' => $prod['nombre'],
                        'delta'       => $delta,
                        'before'      => $before,
                        'after'       => $after,
                        'reason'      => $reason,
                        'createdAt'   => date('c'),
                    ],
                ],
            ];
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}

