<?php

class UsuariosModel extends CI_Model {

    public function obtenerUsuarios($sitio_id) {
        $this->db->select('usuario.nombre, usuario.usr, t.descripcion as status_descripcion');
        $this->db->from('usuario');
        $this->db->join('tipostatus as t', 'usuario.tipostatus_id = t.id'); // Ajuste en la condición de la unión
        $this->db->where('usuario.sitio_id', $sitio_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function actualizarUsuario($userData, $permissions) {
        // Format permissions string
        $permissionsString = '';
        
        // Clients section
        $permissionsString .= $permissions['clientes']['seccion'];
        $permissionsString .= $permissions['clientes']['alta'];
        $permissionsString .= $permissions['clientes']['modificacion'];
        $permissionsString .= $permissions['clientes']['consulta'];
        
        // Branches section
        $permissionsString .= $permissions['sucursales']['seccion'];
        $permissionsString .= $permissions['sucursales']['altasucursal'];
        $permissionsString .= $permissions['sucursales']['modificacionsucursal'];
        $permissionsString .= $permissions['sucursales']['consultasucursal'];
        
        // Operations section
        $permissionsString .= $permissions['operaciones']['seccion'];
        $permissionsString .= $permissions['operaciones']['altaventa'];
        $permissionsString .= $permissions['operaciones']['modificacionventa'];
        $permissionsString .= $permissions['operaciones']['consultaventa'];
        $permissionsString .= $permissions['operaciones']['altacompra'];
        $permissionsString .= $permissions['operaciones']['modificacioncompra'];
        $permissionsString .= $permissions['operaciones']['consultacompra'];
        $permissionsString .= $permissions['operaciones']['altaconsignacion'];
        $permissionsString .= $permissions['operaciones']['modificacionconsignacion'];
        $permissionsString .= $permissions['operaciones']['consultaconsignacion'];
        $permissionsString .= $permissions['operaciones']['altaintermediacion'];
        $permissionsString .= $permissions['operaciones']['modificacionintermediacion'];
        $permissionsString .= $permissions['operaciones']['consultaintermediacion'];
        
        // Vehicles section
        $permissionsString .= $permissions['vehiculos']['seccion'];
        $permissionsString .= $permissions['vehiculos']['altavehiculo'];
        $permissionsString .= $permissions['vehiculos']['modificacionvehiculo'];
        $permissionsString .= $permissions['vehiculos']['consultavehiculo'];
        $permissionsString .= $permissions['vehiculos']['altatenencia'];
        $permissionsString .= $permissions['vehiculos']['modificaciontenencia'];
        $permissionsString .= $permissions['vehiculos']['consultatenencia'];
        $permissionsString .= $permissions['vehiculos']['altafactura'];
        $permissionsString .= $permissions['vehiculos']['modificacionfactura'];
        $permissionsString .= $permissions['vehiculos']['consultafactura'];
        
        // Super user section - catalog management
        $permissionsString .= $permissions['superusuario']['altamodelo'];
        $permissionsString .= $permissions['superusuario']['modificacionmodelo'];
        $permissionsString .= $permissions['superusuario']['consultamodelo'];
        $permissionsString .= $permissions['superusuario']['altaannio'];
        $permissionsString .= $permissions['superusuario']['modificacionannio'];
        $permissionsString .= $permissions['superusuario']['consultaannio'];
        $permissionsString .= $permissions['superusuario']['altamarca'];
        $permissionsString .= $permissions['superusuario']['modificacionmarca'];
        $permissionsString .= $permissions['superusuario']['consultamarca'];
        $permissionsString .= $permissions['superusuario']['altaversion'];
        $permissionsString .= $permissions['superusuario']['modificacionversion'];
        $permissionsString .= $permissions['superusuario']['consultaversion'];
        
        // Vehicles section - publications
        $permissionsString .= $permissions['vehiculos']['altapublicacion'];
        $permissionsString .= $permissions['vehiculos']['modificacionpublicacion'];
        $permissionsString .= $permissions['vehiculos']['consultapublicacion'];
        $permissionsString .= $permissions['vehiculos']['altaimagenen'];
        $permissionsString .= $permissions['vehiculos']['modificacionimagenen'];
        $permissionsString .= $permissions['vehiculos']['consultaimagenen'];
        $permissionsString .= $permissions['vehiculos']['altafina'];
        $permissionsString .= $permissions['vehiculos']['modificacionfina'];
        $permissionsString .= $permissions['vehiculos']['consultafina'];
        $permissionsString .= $permissions['vehiculos']['altaatributo'];
        $permissionsString .= $permissions['vehiculos']['modificacionatributo'];
        $permissionsString .= $permissions['vehiculos']['consultaatributo'];
        $permissionsString .= $permissions['vehiculos']['altaextra'];
        $permissionsString .= $permissions['vehiculos']['modificacionextra'];
        $permissionsString .= $permissions['vehiculos']['consultaextra'];
        $permissionsString .= $permissions['vehiculos']['altacaracteristica'];
        $permissionsString .= $permissions['vehiculos']['modificacioncaracteristica'];
        $permissionsString .= $permissions['vehiculos']['consultacaracteristica'];
        
        // Sites section
        $permissionsString .= $permissions['sitios']['seccion'];
        $permissionsString .= $permissions['sitios']['altasitio'];
        $permissionsString .= $permissions['sitios']['modificacionsitio'];
        $permissionsString .= $permissions['sitios']['consultasitio'];
        
        // Users section
        $permissionsString .= $permissions['usuarios']['seccion'];
        $permissionsString .= $permissions['usuarios']['altausuario'];
        $permissionsString .= $permissions['usuarios']['modificacionusuario'];
        $permissionsString .= $permissions['usuarios']['consultausuario'];
        
        // Super user section
        $permissionsString .= $permissions['superusuario']['seccion'];
        $permissionsString .= $permissions['superusuario']['altafinanciamiento'];
        $permissionsString .= $permissions['superusuario']['modificacionfinanciamiento'];
        $permissionsString .= $permissions['superusuario']['consultafinanciamiento'];
        
        // Opportunities section
        $permissionsString .= $permissions['oportunidades']['seccion'];
        $permissionsString .= $permissions['oportunidades']['altaprospecto'];
        $permissionsString .= $permissions['oportunidades']['modificacionprospecto'];
        $permissionsString .= $permissions['oportunidades']['consultaprospecto'];
        $permissionsString .= $permissions['oportunidades']['altaoportunidad'];
        $permissionsString .= $permissions['oportunidades']['modificacionoportunidad'];
        $permissionsString .= $permissions['oportunidades']['consultaoportunidad'];
        $permissionsString .= $permissions['oportunidades']['altaseguimiento'];
        $permissionsString .= $permissions['oportunidades']['modificacionseguimiento'];
        $permissionsString .= $permissions['oportunidades']['consultaseguimiento'];
        
        // Client section - additional permissions
        $permissionsString .= $permissions['clientes']['consulta1'];
        
        // Vehicle section - additional permissions
        $permissionsString .= $permissions['vehiculos']['consultafacturaimagen1'];
        $permissionsString .= $permissions['vehiculos']['consultafacturaimagen'];
        
        // Reports
        $permissionsString .= $permissions['clientes']['reporteclientes'];
        $permissionsString .= $permissions['operaciones']['reporteventas'];
        $permissionsString .= $permissions['operaciones']['reportecompras'];
        $permissionsString .= $permissions['operaciones']['reporteconsignacion'];
        $permissionsString .= $permissions['operaciones']['reporteintermediacion'];
        $permissionsString .= $permissions['vehiculos']['reportevehiculos'];
        $permissionsString .= $permissions['oportunidades']['reportesegimiento'];
        $permissionsString .= $permissions['oportunidades']['reporteoportunidad'];
        
        // Opportunities - additional permissions
        $permissionsString .= $permissions['oportunidades']['consultaprospectousuario'];
        $permissionsString .= $permissions['oportunidades']['consultaoportunidadusuario'];
        
        // Quotes section
        $permissionsString .= $permissions['cotizaciones']['seccion'];
        $permissionsString .= $permissions['cotizaciones']['altacotizacion'];
        
        // Cash movements section
        $permissionsString .= $permissions['movimientosCaja']['seccion'];
        $permissionsString .= $permissions['movimientosCaja']['altrecibo'];
        $permissionsString .= $permissions['movimientosCaja']['modificacionrecibo'];
        $permissionsString .= $permissions['movimientosCaja']['consultarecibo'];
        $permissionsString .= $permissions['movimientosCaja']['modificacionmovimientos'];
        $permissionsString .= $permissions['movimientosCaja']['consultarmovimientos'];
        $permissionsString .= $permissions['movimientosCaja']['altacorte'];
        $permissionsString .= $permissions['movimientosCaja']['consultarcaja'];
        $permissionsString .= $permissions['movimientosCaja']['altamovimientos'];
        
        // Operations - additional permissions
        $permissionsString .= $permissions['operaciones']['consultacartafactura'];
        $permissionsString .= $permissions['operaciones']['consultarecibodoc'];
        
        // Cash movements - additional permissions
        $permissionsString .= $permissions['movimientosCaja']['cuentaspagar'];
        $permissionsString .= $permissions['movimientosCaja']['cuentascobrar'];
        
        // Diagnostics section
        $permissionsString .= $permissions['diagnosticos']['seccion'];
        $permissionsString .= $permissions['diagnosticos']['altadiagnostico'];
        $permissionsString .= $permissions['diagnosticos']['consultadiagnostico'];
        $permissionsString .= $permissions['diagnosticos']['imprimirdiagnostico'];
        $permissionsString .= $permissions['diagnosticos']['agregarartipresu'];
        $permissionsString .= $permissions['diagnosticos']['autorizarrefacciones'];
        $permissionsString .= $permissions['diagnosticos']['pedirrefacciones'];
        $permissionsString .= $permissions['diagnosticos']['recibirrefacciones'];
        $permissionsString .= $permissions['diagnosticos']['repararfallas'];
        $permissionsString .= $permissions['diagnosticos']['reimprimirrecepcion'];
        $permissionsString .= $permissions['diagnosticos']['reimprimirentrega'];
        $permissionsString .= $permissions['diagnosticos']['guardardiagnostico'];
        $permissionsString .= $permissions['diagnosticos']['modificaciondiagnostico'];
        $permissionsString .= $permissions['diagnosticos']['regresarstatus'];
        
        // Tasks section
        $permissionsString .= $permissions['tareas']['seccion'];
        $permissionsString .= $permissions['tareas']['altatareas'];
        
        // Vehicles - additional permissions
        $permissionsString .= $permissions['vehiculos']['eliminarimagenes'];
        
        // Diagnostics - additional permissions
        $permissionsString .= $permissions['diagnosticos']['deleteimgdiag'];
        $permissionsString .= $permissions['diagnosticos']['verdiagcompleto'];
        $permissionsString .= $permissions['diagnosticos']['verdiagusuario'];
        $permissionsString .= $permissions['diagnosticos']['imgcompletasdiag'];
        $permissionsString .= $permissions['diagnosticos']['imgusuariodiag'];
        $permissionsString .= $permissions['diagnosticos']['verpresucompleto'];
        $permissionsString .= $permissions['diagnosticos']['verpresuusuario'];
        $permissionsString .= $permissions['diagnosticos']['enviarcotizacion'];
        $permissionsString .= $permissions['diagnosticos']['deleteimgrecepcion'];
        $permissionsString .= $permissions['diagnosticos']['deleteimgreparacion'];
        $permissionsString .= $permissions['diagnosticos']['entregarvehiculo'];
        $permissionsString .= $permissions['diagnosticos']['agregarmodulosdiag'];
        $permissionsString .= $permissions['diagnosticos']['imgcompletasrepa'];
        $permissionsString .= $permissions['diagnosticos']['imgusuariorepa'];
        $permissionsString .= $permissions['diagnosticos']['pedirrefausuario1'];
        $permissionsString .= $permissions['diagnosticos']['pedirrefatodas'];
        $permissionsString .= $permissions['diagnosticos']['recibirrefausuario'];
        $permissionsString .= $permissions['diagnosticos']['recibirrefacompletas'];
        $permissionsString .= $permissions['diagnosticos']['consultadiagnosticorecibidos'];
        $permissionsString .= $permissions['diagnosticos']['consultadiagnosticorepara'];
        $permissionsString .= $permissions['diagnosticos']['repafallas'];
        $permissionsString .= $permissions['diagnosticos']['repararfallasusuario'];
        $permissionsString .= $permissions['diagnosticos']['altarefacciones'];
        $permissionsString .= $permissions['diagnosticos']['modificarrefacciones'];
        
        // Super user - additional permissions
        $permissionsString .= $permissions['superusuario']['porsitio'];
        $permissionsString .= $permissions['superusuario']['porrazon'];
        
        // Branches - additional permissions
        $permissionsString .= $permissions['sucursales']['cambiarsitio'];
        
        // Billing section
        $permissionsString .= $permissions['facturacion']['seccion'];
        $permissionsString .= $permissions['facturacion']['altafacturacion'];
        $permissionsString .= $permissions['facturacion']['consultafacturas'];
        $permissionsString .= $permissions['facturacion']['cancelarfacturas'];
        $permissionsString .= $permissions['facturacion']['agregarcomplementos'];
        $permissionsString .= $permissions['facturacion']['verpdf'];
        $permissionsString .= $permissions['facturacion']['descargarxml'];
        
        // Super user - accounts
        $permissionsString .= $permissions['superusuario']['altacuentas'];
        $permissionsString .= $permissions['superusuario']['modificarcuentas'];
        $permissionsString .= $permissions['superusuario']['consultacuentas'];
        
        // Business name section
        $permissionsString .= $permissions['razonSocial']['seccion'];
        $permissionsString .= $permissions['razonSocial']['altarazon'];
        $permissionsString .= $permissions['razonSocial']['consultarazon'];
        $permissionsString .= $permissions['razonSocial']['modificarrazon'];
        
        // Cash movements - additional permissions
        $permissionsString .= $permissions['movimientosCaja']['estadoresultados'];
        
        // Prepare data for update
        $data = [
            'nombre' => $userData['nombre'],
            'usr' => $userData['usuario'],
            'pwd' => $userData['contra'],
            'permisos' => $permissionsString,
            'tipostatus_id' => $userData['status'],
            'sitio_id' => $userData['sitio'],
            'rol_id' => $userData['rol']
        ];
        
        // Update user
        $this->db->where('id', $userData['id']);
        $this->db->update('usuario', $data);
        
        // Return result
        return $this->db->affected_rows() > 0;
    }

    public function liststatus() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 19);
        $query = $this->db->get();
        return $query->result();
    }

    public function listSitios($sitio_id) {
        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio_id);

        $query = $this->db->get();

        $this->db->select('id, nombre');
        $this->db->from('sitio');
        $this->db->where('razonsocial_id', $query->row()->razonsocial_id);

        return $this->db->get()->result();
    }

}