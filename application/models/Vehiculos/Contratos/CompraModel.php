<?php
class CompraModel extends CI_Model {

    /**
     * Validar existencia de factura y tenencia para un vehículo
     */
    public function validarFacturaYTenencia($vehiculo_id) {
        $this->db->select('COUNT(*) as factura');
        $this->db->from('factura');
        $this->db->where('vehiculo_id', $vehiculo_id);
        $cantidad_facturas = $this->db->get()->row()->factura;

        $this->db->select('COUNT(*) as tenencia');
        $this->db->from('tenencia');
        $this->db->where('vehiculo_id', $vehiculo_id);
        $cantidad_tenencias = $this->db->get()->row()->tenencia;

        return ($cantidad_facturas > 0 && $cantidad_tenencias > 0);
    }

    /**
     * Listar todas las compras de un sitio
     */
    public function listarCompras($sitio_id) {
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
        $this->db->where('o.tipo_operacion', 11); // 11 = Compra
        $this->db->order_by('o.fecha', 'DESC');

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

    /**
     * Agregar una nueva compra
     */
    public function agregarCompra($dataCompra, $formasPago, $sitio_id) {
        if(!$this->validarFacturaYTenencia($dataCompra['vehiculo_id'])) {
            return false; // Ya existe una factura y tenencia para este vehículo
        }

        $this->db->insert('operacion', $dataCompra);
        $compra_id = $this->db->insert_id();

        $data = [
            'id' => $compra_id,
            'tipo_operacion' => 11, // 11 = Compra
            'sitio_id' => $sitio_id,
            'vehiculo_id' => $dataCompra['vehiculo_id'],
            'clientecompra_id' => $dataCompra['clientecompra_id'],
            'clienteventa_id' => $this->obtenerClienteVentaDefault($sitio_id),
            'importe' => $dataCompra['importe'],
            'fecha' => date('Y-m-d H:i:s')
        ];

    }

    /**
     * Obtener el cliente vendedor por defecto
     */
    private function obtenerClienteVentaDefault($sitio_id) {
        $this->db->select('id');
        $this->db->from('clientes');
        $this->db->where('razonsocial_id', $sitio_id);
        $this->db->where('id_interno', 1); // Cliente interno por defecto
        $cliente = $this->db->get()->row();

        return $cliente ? $cliente->id : null;
    }

    /**
     * Actualizar una compra existente
     */
    public function actualizarCompra($id, $data, $sitio_id) {
        $this->db->where('id', $id);
        $this->db->where('sitio_id', $sitio_id);
        $this->db->where('tipo_operacion', 11);
        return $this->db->update('operacion', $data);
    }
}