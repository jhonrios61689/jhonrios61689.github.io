<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/config/Database.php';

/**
 * Funcion auxiliar de compatibilidad que delega en Database::getConnection().
 */
function obtenerConexion(): PDO
{
    return Database::getConnection();
}
