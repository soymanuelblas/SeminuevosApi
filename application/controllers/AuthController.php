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
            error_log("Iniciando register_user()");
            
            // Verificar token
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            error_log("Header Token recibido: " . ($headerToken ? "presente" : "ausente"));
            
            if (empty($headerToken)) {
                error_log("Error: Token no proporcionado");
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }
    
            $splitToken = explode(' ', $headerToken);
            error_log("Token dividido: " . print_r($splitToken, true));
            
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                error_log("Error: Formato de token inválido");
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
    
            $token = $splitToken[1];
            error_log("Token a verificar: $token");
            
            $jsonData = json_decode(file_get_contents('php://input'), true);
            error_log("Datos JSON recibidos: " . print_r($jsonData, true));
    
            if (!$jsonData) {
                error_log("Error: No se recibieron datos válidos");
                echo json_encode(['error' => 'No se recibieron datos válidos']);
                exit;
            }
    
            $valid = verifyAuthToken($token);
            error_log("Resultado de verifyAuthToken: " . ($valid ? "válido" : "inválido"));
            
            if (!$valid) {
                error_log("Error: Token inválido");
                echo json_encode(['error' => 'Token inválido']);
                exit;
            }
    
            $info = json_decode($valid);
            $sitio = $info->data->sitio_id ?? null;
            error_log("Sitio ID obtenido del token: " . ($sitio ?? "null"));
    
            // Validar datos requeridos
            $requiredFields = ['rfc', 'razon_social', 'representante_legal', 'regimen_fiscal', 'contrasenia', 'recontrasenia'];
            error_log("Validando campos requeridos...");
            
            foreach ($requiredFields as $field) {
                if (empty($jsonData[$field])) {
                    error_log("Error: Campo requerido faltante - $field");
                    echo json_encode(['error' => 'El campo ' . $field . ' es requerido']);
                    exit;
                }
            }
    
            if ($jsonData['contrasenia'] !== $jsonData['recontrasenia']) {
                error_log("Error: Las contraseñas no coinciden");
                echo json_encode(['error' => 'Las contraseñas no coinciden']);
                exit;
            }
    
            if (strlen($jsonData['contrasenia']) < 8) {
                error_log("Error: Contraseña demasiado corta");
                echo json_encode(['error' => 'La contraseña debe tener al menos 8 caracteres']);
                exit;
            }
    
            // Inicializar variables
            $success = true;
            $messages = [];
            error_log("Iniciando registro de datos...");
    
            // Registrar datos principales
            error_log("Registrando datos del usuario...");
            $resultado = $this->AuthModel->register_user_data(
                $sitio, 
                $jsonData['rfc'], 
                $jsonData['razon_social'], 
                $jsonData['representante_legal'], 
                $jsonData['regimen_fiscal']
            );
            error_log("Resultado de register_user_data: " . ($resultado ? "éxito" : "fallo"));
    
            if (!$resultado) {
                $success = false;
                $messages[] = 'Error al registrar datos del usuario';
                error_log("Error al registrar datos del usuario");
            }
    
            // Registrar contraseña
            if ($success) {
                error_log("Registrando contraseña...");
                $resultado_pwd = $this->AuthModel->register_data_pwd($sitio, base64_encode($jsonData['contrasenia']));
                error_log("Resultado de register_data_pwd: " . ($resultado_pwd ? "éxito" : "fallo"));
                
                if (!$resultado_pwd) {
                    $success = false;
                    $messages[] = 'Error al registrar la contraseña';
                    error_log("Error al registrar la contraseña");
                }
            }
    
            // Registrar complemento
            if ($success) {
                error_log("Registrando complemento del sitio...");
                $resultado_complemento = $this->AuthModel->register_complemento_sitio($sitio);
                error_log("Resultado de register_complemento_sitio: " . ($resultado_complemento ? "éxito" : "fallo"));
                
                if (!$resultado_complemento) {
                    $success = false;
                    $messages[] = 'Error al registrar complemento del sitio';
                    error_log("Error al registrar complemento del sitio");
                }
            }
    
            // Registrar bancos si existen
            if ($success && !empty($jsonData['banco']) && is_array($jsonData['banco'])) {
                error_log("Registrando cuentas bancarias... Número de cuentas: " . count($jsonData['banco']));
                
                foreach ($jsonData['banco'] as $index => $banco) {
                    log_message("Procesando banco #$index: " . print_r($banco, true), 'debug');
                    
                    $bankResult = $this->AuthModel->register_bank_account([
                        'nombre' => $banco['descripcion'],
                        'sitio_id' => $sitio
                    ]);
                    error_log("Resultado de registro banco #$index: " . ($bankResult ? "éxito" : "fallo"));
                    
                    if (!$bankResult) {
                        $messages[] = 'Error al registrar cuenta bancaria';
                        error_log("Error al registrar cuenta bancaria #$index");
                    }
                }
            }
    
            // Registrar ingresos si existen
            if ($success && !empty($jsonData['ingresos']) && is_array($jsonData['ingresos'])) {
                error_log("Registrando ingresos... Número de ingresos: " . count($jsonData['ingresos']));
                
                foreach ($jsonData['ingresos'] as $index => $ingreso) {
                    error_log("Procesando ingreso #$index: " . print_r($ingreso, true));
                    
                    $incomeResult = $this->AuthModel->register_data_cash_ingresos([
                        'tipo' => '1',
                        'descripcion' => $ingreso['descripcion'],
                        'valor' => '0'
                    ], $sitio);
                    error_log("Resultado de registro ingreso #$index: " . ($incomeResult ? "éxito" : "fallo"));
                    
                    if (!$incomeResult) {
                        $messages[] = 'Error al registrar ingreso';
                        error_log("Error al registrar ingreso #$index");
                    }
                }
            }
    
            // Registrar egresos si existen
            if ($success && !empty($jsonData['egresos']) && is_array($jsonData['egresos'])) {
                error_log("Registrando egresos... Número de egresos: " . count($jsonData['egresos']));
                
                foreach ($jsonData['egresos'] as $index => $egreso) {
                    error_log("Procesando egreso #$index: " . print_r($egreso, true));
                    
                    $expenseResult = $this->AuthModel->register_data_cash_egresos([
                        'tipo' => '2',
                        'descripcion' => $egreso['descripcion'],
                        'valor' => '0'
                    ], $sitio);
                    error_log("Resultado de registro egreso #$index: " . ($expenseResult ? "éxito" : "fallo"));
                    
                    if (!$expenseResult) {
                        $messages[] = 'Error al registrar egreso';
                        error_log("Error al registrar egreso #$index");
                    }
                }
            }
    
            // Respuesta final
            if ($success) {
                error_log("Registro completado con éxito");
                echo json_encode([
                    'message' => 'Información actualizada correctamente',
                    'status' => 'success',
                ], JSON_UNESCAPED_UNICODE);
            } else {
                error_log("Registro completado con errores: " . implode(', ', $messages));
                echo json_encode([
                    'message' => implode(', ', $messages),
                    'status' => 'error',
                ], JSON_UNESCAPED_UNICODE);
            }
    
        } catch (Exception $e) {
            error_log("Excepción en register_user(): " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode([
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
}