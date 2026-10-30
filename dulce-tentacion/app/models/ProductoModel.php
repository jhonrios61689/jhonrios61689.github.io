<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

/**
 * CAPA MODELO: ProductoModel
 * Gestiona las consultas y actualizaciones sobre la tabla `productos`.
 */
class ProductoModel
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
    }

    /**
     * Asegura que los productos base del catalogo existan y devuelve todos los productos.
     */
    public function obtenerTodos(): array
    {
        $this->asegurarProductosBase();

        $sql = 'SELECT id_producto, nombre, precio, stock FROM productos ORDER BY id_producto ASC';
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Busca un producto por su ID primario.
     */
    public function buscarPorId(int $idProducto): ?array
    {
        $stmt = $this->db->prepare('SELECT id_producto, nombre, precio, stock FROM productos WHERE id_producto = :id LIMIT 1');
        $stmt->execute([':id' => $idProducto]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Busca un producto por su nombre (insensible a mayusculas/minusculas).
     */
    public function buscarPorNombre(string $nombre): ?array
    {
        $stmt = $this->db->prepare('SELECT id_producto, nombre, precio, stock FROM productos WHERE LOWER(nombre) = LOWER(:nombre) LIMIT 1');
        $stmt->execute([':nombre' => $nombre]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Descuenta unidades del stock disponible de un producto.
     */
    public function descontarStock(int $idProducto, int $cantidad): void
    {
        $stmt = $this->db->prepare('UPDATE productos SET stock = GREATEST(0, stock - :cantidad) WHERE id_producto = :id_producto');
        $stmt->execute([
            ':cantidad'    => $cantidad,
            ':id_producto' => $idProducto,
        ]);
    }

    /**
     * Inserta los productos iniciales del catalogo si aun no estan registrados en la tabla.
     */
    private function asegurarProductosBase(): void
    {
        $faltantes = [
            ['nombre' => 'Cupcake de fresa', 'precio' => 6500.00, 'stock' => 40],
            ['nombre' => 'Postre en vaso',   'precio' => 11000.00, 'stock' => 30],
        ];

        $stmtCheck  = $this->db->prepare('SELECT id_producto FROM productos WHERE LOWER(nombre) = LOWER(:nombre) LIMIT 1');
        $stmtInsert = $this->db->prepare('INSERT INTO productos (nombre, precio, stock) VALUES (:nombre, :precio, :stock)');

        foreach ($faltantes as $item) {
            $stmtCheck->execute([':nombre' => $item['nombre']]);
            if (!$stmtCheck->fetch()) {
                $stmtInsert->execute([
                    ':nombre' => $item['nombre'],
                    ':precio' => $item['precio'],
                    ':stock'  => $item['stock'],
                ]);
            }
        }
    }
}

