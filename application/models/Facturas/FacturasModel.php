<?php

class FacturasModel extends CI_Model {

    public function obtenerFacturas($vehiculo_id) {
        $this->db->select('factura.id, factura.vehiculo_id, factura.expedidapor, factura.folio, factura.fecha, factura.archivo, tipofactura.descripcion AS tipofactura, statusfactura.descripcion AS statusfactura');
        $this->db->from('factura');
        $this->db->join('tipostatus AS tipofactura', 'tipofactura.id = factura.tipofactura_id', 'left');
        $this->db->join('tipostatus AS statusfactura', 'statusfactura.id = factura.tipostatus_id', 'left');
        $this->db->where('factura.vehiculo_id', $vehiculo_id);
        $query = $this->db->get();

        return $query->result_array();
    }

}