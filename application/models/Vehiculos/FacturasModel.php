<?php

class FacturasModel extends CI_Model {

    public function obtenerInfoVehiculo($vehiculo_id) {
        $this->db->select('vehiculo.noexpediente as expediente, vehiculo.sitio_id');
        $this->db->from('vehiculo');
        $this->db->where('vehiculo.id', $vehiculo_id);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function insertar_factura($data) {
        return $this->db->insert('factura', $data);
    }

}