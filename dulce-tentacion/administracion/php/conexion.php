<?php
declare(strict_types=1);

/**
 * Conexion PDO independiente para el Portal de Administracion y Empleados.
 * Base de datos: sistema_dulcetentacion
 */
function obtenerConexionAdmin(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = '127.0.0.1';
    $port = 3306;
    $dbname = 'sistema_dulcetentacion';
    $user = 'root';
    $passwords = ['root', ''];

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $lastException = null;
    foreach ($passwords as $pass) {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (PDOException $e) {
            $lastException = $e;
        }
    }

    throw $lastException ?? new PDOException('No se pudo conectar a la base de datos sistema_dulcetentacion.');
}

