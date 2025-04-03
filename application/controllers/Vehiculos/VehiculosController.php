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

            

        }catch(Exception $e){
            echo json_encode([
                'error' => 'Error al agregar el vehiculo',
                'status' => 'error']);
        }
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

            if (empty($jsonData)) {
                echo json_encode([
                    'error' => 'No se proporcionaron datos',
                    'status' => 'error']);
                exit;
            }

            if (!$jsonData['id'] || !$jsonData['numeroserie'] || !$jsonData['tipo_vehiculo'] ||
                !$jsonData['precio'] || !$jsonData['kilometraje'] || !$jsonData['color_id'] ||
                !$jsonData['noexpediente'] || !$jsonData['status_venta'] || !$jsonData['duplicado'] ||
                !$jsonData['numero_placa'] || !$jsonData['observaciones'] || !$jsonData['fecha'] ||
                !$jsonData['duenio'] || !$jsonData['version_id'] || !$jsonData['precio_contado'] ||
                !$jsonData['nomotor'] || !$jsonData['garantia']) { 

                echo json_encode([
                    'error' => 'Faltan datos',
                    'status' => 'error']);
                exit;
            }

            $data = [
                'numeroserie' => $jsonData['numeroserie'], // 1
                'tipo_vehiculo' => $jsonData['tipo_vehiculo'], // 2
                'version_id' => $jsonData['version_id'], // 3 
                'color_id' => $jsonData['color_id'], // 4 
                'noexpediente' => $jsonData['noexpediente'],  // 5 
                'nomotor' => $jsonData['nomotor'], // 6
                'kilometraje' => $jsonData['kilometraje'],  // 7
                'precio' => $jsonData['precio'], // 8
                'precio_contado' => $jsonData['precio_contado'], // 9
                'fecha' => $jsonData['fecha'],  // 10
                'duenio' => $jsonData['duenio'], // 11 
                'garantia' => $jsonData['garantia'], // 12
                'status_venta' => $jsonData['status_venta'], // 13
                'duplicado' => $jsonData['duplicado'], // 14
                'numero_placa' => $jsonData['numero_placa'], // 15 
                'observaciones' => $jsonData['observaciones'] // 16
            ];

            $result = $this->VehiculosModel->actualizarVehiculo($jsonData['id'], $sitio_id, $data);

            if ($result) {
                echo json_encode([
                    'success' => 'Vehiculo actualizado correctamente',
                    'status' => 'success']);
            } else {
                echo json_encode([
                    'error' => 'Error al actualizar los vehiculos',
                    'status' => 'error']);
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
                log_message('error', 'Token inválido o mal formado');
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

            if (empty($jsonData)) {
                echo json_encode([
                    'error' => 'No se proporcionaron datos',
                    'status' => 'error']);
                exit;
            }

            if (!$jsonData['marcaid']) {
                echo json_encode([
                    'error' => 'No se proporcionó el ID de la marca',
                    'status' => 'error']);
                exit;
            }

            $result = $this->VehiculosModel->obtenerModelo($jsonData['marcaid']);

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

}