<?php
declare(strict_types=1);

/**
 * Configuracion y Conexion PDO (Singleton) a la base de datos MySQL.
 * Base de datos: sistema_dulcetentacion
 */
class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance instanceof PDO) {
            return self::$instance;
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
                self::$instance = new PDO($dsn, $user, $pass, $options);
                return self::$instance;
            } catch (PDOException $e) {
                $lastException = $e;
            }
        }

        throw $lastException ?? new PDOException('No se pudo conectar a la base de datos sistema_dulcetentacion.');
    }
}

