<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AuthModel');
        $this->load->helper('verifyauthtoken_helper');
        
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

    // public function signup() {
    //     try {
    //         if($this->input->post()) {
    //             $nombre = $this->input->post('nombre');

    //             $data = array(
    //                 'nombre' => $nombre,
    //                 'rol_id' => '5803',
    //             );
    //             $userId = $this->AuthModel->signup($data);
    //             if($userId) {
    //                 echo json_encode(['success' => 'Usuario registrado correctamente'], JSON_UNESCAPED_UNICODE);
    //             } else {
    //                 echo json_encode(['error' => 'Error al registrar el usuario'], JSON_UNESCAPED_UNICODE);
    //             }
    //         }
    //     } catch (Exception $e) {
    //         echo json_encode($e->getMessage());
    //     }
    // }

    // public function register_user() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode(['error' => 'Token no proporcionado']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode(['error' => 'Formato de token inválido']);
    //             exit;
    //         }
    
    //         $token = $splitToken[1];
    
    //         // Decodificar el JSON recibido
    //         $jsonData = json_decode(file_get_contents('php://input'), true);
    
    //         if (!$jsonData) {
    //             echo json_encode(['error' => 'No se recibieron datos válidos']);
    //             exit;
    //         }
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode(['error' => 'Token inválido']);
    //             exit;
    //         }
    
    //         // Extraer información del token
    //         $info = json_decode($valid);
    //         $sitio = $info->data->sitio_id ?? null;
    
    //         // Extraer datos del JSON
    //         $rfc = $jsonData['rfc'] ?? null;
    //         $razon_social = $jsonData['razon_social'] ?? null;
    //         $representante_legal = $jsonData['representante_legal'] ?? null;
    //         $regimen_fiscal = $jsonData['regimen_fiscal'] ?? null;
    //         $contrasenia = $jsonData['contrasenia'] ?? null;
    //         $recontrasenia = $jsonData['recontrasenia'] ?? null;
    
    //         if (empty($rfc) || empty($razon_social) || empty($representante_legal) || 
    //             empty($regimen_fiscal) || empty($contrasenia) || empty($recontrasenia)) {
    //             echo json_encode(['error' => 'Todos los campos son requeridos']);
    //             exit;
    //         }
    
    //         if ($contrasenia !== $recontrasenia) {
    //             echo json_encode(['error' => 'Las contraseñas no coinciden']);
    //             exit;
    //         }
    
    //         if (strlen($contrasenia) < 8) {
    //             echo json_encode(['error' => 'La contraseña debe tener al menos 8 caracteres']);
    //             exit;
    //         }
    
    //         // Registrar usuario
    //         $resultado = $this->AuthModel->register_user_data(
    //             $sitio, $rfc, $razon_social, $representante_legal, $regimen_fiscal
    //         );
    
    //         if ($resultado) {
    //             $resultado_pwd = $this->AuthModel->register_data_pwd($sitio, base64_encode($contrasenia));
    //         }
    
    //         if ($resultado && $resultado_pwd) {
    //             echo json_encode(['success' => 'Información actualizada correctamente'], JSON_UNESCAPED_UNICODE);
    //         } else {
    //             echo json_encode(['error' => 'Error al registrar la información del usuario'], JSON_UNESCAPED_UNICODE);
    //         }
    
    //         exit;
    //     } catch (Exception $e) {
    //         echo json_encode(['error' => $e->getMessage()]);
    //         exit;
    //     }
    // }
}