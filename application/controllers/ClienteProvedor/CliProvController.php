<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CliProvController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ClienteProveedor/ClienteProvModel');
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

    public function addClienteProvedor() {
        try {
            // Obtener el token de autorización
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }
            
            // Validar formato del token
            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
    
            // Extraer y validar el token
            $token = $splitToken[1];
    
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                echo json_encode(['error' => 'Token inválido o mal formado']);
                exit;
            }
    
            $info = json_decode($valid);
            $sitio = $info->data->sitio_id ?? null;
            $usuario_id = $info->data->id ?? null;
            
            // Obtener los datos del cliente/proveedor
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            
            if (!$jsonData || empty($jsonData['nombre']) || empty($jsonData['rfc']) || 
                empty($jsonData['telefono1']) || empty($jsonData['telefono2']) || 
                empty($jsonData['email']) || empty($jsonData['domicilio']) || 
                empty($jsonData['colonia']) || empty($jsonData['cp']) || 
                empty($jsonData['ciudad']) || empty($jsonData['estado']) || 
                empty($jsonData['tipocliente_id'])) {
                echo json_encode([
                    'error' => 'Todos los campos son requeridos',
                    'status' => 'error'
                ]);
                exit;
            }
    
            // Validar longitud del RFC
            if (strlen($jsonData['rfc']) > 13 || strlen($jsonData['rfc']) < 12) {
                echo json_encode([
                    'error' => 'El RFC no puede ser mayor a 13 caracteres ni menor a 12 caracteres',
                    'status' => 'error'
                ]);
                exit;
            }

            if($jsonData['tipocliente_id'] == 5302) {
                $jsonData['tipocliente_id'] = 5300;
            }
    
            // Preparar los datos para la inserción
            $data = [
                'nombre' => strtoupper($jsonData['nombre']),
                'rfc' => strtoupper($jsonData['rfc']),
                'telefono1' => $jsonData['telefono1'],
                'telefono2' => $jsonData['telefono2'],
                'email' => $jsonData['email'],
                'domicilio' => strtoupper($jsonData['domicilio']),
                'colonia' => strtoupper($jsonData['colonia']),
                'cp' => $jsonData['cp'],
                'usuario_id' => $usuario_id,
                'ciudad' => strtoupper($jsonData['ciudad']),
                'estado' => strtoupper($jsonData['estado']),
                'tipostatus_id' => 851,
                'tipocliente_id' => strtoupper($jsonData['tipocliente_id']),
                'password' => 'X',
                'localizacion' => 0
            ];

            $data_moral = [
                'escriturapublica' => strtoupper($jsonData['escritura']),
                'numeronotaria' => strtoupper($jsonData['num_notaria']),
                'nombrenotario' => strtoupper($jsonData['nom_notario']),
                'ciudadnotaria' => strtoupper($jsonData['cd_notaria']),
                'fechaconstitucion' => $jsonData['fecha_constitucion'],
                'PersonaMoral' => strtoupper($jsonData['nom_representante']),
                'sitio_id' => $sitio
            ];

            // Insertar en la base de datos
            $result = $this->ClienteProvModel->add_cliente_provedor($sitio, $data, $data_moral);
    
            if ($result) {
                echo json_encode([
                    'success' => 'Cliente/Proveedor agregado correctamente',
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al agregar cliente/proveedor',
                    'status' => 'error'
                ]);
            }
            exit;
    
        } catch (Exception $e) {
            error_log("Excepción capturada: " . $e->getMessage());
            echo json_encode([
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function updateClienteProveedor() {
        try {
            // TODO Implementar actualización de cliente/proveedor
            // // Obtener el token de autorización
            // $headerToken = $this->input->get_request_header('Authorization', TRUE);
            // if (empty($headerToken)) {
            //     echo json_encode(['error' => 'Token no proporcionado']);
            //     exit;
            // }
            
            // // Validar formato del token
            // $splitToken = explode(' ', $headerToken);
            // if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
            //     echo json_encode(['error' => 'Formato de token inválido']);
            //     exit;
            // }

            // // Extraer y validar el token
            // $token = $splitToken[1];

            // $valid = verifyAuthToken($token);
            // if (!$valid || !is_string($valid) || !json_decode($valid)) {
            //     echo json_encode(['error' => 'Token inválido o mal formado']);
            //     exit;
            // }

            // // Obtener los datos del cliente/proveedor
            // $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            // if (!$jsonData) {
            //     echo json_encode(['error' => 'Datos no proporcionados']);
            //     exit;
            // }

            // // Validar datos requeridos
            // $requiredFields = ['id', 'nombre', 'rfc', 'telefono1', 'telefono2', 'email', 'domicilio', 'colonia', 'cp', 'ciudad', 'estado', 'tipocliente_id', 'tipostatus_id'];
            // foreach ($requiredFields as $field) {
            //     if (empty($jsonData[$field])) {
            //         echo json_encode([
            //             'error' => "El campo $field es requerido",
            //             'status' => 'error'
            //         ]);
            //         exit;
            //     }
            // }

            // // Preparar datos para la actualización
            // $updateData = [
            //     'id' => $jsonData['id'],
            //     'nombre' => $jsonData['nombre'],
            //     'domicilio' => $jsonData['domicilio'],
            //     'colonia' => $jsonData['colonia'],
            //     'cp' => $jsonData['cp'],
            //     'ciudad' => $jsonData['ciudad'],
            //     'estado' => $jsonData['estado'],
            //     'rfc' => $jsonData['rfc'],
            //     'telefono1' => $jsonData['telefono1'],
            //     'telefono2' => $jsonData['telefono2'],
            //     'email' => $jsonData['email'],
            //     'tipocliente_id' => $jsonData['tipocliente_id'],
            //     'tipostatus_id' => $jsonData['tipostatus_id']
            // ];
            // // Actualizar cliente/proveedor en la base de datos
            // $result = $this->ClienteProvModel->updateClientProvider($jsonData['id'], $updateData);

            // if ($result) {
            //     echo json_encode([
            //         'success' => 'Cliente/Proveedor actualizado correctamente',
            //         'status' => 'success'
            //     ]);
            // } else {
            //     echo json_encode([
            //         'error' => 'Error al actualizar cliente/proveedor',
            //         'status' => 'error'
            //     ]);
            // }

        } catch (Exception $e) {
            log_message("Excepción capturada: " . $e->getMessage());
            echo json_encode([
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function listClientesProveedores() {
        try {
            // Obtener el token de autorización
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }
            
            // Validar formato del token
            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
    
            // Extraer y validar el token
            $token = $splitToken[1];
    
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                echo json_encode(['error' => 'Token inválido o mal formado']);
                exit;
            }

            $info = json_decode($valid);
            $usuario_id = $info->data->id ?? null;

            // Obtener los clientes/proveedores
            $clientesProveedores = $this->ClienteProvModel->listClientProvider($usuario_id);

            if($clientesProveedores) {
                echo json_encode([
                    'data' => $clientesProveedores,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'No se encontraron clientes/proveedores',
                    'status' => 'error'
                ]);
            }

        }catch (Exception $e) {
            error_log("Excepción capturada: " . $e->getMessage());
            echo json_encode([
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

}