<?php

class FacturasModel extends CI_Model {

    public function agregarFactura($sitio_id, $data) {

        $this->db->select('sitio_id');
        $this->db->from('vehiculo');
        $this->db->where('id', $data['vehiculo_id']);
        $row = $this->db->get()->row_array();
        $sitio = isset($row['sitio_id']) ? $row['sitio_id'] : null;

        // Validar si el sitio coincide
        if ($sitio != $sitio_id) {
            return false;
        }

        return $this->db->insert('factura', $data);
    }

    public function listarFacturas($vehiculo_id, $sitio_id) {
        // Consultar el sitio asociado al vehículo
        $this->db->select('sitio_id');
        $this->db->from('vehiculo');
        $this->db->where('id', $vehiculo_id);
        
        $row = $this->db->get()->row_array();
        $sitio = isset($row['sitio_id']) ? $row['sitio_id'] : null;
        
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

    // public function actualizarFactura($factura_id, $sitio_id, $data) {
    //     $this->db->select('sitio_id');
    //     $this->db->from('vehiculo');
    //     $this->db->where('id', $data['vehiculo_id']);
    //     $sitio = $this->db->get()->row_array()['sitio_id'] ?? null;

    //     // Validar si el sitio coincide
    //     if ($sitio != $sitio_id) {
    //         return false;
    //     }

    //     $this->db->select('id');
    //     $this->db->from('factura');
    //     $this->db->where('id', $factura_id);
    //     $factura = $this->db->get()->row_array()['id'] ?? null;

    //     // Validar si la factura existe
    //     if (!$factura) {
    //         return false;
    //     }
    //     // Validar si el vehiculo_id coincide con la factura
    //     $this->db->select('vehiculo_id');
    //     $this->db->from('factura');
    //     $this->db->where('id', $factura_id);
    //     $vehiculo_id = $this->db->get()->row_array()['vehiculo_id'] ?? null;

    //     if ($vehiculo_id != $data['vehiculo_id']) {
    //         return false;
    //     }

    //     // Actualizar la factura
    //     $this->db->where('id', $factura_id);
    //     return $this->db->update('factura', $data);
    // }

    public function obtenerFacturaCompleta($factura_id, $sitio_id) {
        $this->db->select('f.*, v.sitio_id as vehiculo_sitio_id');
        $this->db->from('factura f');
        $this->db->join('vehiculo v', 'f.vehiculo_id = v.id', 'left');
        $this->db->where('f.id', $factura_id);
        $query = $this->db->get();
        
        $factura = $query->row_array();
        
        // Verificar que la factura pertenezca al sitio correcto
        return ($factura && $factura['vehiculo_sitio_id'] == $sitio_id) ? $factura : false;
    }
    
    public function actualizarFactura($factura_id, $sitio_id, $data) {
        // Verificar que la factura pertenece al sitio
        $factura = $this->obtenerFacturaCompleta($factura_id, $sitio_id);
        if (!$factura) {
            return false;
        }
    
        // Verificar que el vehículo no esté siendo cambiado a otro sitio
        if (isset($data['vehiculo_id'])) {
            $this->db->select('sitio_id');
            $this->db->from('vehiculo');
            $this->db->where('id', $data['vehiculo_id']);

            $row = $this->db->get()->row_array();
            $nuevo_sitio = $row['sitio_id'] ? $row['sitio_id'] : null;
            
            if ($nuevo_sitio != $sitio_id) {
                return false;
            }
        }
    
        $this->db->where('id', $factura_id);
        return $this->db->update('factura', $data);
    }

}