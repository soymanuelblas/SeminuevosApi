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
   
    public function validarVinVehiculo($vehiculo_id, $sitio_id) {
        $this->db->select('numeroserie, version_id');
        $this->db->from('vehiculo');
        $this->db->where('id', $vehiculo_id);
        $this->db->where('sitio_id', $sitio_id);

        $row = $this->db->get()->row();

        // Verificar si se encontró el vehículo
        if (!$row) {
            return false;
        }

        $vin = $row->numeroserie;
        $version_id = $row->version_id;

        $this->db->select('ta.descripcion as anio');
        $this->db->from('version');
        $this->db->where('id', $version_id);
        $this->db->join('tipoannio as ta', 'ta.id = version.tipoannio_id', 'left');     

        $row = $this->db->get()->row();

        // Verificar si se encontró la versión
        if (!$row) {
            return false;
        }

        $anio = $row->anio;

        // Verificar si el año es válido
        if ($anio < 1981 || $anio > date('Y')) {
            return false;
        }

        // Validar el formato del VIN
        if ($this->esVinValido($vin)) {
            return true;
        }
        return false;
    }

    // Función para validar el formato del VIN
    private function esVinValido($vin) {
        // El VIN debe tener exactamente 17 caracteres alfanuméricos y no contener I, O, Q
        $regex = '/^[A-HJ-NPR-Z0-9]{17}$/';
        return preg_match($regex, $vin);
    }

    public function agregarVenta() {
        
    }

    public function actualizarVenta() {

    }

}