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
            
            $validar_factura_tenencia = $this->validarFacturaYTenencia($jsonData['vehiculo_id']);

            if(!$validar_factura_tenencia) {
                echo json_encode([
                    'error' => 'Agrega la factura y tenencia antes de agregar la venta',
                    'status' => 'error'
                ]);
                return;
            }

            $validar_vin = $this->validarVinVehiculo($jsonData['vehiculo_id'], $sitio_id);

            if(!$validar_vin) {
                echo json_encode([
                    'error' => 'El número de serie del vehículo no es válido',
                    'status' => 'error'
                ]);
                return;
            }

            $filter_field = [];



        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la venta: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function updateVenta() {

    }

}