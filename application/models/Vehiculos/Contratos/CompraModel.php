<?php

class CompraModel extends CI_Model {

    public function listarCompras($sitio_id) {
        $this->db->select('o.id as id, cli.nombre as cliente, o.importe as precio, DATE(o.fecha) as fecha');
        $this->db->from('operacion as o');
        $this->db->where('o.sitio_id', $sitio_id);
        $this->db->where('o.tipo_operacion', 11);
        $this->db->join('clientes as cli', 'cli.id = o.clienteventa_id');

        return $this->db->get()->result();
    }

    public function agregarCompra($data, $sitio_id) {

    }

    public function actualizarCompra() {
        
    }

}