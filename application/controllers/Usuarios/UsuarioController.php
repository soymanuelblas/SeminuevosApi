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
                log_message('error', 'No se encontraron usuarios');
                echo json_encode([
                    'message' => 'No se encontraron usuarios',
                    'status' => 'error']);
            }
        }catch(Exception $e) {
            $error = array(
                'message' => $e->getMessage(),
                'status' => 'error'
            );
            echo json_encode($error);
        }
    }

    public function updateUsuario() {
        try {
            // Verify token
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
            $valid = verifyAuthToken($token);
            
            if (!$valid) {
                echo json_encode(['error' => 'Token inválido']);
                exit;
            }
    
            // Get POST data
            $postData = json_decode(file_get_contents('php://input'), true) ?: $this->input->post();
            
            if (empty($postData)) {
                echo json_encode(['error' => 'No se recibieron datos']);
                exit;
            }
            
            // Validate required fields
            $requiredFields = ['usuarioid', 'nombre', 'usuario', 'contra', 'statuscliente', 'sitioedit', 'roledit'];
            foreach ($requiredFields as $field) {
                if (!isset($postData[$field])) {
                    echo json_encode(['error' => "El campo {$field} es requerido"]);
                    exit;
                }
            }
            
            // Prepare user data
            $userData = [
                'id' => $postData['usuarioid'],
                'nombre' => strtoupper($postData['nombre']),
                'usuario' => $postData['usuario'],
                'contra' => base64_encode($postData['contra']),
                'status' => $postData['statuscliente'],
                'sitio' => $postData['sitioedit'],
                'rol' => $postData['roledit']
            ];
            
            // Process permissions
            $permissions = [];
            
            // Section 1: Clients
            $permissions['clientes'] = [
                'seccion' => isset($postData['seccion1']) ? $postData['seccion1'] : 0,
                'alta' => isset($postData['alta']) ? $postData['alta'] : 0,
                'modificacion' => isset($postData['modificacion']) ? $postData['modificacion'] : 0,
                'consulta' => isset($postData['consulta']) ? $postData['consulta'] : 0,
                'consulta1' => isset($postData['consulta1']) ? $postData['consulta1'] : 0,
                'reporteclientes' => isset($postData['reporteclientes']) ? $postData['reporteclientes'] : 0
            ];
            
            // If section is disabled, set all permissions to 0
            if ($permissions['clientes']['seccion'] == 0) {
                foreach ($permissions['clientes'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['clientes'][$key] = 0;
                    }
                }
            }
            
            // Section 2: Operations
            $permissions['operaciones'] = [
                'seccion' => isset($postData['seccion2']) ? $postData['seccion2'] : 0,
                'altaventa' => isset($postData['altaventa']) ? $postData['altaventa'] : 0,
                'modificacionventa' => isset($postData['modificacionventa']) ? $postData['modificacionventa'] : 0,
                'consultaventa' => isset($postData['consultaventa']) ? $postData['consultaventa'] : 0,
                'altacompra' => isset($postData['altacompra']) ? $postData['altacompra'] : 0,
                'modificacioncompra' => isset($postData['modificacioncompra']) ? $postData['modificacioncompra'] : 0,
                'consultacompra' => isset($postData['consultacompra']) ? $postData['consultacompra'] : 0,
                'altaconsignacion' => isset($postData['altaconsignacion']) ? $postData['altaconsignacion'] : 0,
                'modificacionconsignacion' => isset($postData['modificacionconsignacion']) ? $postData['modificacionconsignacion'] : 0,
                'consultaconsignacion' => isset($postData['consultaconsignacion']) ? $postData['consultaconsignacion'] : 0,
                'altaintermediacion' => isset($postData['altaintermediacion']) ? $postData['altaintermediacion'] : 0,
                'modificacionintermediacion' => isset($postData['modificacionintermediacion']) ? $postData['modificacionintermediacion'] : 0,
                'consultaintermediacion' => isset($postData['consultaintermediacion']) ? $postData['consultaintermediacion'] : 0,
                'reporteventas' => isset($postData['reporteventas']) ? $postData['reporteventas'] : 0,
                'reportecompras' => isset($postData['reportecompras']) ? $postData['reportecompras'] : 0,
                'reporteconsignacion' => isset($postData['reporteconsignacion']) ? $postData['reporteconsignacion'] : 0,
                'reporteintermediacion' => isset($postData['reporteintermediacion']) ? $postData['reporteintermediacion'] : 0,
                'consultacartafactura' => isset($postData['consultacartafactura']) ? $postData['consultacartafactura'] : 0,
                'consultarecibodoc' => isset($postData['consultarecibodoc']) ? $postData['consultarecibodoc'] : 0
            ];
            
            if ($permissions['operaciones']['seccion'] == 0) {
                foreach ($permissions['operaciones'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['operaciones'][$key] = 0;
                    }
                }
            }
            
            // Section 3: Vehicles
            $permissions['vehiculos'] = [
                'seccion' => isset($postData['seccion3']) ? $postData['seccion3'] : 0,
                'altavehiculo' => isset($postData['altavehiculo']) ? $postData['altavehiculo'] : 0,
                'modificacionvehiculo' => isset($postData['modificacionvehiculo']) ? $postData['modificacionvehiculo'] : 0,
                'consultavehiculo' => isset($postData['consultavehiculo']) ? $postData['consultavehiculo'] : 0,
                'altatenencia' => isset($postData['altatenencia']) ? $postData['altatenencia'] : 0,
                'modificaciontenencia' => isset($postData['modificaciontenencia']) ? $postData['modificaciontenencia'] : 0,
                'consultatenencia' => isset($postData['consultatenencia']) ? $postData['consultatenencia'] : 0,
                'altafactura' => isset($postData['altafactura']) ? $postData['altafactura'] : 0,
                'modificacionfactura' => isset($postData['modificacionfactura']) ? $postData['modificacionfactura'] : 0,
                'consultafactura' => isset($postData['consultafactura']) ? $postData['consultafactura'] : 0,
                'consultafacturaimagen1' => isset($postData['consultafacturaimagen1']) ? $postData['consultafacturaimagen1'] : 0,
                'consultafacturaimagen' => isset($postData['consultafacturaimagen']) ? $postData['consultafacturaimagen'] : 0,
                'altapublicacion' => isset($postData['altapublicacion']) ? $postData['altapublicacion'] : 0,
                'modificacionpublicacion' => isset($postData['modificacionpublicacion']) ? $postData['modificacionpublicacion'] : 0,
                'consultapublicacion' => isset($postData['consultapublicacion']) ? $postData['consultapublicacion'] : 0,
                'altaimagenen' => isset($postData['altaimagenen']) ? $postData['altaimagenen'] : 0,
                'modificacionimagenen' => isset($postData['modificacionimagenen']) ? $postData['modificacionimagenen'] : 0,
                'consultaimagenen' => isset($postData['consultaimagenen']) ? $postData['consultaimagenen'] : 0,
                'eliminarimagenes' => isset($postData['eliminarimagenes']) ? $postData['eliminarimagenes'] : 0,
                'altafina' => isset($postData['altafina']) ? $postData['altafina'] : 0,
                'modificacionfina' => isset($postData['modificacionfina']) ? $postData['modificacionfina'] : 0,
                'consultafina' => isset($postData['consultafina']) ? $postData['consultafina'] : 0,
                'altaatributo' => isset($postData['altaatributo']) ? $postData['altaatributo'] : 0,
                'modificacionatributo' => isset($postData['modificacionatributo']) ? $postData['modificacionatributo'] : 0,
                'consultaatributo' => isset($postData['consultaatributo']) ? $postData['consultaatributo'] : 0,
                'altaextra' => isset($postData['altaextra']) ? $postData['altaextra'] : 0,
                'modificacionextra' => isset($postData['modificacionextra']) ? $postData['modificacionextra'] : 0,
                'consultaextra' => isset($postData['consultaextra']) ? $postData['consultaextra'] : 0,
                'altacaracteristica' => isset($postData['altacaracteristica']) ? $postData['altacaracteristica'] : 0,
                'modificacioncaracteristica' => isset($postData['modificacioncaracteristica']) ? $postData['modificacioncaracteristica'] : 0,
                'consultacaracteristica' => isset($postData['consultacaracteristica']) ? $postData['consultacaracteristica'] : 0,
                'reportevehiculos' => isset($postData['reportevehiculos']) ? $postData['reportevehiculos'] : 0
            ];
            
            if ($permissions['vehiculos']['seccion'] == 0) {
                foreach ($permissions['vehiculos'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['vehiculos'][$key] = 0;
                    }
                }
            }
            
            // Section 4: Sites
            $permissions['sitios'] = [
                'seccion' => isset($postData['seccion4']) ? $postData['seccion4'] : 0,
                'altasitio' => isset($postData['altasitio']) ? $postData['altasitio'] : 0,
                'modificacionsitio' => isset($postData['modificacionsitio']) ? $postData['modificacionsitio'] : 0,
                'consultasitio' => isset($postData['consultasitio']) ? $postData['consultasitio'] : 0
            ];
            
            if ($permissions['sitios']['seccion'] == 0) {
                foreach ($permissions['sitios'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['sitios'][$key] = 0;
                    }
                }
            }
            
            // Section 5: Users
            $permissions['usuarios'] = [
                'seccion' => isset($postData['seccion5']) ? $postData['seccion5'] : 0,
                'altausuario' => isset($postData['altausuario']) ? $postData['altausuario'] : 0,
                'modificacionusuario' => isset($postData['modificacionusuario']) ? $postData['modificacionusuario'] : 0,
                'consultausuario' => isset($postData['consultausuario']) ? $postData['consultausuario'] : 0
            ];
            
            if ($permissions['usuarios']['seccion'] == 0) {
                foreach ($permissions['usuarios'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['usuarios'][$key] = 0;
                    }
                }
            }
            
            // Section 6: Branches
            $permissions['sucursales'] = [
                'seccion' => isset($postData['seccion6']) ? $postData['seccion6'] : 0,
                'altasucursal' => isset($postData['altasucursal']) ? $postData['altasucursal'] : 0,
                'modificacionsucursal' => isset($postData['modificacionsucursal']) ? $postData['modificacionsucursal'] : 0,
                'consultasucursal' => isset($postData['consultasucursal']) ? $postData['consultasucursal'] : 0,
                'cambiarsitio' => isset($postData['cambiarsitio']) ? $postData['cambiarsitio'] : 0
            ];
            
            if ($permissions['sucursales']['seccion'] == 0) {
                foreach ($permissions['sucursales'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['sucursales'][$key] = 0;
                    }
                }
            }
            
            // Section 7: Super User
            $permissions['superusuario'] = [
                'seccion' => isset($postData['seccion7']) ? $postData['seccion7'] : 0,
                'altafinanciamiento' => isset($postData['altafinanciamiento']) ? $postData['altafinanciamiento'] : 0,
                'modificacionfinanciamiento' => isset($postData['modificacionfinanciamiento']) ? $postData['modificacionfinanciamiento'] : 0,
                'consultafinanciamiento' => isset($postData['consultafinanciamiento']) ? $postData['consultafinanciamiento'] : 0,
                'altamodelo' => isset($postData['altamodelo']) ? $postData['altamodelo'] : 0,
                'modificacionmodelo' => isset($postData['modificacionmodelo']) ? $postData['modificacionmodelo'] : 0,
                'consultamodelo' => isset($postData['consultamodelo']) ? $postData['consultamodelo'] : 0,
                'altaannio' => isset($postData['altaannio']) ? $postData['altaannio'] : 0,
                'modificacionannio' => isset($postData['modificacionannio']) ? $postData['modificacionannio'] : 0,
                'consultaannio' => isset($postData['consultaannio']) ? $postData['consultaannio'] : 0,
                'altamarca' => isset($postData['altamarca']) ? $postData['altamarca'] : 0,
                'modificacionmarca' => isset($postData['modificacionmarca']) ? $postData['modificacionmarca'] : 0,
                'consultamarca' => isset($postData['consultamarca']) ? $postData['consultamarca'] : 0,
                'altaversion' => isset($postData['altaversion']) ? $postData['altaversion'] : 0,
                'modificacionversion' => isset($postData['modificacionversion']) ? $postData['modificacionversion'] : 0,
                'consultaversion' => isset($postData['consultaversion']) ? $postData['consultaversion'] : 0,
                'altacuentas' => isset($postData['altacuentas']) ? $postData['altacuentas'] : 0,
                'modificarcuentas' => isset($postData['modificarcuentas']) ? $postData['modificarcuentas'] : 0,
                'consultacuentas' => isset($postData['consultacuentas']) ? $postData['consultacuentas'] : 0,
                'porsitio' => 0,
                'porrazon' => 0
            ];
            
            // Process view info permissions
            if (isset($postData['verinfo'])) {
                if ($postData['verinfo'] == 1) {
                    $permissions['superusuario']['porsitio'] = 1;
                    $permissions['superusuario']['porrazon'] = 0;
                } else if ($postData['verinfo'] == 2) {
                    $permissions['superusuario']['porsitio'] = 0;
                    $permissions['superusuario']['porrazon'] = 1;
                }
            }
            
            if ($permissions['superusuario']['seccion'] == 0) {
                foreach ($permissions['superusuario'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['superusuario'][$key] = 0;
                    }
                }
            }
            
            // Section 8: Opportunities/Follow-ups/Prospects
            $permissions['oportunidades'] = [
                'seccion' => isset($postData['seccion8']) ? $postData['seccion8'] : 0,
                'altaprospecto' => isset($postData['altaprospecto']) ? $postData['altaprospecto'] : 0,
                'modificacionprospecto' => isset($postData['modificacionprospecto']) ? $postData['modificacionprospecto'] : 0,
                'consultaprospecto' => isset($postData['consultaprospecto']) ? $postData['consultaprospecto'] : 0,
                'consultaprospectousuario' => isset($postData['consultaprospectousuario']) ? $postData['consultaprospectousuario'] : 0,
                'altaseguimiento' => isset($postData['altaseguimiento']) ? $postData['altaseguimiento'] : 0,
                'modificacionseguimiento' => isset($postData['modificacionseguimiento']) ? $postData['modificacionseguimiento'] : 0,
                'consultaseguimiento' => isset($postData['consultaseguimiento']) ? $postData['consultaseguimiento'] : 0,
                'altaoportunidad' => isset($postData['altaoportunidad']) ? $postData['altaoportunidad'] : 0,
                'modificacionoportunidad' => isset($postData['modificacionoportunidad']) ? $postData['modificacionoportunidad'] : 0,
                'consultaoportunidad' => isset($postData['consultaoportunidad']) ? $postData['consultaoportunidad'] : 0,
                'consultaoportunidadusuario' => isset($postData['consultaoportunidadusuario']) ? $postData['consultaoportunidadusuario'] : 0,
                'reportesegimiento' => isset($postData['reportesegimiento']) ? $postData['reportesegimiento'] : 0,
                'reporteoportunidad' => isset($postData['reporteoportunidad']) ? $postData['reporteoportunidad'] : 0
            ];
            
            if ($permissions['oportunidades']['seccion'] == 0) {
                foreach ($permissions['oportunidades'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['oportunidades'][$key] = 0;
                    }
                }
            }
            
            // Section 9: Quotes
            $permissions['cotizaciones'] = [
                'seccion' => isset($postData['seccion9']) ? $postData['seccion9'] : 0,
                'altacotizacion' => isset($postData['altacotizacion']) ? $postData['altacotizacion'] : 0
            ];
            
            if ($permissions['cotizaciones']['seccion'] == 0) {
                foreach ($permissions['cotizaciones'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['cotizaciones'][$key] = 0;
                    }
                }
            }
            
            // Section 10: Cash Movements
            $permissions['movimientosCaja'] = [
                'seccion' => isset($postData['seccion10']) ? $postData['seccion10'] : 0,
                'altrecibo' => isset($postData['altrecibo']) ? $postData['altrecibo'] : 0,
                'modificacionrecibo' => isset($postData['modificacionrecibo']) ? $postData['modificacionrecibo'] : 0,
                'consultarecibo' => isset($postData['consultarecibo']) ? $postData['consultarecibo'] : 0,
                'modificacionmovimientos' => isset($postData['modificacionmovimientos']) ? $postData['modificacionmovimientos'] : 0,
                'consultarmovimientos' => isset($postData['consultarmovimientos']) ? $postData['consultarmovimientos'] : 0,
                'altacorte' => isset($postData['altacorte']) ? $postData['altacorte'] : 0,
                'consultarcaja' => isset($postData['consultarcaja']) ? $postData['consultarcaja'] : 0,
                'altamovimientos' => isset($postData['altamovimientos']) ? $postData['altamovimientos'] : 0,
                'cuentaspagar' => isset($postData['cuentaspagar']) ? $postData['cuentaspagar'] : 0,
                'cuentascobrar' => isset($postData['cuentascobrar']) ? $postData['cuentascobrar'] : 0,
                'estadoresultados' => isset($postData['estadoresultados']) ? $postData['estadoresultados'] : 0
            ];
            
            if ($permissions['movimientosCaja']['seccion'] == 0) {
                foreach ($permissions['movimientosCaja'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['movimientosCaja'][$key] = 0;
                    }
                }
            }
            
            // Section 11: Diagnostics
            $permissions['diagnosticos'] = [
                'seccion' => isset($postData['seccion11']) ? $postData['seccion11'] : 0,
                'altadiagnostico' => isset($postData['altadiagnostico']) ? $postData['altadiagnostico'] : 0,
                'modificaciondiagnostico' => isset($postData['modificaciondiagnostico']) ? $postData['modificaciondiagnostico'] : 0,
                'imprimirdiagnostico' => isset($postData['imprimirdiagnostico']) ? $postData['imprimirdiagnostico'] : 0,
                'agregarartipresu' => isset($postData['agregarartipresu']) ? $postData['agregarartipresu'] : 0,
                'autorizarrefacciones' => isset($postData['autorizarrefacciones']) ? $postData['autorizarrefacciones'] : 0,
                'pedirrefacciones' => isset($postData['pedirrefacciones']) ? $postData['pedirrefacciones'] : 0,
                'recibirrefacciones' => isset($postData['recibirrefacciones']) ? $postData['recibirrefacciones'] : 0,
                'repararfallas' => isset($postData['repararfallas']) ? $postData['repararfallas'] : 0,
                'reimprimirrecepcion' => isset($postData['reimprimirrecepcion']) ? $postData['reimprimirrecepcion'] : 0,
                'reimprimirentrega' => isset($postData['reimprimirentrega']) ? $postData['reimprimirentrega'] : 0,
                'guardardiagnostico' => isset($postData['guardardiagnostico']) ? $postData['guardardiagnostico'] : 0,
                'regresarstatus' => isset($postData['regresarstatus']) ? $postData['regresarstatus'] : 0,
                'deleteimgdiag' => isset($postData['deleteimgdiag']) ? $postData['deleteimgdiag'] : 0,
                'deleteimgrecepcion' => isset($postData['deleteimgrecepcion']) ? $postData['deleteimgrecepcion'] : 0,
                'deleteimgreparacion' => isset($postData['deleteimgreparacion']) ? $postData['deleteimgreparacion'] : 0,
                'entregarvehiculo' => isset($postData['entregarvehiculo']) ? $postData['entregarvehiculo'] : 0,
                'agregarmodulosdiag' => isset($postData['agregarmodulosdiag']) ? $postData['agregarmodulosdiag'] : 0,
                'enviarcotizacion' => isset($postData['enviarcotizacion']) ? $postData['enviarcotizacion'] : 0,
                'altarefacciones' => isset($postData['altarefacciones']) ? $postData['altarefacciones'] : 0,
                'modificarrefacciones' => isset($postData['modificarrefacciones']) ? $postData['modificarrefacciones'] : 0,
                'consultadiagnostico' => 0,
                'consultadiagnosticorecibidos' => 0,
                'consultadiagnosticorepara' => 0,
                'verdiagcompleto' => 0,
                'verdiagusuario' => 0,
                'verpresucompleto' => 0,
                'verpresuusuario' => 0,
                'imgcompletasdiag' => 0,
                'imgusuariodiag' => 0,
                'imgcompletasrepa' => 0,
                'imgusuariorepa' => 0,
                'pedirrefausuario1' => 0,
                'pedirrefatodas' => 0,
                'recibirrefausuario' => 0,
                'recibirrefacompletas' => 0,
                'repafallas' => 0,
                'repararfallasusuario' => 0
            ];
            
            // Process diagnostic consultation permissions
            if (isset($postData['consultadiagnostico'])) {
                if ($postData['consultadiagnostico'] == 1) {
                    $permissions['diagnosticos']['consultadiagnostico'] = 1;
                } else if ($postData['consultadiagnostico'] == 2) {
                    $permissions['diagnosticos']['consultadiagnosticorecibidos'] = 1;
                } else if ($postData['consultadiagnostico'] == 3) {
                    $permissions['diagnosticos']['consultadiagnosticorepara'] = 1;
                }
            }
            
            // Process view diagnostic permissions
            if (isset($postData['verdiagcompleto'])) {
                if ($postData['verdiagcompleto'] == 1) {
                    $permissions['diagnosticos']['verdiagcompleto'] = 1;
                } else if ($postData['verdiagcompleto'] == 2) {
                    $permissions['diagnosticos']['verdiagusuario'] = 1;
                }
            }
            
            // Process view budget permissions
            if (isset($postData['verpresucompleto'])) {
                if ($postData['verpresucompleto'] == 1) {
                    $permissions['diagnosticos']['verpresucompleto'] = 1;
                } else if ($postData['verpresucompleto'] == 2) {
                    $permissions['diagnosticos']['verpresuusuario'] = 1;
                }
            }
            
            // Process diagnostic images permissions
            if (isset($postData['imgcompletasdiag'])) {
                if ($postData['imgcompletasdiag'] == 1) {
                    $permissions['diagnosticos']['imgcompletasdiag'] = 1;
                } else if ($postData['imgcompletasdiag'] == 2) {
                    $permissions['diagnosticos']['imgusuariodiag'] = 1;
                }
            }
            
            // Process repair images permissions
            if (isset($postData['imgcompletasrepa'])) {
                if ($postData['imgcompletasrepa'] == 1) {
                    $permissions['diagnosticos']['imgcompletasrepa'] = 1;
                } else if ($postData['imgcompletasrepa'] == 2) {
                    $permissions['diagnosticos']['imgusuariorepa'] = 1;
                }
            }
            
            // Process request parts permissions
            if (isset($postData['pedirrefausuario'])) {
                if ($postData['pedirrefausuario'] == 1) {
                    $permissions['diagnosticos']['pedirrefausuario1'] = 1;
                } else if ($postData['pedirrefausuario'] == 2) {
                    $permissions['diagnosticos']['pedirrefatodas'] = 1;
                }
            }
            
            // Process receive parts permissions
            if (isset($postData['recibirrefausuario'])) {
                if ($postData['recibirrefausuario'] == 1) {
                    $permissions['diagnosticos']['recibirrefausuario'] = 1;
                } else if ($postData['recibirrefausuario'] == 2) {
                    $permissions['diagnosticos']['recibirrefacompletas'] = 1;
                }
            }
            
            // Process repair failures permissions
            if (isset($postData['repafallas'])) {
                if ($postData['repafallas'] == 1) {
                    $permissions['diagnosticos']['repafallas'] = 1;
                } else if ($postData['repafallas'] == 2) {
                    $permissions['diagnosticos']['repararfallasusuario'] = 1;
                }
            }
            
            if ($permissions['diagnosticos']['seccion'] == 0) {
                foreach ($permissions['diagnosticos'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['diagnosticos'][$key] = 0;
                    }
                }
            }
            
            // Section 12: Tasks
            $permissions['tareas'] = [
                'seccion' => isset($postData['seccion12']) ? $postData['seccion12'] : 0,
                'altatareas' => isset($postData['altatareas']) ? $postData['altatareas'] : 0
            ];
            
            if ($permissions['tareas']['seccion'] == 0) {
                foreach ($permissions['tareas']as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['tareas'][$key] = 0;
                    }
                }
            }
            
            // Section 13: Billing
            $permissions['facturacion'] = [
                'seccion' => isset($postData['seccion13']) ? $postData['seccion13'] : 0,
                'altafacturacion' => isset($postData['altafacturacion']) ? $postData['altafacturacion'] : 0,
                'consultafacturas' => isset($postData['consultafacturas']) ? $postData['consultafacturas'] : 0,
                'cancelarfacturas' => isset($postData['cancelarfacturas']) ? $postData['cancelarfacturas'] : 0,
                'agregarcomplementos' => isset($postData['agregarcomplementos']) ? $postData['agregarcomplementos'] : 0,
                'verpdf' => isset($postData['verpdf']) ? $postData['verpdf'] : 0,
                'descargarxml' => isset($postData['descargarxml']) ? $postData['descargarxml'] : 0
            ];
            
            if ($permissions['facturacion']['seccion'] == 0) {
                foreach ($permissions['facturacion'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['facturacion'][$key] = 0;
                    }
                }
            }
            
            // Section 14: Business Name
            $permissions['razonSocial'] = [
                'seccion' => isset($postData['seccion14']) ? $postData['seccion14'] : 0,
                'altarazon' => isset($postData['altarazon']) ? $postData['altarazon'] : 0,
                'consultarazon' => isset($postData['consultarazon']) ? $postData['consultarazon'] : 0,
                'modificarrazon' => isset($postData['modificarrazon']) ? $postData['modificarrazon'] : 0
            ];
            
            if ($permissions['razonSocial']['seccion'] == 0) {
                foreach ($permissions['razonSocial'] as $key => $value) {
                    if ($key != 'seccion') {
                        $permissions['razonSocial'][$key] = 0;
                    }
                }
            }
            
            // Update user
            $result = $this->UserModel->actualizarUsuario($userData, $permissions);
            
            if ($result) {
                echo json_encode([
                    'message' => 'Usuario actualizado correctamente',
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'message' => 'Error al actualizar el usuario',
                    'status' => 'error'
                ]);
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function liststatus() {
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

            $result = $this->UsuariosModel->liststatus();

            if ($result) {
                echo json_encode([
                    'data' => $result,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'No se encontraron estados',
                    'status' => 'error']);
            }
        }catch(Exception $e) {
            echo json_encode([
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function listSitios() {
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

            $result = $this->UsuariosModel->listSitios($sitio_id);

            if ($result) {
                echo json_encode([
                    'data' => $result,
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'error' => 'No se encontraron sitios',
                    'status' => 'error']);
            }
        }catch(Exception $e) {
            echo json_encode([
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

}