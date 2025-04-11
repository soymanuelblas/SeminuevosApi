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

    public function register_user() {
        try {
            // Verificar token
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
    
            $token = $splitToken[1];
            
            $jsonData = json_decode(file_get_contents('php://input'), true);
    
            if (!$jsonData) {
                echo json_encode(['error' => 'No se recibieron datos válidos']);
                exit;
            }
    
            $valid = verifyAuthToken($token);
            
            if (!$valid) {
                echo json_encode(['error' => 'Token inválido']);
                exit;
            }
    
            $info = json_decode($valid);
            $sitio = $info->data->sitio_id ?? null;
    
            // Validar datos requeridos
            $requiredFields = ['rfc', 'razon_social', 'representante_legal', 'regimen_fiscal', 'contrasenia', 'recontrasenia'];
            
            foreach ($requiredFields as $field) {
                if (empty($jsonData[$field])) {
                    echo json_encode(['error' => 'El campo ' . $field . ' es requerido']);
                    exit;
                }
            }
    
            if ($jsonData['contrasenia'] !== $jsonData['recontrasenia']) {
                echo json_encode(['error' => 'Las contraseñas no coinciden']);
                exit;
            }
    
            if (strlen($jsonData['contrasenia']) < 8) {
                echo json_encode(['error' => 'La contraseña debe tener al menos 8 caracteres']);
                exit;
            }
    
            // Inicializar variables
            $success = true;
            $messages = [];
    
            // Registrar datos principales
            $resultado = $this->AuthModel->register_user_data(
                $sitio, 
                $jsonData['rfc'], 
                $jsonData['razon_social'], 
                $jsonData['representante_legal'], 
                $jsonData['regimen_fiscal']
            );
    
            if (!$resultado) {
                $success = false;
                $messages[] = 'Error al registrar datos del usuario';
            }
    
            // Registrar contraseña
            if ($success) {
                $resultado_pwd = $this->AuthModel->register_data_pwd($sitio, base64_encode($jsonData['contrasenia']));
                
                if (!$resultado_pwd) {
                    $success = false;
                    $messages[] = 'Error al registrar la contraseña';
                }
            }
    
            // Registrar complemento
            if ($success) {
                $resultado_complemento = $this->AuthModel->register_complemento_sitio($sitio);
                
                if (!$resultado_complemento) {
                    $success = false;
                    $messages[] = 'Error al registrar complemento del sitio';
                }
            }
    
            // Registrar bancos si existen
            if ($success && !empty($jsonData['banco']) && is_array($jsonData['banco'])) {
                
                foreach ($jsonData['banco'] as $index => $banco) {
                    
                    $bankResult = $this->AuthModel->register_bank_account([
                        'nombre' => $banco['descripcion'],
                        'sitio_id' => $sitio
                    ]);
                    
                    if (!$bankResult) {
                        $messages[] = 'Error al registrar cuenta bancaria';
                    }
                }
            }
    
            // Registrar ingresos si existen
            if ($success && !empty($jsonData['ingresos']) && is_array($jsonData['ingresos'])) {
                
                foreach ($jsonData['ingresos'] as $index => $ingreso) {
                    
                    $incomeResult = $this->AuthModel->register_data_cash_ingresos([
                        'tipo' => '1',
                        'descripcion' => $ingreso['descripcion'],
                        'valor' => '0'
                    ], $sitio);
                    
                    if (!$incomeResult) {
                        $messages[] = 'Error al registrar ingreso';
                    }
                }
            }
    
            // Registrar egresos si existen
            if ($success && !empty($jsonData['egresos']) && is_array($jsonData['egresos'])) {
                
                foreach ($jsonData['egresos'] as $index => $egreso) {
                    
                    $expenseResult = $this->AuthModel->register_data_cash_egresos([
                        'tipo' => '2',
                        'descripcion' => $egreso['descripcion'],
                        'valor' => '0'
                    ], $sitio);
                    
                    if (!$expenseResult) {
                        $messages[] = 'Error al registrar egreso';
                    }
                }
            }
    
            // Respuesta final
            if ($success) {
                echo json_encode([
                    'message' => 'Información actualizada correctamente',
                    'status' => 'success',
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'message' => implode(', ', $messages),
                    'status' => 'error',
                ], JSON_UNESCAPED_UNICODE);
            }
    
        } catch (Exception $e) {
            echo json_encode([
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
}