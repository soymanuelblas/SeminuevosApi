<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehiculosController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehiculos/VehiculosModel');
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

    public function listVehiculos() {
        try{
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
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            $marca = $jsonData['marca'];
            $modelo = $jsonData['modelo'];
            $annio = $jsonData['annio'];
            $expediente = $jsonData['expediente'];

            $result = $this->VehiculosModel->obtenerVehiculos($sitio_id, $marca, $modelo, $annio, $expediente);

            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron vehiculos',
                    'status' => 'error']);
            }

        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar los vehiculos',
                'status' => 'error']);
        }
    }
}