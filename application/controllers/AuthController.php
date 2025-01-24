<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AuthModel');
    }

    public function login() {
        try {
            $jwt = new JWT();
            $JwtSecret = getenv('SECRET_KEY');
            
            $email = $this->input->post('usr');
            $password = $this->input->post('pwd');
            $pass = base64_encode($password);

            
            $result = $this->AuthModel->check_login($email, $pass);

            $token = $jwt->encode($result, $JwtSecret, 'HS256');

            echo json_encode($token);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            echo json_encode($e->getMessage());
        }
    }

    public function signup() {
        try {

        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

}