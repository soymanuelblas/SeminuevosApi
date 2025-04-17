<?php

class FacturasModel extends CI_Model {

    public function agregarFactura($data) {
        return $this->db->insert('factura', $data);
    }

    public function listarTiposFacturas() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 18);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function listarStatusFacturas() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 44);
        $query = $this->db->get();

        return $query->result_array();
    }

}