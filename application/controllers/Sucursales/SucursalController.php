<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SucursalController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Sucursales/SucursalModel');
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

    public function add_sucursal() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                echo json_encode([
                    'error' => 'Token no proporcionado',
                    'status' => 'error']);
                exit;
            }
    
            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode([
                    'error' => 'Formato de token inválido',
                    'status' => 'error']);
                exit;
            }
            $token = $splitToken[1];
    
            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                echo json_encode(['error' => 'Token inválido o mal formado']);
                exit;
            }
    
            $info = json_decode($valid);
            $id = $info->data->id;
            $sitio_id = $info->data->sitio_id;
    
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
    
            if (empty($jsonData)) {
                echo json_encode([
                    'error' => 'No se proporcionaron datos',
                    'status' => 'error']);
                exit;
            }

            if (empty($jsonData['nombre']) || empty($jsonData['domicilio1']) || 
            empty($jsonData['ciudad']) || empty($jsonData['estado']) || 
            empty($jsonData['cp']) || empty($jsonData['pais']) || 
            empty($jsonData['telefono1']) || empty($jsonData['correo']) || 
            empty($jsonData['pass_correo']) || empty($jsonData['rfc'])) {
                echo json_encode([
                    'error' => 'Faltan datos obligatorios',
                    'status' => 'error']);
                exit;
            }
    
            $data = [
                'nombre' => strtoupper($jsonData['nombre']),
                'domicilio1' => strtoupper($jsonData['domicilio1']),
                'domicilio2' => strtoupper($jsonData['domicilio2']),
                'ciudad' => strtoupper($jsonData['ciudad']),
                'estado' => strtoupper($jsonData['estado']),
                'cp' => $jsonData['cp'],
                'pais' => strtoupper($jsonData['pais']),
                'telefono1' => $jsonData['telefono1'],
                'telefono2' => $jsonData['telefono2'],
                'contacto' => isset($jsonData['contacto']) ? strtoupper($jsonData['contacto']) : '',
                'correo' => $jsonData['correo'],
                'pass_correo' => $jsonData['pass_correo'],
            ];
    
            $rfc = $jsonData['rfc'];
    
            $result = $this->SucursalModel->add_sucursal($id, $data, $id, $rfc, $sitio_id);
            
            if($result) {
                echo json_encode(
                [
                    'message' => 'Sucursal agregada correctamente',
                    'status' => 'success'
                ]);
            } else {
                echo json_encode(
                [
                    'message' => 'No se pudo agregar la sucursal',
                    'status' => 'error'
                ]);
            }
    
        }catch(Exception $e) {
            echo json_encode(
            [
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function update_sucursal() {
        try {

            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                echo json_encode([
                    'error' => 'Token no proporcionado',
                    'status' => 'error']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode([
                    'error' => 'Formato de token inválido',
                    'status' => 'error']);
                exit;
            }
            $token = $splitToken[1];
    
            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                echo json_encode(['error' => 'Token inválido o mal formado']);
                exit;
            }

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            if (empty($jsonData)) {
                echo json_encode([
                    'error' => 'No se proporcionaron datos',
                    'status' => 'error']);
                exit;
            }

            $data = [
                'id' => $jsonData['id'],
                'nombre' => strtoupper($jsonData['nombre']),
                'domicilio1' => strtoupper($jsonData['domicilio1']),
                'domicilio2' => strtoupper($jsonData['domicilio2']),
                'ciudad' => strtoupper($jsonData['ciudad']),
                'estado' => strtoupper($jsonData['estado']),
                'cp' => $jsonData['cp'],
                'pais' => strtoupper($jsonData['pais']),
                'telefono1' => $jsonData['telefono1'],
                'telefono2' => $jsonData['telefono2'],
                'contacto' => strtoupper($jsonData['contacto']),
                'correo' => $jsonData['correo'],
                'pass_correo' => $jsonData['pass_correo'],
            ];

            $result = $this->SucursalModel->update_sucursal($data);
            
            if($result) {
                echo json_encode(
                    [
                        'message' => 'Sucursal actualizada correctamente',
                        'status' => 'success'
                    ]);
            } else {
                echo json_encode(
                    [
                        'message' => 'No se pudo actualizar la sucursal',
                        'status' => 'error'
                    ]);
            } 

        }catch(Exception $e){
            echo json_encode(
            [
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function list_sucursal() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                echo json_encode([
                    'error' => 'Token no proporcionado',
                    'status' => 'error']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode([
                    'error' => 'Formato de token inválido',
                    'status' => 'error']);
                exit;
            }
            $token = $splitToken[1];
    
            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                echo json_encode(['error' => 'Token inválido o mal formado']);
                exit;
            }

            $info = json_decode($valid);
            $id = $info->data->sitio_id;

            $result = $this->SucursalModel->list_sucursal($id);
            
            if($result) {
                echo json_encode(
                    [
                        'data' => $result,
                        'status' => 'success'
                    ]);
            } else {
                echo json_encode(
                    [
                        'message' => 'No se encontraron sucursales',
                        'status' => 'error'
                    ]);
            }
        }catch(Exception $e){
            echo json_encode(
                [
                    'error' => $e->getMessage(),
                    'status' => 'error'
                ]);
        }
    }


}