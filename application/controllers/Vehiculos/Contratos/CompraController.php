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

    public function listCompra() {
        try {
            $valid = $this->validate();

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $result = $this->CompraModel->listarCompras($sitio_id);

            if (empty($result)) {
                echo json_encode([
                    'error' => 'No se encontraron compras',
                    'status' => 'error'
                ]);
                exit;
            }

            if($result) {
                echo json_encode([
                    'data' => $result,
                    'status' => 'success'
                ]);
            }else {
                echo json_encode([
                    'error' => 'No se encontraron compras',
                    'status' => 'error'
                ]);
                exit;
            }
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al listar las compras',
                'status' => 'error'
            ]);
            exit;
        }
    }

    public function addCompra() {
        try {
            $valid = $this->validate();

            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            $vehiculo_id = $this->input->get_post('vehiculo_id') ?: (isset($jsonData['vehiculo_id']) ? $jsonData['vehiculo_id'] : null);
            $clientecompra = $this->input->get_post('clientecompra') ?: (isset($jsonData['clientecompra']) ? $jsonData['clientecompra'] : null);
            $precio = $this->input->get_post('precio') ?: (isset($jsonData['precio']) ? $jsonData['precio'] : null);
            $km = $this->input->get_post('km') ?: (isset($jsonData['km']) ? $jsonData['km'] : null);
            $formato = 'd-m-Y H:i:s';
            $fecha = $this->input->get_post('fecha') ?: (isset($jsonData['fecha']) ? $jsonData['fecha'] : null);
            $fecha = DateTime::createFromFormat($formato, $fecha);
            $testigo1 = $this->input->get_post('testigo1') ?: (isset($jsonData['testigo1']) ? $jsonData['testigo1'] : null);
            $testigo2 = $this->input->get_post('testigo2') ?: (isset($jsonData['testigo2']) ? $jsonData['testigo2'] : null);
            $statusventa = $this->input->get_post('statusventa') ?: (isset($jsonData['statusventa']) ? $jsonData['statusventa'] : null);
            
            $filter_datos = ['vehiculo_id', 'clientecompra', 'precio', 'km', 'fecha', 'testigo1', 'testigo2', 'statusventa', 'necesitas'];

            foreach ($filter_datos as $key) {
                if (empty($$key)) {
                    echo json_encode([
                        'error' => "El campo $key es obligatorio",
                        'status' => 'error'
                    ]);
                    exit;
                }
            }

            $data = [
                'vehiculo_id' => $vehiculo_id,
                'clientecompra' => $clientecompra,
                'precio' => $precio,
                'km' => $km,
                'fecha' => $fecha->format('Y-m-d H:i:s'),
                'testigo1' => $testigo1,
                'testigo2' => $testigo2,
                'statusventa' => $statusventa,
            ];

            $result = $this->CompraModel->agregarCompra($data, $sitio_id);
            
            if($result) {
                echo json_encode([
                    'data' => 'Compra agregada correctamente',
                    'status' => 'success'
                ]);
            }else {
                echo json_encode([
                    'error' => 'Error al agregar la compra',
                    'status' => 'error'
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la compra',
                'status' => 'error'
            ]);
            exit;
        }
    }

    public function updateCompra() {
        try {
            $valid = $this->validate();
            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            

        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al actualizar la compra',
                'status' => 'error'
            ]);
            exit;
        }
    }

}