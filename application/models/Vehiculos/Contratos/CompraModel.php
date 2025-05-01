<?php

class CompraModel extends CI_Model {

    public function validarFacturaYTenencia($vehiculo_id) {
        $this->db->select('COUNT(*) as factura');
        $this->db->from('factura');
        $this->db->where('vehiculo_id', $vehiculo_id);

        $cantidad_facturas = $this->db->get()->row()->factura;

        $this->db->select('COUNT(*) as tenencia');
        $this->db->from('tenencia');
        $this->db->where('vehiculo_id', $vehiculo_id);

        $cantidad_tenencias = $this->db->get()->row()->tenencia;

        return $cantidad_facturas + $cantidad_tenencias;
    }

    public function listarCompras($sitio_id) {
        $this->db->select('o.id as id, cli.nombre as cliente, o.importe as precio, DATE(o.fecha) as fecha');
        $this->db->from('operacion as o');
        $this->db->where('o.sitio_id', $sitio_id);
        $this->db->where('o.tipo_operacion', 11);
        $this->db->join('clientes as cli', 'cli.id = o.clienteventa_id');

        return $this->db->get()->result();
    }

    public function agregarCompra($data, $sitio_id) {
        $cantidad = $this->validarFacturaYTenencia($data['vehiculo_id']);
        if($cantidad <= 0) {
            return false;
        }

        $this->db->insert('operacion', $data);
        $result = $this->db->insert_id();

        return $result;
    }

    public function actualizarCompra() {
        
    }

}