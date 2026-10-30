<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

/**
 * CAPA MODELO: ClienteModel
 * Gestiona la autenticacion, registro y actualizacion sobre la tabla `clientes`.
 */
class ClienteModel
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
    }

    /**
     * Busca un cliente por correo electronico.
     */
    public function buscarPorEmail(string $email): ?array
    {
        $sql = 'SELECT id_cliente, nombre, apellido, telefono, email, direccion, contrasena
                FROM clientes
                WHERE LOWER(email) = LOWER(:email)
                LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Valida las credenciales de un cliente y retorna sus datos formateados para la sesion.
     */
    public function autenticar(string $email, string $password): ?array
    {
        $cliente = $this->buscarPorEmail($email);
        if (!$cliente) {
            return null;
        }

        $hashGuardado = (string)$cliente['contrasena'];
        $valida = hash_equals($hashGuardado, $password) || password_verify($password, $hashGuardado);
        if (!$valida) {
            return ['error' => 'password_mismatch'];
        }

        return $this->formatearCliente($cliente);
    }

    /**
     * Registra un nuevo cliente o actualiza uno existente con el mismo correo.
     */
    public function registrarOActualizar(string $name, string $email, string $phone, string $address, string $password): array
    {
        $partes   = preg_split('/\s+/', trim($name), 2);
        $nombre   = mb_substr($partes[0] ?? $name, 0, 50);
        $apellido = mb_substr($partes[1] ?? 'Cliente', 0, 50);

        $existente = $this->buscarPorEmail($email);

        if ($existente) {
            $idCliente = (int)$existente['id_cliente'];
            $sql = 'UPDATE clientes
                    SET nombre = :nombre,
                        apellido = :apellido,
                        telefono = :telefono,
                        direccion = COALESCE(NULLIF(:direccion, ""), direccion),
                        contrasena = :contrasena
                    WHERE id_cliente = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nombre'     => $nombre,
                ':apellido'   => $apellido,
                ':telefono'   => $phone !== '' ? $phone : null,
                ':direccion'  => $address,
                ':contrasena' => $password,
                ':id'         => $idCliente,
            ]);
        } else {
            $sql = 'INSERT INTO clientes (nombre, apellido, telefono, email, direccion, contrasena)
                    VALUES (:nombre, :apellido, :telefono, :email, :direccion, :contrasena)';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nombre'     => $nombre,
                ':apellido'   => $apellido,
                ':telefono'   => $phone !== '' ? $phone : null,
                ':email'      => $email,
                ':direccion'  => $address !== '' ? $address : null,
                ':contrasena' => $password,
            ]);
            $idCliente = (int)$this->db->lastInsertId();
        }

        $nombreVisible = trim($nombre . ' ' . ($apellido === 'Cliente' && count($partes) < 2 ? '' : $apellido));

        return [
            'id_cliente' => $idCliente,
            'name'       => $nombreVisible,
            'nombre'     => $nombre,
            'apellido'   => $apellido,
            'email'      => $email,
            'phone'      => $phone,
            'address'    => $address,
        ];
    }

    /**
     * Actualiza la direccion de entrega del cliente.
     */
    public function actualizarDireccion(int $idCliente, string $direccion): void
    {
        if ($direccion === '') {
            return;
        }
        $stmt = $this->db->prepare('UPDATE clientes SET direccion = :direccion WHERE id_cliente = :id');
        $stmt->execute([
            ':direccion' => mb_substr($direccion, 0, 150),
            ':id'        => $idCliente,
        ]);
    }

    private function formatearCliente(array $cliente): array
    {
        $apellido = $cliente['apellido'] === 'Cliente' ? '' : $cliente['apellido'];
        return [
            'id_cliente' => (int)$cliente['id_cliente'],
            'name'       => trim($cliente['nombre'] . ' ' . $apellido),
            'nombre'     => $cliente['nombre'],
            'apellido'   => $cliente['apellido'],
            'email'      => $cliente['email'],
            'phone'      => $cliente['telefono'] ?? '',
            'address'    => $cliente['direccion'] ?? '',
        ];
    }
}

