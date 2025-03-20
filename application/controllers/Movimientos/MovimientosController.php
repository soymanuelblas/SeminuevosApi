<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MovimientosController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Movimientos/MovimientosModel');
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

    public function listMovimientos() {
        try{
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
            
            // Obtener el último ID de operación
            $ultimoID = $this->MovimientosModel->obtenerUltimoID($sitio_id);
            if (!$ultimoID) {
                $this->response([
                    'error' => 'No se encontraron operaciones',
                    'status' => 'error']);
                return;
            }

            $movresultado = $ultimoID['idope'];

            // Obtener el ID del corte
            $corteID = $this->MovimientosModel->obtenerCorteID($movresultado, $sitio_id);

            // Obtener los movimientos
            $movimientos = $this->MovimientosModel->obtenerMovimientos($sitio_id, $movresultado);

            if(!$movimientos){
                $this->response([
                    'error' => 'No se encontraron movimientos',
                    'status' => 'error']);
                return;
            }

            echo json_encode([
                'movimientos' => $movimientos,
                'status' => 'success'
            ]);

        }catch(Exception $e){
            echo json_encode([
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
}