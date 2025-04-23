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
                    'status' => 'success',
                    'message' => 'Factura agregada correctamente',
                    'archivo' => $archivo,
                    'data' => $data
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
            // Validación de token
            $valid = $this->validate();
            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
    
            // Obtener datos del POST
            $factura_id = $this->input->post('factura_id'); // Nuevo campo para identificar la factura a actualizar
            $vehiculo_id = $this->input->post('idvehiculo');
            $tipo_factura = $this->input->post('tipofac');
            $expedida_por = $this->input->post('expedida');
            $folio = $this->input->post('folio');
            $fecha = $this->input->post('fecha');
            $status = $this->input->post('statusfac');
    
            // Validar campos requeridos
            $campos_requeridos = [
                'factura_id' => $factura_id,
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
    
            // Obtener la factura actual para mantener el archivo si no se sube uno nuevo
            $factura_actual = $this->FacturasModel->obtenerFacturaPorId($factura_id, $sitio_id);
            if (!$factura_actual) {
                echo json_encode([
                    'error' => 'Factura no encontrada',
                    'status' => 'error'
                ]);
                return;
            }
    
            $archivo = $factura_actual['archivo']; // Mantener el archivo actual por defecto
    
            // Procesar archivo si se envió uno nuevo
            if (!empty($_FILES['file']['name'])) {
                // Ruta base externa
                $base_path = '/seminuevos/images/';
                
                // Verificar permisos de escritura
                if (!is_writable($base_path)) {
                    $this->output
                        ->set_status_header(500)
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'error' => 'El directorio base no tiene permisos de escritura',
                            'status' => 'error'
                        ]));
                    return;
                }
    
                // Ruta completa para este vehículo
                $upload_path = $base_path . $sitio_id . '/' . $vehiculo_id . '/';
                
                // Crear directorios si no existen
                if (!file_exists($upload_path)) {
                    if (!mkdir($upload_path, 0777, true)) {
                        $this->output
                            ->set_status_header(500)
                            ->set_content_type('application/json')
                            ->set_output(json_encode([
                                'error' => 'No se pudo crear el directorio de destino',
                                'status' => 'error',
                                'path' => $upload_path
                            ]));
                        return;
                    }
                    chmod($upload_path, 0777);
                }
    
                // Configuración de upload
                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size' => 40000,
                    'encrypt_name' => true,
                    'overwrite' => false,
                    'remove_spaces' => true
                ];
    
                $this->load->library('upload', $config);
    
                if (!$this->upload->do_upload('file')) {
                    $error = $this->upload->display_errors('', '');
                    log_message('error', 'Error al subir archivo: ' . $error);
                    
                    echo json_encode([
                        'error' => 'Error al subir el archivo: ' . $error,
                        'status' => 'error'
                    ]);
                    return;
                }
    
                $upload_data = $this->upload->data();
                $archivo = '/seminuevos/images/' . $sitio_id . '/' . $vehiculo_id . '/' . $upload_data['file_name'];
                
                // Verificar que el archivo se subió correctamente
                if (!file_exists($upload_path . $upload_data['file_name'])) {
                    $this->output
                        ->set_status_header(500)
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'error' => 'El archivo se subió pero no se encuentra en la ruta esperada',
                            'status' => 'error'
                        ]));
                    return;
                }
    
                // Eliminar el archivo anterior si existe y no es "SIN ARCHIVO"
                if ($factura_actual['archivo'] != 'SIN ARCHIVO' && file_exists(FCPATH . $factura_actual['archivo'])) {
                    unlink(FCPATH . $factura_actual['archivo']);
                }
            }
    
            // Preparar datos para actualizar
            $data = [
                'vehiculo_id' => $vehiculo_id,
                'tipofactura_id' => $tipo_factura,
                'expedidapor' => $expedida_por,
                'folio' => $folio,
                'fecha' => $fecha,
                'archivo' => $archivo,
                'tipostatus_id' => $status,
            ];
    
            // Actualizar en la base de datos
            $result = $this->FacturasModel->actualizarFactura($factura_id, $sitio_id, $data);
    
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' => 'Factura actualizada correctamente',
                    'archivo' => $archivo,
                    'ruta_completa' => $archivo
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error al actualizar la factura'
                ];
            }
    
        } catch (Exception $e) {
            log_message('error', 'Exception en updateFactura: ' . $e->getMessage());
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => 'Error al actualizar la factura: ' . $e->getMessage(),
                    'status' => 'error'
                ]));
        }
    }

}