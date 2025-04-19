<?php

class FacturasModel extends CI_Model {

    public function agregarFactura($data) {
        return $this->db->insert('factura', $data);
    }

    public function listarFacturas($vehiculo_id, $sitio_id) {
        // Consultar el sitio asociado al vehículo
        $this->db->select('sitio_id');
        $this->db->from('vehiculo');
        $this->db->where('id', $vehiculo_id);
        
        $sitio = $this->db->get()->row_array()['sitio_id'] ?? null;
        
        // Validar si el sitio coincide
        if ($sitio != $sitio_id) {
            return false;
        }

        // Consultar las facturas asociadas al vehículo
        $this->db->select('
            f.id as id, f.vehiculo_id as vehiculo_id, 
            tf.descripcion as tipofactura,
            f.expedidapor as expedida, f.folio as folio, 
            f.fecha as fecha, f.archivo as archivo, 
            ts.descripcion as tipostatus');
        $this->db->from('factura as f');
        $this->db->join('tipostatus as ts', 'f.tipostatus_id = ts.id');
        $this->db->join('tipostatus as tf', 'f.tipofactura_id = tf.id');
        $this->db->where('vehiculo_id', $vehiculo_id);
       
        return $this->db->get()->result_array();
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