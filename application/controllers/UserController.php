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
            if($decoded) {
                log_message('info', $decoded);
                $info = json_decode($decoded);
                $id = $info->data->id;
                log_message('info', "Buscando usuario ID: $id");
                $user = $this->UserModel->get_user($id);
                if($user) {
                    echo json_encode($user);
                } else {
                    echo json_encode(['error' => 'Usuario no encontrado']);
                }
            } else {
                echo json_encode(['error' => 'Token inválido']);
            }
        }catch(Exception $e) {
            $error = array(
                'status' => 500,
                'message' => 'Token inválido',
                'success' => false,
                'error' => $e->getMessage()
            );
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
        }
    }


}