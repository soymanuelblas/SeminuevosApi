<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
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

    function getuser() {
        try {
            $headerToken = $this->input->get_request_header('Authorization');
            $splitToken = explode(' ', $headerToken);
            
            if (empty($headerToken)) {
                echo json_encode(['error' => 'Token no proporcionado']);
                return;
            }
            
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode(['error' => 'Formato de token inválido']);
                return;
            }
            $token = $splitToken[1];
            $decoded = verifyAuthToken($token);
            if(!$decoded) {
                echo json_encode(['error' => 'Token inválido']);
                return;
            }
            log_message('info', 'Usuario autenticado'.$decoded->data->id.' AQUI');
            $result = $this->UserModel->get_user($decoded->data->id);
            if($result) {
                echo json_encode($result);
            } else {
                echo json_encode(array(
                    'status' => 400,
                    'message' => 'Usuario no encontrado'
                ));
            }
        }catch(Exception $e) {
            echo json_encode(array(
                'status' => 400,
                'message' => $e->getMessage()
            ));
        }
    }


}