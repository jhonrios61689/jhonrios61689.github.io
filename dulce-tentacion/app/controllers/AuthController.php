<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/ClienteModel.php';

/**
 * CAPA CONTROLADOR: AuthController
 * Gestiona el inicio de sesion y el registro de clientes.
 */
class AuthController
{
    private ClienteModel $model;

    public function __construct(?ClienteModel $model = null)
    {
        $this->model = $model ?? new ClienteModel();
    }

    /**
     * Accion POST ?action=login
     */
    public function login(array $payload): array
    {
        $email    = trim((string)($payload['email'] ?? ''));
        $password = (string)($payload['password'] ?? '');

        if ($email === '' || $password === '') {
            return [
                'status' => 400,
                'body'   => [
                    'ok'      => false,
                    'message' => 'Ingresa tu correo electrónico y contraseña.',
                ],
            ];
        }

        $resultado = $this->model->autenticar($email, $password);

        if ($resultado === null) {
            return [
                'status' => 404,
                'body'   => [
                    'ok'      => false,
                    'message' => 'No encontramos una cuenta con este correo en la base de datos. ¡Regístrate en la pestaña Crear cuenta!',
                ],
            ];
        }

        if (isset($resultado['error']) && $resultado['error'] === 'password_mismatch') {
            return [
                'status' => 401,
                'body'   => [
                    'ok'      => false,
                    'message' => 'La contraseña ingresada no coincide. Verifica e inténtalo de nuevo.',
                ],
            ];
        }

        return [
            'status' => 200,
            'body'   => [
                'ok'   => true,
                'user' => $resultado,
            ],
        ];
    }

    /**
     * Accion POST ?action=register
     */
    public function register(array $payload): array
    {
        $name     = trim((string)($payload['name'] ?? ''));
        $email    = trim((string)($payload['email'] ?? ''));
        $phone    = trim((string)($payload['phone'] ?? ''));
        $address  = trim((string)($payload['address'] ?? ''));
        $password = (string)($payload['password'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            return [
                'status' => 400,
                'body'   => [
                    'ok'      => false,
                    'message' => 'Completa tu nombre, correo electrónico y contraseña.',
                ],
            ];
        }

        $usuario = $this->model->registrarOActualizar($name, $email, $phone, $address, $password);

        return [
            'status' => 200,
            'body'   => [
                'ok'   => true,
                'user' => $usuario,
            ],
        ];
    }
}

