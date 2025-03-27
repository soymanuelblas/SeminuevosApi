<?php

class ClienteProvModel extends CI_Model {

    function add_cliente_provedor($sitio, $data, $data_moral) {

        // Obtener razonsocial_id del sitio
        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio);
        $query = $this->db->get();

        if($query->num_rows() > 0) {
            $razonsocial_id = $query->row_array()['razonsocial_id'];
            $data['razonsocial_id'] = $razonsocial_id;

            $this->db->select_max('id_interno');
            $this->db->from('clientes');
            $this->db->where('razonsocial_id', $razonsocial_id);
            $query = $this->db->get();

            $result = $query->row();
            $id_interno = $result->id_interno + 1;
            $data['id_interno'] = $id_interno;

            $this->db->insert('clientes', $data);

            if($data_moral != null) {
                $data_moral['cliente_id'] = $this->db->insert_id();
                $this->db->insert('cliente_moral', $data_moral);
            }
            
            return $this->db->affected_rows() > 0;
        }
        return false;
    }

    function listClientProvider($usuario_id) {
        $this->db->select('
            clientes.id, clientes.nombre, 
            clientes.domicilio, clientes.colonia, 
            clientes.cp, clientes.ciudad, clientes.estado, 
            clientes.rfc, clientes.telefono1, clientes.email, 
            tipocliente.descripcion as tipo, 
            tipostatus.descripcion as status,
            cm.PersonaMoral as persona_moral,
            cm.escriturapublica as escritura_publica,
            cm.numeronotaria as numero_notaria,
            cm.nombrenotario as nombre_notario,
            cm.ciudadnotaria as ciudad_notaria,
            cm.fechaconstitucion as fecha_constitucion');
        $this->db->from('clientes');
        $this->db->join('tipostatus as tipocliente', 'tipocliente.id = clientes.tipocliente_id', 'left');
        $this->db->join('tipostatus as tipostatus', 'tipostatus.id = clientes.tipostatus_id', 'left');
        $this->db->join('cliente_moral as cm', 'cm.cliente_id = clientes.id', 'left');
        $this->db->where('clientes.usuario_id', $usuario_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    function updateClientProvider($id, $data, $data_moral) {
        try {
            $this->db->where('id', $id);
            $this->db->update('clientes', $data);
            
            if($data_moral != null) {
                $this->db->where('cliente_id', $id);
                $this->db->update('cliente_moral', $data_moral);
            }
            log_message('info', "updateClientProvider: " . $this->db->last_query());
            log_message('info', "updateClientProvider: " . $this->db->affected_rows());
            return $this->db->affected_rows() > 0;
        } catch (Exception $e) {
            log_message('error', "Error en updateClientProvider: " . $e->getMessage());
            return false;
        }
    }

}