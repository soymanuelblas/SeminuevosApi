<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImagenesController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehiculos/Imagenes/ImagenesModel');
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

    public function listTipoImagen() {
        try {
            $valid = $this->validate();

            $result = $this->ImagenesModel->listarTipoImagen();

            if ($result) {
                echo json_encode([
                    'data' => $result,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'No se encontraron tipos de imagen',
                    'status' => 'error'
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al listar tipos de imagen',
                'status' => 'error'
            ]);
        }
    }

    public function addImagen() {
        try {
            // Validar token
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
    
            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            $vehiculo_id = $jsonData['vehiculo_id'] ? $jsonData['vehiculo_id'] : null;

            // Validar campos requeridos
            $required = ['vehiculo_id', 'tipo', 'titulo'];
            foreach ($required as $field) {
                if (empty($jsonData[$field])) {
                    $error = "Campo requerido faltante: $field";
                    echo json_encode(['error' => $error, 'status' => 'error']);
                    return;
                }
            }
    
            // Procesar archivo
            $archivo = 'SIN ARCHIVO';
            if (!empty($_FILES['file']['name'])) {
                $base_path = realpath(APPPATH . '../..') . '/images/';
                $upload_path = $base_path . $sitio_id . '/' . $vehiculo_id . '/Principales/';
                
                // Crear directorios recursivamente
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }
    
                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size' => 40000,
                    'encrypt_name' => true,
                    'overwrite' => false
                ];
    
                $this->load->library('upload', $config);
                
                if (!$this->upload->do_upload('file')) {
                    $error = $this->upload->display_errors();
                    log_message('error', 'Error al subir archivo: '.$error);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Error al subir archivo',
                        'upload_error' => $error
                    ]);
                    return;
                }
    
                $upload_data = $this->upload->data();
                $archivo = "{$sitio_id}/{$vehiculo_id}/Principales/{$upload_data['file_name']}";
            }
    
            // Preparar datos para insertar
            $data = [
                'vehiculo_id' => $vehiculo_id,
                'tipo' => $jsonData['tipo'],
                'titulo' => $jsonData['titulo'],
                'archivo' => $archivo,
            ];
            
            // Insertar en la base de datos
            $result = $this->ImagenesModel->agregarImagen($data, $sitio_id);
    
            if ($result) {
                echo json_encode([
                    'message' => 'Imagen agregada correctamente',
                    'status' => 'success',
                    'imagen_id' => $result
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al guardar en la base de datos',
                    'status' => 'error'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Error interno: '.$e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function deleteImagen() {
        try {
            // Validar token y obtener sitio_id
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
    
            // Obtener ID de la imagen a eliminar
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            $imagen_id = isset($jsonData['imagen_id']) ? $jsonData['imagen_id'] : null;
    
            if (empty($imagen_id)) {
                echo json_encode([
                    'error' => 'ID de imagen requerido',
                    'status' => 'error'
                ]);
                return;
            }
    
            // 1. Primero obtener información de la imagen (incluyendo la ruta del archivo)
            $imagen = $this->ImagenesModel->obtenerImagenPorId($imagen_id, $sitio_id);
            
            if (!$imagen) {
                echo json_encode([
                    'error' => 'Imagen no encontrada o no tienes permisos',
                    'status' => 'error'
                ]);
                return;
            }
    
            // 2. Eliminar el archivo físico si existe
            if (!empty($imagen['archivo']) && $imagen['archivo'] != 'SIN ARCHIVO') {
                $base_path = realpath(APPPATH . '../..') . '/images/';
                $ruta_completa = $base_path . ltrim($imagen['archivo'], '/');
                
                if (file_exists($ruta_completa)) {
                    if (!unlink($ruta_completa)) {
                        log_message('error', 'No se pudo eliminar el archivo: ' . $ruta_completa);
                    }
                }
            }
    
            // 3. Eliminar el registro de la base de datos
            $result = $this->ImagenesModel->eliminarImagen($imagen_id, $sitio_id);
    
            if ($result) {
                echo json_encode([
                    'message' => 'Imagen eliminada correctamente',
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al eliminar el registro de la imagen',
                    'status' => 'error'
                ]);
            }
        } catch (Exception $e) {
            log_message('error', 'Error en deleteImagen: ' . $e->getMessage());
            echo json_encode([
                'error' => 'Error interno al eliminar la imagen',
                'status' => 'error'
            ]);
        }
    }

    public function listImagen() {
        try {
            $valid = $this->validate();

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            $vehiculo_id = $jsonData['vehiculo_id'] ? $jsonData['vehiculo_id'] : null;
            $tipo = $jsonData['tipo'] ? $jsonData['tipo'] : null;

            if (empty($vehiculo_id) || empty($tipo)) {
                echo json_encode([
                    'error' => 'Datos incompletos',
                    'status' => 'error'
                ]);
                return;
            }

            $result = $this->ImagenesModel->listarImagen($vehiculo_id, $tipo, $sitio_id);

            if ($result) {
                echo json_encode([
                    'data' => $result,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'No se encontraron imagenes',
                    'status' => 'error'
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al listar imagenes',
                'status' => 'error'
            ]);
        }
    }

}