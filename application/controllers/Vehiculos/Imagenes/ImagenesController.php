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

    public function addPrincipal() {
        try {
            log_message('debug', 'Iniciando el método addPrincipal.');

            // Validar
            if (!method_exists($this, 'validate')) {
                log_message('error', 'El método validate() no está definido.');
                echo json_encode([
                    'error' => 'El método validate() no está definido.',
                    'status' => 'error'
                ]);
                return;
            }

            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $vehiculo_id = $jsonData['vehiculo_id'] ?? null;

            log_message('debug', 'Validación completada.');

            // Leer datos JSON
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            log_message('debug', 'Datos JSON recibidos: ' . json_encode($jsonData));

            // Validar campos requeridos
            if (empty($jsonData['vehiculo_id']) || empty($jsonData['tipo']) || empty($jsonData['titulo'])) {
                log_message('error', 'Datos incompletos: ' . json_encode($jsonData));
                echo json_encode([
                    'error' => 'Datos incompletos',
                    'status' => 'error'
                ]);
                return;
            }

            // Procesar archivo
            $archivo = 'SIN IMAGEN';
            if (!empty($_FILES['archivo']['name'])) {
                log_message('debug', 'Archivo recibido: ' . $_FILES['archivo']['name']);

                // Configurar ruta de subida
                $upload_path = FCPATH . "{$sitio_id}/{$vehiculo_id}/Principales/";
                log_message('debug', 'Ruta de subida configurada: ' . $upload_path);

                // Crear directorios si no existen
                if (!file_exists($upload_path)) {
                    log_message('debug', 'El directorio no existe. Creando: ' . $upload_path);
                    mkdir($upload_path, 0777, true);
                    chmod($upload_path, 0777);
                }

                // Configurar librería de upload
                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size' => 40000, // 40MB
                    'encrypt_name' => true,
                    'remove_spaces' => true,
                    'overwrite' => false
                ];

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('archivo')) {
                    $upload_data = $this->upload->data();
                    $archivo = "images/principales/{$sitio_id}/{$vehiculo_id}/{$upload_data['file_name']}";
                    log_message('debug', 'Archivo subido correctamente: ' . $archivo);

                    // Verificar que el archivo se guardó físicamente
                    if (!file_exists($upload_path . $upload_data['file_name'])) {
                        log_message('error', 'El archivo no se guardó correctamente en el servidor.');
                        throw new Exception('El archivo no se guardó correctamente en el servidor');
                    }
                } else {
                    $error = $this->upload->display_errors();
                    log_message('error', 'Error al subir archivo: ' . $error);
                    echo json_encode([
                        'error' => 'Error al subir archivo: ' . $error,
                        'status' => 'error'
                    ]);
                    return;
                }
            } else {
                log_message('debug', 'No se recibió ningún archivo.');
            }

            // Preparar datos para insertar
            $data = [
                'vehiculo_id' => $jsonData['vehiculo_id'],
                'tipo' => $jsonData['tipo'],
                'titulo' => $jsonData['titulo'],
                'archivo' => $archivo,
            ];
            log_message('debug', 'Datos preparados para insertar: ' . json_encode($data));

            // Insertar en la base de datos
            $result = $this->ImagenesModel->agregarPrincipal($data);

            if ($result) {
                log_message('debug', 'Imagen principal agregada correctamente.');
                echo json_encode([
                    'message' => 'Imagen principal agregada correctamente',
                    'status' => 'success'
                ]);
            } else {
                log_message('error', 'Error al agregar la imagen principal en la base de datos.');
                echo json_encode([
                    'error' => 'Error al agregar la imagen principal',
                    'status' => 'error'
                ]);
            }
        } catch (Exception $e) {
            log_message('error', 'Excepción capturada: ' . $e->getMessage());
            echo json_encode([
                'error' => 'Error al agregar la imagen principal: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function deletePrincipal() {
        try {
            $valid = $this->validate();

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $jsonData = json_decode(file_get_contents('php://input'), true);

            $id = $jsonData['id'];

            $result = $this->ImagenesModel->eliminarPrincipal($id, $sitio_id);

            if ($result) {
                echo json_encode([
                    'message' => 'Imagen principal eliminada correctamente',
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al eliminar la imagen principal',
                    'status' => 'error'
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la imagen principal',
                'status' => 'error'
            ]);
        }
    }

    public function addInterior() {
        try {

        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la imagen interior',
                'status' => 'error'
            ]);
        }
    }

    public function deleteInterior() {
        try {

        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la imagen interior',
                'status' => 'error'
            ]);
        }
    }

    public function addExterior() {
        try {

        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la imagen exterior',
                'status' => 'error'
            ]);
        }
    }

    public function deleteExterior() {
        try {

        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la imagen exterior',
                'status' => 'error'
            ]);
        }
    }

    public function addLlantas() {
        try {

        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la imagen de llantas',
                'status' => 'error'
            ]);
        }
    }

    public function deleteLlantas() {
        try {

        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la imagen de llantas',
                'status' => 'error'
            ]);
        }
    }

    public function addImperfecciones() {
        try {

        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la imagen de imperfecciones',
                'status' => 'error'
            ]);
        }
    }
    
    public function deleteImperfecciones() {
        try {

        }catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al agregar la imagen de imperfecciones',
                'status' => 'error'
            ]);
        }
    }

}