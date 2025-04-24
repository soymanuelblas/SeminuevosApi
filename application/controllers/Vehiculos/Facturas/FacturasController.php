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
    
    public function addFactura() {
        try {
            // Validación inicial
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
    
            // Obtener datos (tanto POST como raw JSON)
            $jsonData = $this->input->post() ?: json_decode(file_get_contents('php://input'), true);
    
            $vehiculo_id = $jsonData['idvehiculo'] ?? null;
    
            // Procesar archivo si existe
            $archivo = 'SIN ARCHIVO';
            if (!empty($_FILES['file']['name'])) {
                // Ruta absoluta más confiable
                $base_path = realpath(APPPATH . '../..') . '/images/';
                $upload_path = $base_path . $sitio_id . '/' . $vehiculo_id . '/';
    
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
                    $archivo = "/images/{$sitio_id}/{$vehiculo_id}/{$upload_data['file_name']}";
                } else {
                    $error = $this->upload->display_errors();
                    log_message('error', 'Error al subir archivo: ' . $error);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Error al subir el archivo',
                        'upload_error' => $error
                    ]);
                    return;
                }
            }
    
            // Construir datos después de procesar el archivo
            $data = [
                'vehiculo_id' => $vehiculo_id,
                'tipofactura_id' => $jsonData['tipofac'] ?? null,
                'expedidapor' => $jsonData['expedida'] ?? null,
                'folio' => $jsonData['folio'] ?? null,
                'fecha' => $jsonData['fecha'] ?? null,
                'archivo' => $archivo,
                'tipostatus_id' => $jsonData['statusfac'] ?? null,
            ];
    
            // Validación de campos requeridos
            $required = ['vehiculo_id', 'tipofactura_id', 'expedidapor', 'folio', 'fecha', 'tipostatus_id'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $response = [
                        'status' => 'error',
                        'message' => "Falta el campo requerido: $field",
                        'data_received' => $jsonData
                    ];
                    echo json_encode($response);
                    return;
                }
            }
    
            $result = $this->FacturasModel->agregarFactura($sitio_id, $data);
    
            if ($result) {
                $response = [
                    'message' => 'Factura agregada correctamente',
                    'status' => 'success',
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error al agregar la factura en la base de datos'
                ];
            }
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Error interno al procesar la factura',
            ];
            echo json_encode($response);
        }
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

    public function listFacturas() {
        try {
            $valid = $this->validate();

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            $vehiculo_id = isset($jsonData['vehiculo_id']) ? $jsonData['vehiculo_id'] : 0;

            if (empty($vehiculo_id)) {
                echo json_encode([
                    'error' => 'El id del vehículo es requerido',
                    'status' => 'error'
                ]);
                return;
            }

            $result = $this->FacturasModel->listarFacturas($vehiculo_id, $sitio_id);

            if ($result) {
                $response = array(
                    'data' => $result,
                    'status' => 'success'
                );
            } else {
                $response = array(
                    'error' => 'Error al obtener las facturas',
                    'status' => 'error',
                );
            }
        echo json_encode($response);
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
            // Validación inicial
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
    
            // Obtener datos
            $jsonData = $this->input->post() ?: json_decode(file_get_contents('php://input'), true);
            
            if (empty($jsonData)) {
                parse_str(file_get_contents('php://input'), $jsonData);
            }
    
            // Validar campos requeridos
            $required = ['factura_id', 'idvehiculo', 'tipofac', 'expedida', 'folio', 'fecha', 'statusfac'];
            foreach ($required as $field) {
                if (empty($jsonData[$field])) {
                    $response = ['status' => 'error', 'message' => "Falta el campo requerido: $field"];
                    echo json_encode($response);
                    return;
                }
            }
    
            $factura_id = $jsonData['factura_id'];
            $vehiculo_id = $jsonData['idvehiculo'];
    
            // Obtener la factura actual completa
            $factura_actual = $this->FacturasModel->obtenerFacturaCompleta($factura_id, $sitio_id);
            if (!$factura_actual) {
                echo json_encode(['status' => 'error', 'message' => 'Factura no encontrada']);
                return;
            }
    
            // Mantener el archivo actual por defecto
            $archivo = $factura_actual['archivo'] ?? 'SIN ARCHIVO';
    
            // Procesar archivo solo si se envía uno nuevo
            if (!empty($_FILES['file']['name'])) {
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
                    $nuevo_archivo = "/images/{$sitio_id}/{$vehiculo_id}/{$upload_data['file_name']}";
                    
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
    
            // Construir datos para actualización
            $data = [
                'vehiculo_id' => $vehiculo_id,
                'tipofactura_id' => $jsonData['tipofac'],
                'expedidapor' => $jsonData['expedida'],
                'folio' => $jsonData['folio'],
                'fecha' => $jsonData['fecha'],
                'archivo' => $archivo, // Mantiene la existente o usa la nueva
                'tipostatus_id' => $jsonData['statusfac'],
            ];
    
            $result = $this->FacturasModel->actualizarFactura($factura_id, $sitio_id, $data);
    
            echo json_encode($result ? [
                'message' => 'Factura actualizada',
                'status' => 'success',
            ] : [
                'message' => 'Error al actualizar',
                'status' => 'error',
            ]);
    
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al procesar la solicitud',
                'status' => 'error',
            ]);
        }
    }
}