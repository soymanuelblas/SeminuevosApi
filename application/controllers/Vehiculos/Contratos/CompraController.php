<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CompraController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehiculos/Contratos/CompraModel');
        $this->load->helper('verifyauthtoken_helper');
        
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Max-Age: 3600');
            exit(0);
        }
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Credentials: true');
    }

    function validate() {
        $headerToken = $this->input->get_request_header('Authorization', TRUE);
        if (empty($headerToken)) {
            echo json_encode([
                'error' => 'Token no proporcionado',
                'status' => 'error'
            ]);
            exit;
        }

        $splitToken = explode(' ', $headerToken);
        if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
            echo json_encode([
                'error' => 'Formato de token inválido',
                'status' => 'error',
            ]);
            exit;
        }
        $token = $splitToken[1];

        // Validar token
        $valid = verifyAuthToken($token);
        if (!$valid || !is_string($valid) || !json_decode($valid)) {
            echo json_encode([
                'error' => 'Token inválido o mal formado',
                'status' => 'error']);
            exit;
        }

        return $valid;
    }

    public function listCompras() {
        try {
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = $info->data->sitio_id;

            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            $vehiculo_id = $this->input->get_post('vehiculo_id') ?: (isset($jsonData['vehiculo_id']) ? $jsonData['vehiculo_id'] : null);

            $result = $this->CompraModel->listarCompras($vehiculo_id, $sitio_id);

            if (empty($result)) {
                echo json_encode([
                    'error' => 'No se encontraron compras',
                    'status' => 'error'
                ]);
                return;
            }

            $result_pagos = $this->CompraModel->listarPagos($vehiculo_id, $sitio_id);

            if ($result && $result_pagos) {
                echo json_encode([
                    'data' => $result,
                    'pagos' => $result_pagos,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'No se encontraron compras',
                    'status' => 'error'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al listar las compras: '. $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function addCompra() {
        try {
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = $info->data->sitio_id;
            $usuario_id = $info->data->id;

            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            // Validar campos requeridos
            $requiredFields = [
                'vehiculo_id', 'clientecompra_id', 'precio', 'km', 
                'fechaventa', 'testigo1', 'testigo2', 'statusventa', 'formas_pago'
            ];
            
            foreach ($requiredFields as $field) {
                if (!isset($jsonData[$field])) {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(400)
                        ->set_output(json_encode([
                            'error' => "El campo {$field} es requerido",
                            'status' => 'error'
                        ]));
                    return;
                }
            }

            // Procesar fecha
            $fecha1 = DateTime::createFromFormat('d-m-Y H:i:s', $jsonData['fechaventa']);
            if (!$fecha1) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode([
                        'error' => 'Formato de fecha inválido. Use dd-mm-YYYY HH:MM:SS',
                        'status' => 'error'
                    ]));
                return;
            }

            // Validar factura y tenencia
            $validar_factura_tenencia = $this->CompraModel->validarFacturaYTenencia($jsonData['vehiculo_id']);
            if (!$validar_factura_tenencia) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode([
                        'error' => 'Agrega la factura y tenencia antes de registrar la compra',
                        'status' => 'error'
                    ]));
                return;
            }

            // Obtener corte de caja
            $cortecaja = $this->CompraModel->obtenerCorteId($sitio_id);
            $serie = $cortecaja->serie;
            $numero = $cortecaja->id;

            // Preparar datos para la compra
            $dataCompra = [
                'tipo_operacion' => 11, // Código para compra
                'sitio_id' => $sitio_id,
                'vehiculo_id' => $jsonData['vehiculo_id'],
                'clientecompra_id' => $jsonData['clientecompra_id'],
                'clienteventa_id' => $this->CompraModel->obtenerClienteVentaDefault($sitio_id),
                'importe' => str_replace(",", "", $jsonData['precio']),
                'fecha' => $fecha1->format('Y-m-d H:i:s'),
                'fecha_entrega' => date('Y-m-d H:i:s'),
                'tipostatus_id' => $jsonData['statusventa'],
                'usuario_id' => $usuario_id,
                'corte_id' => $serie.$numero,
                'adicional_id' => 0,
                'fecha_entrega' => $fecha1->format('Y-m-d H:i:s')
            ];

            // Datos para operacion_auto
            $data_operacion_auto = [
                'kilometraje' => str_replace(",", "", $jsonData['km']),
                'testigo1' => strtoupper($jsonData['testigo1']),
                'testigo2' => strtoupper($jsonData['testigo2']),
                'sitio_id' => $sitio_id
            ];

            // Insertar compra
            $result = $this->CompraModel->agregarCompra($dataCompra, $data_operacion_auto, $jsonData['formas_pago']);

            if ($result) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode([
                        'data' => [
                            'message' => 'Compra registrada correctamente',
                            'operacion_id' => $result['operacion_id'],
                        ],
                        'status' => 'success'
                    ]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode([
                        'error' => 'Error al registrar la compra',
                        'status' => 'error'
                    ]));
            }
        } catch (Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'error' => 'Error al registrar la compra: ' . $e->getMessage(),
                    'status' => 'error'
                ]));
        }
    }

    public function updateCompra() {
        try {
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = $info->data->sitio_id;
            $usuario_id = $info->data->id;

            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            // Validar campos requeridos
            $requiredFields = [
                'id_operacion', 'vehiculo_id', 'clientecompra_id', 'precio', 'km',
                'testigo1', 'testigo2', 'statusventa', 'formas_pago'
            ];
            
            foreach ($requiredFields as $field) {
                if (!isset($jsonData[$field])) {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(400)
                        ->set_output(json_encode([
                            'error' => "El campo {$field} es requerido",
                            'status' => 'error'
                        ]));
                    return;
                }
            }

            // Validar que la operación exista y pertenezca al sitio
            $operacion_existente = $this->CompraModel->obtenerOperacion($jsonData['id_operacion'], $sitio_id);
            if (!$operacion_existente) {
                echo json_encode([
                    'error' => 'La operación no existe o no pertenece a este sitio',
                    'status' => 'error'
                ]);
                return;
            }

            // Validar factura y tenencia
            $validar_factura_tenencia = $this->CompraModel->validarFacturaYTenencia($jsonData['vehiculo_id']);
            if (!$validar_factura_tenencia) {
                echo json_encode([
                    'error' => 'Agrega la factura y tenencia antes de actualizar la compra',
                    'status' => 'error'
                ]);
                return;
            }

            // Procesar fecha
            $fecha1 = DateTime::createFromFormat('d-m-Y H:i:s', $jsonData['fechaventa']);
            if (!$fecha1) {
                echo json_encode([
                    'error' => 'Formato de fecha inválido. Use dd-mm-YYYY HH:MM:SS',
                    'status' => 'error'
                ]);
                return;
            }

            // Preparar datos para la actualización
            $dataCompra = [
                'vehiculo_id' => $jsonData['vehiculo_id'],
                'clientecompra_id' => $jsonData['clientecompra_id'],
                'importe' => $jsonData['precio'],
                'fecha' => $fecha1->format('Y-m-d H:i:s'),
                'fecha_entrega' => $fecha1->format('Y-m-d H:i:s'),
                'tipostatus_id' => $jsonData['statusventa'],
                'usuario_id' => $usuario_id
            ];

            // Datos para operacion_auto
            $data_operacion_auto = [
                'kilometraje' => str_replace(",", "", $jsonData['km']),
                'testigo1' => strtoupper($jsonData['testigo1']),
                'testigo2' => strtoupper($jsonData['testigo2'])
            ];

            // Actualizar compra
            $result = $this->CompraModel->actualizarCompra(
                $jsonData['id_operacion'],
                $sitio_id,
                $dataCompra,
                $data_operacion_auto,
                $jsonData['formas_pago']
            );

            if ($result) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode([
                        'data' => [
                            'message' => 'Compra actualizada correctamente',
                            'operacion_id' => $jsonData['id_operacion']
                        ],
                        'status' => 'success'
                    ]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode([
                        'error' => 'Error al actualizar la compra',
                        'status' => 'error'
                    ]));
            }
        } catch (Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode([
                    'error' => 'Error al actualizar la compra: ' . $e->getMessage(),
                    'status' => 'error'
                ]));
        }
    }
}