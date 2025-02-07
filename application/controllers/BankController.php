<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BankController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('BankModel');
        $this->load->helper('verifyAuthToken_helper');
        
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

    public function add_bank_account() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
    
            // Extraer token
            $token = $splitToken[1];
    
            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                echo json_encode(['error' => 'Token inválido o mal formado']);
                exit;
            }
    
            $info = json_decode($valid);
            $sitio = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
    
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
    
            if (!$jsonData || empty($jsonData['nombre'])) {
                echo json_encode(['error' => 'El nombre de la cuenta bancaria no puede estar vacío']);
                exit;
            }
    
            $nombre = strtoupper($jsonData['nombre']);
    
            $result = $this->BankModel->add_bank_account($sitio, $nombre);
    
            if ($result) {
                echo json_encode([
                    'success' => 'Cuenta bancaria agregada correctamente',
                    'status' => 'success',
                ]);
            } else {
                echo json_encode(['error' => 'Error al agregar cuenta bancaria', 'details' => $this->db->error()]);
            }
            exit;
        } catch (Exception $e) {
            echo json_encode(['error' => 'Excepción atrapada', 'message' => $e->getMessage()]);
            exit;
        }
    }

    function listBankAccounts() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
    
            // Extraer token
            $token = $splitToken[1];
    
            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                echo json_encode(['error' => 'Token inválido o mal formado']);
                exit;
            }

            $info = json_decode($valid);
            $sitio = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $result = $this->BankModel->listBankAccounts($sitio);

            if ($result) {
                echo json_encode([
                    'success' => 'Cuentas bancarias listadas correctamente',
                    'status' => 'success',
                    'data' => $result,
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al listar cuentas bancarias',
                    'status' => 'error',
                ]);
            }

        }catch (Exception $e) {
            echo json_encode(['error' => 'Excepción atrapada', 'message' => $e->getMessage()]);
            exit;
        }
    }
}