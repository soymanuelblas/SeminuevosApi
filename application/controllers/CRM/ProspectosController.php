<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProspectosController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('CRM/ProspectosModel');
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

    public function listProspectos() {
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

            $prospectos = $this->ProspectosModel->obtenerProspectos($sitio_id);

            if($prospectos) {
                echo json_encode([
                    'data' => $prospectos,
                    'status' => 'success',
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontraron prospectos'
                ]);
            }
        }catch(Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }



}