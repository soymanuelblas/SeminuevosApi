<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InicioController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Inicio/InicioModel');
        $this->load->helper('verifyauthtoken_helper');
        
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Max-Age: 3600');
            exit(0);
        }
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Credentials: true');
    }

    // public function getVehiculosPorMes() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

    //         $fecha_ini = $jsonData['fecha_ini'];
    //         $fecha_fin = $jsonData['fecha_fin'];

    //         $result = $this->InicioModel->obtenerVehiculosPorMes($sitio_id, $fecha_ini, $fecha_fin);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    // public function getVehiculosPorAnio() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

    //         $fecha_ini_anio = $jsonData['fecha_ini_anio'];
    //         $fecha_fin_anio = $jsonData['fecha_fin_anio'];

    //         $result = $this->InicioModel->obtenerVehiculosPorAnio($sitio_id, $fecha_ini_ano, $fecha_fin_ano);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    // public function getOportunidadesAtrasadas() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

    //         $today = $jsonData['today'];

    //         $result = $this->InicioModel->obtenerOportunidadesAtrasadas($sitio_id, $today);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    // public function getOportunidadesProceso() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $result = $this->InicioModel->obtenerOportunidadesProceso($sitio_id);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    // public function getOportunidadesNoLogradas() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $result = $this->InicioModel->obtenerOportunidadesNoLogradas($sitio_id);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    // public function getCobrarTotales() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $result = $this->InicioModel->obtenerCobrarTotales($sitio_id);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    // public function getCobrarMes() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

    //         $fecha_ini = $jsonData['fecha_ini'];
    //         $fecha_fin = $jsonData['fecha_fin'];

    //         $result = $this->InicioModel->obtenerCobrarMes($sitio_id, $fecha_ini, $fecha_fin);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    // public function getCobrarVencidas() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

    //         $today = $jsonData['today'];

    //         $result = $this->InicioModel->obtenerCobrarVencidas($sitio_id, $today);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    // public function getPagarVencidas() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

    //         $today = $jsonData['today'];

    //         $result = $this->InicioModel->obtenerPagarVencidas($sitio_id, $today);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    // public function getPagarMes() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

    //         $fecha_ini = $jsonData['fecha_ini'];
    //         $fecha_fin = $jsonData['fecha_fin'];

    //         $result = $this->InicioModel->obtenerPagarMes($sitio_id, $fecha_ini, $fecha_fin);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    // public function getPagarTotales() {
    //     try {
    //         // Verificar si el encabezado Authorization llega correctamente
    //         $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
    //         if (empty($headerToken)) {
    //             echo json_encode([
    //                 'error' => 'Token no proporcionado',
    //                 'status' => 'error']);
    //             exit;
    //         }
    
    //         // Verificar si el token tiene el formato correcto
    //         $splitToken = explode(' ', $headerToken);
    //         if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
    //             echo json_encode([
    //                 'error' => 'Formato de token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }
    //         $token = $splitToken[1];
    
    //         // Verificar el token
    //         $valid = verifyAuthToken($token);
    //         if (!$valid) {
    //             echo json_encode([
    //                 'error' => 'Token inválido',
    //                 'status' => 'error']);
    //             exit;
    //         }

    //         $info = json_decode($valid);
    //         $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            
    //         $result = $this->InicioModel->obtenerPagarTotales($sitio_id);

    //         if($result) {
    //             echo json_encode([
    //                 'data' => $result,
    //                 'status' => 'success'
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'message' => 'No se encontraron registros',
    //                 'status' => 'error'
    //             ]);
    //         }
    //     }catch (Exception $e) {
    //         echo json_encode([
    //             'message' => $e->getMessage(),
    //             'status' => 'error'
    //         ]);
    //     }
    // }

    public function listDataStatistic() {
        try {
            // Verificar si el encabezado Authorization llega correctamente
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
    
            if (empty($headerToken)) {
                echo json_encode([
                    'error' => 'Token no proporcionado',
                    'status' => 'error']);
                exit;
            }
    
            // Verificar si el token tiene el formato correcto
            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode([
                    'error' => 'Formato de token inválido',
                    'status' => 'error']);
                exit;
            }
            $token = $splitToken[1];
    
            // Verificar el token
            $valid = verifyAuthToken($token);
            if (!$valid) {
                echo json_encode([
                    'error' => 'Token inválido',
                    'status' => 'error']);
                exit;
            }

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            $fecha_ini = $jsonData['fecha_ini'];
            $fecha_fin = $jsonData['fecha_fin'];

            $today = $jsonData['today'];

            $vehiculosPorMes = $this->InicioModel->obtenerVehiculosPorMes($sitio_id, $fecha_ini, $fecha_fin);
            $vehiculosPorAnio = $this->InicioModel->obtenerVehiculosPorAnio($sitio_id, $fecha_ini, $fecha_fin);
            $oportunidadesAtrasadas = $this->InicioModel->obtenerOportunidadesAtrasadas($sitio_id, $today);
            $oportunidadesProceso = $this->InicioModel->obtenerOportunidadesProceso($sitio_id);
            $oportunidadesNoLogradas = $this->InicioModel->obtenerOportunidadesNoLogradas($sitio_id);
            $cobrarTotales = $this->InicioModel->obtenerCobrarTotales($sitio_id);
            $cobrarMes = $this->InicioModel->obtenerCobrarMes($sitio_id, $fecha_ini, $fecha_fin);
            $cobrarVencidas = $this->InicioModel->obtenerCobrarVencidas($sitio_id, $today);
            $pagarVencidas = $this->InicioModel->obtenerPagarVencidas($sitio_id, $today);
            $pagarMes = $this->InicioModel->obtenerPagarMes($sitio_id, $fecha_ini, $fecha_fin);
            $pagarTotales = $this->InicioModel->obtenerPagarTotales($sitio_id);

            $data = array(
                $vehiculosPorMes,
                $vehiculosPorAnio,
                $oportunidadesAtrasadas,
                $oportunidadesProceso,
                $oportunidadesNoLogradas,
                $cobrarTotales,
                $cobrarMes,
                $cobrarVencidas,
                $pagarVencidas,
                $pagarMes,
                $pagarTotales
            );

            if($data) {
                echo json_encode([
                    'data' => $data,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'message' => 'No se encontraron registros',
                    'status' => 'error'
                ]);
            }
        }catch (Exception $e) {
            echo json_encode([
                'message' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

}