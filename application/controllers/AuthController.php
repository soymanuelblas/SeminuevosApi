<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AuthModel');
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

    public function login() {
        try {
            $jsonData = json_decode(file_get_contents('php://input'), true);
            $jwt = new JWT();
            $JwtSecret = getenv('SECRET_KEY');
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error al decodificar JSON');
            }

            $email = $jsonData['usr'];
            $password = $jsonData['pwd'];
            
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
                'data' => [
                    'id' => $result['id'],
                    'nombre' => $result['nombre'],
                    'email' => $result['usr'],
                    'permisos' => $result['permisos'],
                    'tipostatus_id' => $result['tipostatus_id'],
                    'rol_id' => $result['rol_id'],
                    'sitio_id' => $result['sitio_id']
                ],
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24 * 5)
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

    // REGISTRA LA INFORMACIÓN DEL USUARIO CUANDO YA FUE CREADO EN EL SISTEMA
    public function register_user() {
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
        try {
            if($this->input->post()) {
                $valid = verifyAuthToken($token);
                if($valid) {
                    $info = json_decode($valid);
                    $id = $info->data->id;
                    $rfc = $this->input->post('rfc');
                    $razon_social = $this->input->post('razon_social');
                    $representante_legal = $this->input->post('representante_legal');
                    $regimen_fiscal = $this->input->post('regimen_fiscal');
                    $cuenta_bancaria = $this->input->post('cuenta_bancaria');
                    $contrasenia = $this->input->post('contrasenia');
                    $recontrasenia = $this->input->post('recontrasenia');

                    if(empty($rfc) || empty($razon_social) || 
                        empty($representante_legal) || empty($regimen_fiscal) || 
                        empty($cuenta_bancaria) || empty($contrasenia) || 
                        empty($recontrasenia)) {
                        throw new Exception('Todos los campos son requeridos');
                    }
                    if($contrasenia !== $recontrasenia) {
                        throw new Exception('Las contraseñas no coinciden');
                    }
                    if(strlen($contrasenia) < 8) {
                        throw new Exception('La contraseña debe tener al menos 8 caracteres');
                    }

                    $resultado = $this->AuthModel->register_user(
                        $id,
                        $rfc,
                        $razon_social,
                        $representante_legal,
                        $regimen_fiscal,
                        base64_encode($contrasenia)
                    );

                    if($resultado) {
                        echo json_encode(['success' => 'Información actualizada correctamente'], JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode(['error' => 'Error al registrar la información del usuario'], JSON_UNESCAPED_UNICODE);
                    }
                }
            }
        }catch(Exception $e) {
            echo json_encode($e->getMessage());
        }
    }
}