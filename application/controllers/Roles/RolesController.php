<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RolesController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Roles/RolesModel');
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

    public function listRoles() {
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

            $result = $this->RolesModel->listRoles();

            if ($result) {
                $response = array(
                    'status' => 'success',
                    'data' => $result
                );
                echo json_encode($response);
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'No se encontraron roles'
                );
                echo json_encode($response);
            }
        }catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error al obtener los roles: ' . $e->getMessage()
            );
            echo json_encode($response);
        }
    }
}