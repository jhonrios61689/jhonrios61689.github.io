<?php
declare(strict_types=1);

/**
 * FRONT CONTROLLER DE LA API (Enrutador MVC)
 * Recibe las peticiones HTTP y las despacha al Controlador correspondiente.
 */
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/controllers/ProductoController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/PedidoController.php';

function responder(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

$action = $_GET['action'] ?? '';
$raw    = file_get_contents('php://input');
$body   = json_decode($raw ?: '{}', true);
if (!is_array($body)) {
    $body = $_POST;
}

try {
    switch ($action) {
        case 'productos':
            $controller = new ProductoController();
            $res        = $controller->listar();
            responder($res['body'], $res['status']);

        case 'login':
            $controller = new AuthController();
            $res        = $controller->login($body);
            responder($res['body'], $res['status']);

        case 'register':
            $controller = new AuthController();
            $res        = $controller->register($body);
            responder($res['body'], $res['status']);

        case 'pedido':
            $controller = new PedidoController();
            $res        = $controller->crear($body);
            responder($res['body'], $res['status']);

        default:
            responder(['ok' => false, 'message' => 'Acción no reconocida.'], 400);
    }
} catch (Throwable $e) {
    responder(['ok' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()], 500);
}
