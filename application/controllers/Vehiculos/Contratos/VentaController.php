<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VentaController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehiculos/Contratos/VentaModel');
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

    public function listClientes() {
        try {
            $valid = $this->validate();

            $info = json_decode($valid);
            $sitio_id = $info->data->sitio_id;

            $result = $this->VentaModel->listarClientes($sitio_id);

            if (empty($result)) {
                echo json_encode([
                    'error' => 'No se encontraron clientes',
                    'status' => 'error'
                ]);
                return;
            }
            echo json_encode([
                'data' => $result,
                'status' => 'success'
            ]);
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al mostrar los clientes',
                'status' => 'error'
            ]);
        }
    }

    public function listFormasPago() {
        try {
            $valid = $this->validate();

            $result = $this->VentaModel->listarFormasPago();

            if (empty($result)) {
                echo json_encode([
                    'error' => 'No se encontraron formas de pago',
                    'status' => 'error'
                ]);
                return;
            }
            echo json_encode([
                'data' => $result,
                'status' => 'success'
            ]);
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al mostrar las formas de pago',
                'status' => 'error'
            ]);
        }
    }

    public function listVentas() {
        try {
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = $info->data->sitio_id;

            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            $vehiculo_id = $this->input->get_post('vehiculo_id') ?: (isset($jsonData['vehiculo_id']) ? $jsonData['vehiculo_id'] : null);

            $result = $this->VentaModel->listarVentas($vehiculo_id, $sitio_id);

            if (empty($result)) {
                echo json_encode([
                    'error' => 'No se encontraron ventas',
                    'status' => 'error'
                ]);
                return;
            }

            $result_pagos = $this->VentaModel->listarPagos($vehiculo_id, $sitio_id);

            if ($result) {
                echo json_encode([
                    'data' => $result,
                    'pagos' => $result_pagos,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'No se encontraron ventas',
                    'status' => 'error'
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al listar las ventas'. $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function addVenta() {
        try {
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = $info->data->sitio_id;

            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            
            $filter_field = [
                'vehiculo_id', 'cliente_compra', 'cliente_vende', 'importe', 'kilometraje',
                'fecha_entrega', 'testigo1', 'testigo2', 'formas_pago'
            ];
            
            foreach ($filter_field as $field) {
                if (!isset($jsonData[$field])) {
                    echo json_encode([
                        'error' => 'Campo faltante: ' . $field,
                        'status' => 'error'
                    ]);
                    return;
                }
            }
            
            $validar_factura_tenencia = $this->VentaModel->validarFacturaYTenencia($jsonData['vehiculo_id']);

            if(!$validar_factura_tenencia) {
                echo json_encode([
                    'error' => 'Agrega la factura y tenencia antes de agregar la venta',
                    'status' => 'error'
                ]);
                return;
            }

            $validar_vin = $this->VentaModel->validarVinVehiculo($jsonData['vehiculo_id'], $sitio_id);

            if(!$validar_vin) {
                echo json_encode([
                    'error' => 'El número de serie del vehículo no es válido',
                    'status' => 'error'
                ]);
                return;
            }

            $cortecaja = $this->VentaModel->obtenerCorteId($sitio_id);
            log_message('debug', 'Corte de caja: ' . json_encode($cortecaja));            
            
            $serie = $cortecaja->serie;
            $numero = $cortecaja->id;

            $data = [
                'vehiculo_id' => $jsonData['vehiculo_id'],
                'tipo_operacion' => 3,
                'clientecompra_id' => $jsonData['cliente_compra'],
                'clienteventa_id' => $jsonData['cliente_vende'],
                'importe' => $jsonData['importe'],
                'fecha_entrega' => $jsonData['fecha_entrega'],
                'fecha' => date('Y-m-d H:i:s'),
                'sitio_id' => $sitio_id,
                'tipostatus_id' => 5022,
                'usuario_id' => $info->data->id,
                'corte_id' => $serie.$numero,
                'adicional_id' => 0,
            ];

            $data_operacion_auto = [
                'kilometraje' => $jsonData['kilometraje'],
                'testigo1' => strtoupper($jsonData['testigo1']),
                'testigo2' => strtoupper($jsonData['testigo2']),
                'sitio_id' => $sitio_id
            ];

            $formas_pago = $jsonData['formas_pago'];

            $result = $this->VentaModel->agregarVenta($data, $data_operacion_auto, $formas_pago);

            if ($result) {
                echo json_encode([
                    'data' => 'Venta concretada correctamente',
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al agregar la venta',
                    'status' => 'error'
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la venta: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function updateVenta() {
        try {
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = $info->data->sitio_id;
            $usuario_id = $info->data->id;
    
            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            
            $filter_field = [
                'id_operacion', 'vehiculo_id', 'cliente_compra', 'cliente_vende', 'importe', 
                'kilometraje', 'fecha_entrega', 'testigo1', 'testigo2', 'formas_pago'
            ];
            
            foreach ($filter_field as $field) {
                if (!isset($jsonData[$field])) {
                    echo json_encode([
                        'error' => 'Campo faltante: ' . $field,
                        'status' => 'error'
                    ]);
                    return;
                }
            }
    
            // Validar que la operación exista y pertenezca al sitio
            $operacion_existente = $this->VentaModel->obtenerOperacion($jsonData['id_operacion'], $sitio_id);
            if (!$operacion_existente) {
                echo json_encode([
                    'error' => 'La operación no existe o no pertenece a este sitio',
                    'status' => 'error'
                ]);
                return;
            }
    
            // Validaciones adicionales
            $validar_factura_tenencia = $this->VentaModel->validarFacturaYTenencia($jsonData['vehiculo_id']);
            if (!$validar_factura_tenencia) {
                echo json_encode([
                    'error' => 'Agrega la factura y tenencia antes de actualizar la venta',
                    'status' => 'error'
                ]);
                return;
            }
    
            $validar_vin = $this->VentaModel->validarVinVehiculo($jsonData['vehiculo_id'], $sitio_id);
            if (!$validar_vin) {
                echo json_encode([
                    'error' => 'El número de serie del vehículo no es válido',
                    'status' => 'error'
                ]);
                return;
            }
    
            // Validar que la suma de los pagos coincida con el importe
            $suma_pagos = array_reduce($jsonData['formas_pago'], function($carry, $item) {
                return $carry + $item['importe'];
            }, 0);
            
            if ($suma_pagos != $jsonData['importe']) {
                echo json_encode([
                    'error' => 'La suma de los métodos de pago no coincide con el importe total',
                    'status' => 'error'
                ]);
                return;
            }
    
            // Preparar datos para actualización
            $data_operacion = [
                'vehiculo_id' => $jsonData['vehiculo_id'],
                'clientecompra_id' => $jsonData['cliente_compra'],
                'clienteventa_id' => $jsonData['cliente_vende'],
                'importe' => $jsonData['importe'],
                'fecha_entrega' => $jsonData['fecha_entrega'],
                'tipostatus_id' => 5022, // Estado de venta
                'usuario_id' => $usuario_id
            ];
    
            $data_operacion_auto = [
                'kilometraje' => $jsonData['kilometraje'],
                'testigo1' => strtoupper($jsonData['testigo1']),
                'testigo2' => strtoupper($jsonData['testigo2'])
            ];
    
            // Actualizar la venta
            $result = $this->VentaModel->actualizarVenta(
                $jsonData['id_operacion'],
                $sitio_id,
                $data_operacion,
                $data_operacion_auto,
                $jsonData['formas_pago']
            );
            
            if ($result) {
                echo json_encode([
                    'data' => [
                        'message' => 'Venta actualizada correctamente',
                        'operacion_id' => $jsonData['id_operacion']
                    ],
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al actualizar la venta',
                    'status' => 'error'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al actualizar la venta: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

}