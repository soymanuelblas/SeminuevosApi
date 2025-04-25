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
    
            // Procesar archivo si existe
            $archivo = 'SIN ARCHIVO';
            if (!empty($_FILES['file']['name'])) {
                // Ruta absoluta más confiable
                $base_path = realpath(APPPATH . '../..') . '/images/';
                $upload_path = $base_path . $sitio_id . '/' . $vehiculo_id . '/Tenencias/';
    
                // Crear directorio si no existe
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }
    
                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size' => 40000, // 40 MB
                    'encrypt_name' => TRUE,
                    'file_name' => uniqid() // Nombre único adicional
                ];
    
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $archivo = "/images/{$sitio_id}/{$vehiculo_id}/Tenencias/{$upload_data['file_name']}";
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Error al subir el archivo',                    ]);
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
                    'message' => 'Tenencia registrada correctamente'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error al guardar en la base de datos'
                ];
            }
    
            echo json_encode($response);
    
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al procesar la tenencia',
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
    
            // Obtener la factura actual completa
            $factura_actual = $this->FacturasModel->obtenerFacturaCompleta($factura_id, $sitio_id);
            if (!$factura_actual) {
                echo json_encode(['status' => 'error', 
                'message' => 'Factura no encontrada']);
                return;
            }
    
            // Mantener el archivo actual por defecto
            $archivo = isset($factura_actual['archivo']) ? $factura_actual['archivo'] : 'SIN ARCHIVO';
           
            // Procesar archivo solo si se envía uno nuevo
            if (!empty($_FILES['archivo']['name'])) {
                $base_path = realpath(APPPATH . '../..') . '/images/';
                $upload_path = $base_path . $sitio_id . '/' . $vehiculo_id . '/';
    
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }
    
                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size' => 40000,
                    'encrypt_name' => TRUE,
                    'file_name' => uniqid()
                ];
    
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $nuevo_archivo = "/images/{$sitio_id}/{$vehiculo_id}/Tenencias/{$upload_data['file_name']}";
                    
                    // Eliminar el archivo anterior solo si existe y es diferente al nuevo
                    if ($archivo != 'SIN ARCHIVO' && file_exists($base_path . ltrim($archivo, '/'))) {
                        unlink($base_path . ltrim($archivo, '/'));
                    }
                    
                    $archivo = $nuevo_archivo;
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Error al subir archivo',
                        'upload_error' => $this->upload->display_errors()
                    ]);
                    return;
                }
            }
    
            // Preparar datos para actualizar
            $data = [
                'vehiculo_id' => $vehiculo_id,
                'estado_id' => $estado_id,
                'tipoannio_id' => $tipoannio_id,
                'tipostatus_id' => $tipostatus_id,
                'archivo' => $archivo,
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