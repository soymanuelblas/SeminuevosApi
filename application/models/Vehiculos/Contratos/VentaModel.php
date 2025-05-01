<?php

class VentaModel extends CI_Model {

    public function listarVentas($vehiculo_id, $sitio_id) {
        $this->db->select('o.id, o.id_interno, o.fecha, o.importe as precio, 
                          v.id as vehiculo_id,
                          cli_compra.nombre as cliente_compra,
                          cli_venta.nombre as cliente_venta,
                          m.descripcion as marca, mod.descripcion as modelo');
        $this->db->from('operacion o');
        $this->db->join('vehiculo v', 'v.id = o.vehiculo_id');
        $this->db->join('clientes cli_compra', 'cli_compra.id = o.clientecompra_id');
        $this->db->join('clientes cli_venta', 'cli_venta.id = o.clienteventa_id');
        $this->db->join('version ver', 'ver.id = v.version_id');
        $this->db->join('tipomodelo mod', 'mod.id = ver.tipomodelo_id');
        $this->db->join('tipomarca m', 'm.id = mod.tipomarca_id');
        $this->db->where('o.sitio_id', $sitio_id);
        $this->db->where('o.tipo_operacion', 3); // 11 = Compra
        $this->db->order_by('o.fecha', 'DESC');
        $this->db->where('v.id', $vehiculo_id);

        return $this->db->get()->result();
    }

    public function listarPagos($vehiculo_id, $sitio_id) {
        $this->db->select('id_interno');
        $this->db->from('operacion');
        $this->db->where('vehiculo_id', $vehiculo_id);
        $this->db->where('tipo_operacion', 3);

        $row = $this->db->get()->row();
        $id_interno = $row->id_interno;

        $this->db->select('fp.id, fp.id_interno, 
        ts.descripcion as tipostatus, fp.referencia as referencia,
        fp.importe as importe, fp.fechavencimiento as fechavencimiento,');
        $this->db->from('formapago as fp');
        $this->db->join('tipostatus as ts', 'ts.id = fp.formapago_id');
        $this->db->where('operacion_id', $id_interno);
        $this->db->where('sitio_id', $sitio_id);

        return $this->db->get()->result();
    }

    public function agregarVenta() {

    }

    public function actualizarVenta() {

    }

}