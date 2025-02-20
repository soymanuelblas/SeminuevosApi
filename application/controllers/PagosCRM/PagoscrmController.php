<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PagoscrmController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('PagosCRM/PagoscrmModel');
        $this->load->helper('verifyAuthToken_helper');
        
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

    public function listPayments() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
    
            // Extraer token
            $token = $splitToken[1];
    
            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }

            $info = json_decode($valid);
            $id = isset($info->data->id) ? $info->data->id : 0;

            if($id != 1) {
                echo json_encode([
                    'error' => 'No tienes permisos para realizar esta acción',
                    'status' => 'error']);
                exit;
            }

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            // Obtener fechas desde los parámetros de la URL
            $fInicio = $jsonData['fInicio'];
            $fUltima = $jsonData['fUltima'];

            log_message('info', 'Fechas recibidas: ' . $fInicio . ' - ' . $fUltima);

            $result = $this->PagoscrmModel->obtener_pagos($fInicio, $fUltima);

            if($result) {
                echo json_encode(array(
                    'data' => $result,
                    'status' => 'success'
                ));
            } else {
                echo json_encode(array(
                    'message' => 'No se encontraron pagos',
                    'status' => 'error'
                ));
            }

        }catch(Exception $e) {
            echo json_encode(array(
                'status' => 500,
                'message' => $e->getMessage()
            ));
        }
    }
}