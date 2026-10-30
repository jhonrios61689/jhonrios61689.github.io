<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/VentaModel.php';

/**
 * CAPA CONTROLADOR: PedidoController
 * Recibe los datos del carrito del cliente y solicita al modelo registrar la venta.
 */
class PedidoController
{
    private VentaModel $model;

    public function __construct(?VentaModel $model = null)
    {
        $this->model = $model ?? new VentaModel();
    }

    /**
     * Accion POST ?action=pedido
     */
    public function crear(array $payload): array
    {
        $idCliente = isset($payload['id_cliente']) ? (int)$payload['id_cliente'] : 0;
        $address   = trim((string)($payload['address'] ?? ''));
        $items     = $payload['items'] ?? [];

        if ($idCliente <= 0 || !is_array($items) || empty($items)) {
            return [
                'status' => 400,
                'body'   => [
                    'ok'      => false,
                    'message' => 'Datos de cliente o productos incompletos para registrar la venta.',
                ],
            ];
        }

        $venta = $this->model->registrarVenta($idCliente, $address, $items);

        return [
            'status' => 200,
            'body'   => [
                'ok'       => true,
                'id_venta' => $venta['id_venta'],
                'total'    => $venta['total'],
            ],
        ];
    }
}

