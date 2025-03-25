<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsuarioController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuarios/UsuariosModel');
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

    public function listUsuarios() {
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

            $usuarios = $this->UsuariosModel->obtenerUsuarios($sitio_id);

            if($usuarios) {
                echo json_encode([
                    'data' => $usuarios,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'No se encontraron usuarios',
                    'status' => 'error']);
            }
        }catch(Exception $e) {
            $error = array(
                'error' => $e->getMessage(),
                'status' => 'error'
            );
            echo json_encode($error);
        }
    }

    public function updateUsuario() {
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

            if(!jsonData['seccion1'] || !jsonData['alta'] || !jsonData['modificacion'] || !jsonData['consulta']
            || !jsonData['seccion6'] || !jsonData['altasucursal'] || !jsonData['modificacionsucursal'] || !jsonData['consultasucursal']
            || !jsonData['seccion2'] || !jsonData['altaventa'] || !jsonData['modificacionventa'] || !jsonData['consultaventa']
            || !jsonData['altacompra'] || !jsonData['modificacioncompra'] || !jsonData['consultacompra']
            || !jsonData['altaconsignacion'] || !jsonData['modificacionconsignacion'] || !jsonData['consultaconsignacion']
            || !jsonData['altaintermediacion'] || !jsonData['modificacionintermediacion'] || !jsonData['consultaintermediacion']
            || !jsonData['seccion3'] || !jsonData['altavehiculo'] || !jsonData['modificacionvehiculo'] || !jsonData['consultavehiculo']
            || !jsonData['altatenencia'] || !jsonData['modificaciontenencia'] || !jsonData['consultatenencia']
            || !jsonData['altafactura'] || !jsonData['modificacionfactura'] || !jsonData['consultafactura']
            || !jsonData['altamodelo'] || !jsonData['modificacionmodelo'] || !jsonData['consultamodelo']
            || !jsonData['altaannio'] || !jsonData['modificacionannio'] || !jsonData['consultaannio']
            || !jsonData['altamarca'] || !jsonData['modificacionmarca'] || !jsonData['consultamarca']
            || !jsonData['altaversion'] || !jsonData['modificacionversion'] || !jsonData['consultaversion']
            || !jsonData['seccion4'] || !jsonData['altasitio'] || !jsonData['modificacionsitio'] || !jsonData['consultasitio']
            || !jsonData['seccion5'] || !jsonData['altausuario'] || !jsonData['modificacionusuario'] || !jsonData['consultausuario']
            || !jsonData['seccion7'] || !jsonData['altafinanciamiento'] || !jsonData['modificacionfinanciamiento'] || !jsonData['consultafinanciamiento']
            || !jsonData['seccion8'] || !jsonData['altaprospecto'] || !jsonData['modificacionprospecto'] || !jsonData['consultaprospecto']
            || !jsonData['altaoportunidad'] || !jsonData['modificacionoportunidad'] || !jsonData['consultaoportunidad']
            || !jsonData['altaseguimiento'] || !jsonData['modificacionseguimiento'] || !jsonData['consultaseguimiento']
            || !jsonData['consulta1'] || !jsonData['consultafacturaimagen1'] || !jsonData['consultafacturaimagen']
            || !jsonData['reporteclientes'] || !jsonData['reporteventas'] || !jsonData['reportecompras']
            || !jsonData['reporteconsignacion'] || !jsonData['reporteintermediacion'] || !jsonData['reportevehiculos']
            || !jsonData['reportesegimiento'] || !jsonData['reporteoportunidad'] || !jsonData['consultaprospectousuario']
            || !jsonData['consultaoportunidadusuario'] || !jsonData['seccion9'] || !jsonData['altacotizacion']
            || !jsonData['seccion10'] || !jsonData['altrecibo'] || !jsonData['modificacionrecibo'] || !jsonData['consultarecibo']
            || !jsonData['modificacionmovimientos'] || !jsonData['consultarmovimientos'] || !jsonData['altacorte']
            || !jsonData['consultarcaja'] || !jsonData['altamovimientos'] || !jsonData['consultacartafactura']
            || !jsonData['consultarecibodoc'] || !jsonData['cuentaspagar'] || !jsonData['cuentascobrar']
            || !jsonData['seccion11'] || !jsonData['altadiagnostico'] || !jsonData['consultadiagnostico']
            || !jsonData['imprimirdiagnostico'] || !jsonData['agregarartipresu'] || !jsonData['autorizarrefacciones']
            || !jsonData['pedirrefacciones'] || !jsonData['recibirrefacciones'] || !jsonData['repararfallas']
            || !jsonData['reimprimirrecepcion'] || !jsonData['reimprimirentrega'] || !jsonData['guardardiagnostico']
            || !jsonData['modificaciondiagnostico'] || !jsonData['regresarstatus'] || !jsonData['seccion12']
            || !jsonData['altatareas'] || !jsonData['eliminarimagenes'] || !jsonData['deleteimgdiag']
            || !jsonData['verdiagcompleto'] || !jsonData['verdiagusuario'] || !jsonData['imgcompletasdiag']
            || !jsonData['imgusuariodiag'] || !jsonData['verpresucompleto'] || !jsonData['verpresuusuario']
            || !jsonData['enviarcotizacion'] || !jsonData['deleteimgrecepcion'] || !jsonData['deleteimgreparacion']
            || !jsonData['entregarvehiculo'] || !jsonData['agregarmodulosdiag'] || !jsonData['imgcompletasrepa']
            || !jsonData['imgusuariorepa'] || !jsonData['pedirrefausuario'] || !jsonData['pedirrefatodas']
            || !jsonData['recibirrefausuario'] || !jsonData['recibirrefacompletas'] || !jsonData['consultadiagnosticorecibidos']
            || !jsonData['consultadiagnosticorepara'] || !jsonData['repafallas'] || !jsonData['repararfallasusuario']
            || !jsonData['altarefacciones'] || !jsonData['modificarrefacciones'] || !jsonData['porsitio']
            || !jsonData['porrazon'] || !jsonData['cambiarsitio'] || !jsonData['seccion13']
            || !jsonData['altafacturacion'] || !jsonData['consultafacturas'] || !jsonData['cancelarfacturas']
            || !jsonData['agregarcomplementos'] || !jsonData['verpdf'] || !jsonData['descargarxml']
            || !jsonData['altacuentas'] || !jsonData['modificarcuentas'] || !jsonData['consultacuentas']
            || !jsonData['seccion14'] || !jsonData['altarazon'] || !jsonData['consultarazon'] || !jsonData['modificarrazon']
            || !jsonData['estadoresultados']) {
                echo json_encode([
                    'error' => 'No se proporcionaron los permisos necesarios',
                    'status' => 'error']);
                exit;
            }

            $permisos_str = $this->requestPermisos($jsonData);

            $data = [
                'nombre' => strtoupper($jsonData['nombre']),
                'usr' => $jsonData['usuario'],
                'pwd' => base64_encode($jsonData['contra']),
                'permisos' => $permisos_str,
                'tipostatus_id' => $jsonData['statuscliente'],
                'sitio_id' => $sitio_id,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $result = $this->UsuariosModel->actualizarUsuario($jsonData['id'], $data);

            if ($result) {
                echo json_encode([
                    'success' => 'Usuario actualizado correctamente',
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'Error al actualizar usuario',
                    'status' => 'error'
                ]);
            }
        }catch(Exception $e) {
            $error = array(
                'error' => $e->getMessage(),
                'status' => 'error'
            );
            echo json_encode($error);
        }
    }

    private function requestPermisos($jsonData) {
        return 
            ($jsonData['seccion1'] ? 1 : 0) .
            ($jsonData['alta'] ? 1 : 0) .
            ($jsonData['modificacion'] ? 1 : 0) .
            ($jsonData['consulta'] ? 1 : 0) .
            ($jsonData['consulta1'] ? 1 : 0) .
            ($jsonData['reporteclientes'] ? 1 : 0) .
            ($jsonData['seccion6'] ? 1 : 0) .
            ($jsonData['altasucursal'] ? 1 : 0) .
            ($jsonData['modificacionsucursal'] ? 1 : 0) .
            ($jsonData['consultasucursal'] ? 1 : 0) .
            ($jsonData['seccion2'] ? 1 : 0) .
            ($jsonData['altaventa'] ? 1 : 0) .
            ($jsonData['modificacionventa'] ? 1 : 0) .
            ($jsonData['consultaventa'] ? 1 : 0) .
            ($jsonData['altacompra'] ? 1 : 0) .
            ($jsonData['modificacioncompra'] ? 1 : 0) .
            ($jsonData['consultacompra'] ? 1 : 0) .
            ($jsonData['altaconsignacion'] ? 1 : 0) .
            ($jsonData['modificacionconsignacion'] ? 1 : 0) .
            ($jsonData['consultaconsignacion'] ? 1 : 0) .
            ($jsonData['altaintermediacion'] ? 1 : 0) .
            ($jsonData['modificacionintermediacion'] ? 1 : 0) .
            ($jsonData['consultaintermediacion'] ? 1 : 0) .
            ($jsonData['seccion3'] ? 1 : 0) .
            ($jsonData['altavehiculo'] ? 1 : 0) .
            ($jsonData['modificacionvehiculo'] ? 1 : 0) .
            ($jsonData['consultavehiculo'] ? 1 : 0) .
            ($jsonData['altatenencia'] ? 1 : 0) .
            ($jsonData['modificaciontenencia'] ? 1 : 0) .
            ($jsonData['consultatenencia'] ? 1 : 0) .
            ($jsonData['altafactura'] ? 1 : 0) .
            ($jsonData['modificacionfactura'] ? 1 : 0) .
            ($jsonData['consultafactura'] ? 1 : 0) .
            ($jsonData['consultafacturaimagen1'] ? 1 : 0) .
            ($jsonData['consultafacturaimagen'] ? 1 : 0) .
            ($jsonData['altamodelo'] ? 1 : 0) .
            ($jsonData['modificacionmodelo'] ? 1 : 0) .
            ($jsonData['consultamodelo'] ? 1 : 0) .
            ($jsonData['altaannio'] ? 1 : 0) .
            ($jsonData['modificacionannio'] ? 1 : 0) .
            ($jsonData['consultaannio'] ? 1 : 0) .
            ($jsonData['altamarca'] ? 1 : 0) .
            ($jsonData['modificacionmarca'] ? 1 : 0) .
            ($jsonData['consultamarca'] ? 1 : 0) .
            ($jsonData['altaversion'] ? 1 : 0) .
            ($jsonData['modificacionversion'] ? 1 : 0) .
            ($jsonData['consultaversion'] ? 1 : 0) .
            ($jsonData['seccion4'] ? 1 : 0) .
            ($jsonData['altasitio'] ? 1 : 0) .
            ($jsonData['modificacionsitio'] ? 1 : 0) .
            ($jsonData['consultasitio'] ? 1 : 0) .
            ($jsonData['seccion5'] ? 1 : 0) .
            ($jsonData['altausuario'] ? 1 : 0) .
            ($jsonData['modificacionusuario'] ? 1 : 0) .
            ($jsonData['consultausuario'] ? 1 : 0) .
            ($jsonData['seccion7'] ? 1 : 0) .
            ($jsonData['altafinanciamiento'] ? 1 : 0) .
            ($jsonData['modificacionfinanciamiento'] ? 1 : 0) .
            ($jsonData['consultafinanciamiento'] ? 1 : 0) .
            ($jsonData['seccion8'] ? 1 : 0) .
            ($jsonData['altaprospecto'] ? 1 : 0) .
            ($jsonData['modificacionprospecto'] ? 1 : 0) .
            ($jsonData['consultaprospecto'] ? 1 : 0) .
            ($jsonData['altaoportunidad'] ? 1 : 0) .
            ($jsonData['modificacionoportunidad'] ? 1 : 0) .
            ($jsonData['consultaoportunidad'] ? 1 : 0) .
            ($jsonData['altaseguimiento'] ? 1 : 0) .
            ($jsonData['modificacionseguimiento'] ? 1 : 0) .
            ($jsonData['consultaseguimiento'] ? 1 : 0) .
            ($jsonData['reporteventas'] ? 1 : 0) .
            ($jsonData['reportecompras'] ? 1 : 0) .
            ($jsonData['reporteconsignacion'] ? 1 : 0) .
            ($jsonData['reporteintermediacion'] ? 1 : 0) .
            ($jsonData['reportevehiculos'] ? 1 : 0) .
            ($jsonData['reportesegimiento'] ? 1 : 0) .
            ($jsonData['reporteoportunidad'] ? 1 : 0) .
            ($jsonData['consultaprospectousuario'] ? 1 : 0) .
            ($jsonData['consultaoportunidadusuario'] ? 1 : 0) .
            ($jsonData['seccion9'] ? 1 : 0) .
            ($jsonData['altacotizacion'] ? 1 : 0) .
            ($jsonData['seccion10'] ? 1 : 0) .
            ($jsonData['altrecibo'] ? 1 : 0) .
            ($jsonData['modificacionrecibo'] ? 1 : 0) .
            ($jsonData['consultarecibo'] ? 1 : 0) .
            ($jsonData['modificacionmovimientos'] ? 1 : 0) .
            ($jsonData['consultarmovimientos'] ? 1 : 0) .
            ($jsonData['altacorte'] ? 1 : 0) .
            ($jsonData['consultarcaja'] ? 1 : 0) .
            ($jsonData['altamovimientos'] ? 1 : 0) .
            ($jsonData['consultacartafactura'] ? 1 : 0) .
            ($jsonData['consultarecibodoc'] ? 1 : 0) .
            ($jsonData['cuentaspagar'] ? 1 : 0) .
            ($jsonData['cuentascobrar'] ? 1 : 0) .
            ($jsonData['seccion11'] ? 1 : 0) .
            ($jsonData['altadiagnostico'] ? 1 : 0) .
            ($jsonData['consultadiagnostico'] == 1 ? 1 : 0) .
            ($jsonData['imprimirdiagnostico'] ? 1 : 0) .
            ($jsonData['agregarartipresu'] ? 1 : 0) .
            ($jsonData['autorizarrefacciones'] ? 1 : 0) .
            ($jsonData['pedirrefacciones'] ? 1 : 0) .
            ($jsonData['recibirrefacciones'] ? 1 : 0) .
            ($jsonData['repararfallas'] ? 1 : 0) .
            ($jsonData['reimprimirrecepcion'] ? 1 : 0) .
            ($jsonData['reimprimirentrega'] ? 1 : 0) .
            ($jsonData['guardardiagnostico'] ? 1 : 0) .
            ($jsonData['modificaciondiagnostico'] ? 1 : 0) .
            ($jsonData['regresarstatus'] ? 1 : 0) .
            ($jsonData['seccion12'] ? 1 : 0) .
            ($jsonData['altatareas'] ? 1 : 0) .
            ($jsonData['eliminarimagenes'] ? 1 : 0) .
            ($jsonData['deleteimgdiag'] ? 1 : 0) .
            ($jsonData['verdiagcompleto'] == 1 ? 1 : 0) .
            ($jsonData['verdiagusuario'] == 1 ? 1 : 0) .
            ($jsonData['imgcompletasdiag'] == 1 ? 1 : 0) .
            ($jsonData['imgusuariodiag'] == 1 ? 1 : 0) .
            ($jsonData['verpresucompleto'] == 1 ? 1 : 0) .
            ($jsonData['verpresuusuario'] == 1 ? 1 : 0) .
            ($jsonData['enviarcotizacion'] ? 1 : 0) .
            ($jsonData['deleteimgrecepcion'] ? 1 : 0) .
            ($jsonData['deleteimgreparacion'] ? 1 : 0) .
            ($jsonData['entregarvehiculo'] ? 1 : 0) .
            ($jsonData['agregarmodulosdiag'] ? 1 : 0) .
            ($jsonData['imgcompletasrepa'] == 1 ? 1 : 0) .
            ($jsonData['imgusuariorepa'] == 1 ? 1 : 0) .
            ($jsonData['pedirrefausuario'] == 1 ? 1 : 0) .
            ($jsonData['pedirrefatodas'] == 1 ? 1 : 0) .
            ($jsonData['recibirrefausuario'] == 1 ? 1 : 0) .
            ($jsonData['recibirrefacompletas'] == 1 ? 1 : 0) .
            ($jsonData['consultadiagnosticorecibidos'] == 1 ? 1 : 0) .
            ($jsonData['consultadiagnosticorepara'] == 1 ? 1 : 0) .
            ($jsonData['repafallas'] == 1 ? 1 : 0) .
            ($jsonData['repararfallasusuario'] == 1 ? 1 : 0) .
            ($jsonData['altarefacciones'] ? 1 : 0) .
            ($jsonData['modificarrefacciones'] ? 1 : 0) .
            ($jsonData['porsitio'] == 1 ? 1 : 0) .
            ($jsonData['porrazon'] == 1 ? 1 : 0) .
            ($jsonData['cambiarsitio'] ? 1 : 0) .
            ($jsonData['seccion13'] ? 1 : 0) .
            ($jsonData['altafacturacion'] ? 1 : 0) .
            ($jsonData['consultafacturas'] ? 1 : 0) .
            ($jsonData['cancelarfacturas'] ? 1 : 0) .
            ($jsonData['agregarcomplementos'] ? 1 : 0) .
            ($jsonData['verpdf'] ? 1 : 0) .
            ($jsonData['descargarxml'] ? 1 : 0) .
            ($jsonData['altacuentas'] ? 1 : 0) .
            ($jsonData['modificarcuentas'] ? 1 : 0) .
            ($jsonData['consultacuentas'] ? 1 : 0) .
            ($jsonData['seccion14'] ? 1 : 0) .
            ($jsonData['altarazon'] ? 1 : 0) .
            ($jsonData['consultarazon'] ? 1 : 0) .
            ($jsonData['modificarrazon'] ? 1 : 0) .
            ($jsonData['estadoresultados'] ? 1 : 0);
    }

}