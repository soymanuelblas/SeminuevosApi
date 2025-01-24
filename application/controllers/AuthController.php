<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AuthModel');
        $this->load->helper('verifyAuthToken_helper');
    }

    public function login() {
        try {
            $jwt = new JWT();
            $JwtSecret = getenv('SECRET_KEY');
            
            $email = $this->input->post('usr');
            $password = $this->input->post('pwd');
            
            if(empty($email)) {
                throw new Exception('El correo electrónico no puede estar vacío');
            }
            // Validaciones
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Correo electrónico no válido');
            }

            if (empty($password)) {
                throw new Exception('La contraseña no puede estar vacía');
            }
            
            $pass = base64_encode($password);

            $result = $this->AuthModel->check_login($email, $pass);

            if (!$result) {
                throw new Exception('Credenciales inválidas');
            }

            $payload = [
                'id' => $result['id'],
                'nombre' => $result['nombre'],
                'email' => $result['usr'],
                'permisos' => $result['permisos'],
                'tipostatus_id' => $result['tipostatus_id'],
                'rol_id' => $result['rol_id'],
                'sitio_id' => $result['sitio_id'],
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24 * 5), // Expiración en 5 días
            ];

            $token = $jwt->encode($payload, $JwtSecret, 'HS256');

            echo json_encode(['token' => $token], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

    public function signup() {
        try {
            if($this->input->post()) {
                $nombre = $this->input->post('nombre');

                $data = array(
                    'nombre' => $nombre,
                    'rol_id' => '5803',
                );
                $userId = $this->AuthModel->signup($data);
                if($userId) {
                    echo json_encode(['success' => 'Usuario registrado correctamente'], JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(['error' => 'Error al registrar el usuario'], JSON_UNESCAPED_UNICODE);
                }
            }
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

}