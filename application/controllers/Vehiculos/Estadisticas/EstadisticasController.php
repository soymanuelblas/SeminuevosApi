<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstadisticasController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehiculos/Estadisticas/EstadisticasModel');
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

    public function listEstadisticas() {
        try {
            // Validar autenticación si es necesario
            $valid = $this->validate();

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
    
            // Obtener datos de entrada
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            
            $vehiculo_id = $this->input->get_post('vehiculo_id') ?: 
                          (isset($jsonData['vehiculo_id']) ? $jsonData['vehiculo_id'] : null);
            
            if (!$vehiculo_id) {
                throw new Exception("El parámetro 'vehiculo_id' es requerido");
            }
    
            // Obtener estadísticas
            $result = $this->EstadisticasModel->obtenerEstadisticas($vehiculo_id, $sitio_id);
    
            if ($result) {
                $response = [
                    'data' => [
                        'interesados' => [
                            'en_pausa' => [
                                'total' => $result['conteos']['en_pausa'],
                                'porcentaje' => $result['porcentajes']['en_pausa']
                            ],
                            'en_proceso' => [
                                'total' => $result['conteos']['en_proceso'],
                                'porcentaje' => $result['porcentajes']['en_proceso']
                            ],
                            'no_lograda' => [
                                'total' => $result['conteos']['no_lograda'],
                                'porcentaje' => $result['porcentajes']['no_lograda']
                            ],
                            'lograda' => [
                                'total' => $result['conteos']['lograda'],
                                'porcentaje' => $result['porcentajes']['lograda']
                            ],
                            'total' => $result['total'],
                            'total_porcentaje' => 100
                        ],
                        'gastos' => [
                            'total' => $result['gastos'],
                            'detalles' => $result['detalle_gastos']
                        ]
                    ],
                    'status' => 'success',
                ];
            } else {
                $response = [
                    'data' => null,
                    'status' => 'success',
                    'message' => 'No se encontraron datos para los parámetros proporcionados'
                ];
            }
            echo json_encode($response);
        } catch (Exception $e) {
            json_encode([
                'status' => 'error',
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ]);
        }
    }

}