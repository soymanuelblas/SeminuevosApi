<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FacturasController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehiculos/FacturasModel');
        $this->load->helper('verifyauthtoken_helper');
        
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH');
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

    public function addFactura() {
        try {
            $valid = validate();

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            // Procesar archivo si existe
            $archivo = 'SIN ARCHIVO';
            if (!empty($_FILES['file']['name'])) {
                $config['upload_path'] = './images/';
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['max_size'] = 2048; // 2MB

                $this->load->library('upload', $config);

                // Obtener información del vehículo para la ruta
                $vehiculo_info = $this->Factura_model->obtenerInfoVehiculo($vehiculo_id);
                if (!$vehiculo_info) {
                    $this->output->set_status_header(404);
                    echo json_encode(['error' => 'Vehículo no encontrado']);
                    return;
                }

                $site_id = $vehiculo_info['sitio_id'];
                $upload_path = "./images/{$site_id}/{$vehiculo_id}/";

                // Crear directorio si no existe
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $config['upload_path'] = $upload_path;
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $archivo = "/images/{$site_id}/{$vehiculo_id}/{$upload_data['file_name']}";
                } else {
                    $this->output->set_status_header(400);
                    echo json_encode(['error' => $this->upload->display_errors()]);
                    return;
                }
            }

            $data = [
                'idvehiculo' => $jsonData['idvehiculo'] ?? null,
                'tipofac' => $jsonData['tipofac'] ?? null,
                'expedida' => $jsonData['expedida'] ?? null,
                'folio' => $jsonData['folio'] ?? null,
                'fecha' => $jsonData['fecha'] ?? null,
                'facturaimg' => $jsonData['facturaimg'] ?? null,
                'statusfac' => $jsonData['statusfac'] ?? null,
            ];

            if (empty($data['idvehiculo']) || empty($data['tipofac']) || 
                empty($data['expedida']) || empty($data['folio']) || 
                empty($data['fecha']) || empty($data['facturaimg'])) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Faltan datos requeridos'
                ));
                return;
            }

            $result = $this->FacturasModel->agregarFactura($data);

            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Factura agregada correctamente'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Error al agregar la factura'
                );
            }
        }catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error al agregar la factura: ' . $e->getMessage()
            );
            echo json_encode($response);
        }
    }

    public function listFacturas() {
        try {

        }catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error al agregar la factura: ' . $e->getMessage()
            );
            echo json_encode($response);
        }
    }

    public function updateFactura() {
        try {

        }catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error al agregar la factura: ' . $e->getMessage()
            );
            echo json_encode($response);
        }
    }

}