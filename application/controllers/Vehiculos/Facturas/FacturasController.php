<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FacturasController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehiculos/Facturas/FacturasModel');
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

    public function listTipoFacturas() {
        try {
            $valid = $this->validate();

            $result = $this->FacturasModel->listarTiposFacturas();

            if ($result) {
                $response = array(
                    'data' => $result,
                    'status' => 'success'
                );
            } else {
                $response = array(
                    'error' => 'Error al obtener los tipos de factura',
                    'status' => 'error',
                );
            }
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error al agregar la factura'
            );
        }
        echo json_encode($response);
    }

    public function listStatusFacturas() {
        try {
            $valid = $this->validate();

            $result = $this->FacturasModel->listarStatusFacturas();

            if ($result) {
                $response = array(
                    'data' => $result,
                    'status' => 'success'
                );
            } else {
                $response = array(
                    'error' => 'Error al obtener los tipos de factura',
                    'status' => 'error',
                );
            }
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error al agregar la factura'
            );
        }
        echo json_encode($response);
    }

    public function addFactura() {
        try {
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $vehiculo_id = $this->input->post('idvehiculo');
            $tipo_factura = $this->input->post('tipofac');
            $expedida_por = $this->input->post('expedida');
            $folio = $this->input->post('folio');
            $fecha = $this->input->post('fecha');
            $status = $this->input->post('statusfac');
    
            // Validar campos requeridos
            $campos_requeridos = [
                'idvehiculo' => $vehiculo_id,
                'tipofac' => $tipo_factura,
                'expedida' => $expedida_por,
                'folio' => $folio,
                'fecha' => $fecha,
                'statusfac' => $status
            ];
    
            foreach ($campos_requeridos as $campo => $valor) {
                if (empty($valor)) {
                    echo json_encode([
                        'error' => 'El valor '.$campo.' es requerido',
                        'status' => 'error'
                    ]);
                    return;
                }
            }
    
            // Procesar archivo si existe
            $archivo = 'SIN ARCHIVO';
            if (!empty($_FILES['file']['name'])) {
                // Crear estructura de directorios primero
                $upload_path = FCPATH . "images/{$sitio_id}/{$vehiculo_id}/";
                
                if (!file_exists($upload_path)) {
                    if (!mkdir($upload_path, 0777, true)) {
                        echo json_encode([
                            'error' => 'No se pudo crear el directorio: ' . $upload_path,
                            'status' => 'error'
                        ]);
                        return;
                    }
                    chmod($upload_path, 777); // Forzar permisos
                    mkdir($upload_path . 'thumbs', 0777, true);
                }
    
                // Configurar la biblioteca de carga DESPUÉS de crear el directorio
                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size' => 40000, // 40MB
                    'encrypt_name' => TRUE
                ];
    
                $this->load->library('upload', $config);
    
                // Intentar subir el archivo
                if (!$this->upload->do_upload('file')) {
                    $error = $this->upload->display_errors('', '');
                    log_message('error', 'Error al subir archivo: ' . $error);
                    echo json_encode([
                        'error' => 'Error al subir el archivo: ' . $error,
                        'status' => 'error',
                        'file_data' => $_FILES['file']
                    ]);
                    return;
                }
    
                $upload_data = $this->upload->data();
                $archivo = "/images/{$sitio_id}/{$vehiculo_id}/{$upload_data['file_name']}";
                
                // Verificar que el archivo realmente existe después de la carga
                if (!file_exists($upload_path . $upload_data['file_name'])) {
                    echo json_encode([
                        'error' => 'El archivo se subió pero no se encuentra en la ruta esperada',
                        'status' => 'error',
                        // 'path' => $upload_path . $upload_data['file_name']
                    ]);
                    return;
                }
            }
    
            // Preparar datos para insertar
            $data = [
                'vehiculo_id' => $vehiculo_id,
                'tipofactura_id' => $tipo_factura,
                'expedidapor' => $expedida_por,
                'folio' => $folio,
                'fecha' => $fecha,
                'archivo' => $archivo,
                'tipostatus_id' => $status
            ];
    
            $result = $this->FacturasModel->agregarFactura($data);
    
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' => 'Factura agregada correctamente',
                    'archivo' => $archivo,
                    'ruta_completa' => base_url() . ltrim($archivo, '/')
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error al agregar la factura'
                ];
            }
    
            echo json_encode($response);
    
        } catch (Exception $e) {
            log_message('error', 'Exception en addFactura: ' . $e->getMessage());
            $response = [
                'status' => 'error',
                'message' => 'Error al agregar la factura: ' . $e->getMessage()
            ];
            echo json_encode($response);
        }
    }

    public function listFacturas() {
        try {
            $valid = $this->validate();

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();





        }catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error al agregar la factura: ' . $e->getMessage()
            );
            echo json_encode($response);
        }
    }

    public function updateFactura() {
        try {

        }catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'message' => 'Error al agregar la factura: ' . $e->getMessage()
            );
            echo json_encode($response);
        }
    }

}