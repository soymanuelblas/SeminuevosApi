<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CompraController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehiculos/Contratos/CompraModel');
        $this->load->helper('verifyauthtoken_helper');
        $this->load->library('form_validation');
        
        // Configuración de CORS
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Max-Age: 3600');
            exit(0);
        }
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Credentials: true');
    }

    /**
     * Validar token de autenticación
     */
    private function validate() {
        $headerToken = $this->input->get_request_header('Authorization', TRUE);
        if (empty($headerToken)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode([
                    'error' => 'Token no proporcionado',
                    'status' => 'error'
                ]));
            exit;
        }

        $splitToken = explode(' ', $headerToken);
        if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode([
                    'error' => 'Formato de token inválido',
                    'status' => 'error',
                ]));
            exit;
        }
        $token = $splitToken[1];

        // Validar token
        $valid = verifyAuthToken($token);
        if (!$valid || !is_string($valid) || !json_decode($valid)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']));
            exit;
        }

        return json_decode($valid);
    }

    /**
     * Listar compras
     */
    public function listCompras() {
        try {
            $valid = $this->validate();
            $sitio_id = $valid->data->sitio_id ?? 0;

            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            $vehiculo_id = $this->input->get_post('vehiculo_id') ?: (isset($jsonData['vehiculo_id']) ? $jsonData['vehiculo_id'] : null);

            if (!$vehiculo_id) {
                echo json_encode([
                    'error' => 'El parámetro vehiculo_id es requerido',
                    'status' => 'error'
                ]);
                return;
            }

            $result = $this->CompraModel->listarCompras($sitio_id);

            if (empty($result)) {
                json_encode([
                    'error' => 'No se encontraron compras',
                    'status' => 'error'
                ]);
            }

            $result_pagos = $this->CompraModel->listarPagos($vehiculo_id, $sitio_id);

            if($result && $result_pagos) {
                echo json_encode([
                    'data' => $result,
                    'pagos' => $result_pagos,
                    'status' => 'success'
                ]);
            }else {
                echo json_encode([
                    'error' => 'Error al listar las compras',
                    'status' => 'error'
                ]);
            }
        } catch (Exception $e) {
            json_encode([
                'error' => 'Error al listar las compras',
                'status' => 'error'
            ]);
        }
    }

    /**
     * Obtener detalles de una compra
     */
    public function listCompra() {
        try {
            $valid = $this->validate();
            $sitio_id = $valid->data->sitio_id ?? 0;

            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            $id = $this->input->get_post('id') ?: (isset($jsonData['id']) ? $jsonData['id'] : null);

            if(!$id) {
                echo json_encode([
                    'error' => 'El parámetro id es requerido',
                    'status' => 'error'
                ]);
                return;
            }

            $compra = $this->CompraModel->obtenerCompra($id, $sitio_id);

            if (!$compra) {
                echo json_encode([
                        'error' => 'Compra no encontrada',
                        'status' => 'error'
                    ]);
                return;
            }

            echo json_encode([
                'data' => $compra,
                'status' => 'success'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al obtener la compra',
                'status' => 'error'
            ]);
        }
    }

    /**
     * Crear una nueva compra
     */
    public function addCompra() {
        try {
            $valid = $this->validate();
            $sitio_id = $valid->data->sitio_id;
            $usuario_id = $valid->data->id;

            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            foreach ($jsonData as $key => $value) {
                if (in_array($key, ['vehiculo_id', 'clientecompra_id', 'precio', 'km', 'fechaventa', 'testigo1', 'testigo2', 'statusventa'])) {
                    echo json_encode([
                        'error' => "El campo {$key} es requerido",
                        'status' => 'error'
                    ]);
                }
            }

            // Procesar fecha
            $fecha1 = DateTime::createFromFormat('d-m-Y H:i:s', $input['fechaventa']);
            if (!$fecha1) {
                echo json_encode([
                    'error' => 'Formato de fecha inválido. Use dd-mm-YYYY HH:MM:SS',
                    'status' => 'error'
                ]);
                return;
            }

            // Preparar datos para la compra
            $dataCompra = [
                'tipo_operacion' => 11, // Código para compra
                'sitio_id' => $sitio_id,
                'vehiculo_id' => $jsonData['vehiculo_id'],
                'clientecompra_id' => $jsonData['clientecompra_id'],
                'clienteventa_id' => $jsonData['clienteventa_id'],
                'precio' => str_replace(",", "", $jsonData['precio']),
                'km' => str_replace(",", "", $jsonData['km']),
                'fechaventa' => $fecha1->format('Y-m-d H:i:s'),
                'testigo1' => $jsonData['testigo1'],
                'testigo2' => $jsonData['testigo2'],
                'statusventa' => $jsonData['statusventa'],
                'usuario_id' => $usuario_id,
            ];

            // Insertar compra
            $result = $this->CompraModel->agregarCompra($dataCompra, $formasPago, $sitio_id);

            if ($result) {
                echo json_encode([
                    'data' => [
                        'message' => 'Compra registrada correctamente',
                        'operacion_id' => $result['operacion_id'],
                    ],
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al registrar la compra',
                    'status' => 'error'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al registrar la compra: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    /**
     * Actualizar una compra existente
     */
    public function updateCompra() {
        try {
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = $info->data->sitio_id;

            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            // Validar datos (similar a store)
            $filter_data = [
                'vehiculo_id', 
                'clienteventa_id', 
                'importe',
                'kilometraje',
                'fecha_entrega',
                'testigo1',
                'testigo2',
            ];

            foreach ($filter_data as $key) {
                if (!isset($input[$key])) {
                    echo json_encode([
                        'error' => "El campo {$key} es requerido",
                        'status' => 'error'
                    ]);
                    return;
                }
            }

            $data = [
                'vehiculo_id' => $jsonData['vehiculo_id'],
                'clienteventa_id' => $jsonData['clienteventa_id'],
                'importe' => str_replace(",", "", $jsonData['importe']),
                'kilometraje' => str_replace(",", "", $jsonData['kilometraje']),
                'fecha_entrega' => $jsonData['fecha_entrega'],
                'testigo1' => $jsonData['testigo1'],
                'testigo2' => $jsonData['testigo2'],
            ];

            // Actualizar compra
            $result = $this->CompraModel->actualizarCompra($id, $data, $sitio_id);

            if ($result) {
                echo json_encode([
                    'data' => 'Compra actualizada correctamente',
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al actualizar la compra',
                    'status' => 'error'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al actualizar la compra: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
}