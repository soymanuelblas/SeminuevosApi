<?php

class ClienteProvModel extends CI_Model {

    function add_cliente_provedor($sitio, $data) {

        // Obtener razonsocial_id del sitio
        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio);
        $query = $this->db->get();

        if($query->num_rows() > 0) {
            $razonsocial_id = $query->row_array()['razonsocial_id'];
            $data['razonsocial_id'] = $razonsocial_id;

            $this->db->insert('clientes', $data);
            
            return $this->db->affected_rows() > 0;
        }
        return false;
    }

    function listClientProvider() {

    }

}