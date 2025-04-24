<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TenenciasController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehiculos/Tenencias/TenenciasModel');
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

    function validate() {
        $headerToken = $this->input->get_request_header('Authorization', TRUE);
        if (empty($headerToken)) {
            echo json_encode([
                'error' => 'Token no proporcionado',
                'status' => 'error'
            ]);
            exit;
        }

        $splitToken = explode(' ', $headerToken);
        if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
            echo json_encode([
                'error' => 'Formato de token inválido',
                'status' => 'error',
            ]);
            exit;
        }
        $token = $splitToken[1];

        // Validar token
        $valid = verifyAuthToken($token);
        if (!$valid || !is_string($valid) || !json_decode($valid)) {
            echo json_encode([
                'error' => 'Token inválido o mal formado',
                'status' => 'error']);
            exit;
        }

        return $valid;
    }

    public function addTenencias() {
        try {
            // Validar token de autenticación
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
    
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            $vehiculo_id = isset($jsonData['vehiculo_id']) ? $jsonData['vehiculo_id'] : $this->input->post('vehiculo_id');
            $estado_id = isset($jsonData['estado_id']) ? $jsonData['estado_id'] : $this->input->post('estado_id');
            $tipoannio_id = isset($jsonData['tipoannio_id']) ? $jsonData['tipoannio_id'] : $this->input->post('tipoannio_id');
            $tipostatus_id = isset($jsonData['tipostatus_id']) ? $jsonData['tipostatus_id'] : $this->input->post('tipostatus_id');
    
            // Validar campos requeridos
            $requiredFields = [
                'vehiculo_id' => $vehiculo_id,
                'estado_id' => $estado_id,
                'tipoannio_id' => $tipoannio_id,
                'tipostatus_id' => $tipostatus_id
            ];

            foreach ($requiredFields as $fieldName => $fieldValue) {
                if (empty($fieldValue)) {
                    echo json_encode([
                        'error' => "El campo '$fieldName' es requerido",
                        'status' => 'error'
                    ]);
                    return;
                }
            }
    
            // Procesar archivo
            $archivo = 'SIN IMAGEN';
            if (!empty($_FILES['archivo']['name'])) {
                // Configurar ruta de subida
                $upload_path = FCPATH . "images/tenencias/{$sitio_id}/{$vehiculo_id}/";
                
                // Crear directorios si no existen
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0777, true);
                    chmod($upload_path, 0777);
                }
    
                // Configurar librería de upload
                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size' => 40000, // 40 MB
                    'encrypt_name' => true, // Generar nombre único
                    'remove_spaces' => true
                ];
    
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('archivo')) {
                    $upload_data = $this->upload->data();
                    $archivo = "images/tenencias/{$sitio_id}/{$vehiculo_id}/{$upload_data['file_name']}";
                    
                    // Verificar que el archivo se guardó físicamente
                    if (!file_exists($upload_path . $upload_data['file_name'])) {
                        throw new Exception('El archivo no se guardó correctamente en el servidor');
                    }
                } else {
                    $error = $this->upload->display_errors('', '');
                    echo json_encode([
                        'error' => 'Error al subir archivo',
                        'status' => 'error'
                    ]);
                    return;
                }
            }
    
            // Preparar datos para insertar
            $data = [
                'vehiculo_id' => $vehiculo_id,
                'estado_id' => $estado_id,
                'tipoannio_id' => $tipoannio_id,
                'tipostatus_id' => $tipostatus_id,
                'archivo' => $archivo,
            ];
    
            // Insertar en la base de datos
            $result = $this->TenenciasModel->agregarTenencia($data);
    
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' => 'Tenencia registrada correctamente',
                    'data' => [
                        'archivo_url' => base_url($archivo)
                    ]
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error al guardar en la base de datos'
                ];
            }
    
            echo json_encode($response);
    
        } catch (Exception $e) {
            log_message('error', 'Error en addTenencias');
            echo json_encode([
                'error' => 'Error al procesar la tenencia: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function updateTenencias() {
        try {
            log_message('debug', 'Inicio de updateTenencias - Validando token');
            $valid = $this->validate();
            log_message('debug', 'Token validado: ' . json_encode($valid));
    
            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            log_message('debug', "Sitio ID obtenido: {$sitio_id}");
    
            log_message('debug', 'Obteniendo datos de entrada');
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            log_message('debug', 'Datos recibidos: ' . print_r($jsonData, true));
            log_message('debug', 'Datos FILES: ' . print_r($_FILES, true));
    
            $tenencia_id = isset($jsonData['tenencia_id']) ? $jsonData['tenencia_id'] : $this->input->post('tenencia_id');
            $vehiculo_id = isset($jsonData['vehiculo_id']) ? $jsonData['vehiculo_id'] : $this->input->post('vehiculo_id');
            $estado_id = isset($jsonData['estado_id']) ? $jsonData['estado_id'] : $this->input->post('estado_id');
            $tipoannio_id = isset($jsonData['tipoannio_id']) ? $jsonData['tipoannio_id'] : $this->input->post('tipoannio_id');
            $tipostatus_id = isset($jsonData['tipostatus_id']) ? $jsonData['tipostatus_id'] : $this->input->post('tipostatus_id');
            
            log_message('debug', "Datos obtenidos: tenencia_id={$tenencia_id}, vehiculo_id={$vehiculo_id}, estado_id={$estado_id}, tipoannio_id={$tipoannio_id}, tipostatus_id={$tipostatus_id}");
    
            // Validar campos requeridos
            $requiredFields = [
                'tenencia_id' => $tenencia_id,
                'vehiculo_id' => $vehiculo_id,
                'estado_id' => $estado_id,
                'tipoannio_id' => $tipoannio_id,
                'tipostatus_id' => $tipostatus_id
            ];
    
            foreach ($requiredFields as $fieldName => $fieldValue) {
                if (empty($fieldValue)) {
                    log_message('error', "Campo requerido faltante: {$fieldName}");
                    echo json_encode([
                        'error' => "El campo '{$fieldName}' es requerido",
                        'status' => 'error'
                    ]);
                    return;
                }
            }
            log_message('debug', 'Todos los campos requeridos están presentes');
    
            // Obtener tenencia actual para manejar el archivo existente
            log_message('debug', "Obteniendo tenencia actual con ID: {$tenencia_id}");

            
            $archivo = $tenencia_actual['archivo']; // Mantener el archivo actual por defecto
            log_message('debug', "Archivo actual: {$archivo}");
    
            // Procesar archivo si se envía uno nuevo
            if (!empty($_FILES['archivo']['name'])) {
                log_message('debug', 'Nuevo archivo recibido, procesando...');
                
                // Configurar ruta de subida
                $upload_path = FCPATH . "images/tenencias/{$sitio_id}/{$vehiculo_id}/";
                log_message('debug', "Ruta de subida: {$upload_path}");
                
                // Crear directorios si no existen
                if (!file_exists($upload_path)) {
                    log_message('debug', 'Creando directorio para archivos');
                    if (!mkdir($upload_path, 0777, true)) {
                        log_message('error', "No se pudo crear el directorio: {$upload_path}");
                        echo json_encode([
                            'error' => 'Error al crear directorio para archivos',
                            'status' => 'error'
                        ]);
                        return;
                    }
                    chmod($upload_path, 0777);
                    log_message('debug', 'Directorio creado con permisos 0777');
                }
    
                // Configurar librería de upload
                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size' => 40000, // 40 MB
                    'encrypt_name' => true, // Generar nombre único
                    'remove_spaces' => true
                ];
                log_message('debug', 'Configuración de upload: ' . print_r($config, true));
    
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('archivo')) {
                    $upload_data = $this->upload->data();
                    $archivo = "images/tenencias/{$sitio_id}/{$vehiculo_id}/{$upload_data['file_name']}";
                    log_message('debug', "Archivo subido correctamente: {$archivo}");
                    
                    // Verificar que el archivo se guardó físicamente
                    if (!file_exists($upload_path . $upload_data['file_name'])) {
                        log_message('error', 'El archivo no se guardó físicamente en la ruta esperada');
                        throw new Exception('El archivo no se guardó correctamente en el servidor');
                    }
    
                    // Eliminar archivo anterior si existe y no es "SIN IMAGEN"
                    if ($tenencia_actual['archivo'] != 'SIN IMAGEN' && file_exists(FCPATH . $tenencia_actual['archivo'])) {
                        log_message('debug', "Eliminando archivo anterior: {$tenencia_actual['archivo']}");
                        if (!unlink(FCPATH . $tenencia_actual['archivo'])) {
                            log_message('error', "No se pudo eliminar el archivo anterior: {$tenencia_actual['archivo']}");
                        }
                    }
                } else {
                    $error = $this->upload->display_errors('', '');
                    log_message('error', "Error al subir archivo: {$error}");
                    echo json_encode([
                        'error' => 'Error al subir archivo: ' . $error,
                        'status' => 'error'
                    ]);
                    return;
                }
            } else {
                log_message('debug', 'No se recibió nuevo archivo, manteniendo el existente');
            }
    
            // Preparar datos para actualizar
            $data = [
                'estado_id' => $estado_id,
                'tipoannio_id' => $tipoannio_id,
                'tipostatus_id' => $tipostatus_id,
                'archivo' => $archivo,
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ];
            log_message('debug', 'Datos preparados para actualización: ' . print_r($data, true));
    
            // Actualizar en la base de datos
            log_message('debug', 'Actualizando tenencia en la base de datos');
            $result = $this->TenenciasModel->actualizarTenencia($tenencia_id, $sitio_id, $data);
    
            if ($result) {
                log_message('debug', 'Tenencia actualizada correctamente');
                $response = [
                    'status' => 'success',
                    'message' => 'Tenencia actualizada correctamente',
                    'data' => [
                        'archivo_url' => base_url($archivo)
                    ]
                ];
            } else {
                log_message('error', 'Error al actualizar tenencia en la base de datos');
                $response = [
                    'status' => 'error',
                    'message' => 'Error al actualizar en la base de datos'
                ];
            }
    
            log_message('debug', 'Enviando respuesta: ' . print_r($response, true));
            echo json_encode($response);
    
        } catch (Exception $e) {
            log_message('error', 'Excepción en updateTenencias: ' . $e->getMessage());
            echo json_encode([
                'error' => 'Error al actualizar tenencias: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function listTenencias() {
        try {
            $valid = $this->validate();

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            $vehiculo_id = $jsonData['vehiculo_id'];

            $result = $this->TenenciasModel->obtenerTenencias($sitio_id, $vehiculo_id);

            if($result) {
                echo json_encode([
                    'data' => $result,
                    'status' => 'success'
                ]);
            }else {
                echo json_encode([
                    'error' => 'No se encontraron tenencias',
                    'status' => 'error'
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al listar tenencias',
                'status' => 'error'
            ]);
        }
    }

    public function listEstados() {
        try {
            // Validar token
            $this->validate();

            $result = $this->TenenciasModel->obtenerEstados();

            if($result) {
                echo json_encode([
                    'data' => $result,
                    'status' => 'success'
                ]);
            }else {
                echo json_encode([
                    'error' => 'No se encontraron estados',
                    'status' => 'error'
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al listar estados',
                'status' => 'error'
            ]);
        }
    }

    public function listAnnios() {
        try {
            // Validar token
            $this->validate();

            $result = $this->TenenciasModel->obtenerAnnios();

            if($result) {
                echo json_encode([
                    'data' => $result,
                    'status' => 'success'
                ]);
            }else {
                echo json_encode([
                    'error' => 'No se encontraron años',
                    'status' => 'error'
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al listar años',
                'status' => 'error'
            ]);
        }
    }

}