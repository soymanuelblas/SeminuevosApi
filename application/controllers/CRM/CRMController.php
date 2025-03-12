<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CRMController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('CRM/CRMModel');
        $this->load->helper('verifyAuthToken_helper');
        
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

    public function listStatistics() {
        try {
            // Verificar si el encabezado Authorization llega correctamente
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
            if (empty($headerToken)) {
                echo json_encode([
                    'error' => 'Token no proporcionado',
                    'status' => 'error']);
                exit;
            }
    
            // Verificar si el token tiene el formato correcto
            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode([
                    'error' => 'Formato de token inválido',
                    'status' => 'error']);
                exit;
            }
            $token = $splitToken[1];
    
            // Verificar el token
            $valid = verifyAuthToken($token);
            if (!$valid) {
                echo json_encode([
                    'error' => 'Token inválido',
                    'status' => 'error']);
                exit;
            }

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            $fecha_ini = $jsonData['fecha_ini'];
            $fecha_fin = $jsonData['fecha_fin'];

            if (!DateTime::createFromFormat('Y-m-d', $fecha_ini) || !DateTime::createFromFormat('Y-m-d', $fecha_fin)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Formato de fecha no válido'
                ]);
                return;
            }

            $result = $this->CRMModel->obtenerDatosProspectos($fecha_ini, $fecha_fin, $sitio_id);

            if($result) {
                echo json_encode([
                    'data' => $result,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'message' => 'No se encontraron datos',
                    'status' => 'error',
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


}