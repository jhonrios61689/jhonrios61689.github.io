<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/ProductoModel.php';
require_once __DIR__ . '/ClienteModel.php';

/**
 * CAPA MODELO: VentaModel
 * Gestiona el registro transaccional de pedidos en `ventas` y `detalles_venta`.
 */
class VentaModel
{
    private PDO $db;
    private ProductoModel $productoModel;
    private ClienteModel $clienteModel;

    public function __construct(?PDO $db = null)
    {
        $this->db            = $db ?? Database::getConnection();
        $this->productoModel = new ProductoModel($this->db);
        $this->clienteModel  = new ClienteModel($this->db);
    }

    /**
     * Registra una venta completa junto con sus detalles y actualiza el stock de productos.
     */
    public function registrarVenta(int $idCliente, string $direccion, array $items): array
    {
        $this->db->beginTransaction();

        try {
            if ($direccion !== '') {
                $this->clienteModel->actualizarDireccion($idCliente, $direccion);
            }

            $empRow     = $this->db->query('SELECT id_empleado FROM empleados ORDER BY id_empleado ASC LIMIT 1')->fetch();
            $idEmpleado = $empRow ? (int)$empRow['id_empleado'] : null;

            $totalVenta    = 0.0;
            $lineasValidas = [];

            foreach ($items as $item) {
                $cantidad = max(1, (int)($item['cantidad'] ?? 1));
                $prodRow  = null;

                if (!empty($item['id_producto'])) {
                    $prodRow = $this->productoModel->buscarPorId((int)$item['id_producto']);
                }
                if (!$prodRow && !empty($item['nombre'])) {
                    $prodRow = $this->productoModel->buscarPorNombre((string)$item['nombre']);
                }

                if ($prodRow) {
                    $precioUnit  = (float)$prodRow['precio'];
                    $totalVenta += $precioUnit * $cantidad;
                    $lineasValidas[] = [
                        'id_producto'     => (int)$prodRow['id_producto'],
                        'cantidad'        => $cantidad,
                        'precio_unitario' => $precioUnit,
                    ];
                }
            }

            $stmtVenta = $this->db->prepare(
                'INSERT INTO ventas (id_empleado, id_cliente, total) VALUES (:id_empleado, :id_cliente, :total)'
            );
            $stmtVenta->execute([
                ':id_empleado' => $idEmpleado,
                ':id_cliente'  => $idCliente,
                ':total'       => $totalVenta,
            ]);
            $idVenta = (int)$this->db->lastInsertId();

            $stmtDetalle = $this->db->prepare(
                'INSERT INTO detalles_venta (id_venta, id_producto, cantidad, precio_unitario)
                 VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario)'
            );

            foreach ($lineasValidas as $linea) {
                $stmtDetalle->execute([
                    ':id_venta'        => $idVenta,
                    ':id_producto'     => $linea['id_producto'],
                    ':cantidad'        => $linea['cantidad'],
                    ':precio_unitario' => $linea['precio_unitario'],
                ]);
                $this->productoModel->descontarStock($linea['id_producto'], $linea['cantidad']);
            }

            $this->db->commit();

            return [
                'id_venta' => $idVenta,
                'total'    => $totalVenta,
            ];
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}

