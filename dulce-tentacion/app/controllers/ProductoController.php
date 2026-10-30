<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/ProductoModel.php';

/**
 * CAPA CONTROLADOR: ProductoController
 * Coordina las peticiones relacionadas con el catalogo de productos.
 */
class ProductoController
{
    private ProductoModel $model;

    public function __construct(?ProductoModel $model = null)
    {
        $this->model = $model ?? new ProductoModel();
    }

    /**
     * Accion GET ?action=productos
     */
    public function listar(): array
    {
        $productos = $this->model->obtenerTodos();
        return [
            'status' => 200,
            'body'   => [
                'ok'        => true,
                'productos' => $productos,
            ],
        ];
    }
}

