<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FacturasController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Facturas/FacturasModel');
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

    public function listFacturas() {
        try {
            // Obtener el token de autorización
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }
            
            // Validar formato del token
            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
    
            // Extraer y validar el token
            $token = $splitToken[1];
    
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                echo json_encode(['error' => 'Token inválido o mal formado']);
                exit;
            }

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            if (empty($jsonData) || !isset($jsonData['vehiculo_id'])) {
                echo json_encode([
                    'error' => 'No se proporcionaron datos',
                    'status' => 'error']);
                exit;
            }

            $vehiculo_id = $jsonData['vehiculo_id'];

            $facturas = $this->FacturasModel->obtenerFacturas($vehiculo_id);

            if($facturas) {
                echo json_encode([
                    'data' => $facturas,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontraron facturas'
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