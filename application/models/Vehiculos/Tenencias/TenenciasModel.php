<?php

class TenenciasModel extends CI_Model {

    public function agregarTenencia($data) {
        $this->db->insert('tenencia', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function obtenerEstados() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 20);
        $this->db->order_by('descripcion', 'ASC');

        return $this->db->get()->result();
    }

    public function obtenerAnnios() {
        $this->db->select('id, descripcion');
        $this->db->from('tipoannio');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(15);

        return $this->db->get()->result();
    }

    


}