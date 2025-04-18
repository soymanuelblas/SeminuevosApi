<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehiculosController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vehiculos/VehiculosModel');
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

    public function addVehiculo() {
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

            if (empty($jsonData)) {
                echo json_encode(['error' => 'No se proporcionaron datos']);
                exit;
            }

            $requiredFields = [
                'version', 'tipo_vehiculo', 'color', 'noexpediente', 'numeroserie',
                'nomotor', 'kilometraje', 'precio_venta', 'precio_contado', 'duenios',
                'garantia', 'placa', 'duplicado', 'observaciones'
            ];

            foreach ($requiredFields as $field) {
                if (empty($jsonData[$field])) {
                    echo json_encode([
                        'error' => "Falta el dato: $field",
                        'status' => 'error'
                    ]);
                    exit;
                }
            }

            $data = [
                'sitio_id' => $sitio_id,
                'version_id' => $jsonData['version'], // 3 
                'tipo_vehiculo' => $jsonData['tipo_vehiculo'], // 4 
                'color_id' => $jsonData['color'], // 5 
                'noexpediente' => $jsonData['noexpediente'],  // 6 
                'numeroserie' => $jsonData['numeroserie'], // 1
                'nomotor' => $jsonData['nomotor'], // 7
                'kilometraje' => $jsonData['kilometraje'],  // 8
                'precio' => $jsonData['precio_venta'], // 9
                'precio_contado' => $jsonData['precio_contado'], // 10
                'duenio' => $jsonData['duenios'], // 11
                'garantia' => $jsonData['garantia'], // 12
                'status_venta' => 4081,
                'duplicado' => $jsonData['duplicado'],
                'numero_placa' => $jsonData['placa'],
                'observaciones' => $jsonData['observaciones'],
                'tipostatus_id' => 751,
                'fecha' => date('Y-m-d H:i:s')
            ];

            $result = $this->VehiculosModel->insertarVehiculo($data);

            if ($result) {
                echo json_encode([
                    'success' => 'Vehiculo agregado correctamente',
                    'status' => 'success']);
            } else {
                echo json_encode([
                    'error' => 'Error al agregar el vehiculo',
                    'status' => 'error']);
            }
        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al agregar el vehiculo ' . $e->getMessage(),
                'status' => 'error']);
        }
    }

    public function listVehiculos() {
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
    
            // Obtener parámetros sin escapar (para arrays)
            $marca = isset($jsonData['marca']) ? $jsonData['marca'] : null;
            $modelo = isset($jsonData['modelo']) ? $jsonData['modelo'] : null;
            $annio = isset($jsonData['annio']) ? $jsonData['annio'] : null;
            $expediente = isset($jsonData['expediente']) ? $jsonData['expediente'] : null;
    
            // Convertir a array si es un solo valor
            if ($marca && !is_array($marca)) $marca = [$marca];
            if ($modelo && !is_array($modelo)) $modelo = [$modelo];
            if ($annio && !is_array($annio)) $annio = [$annio];
            if ($expediente && !is_array($expediente)) $expediente = [$expediente];
    
            $result = $this->VehiculosModel->obtenerVehiculos($sitio_id, $marca, $modelo, $annio, $expediente);
    
            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron vehiculos con los filtros aplicados',
                    'status' => 'error']);
            }
        } catch(Exception $e) {
            echo json_encode([
                'error' => 'Error al listar los vehiculos: ' . $e->getMessage(),
                'status' => 'error']);
        }
    }

    public function listVehiculoById() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }

            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            if (empty($jsonData) || !$jsonData['vehiculo_id']) {
                echo json_encode([
                    'error' => 'No se proporcionó el ID del vehiculo',
                    'status' => 'error']);
                exit;
            }

            $vehiculo_id = $jsonData['vehiculo_id'];

            $result = $this->VehiculosModel->obtenerVehiculoPorId($vehiculo_id, $sitio_id);

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

    public function updateVehiculos() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar el token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error'
                ]);
                exit;
            }

            // Decodificar el token
            $info = json_decode($valid);
            $sitio_id = isset($info->data->sitio_id) ? $info->data->sitio_id : 0;
            log_message('debug', 'Sitio ID obtenido del token: ' . $sitio_id);

            // Obtener los datos del cuerpo de la solicitud
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            if (empty($jsonData)) {
                log_message('error', 'No se proporcionaron datos en la solicitud');
                echo json_encode([
                    'error' => 'No se proporcionaron datos',
                    'status' => 'error'
                ]);
                exit;
            }
            log_message('debug', 'Datos recibidos: ' . json_encode($jsonData));

            // Validar que todos los campos requeridos estén presentes
            $requiredFields = [
                'id', 'numeroserie', 'tipo_vehiculo', 'precio', 'kilometraje', 'color_id',
                'noexpediente', 'status_venta', 'duplicado', 'numero_placa', 'observaciones',
                'duenio', 'version_id', 'precio_contado', 'nomotor', 'garantia'
            ];
            foreach ($requiredFields as $field) {
                if (empty($jsonData[$field])) {
                    echo json_encode([
                        'error' => "Falta el campo requerido: $field",
                        'status' => 'error'
                    ]);
                    exit;
                }
            }

            // Preparar los datos para la actualización
            $data = [
                'numeroserie' => $jsonData['numeroserie'],
                'tipo_vehiculo' => $jsonData['tipo_vehiculo'],
                'version_id' => $jsonData['version_id'],
                'color_id' => $jsonData['color_id'],
                'noexpediente' => $jsonData['noexpediente'],
                'nomotor' => $jsonData['nomotor'],
                'kilometraje' => $jsonData['kilometraje'],
                'precio' => $jsonData['precio'],
                'precio_contado' => $jsonData['precio_contado'],
                'fecha' => date('Y-m-d H:i:s'),
                'duenio' => $jsonData['duenio'],
                'garantia' => $jsonData['garantia'],
                'status_venta' => $jsonData['status_venta'],
                'duplicado' => $jsonData['duplicado'],
                'numero_placa' => $jsonData['numero_placa'],
                'observaciones' => $jsonData['observaciones']
            ];

            $result = $this->VehiculosModel->actualizarVehiculo($jsonData['id'], $sitio_id, $data);
            if ($result) {
                echo json_encode([
                    'success' => 'Vehículo actualizado correctamente',
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al actualizar los vehículos',
                    'status' => 'error'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'error' => 'Error al actualizar los vehiculos ' . $e->getMessage(),
                'status' => 'error']);
        }
    }

    public function listGarantia() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }
            $result = $this->VehiculosModel->obtenerGarantia();
            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron garantias',
                    'status' => 'error']);
            }
        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar las garantias',
                'status' => 'error']);
        }
    }

    public function listTipoVehiculo() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }

            $result = $this->VehiculosModel->obtenerTipoVehiculo();

            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron tipos de vehiculos',
                    'status' => 'error']);
            }
        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar los tipos de vehiculos',
                'status' => 'error']);
        }
    }

    public function listTipoStatus() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }

            $result = $this->VehiculosModel->obtenerTipoStatus();

            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron tipos de status',
                    'status' => 'error']);
            }
        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar los tipos de status',
                'status' => 'error']);
        }
    }

    public function listColor() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }

            $result = $this->VehiculosModel->obtenerColor();

            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron colores',
                    'status' => 'error']);
            }
        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar los colores',
                'status' => 'error']);
        }
    }

    public function listMarca() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
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
            
            $result = $this->VehiculosModel->obtenerMarca();

            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron marcas',
                    'status' => 'error']);
            }

        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar las marcas',
                'status' => 'error']);
        }
    }

    public function listModelo() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }
            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            $idmarca = isset($jsonData['marcaid']) ? $jsonData['marcaid'] : null;

            if (!isset($jsonData['marcaid'])) {
                $idmarca = null;
            }

            $result = $this->VehiculosModel->obtenerModelo($idmarca);

            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron modelos',
                    'status' => 'error']);
            }
        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar los modelos',
                'status' => 'error']);
        }
    }

    public function listVersion() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }

            $jsonData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();

            if (empty($jsonData)) {
                echo json_encode([
                    'error' => 'No se proporcionaron datos',
                    'status' => 'error']);
                exit;
            }

            if (!$jsonData['tipomodelo_id']) {
                echo json_encode([
                    'error' => 'No se proporcionó el ID del modelo',
                    'status' => 'error']);
                exit;
            }

            $result = $this->VehiculosModel->obtenerVersion($jsonData['tipomodelo_id']);

            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron versiones',
                    'status' => 'error']);
            }
        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar las versiones',
                'status' => 'error']);
        }
    }

    public function listDuenio() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }

            $result = $this->VehiculosModel->obtenerDuenio();

            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron dueños',
                    'status' => 'error']);
            }
        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar los dueños',
                'status' => 'error']);
        }
    }

    public function listDuplicado() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }

            $result = $this->VehiculosModel->obtenerDuplicado();

            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron duplicados',
                    'status' => 'error']);
            }
        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar los duplicados',
                'status' => 'error']);
        }
    }

    public function listAnnio() {
        try {
            $headerToken = $this->input->get_request_header('Authorization', TRUE);
            if (empty($headerToken)) {
                log_message('error', 'Token no proporcionado');
                echo json_encode(['error' => 'Token no proporcionado']);
                exit;
            }

            $splitToken = explode(' ', $headerToken);
            if (count($splitToken) !== 2 || $splitToken[0] !== 'Bearer') {
                log_message('error', 'Formato de token inválido');
                echo json_encode(['error' => 'Formato de token inválido']);
                exit;
            }
            $token = $splitToken[1];

            // Validar token
            $valid = verifyAuthToken($token);
            if (!$valid || !is_string($valid) || !json_decode($valid)) {
                log_message('error', 'Token inválido o mal formado');
                echo json_encode([
                    'error' => 'Token inválido o mal formado',
                    'status' => 'error']);
                exit;
            }

            $result = $this->VehiculosModel->obtenerAnnio();

            if($result){
                echo json_encode([
                    'data' => $result,
                    'status' => 'success']);
            }else{
                echo json_encode([
                    'error' => 'No se encontraron annio',
                    'status' => 'error']);
            }
        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al listar los annio',
                'status' => 'error']);
        }
    }

}