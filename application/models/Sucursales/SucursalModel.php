<?php

class SucursalModel extends CI_Model {

    function add_sucursal($id, $data, $user_id, $rfc, $sitio_id) {
        $id = (int) $id;
    
        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio_id);
        $query2 = $this->db->get();
    
        $razonsocial_id = $query2->row()->razonsocial_id;
        $data['razonsocial_id'] = $razonsocial_id;
        
        // Insertar sucursal
        $this->db->insert('sitio', $data);
        $sitio_id = $this->db->insert_id();
        
        if(!$sitio_id) return false;
        
        // 1. Insertar corte de caja inicial
        $corte_data = array(
            'id_interno' => 1,
            'serie' => 'A',
            'billete_1000' => 0,
            'billete_500' => 0,
            'billete_200' => 0,
            'billete_100' => 0,
            'billete_50' => 0,
            'billete_20' => 0,
            'modena_10' => 0,
            'modena_5' => 0,
            'modena_2' => 0,
            'modena_1' => 0,
            'moneda_50c' => 0,
            'total_efectivo' => 0,
            'fecha' => null,
            'usuario_id' => $user_id,
            'sitio_id' => $sitio_id
        );
        $this->db->insert('cortecaja', $corte_data);
        
        // 2. Verificar/crear cliente interno
        $this->db->where('razonsocial_id', $razonsocial_id);
        $this->db->where('id_interno', 1);
        $query_cliente = $this->db->get('clientes');
        
        if($query_cliente->num_rows() > 0) {
            $cliente_id = $query_cliente->row()->id;
        } else {
            $cliente_data = array(
                'id_interno' => 1,
                'rfc' => $rfc,
                'nombre' => $data['nombre'],
                'domicilio' => $data['domicilio1'],
                'colonia' => $data['domicilio2'],
                'telefono1' => $data['telefono1'],
                'telefono2' => $data['telefono2'],
                'email' => $data['correo'],
                'ciudad' => $data['ciudad'],
                'estado' => $data['estado'],
                'cp' => $data['cp'],
                'usuario_id' => $user_id,
                'tipostatus_id' => 851,
                'razonsocial_id' => $razonsocial_id,
                'tipocliente_id' => 5300,
                'password' => 'X'
            );
            $this->db->insert('clientes', $cliente_data);
            $cliente_id = $this->db->insert_id();
        }
        
        // 3. Obtener sitios de la razón social
        $this->db->select('sitio.id as sitioid');
        $this->db->from('sitio');
        $this->db->join('razon_social', 'razon_social.id = sitio.razonsocial_id');
        $this->db->where('razon_social.id', $razonsocial_id);
        $query_sitios = $this->db->get();
        $sitios_razon = array();
        foreach($query_sitios->result() as $row) {
            $sitios_razon[] = $row->sitioid;
        }
        
        // 4. Operación tipo 1
        $this->db->where_in('sitio_id', $sitios_razon);
        $this->db->where('tipo_operacion', 1);
        $count_ope1 = $this->db->count_all_results('operacion');
        
        $id_interno_ope1 = ($count_ope1 > 0) ? $this->get_max_id_interno($sitios_razon) + 1 : 1;
        
        $operacion1_data = array(
            'id_interno' => $id_interno_ope1,
            'tipo_operacion' => 1,
            'sitio_id' => $sitio_id,
            'clientecompra_id' => $cliente_id,
            'clienteventa_id' => $cliente_id,
            'importe' => 0.00,
            'fecha' => date('Y-m-d H:i:s'),
            'tipostatus_id' => 5022,
            'usuario_id' => $user_id,
            'corte_id' => 'A1',
            'adicional_id' => 0
        );
        $this->db->insert('operacion', $operacion1_data);
        
        // 5. Operación tipo 36
        $this->db->where_in('sitio_id', $sitios_razon);
        $this->db->where('tipo_operacion', 36);
        $count_ope36 = $this->db->count_all_results('operacion');
        
        $id_interno_ope36 = ($count_ope36 > 0) ? $this->get_max_id_interno($sitios_razon) + 1 : 2;
        
        $operacion36_data = array(
            'id_interno' => $id_interno_ope36,
            'tipo_operacion' => 36,
            'sitio_id' => $sitio_id,
            'clientecompra_id' => $cliente_id,
            'clienteventa_id' => $cliente_id,
            'importe' => 0.00,
            'fecha' => date('Y-m-d H:i:s'),
            'tipostatus_id' => 5022,
            'usuario_id' => $user_id,
            'corte_id' => 'A1',
            'adicional_id' => 0
        );
        $this->db->insert('operacion', $operacion36_data);
        
        return $sitio_id;
    }
    
    // Función auxiliar para obtener el máximo id_interno
    private function get_max_id_interno($sitios_razon) {
        $this->db->select_max('id_interno');
        $this->db->where_in('sitio_id', $sitios_razon);
        $query = $this->db->get('operacion');
        return $query->row()->id_interno;
    }

    function update_sucursal($data) {
        $this->db->where('id', $data['id']);
        return $this->db->update('sitio', $data);
    }
    
    function list_sucursal($sitio_id) {

        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio_id);

        $query2 = $this->db->get();

        $razonsocial_id = $query2->row()->razonsocial_id;

        $this->db->select('
            sitio.id, sitio.nombre, 
            sitio.domicilio1, sitio.domicilio2,
            sitio.ciudad, ts.descripcion as estado, 
            sitio.cp, sitio.pais, sitio.telefono1, sitio.telefono2, 
            sitio.contacto, sitio.correo, sitio.pass_correo');
        $this->db->from('sitio');
        $this->db->join('tipostatus as ts', 'sitio.estado = ts.id', 'left');
        $this->db->where('razonsocial_id', $razonsocial_id);

        $query = $this->db->get();

        return $query->result();
    }

}