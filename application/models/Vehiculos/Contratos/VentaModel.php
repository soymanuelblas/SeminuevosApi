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
        $this->db->select('v.numeroserie, v.version_id');
        $this->db->from('vehiculo as v');
        $this->db->where('v.id', $vehiculo_id);
        $this->db->where('v.sitio_id', $sitio_id);

        $row = $this->db->get()->row();

        // Verificar si se encontró el vehículo
        if (!$row) {
            return false;
        }

        $vin = $row->numeroserie;
        $version_id = $row->version_id;

        $this->db->select('ta.descripcion as anio');
        $this->db->from('version as v');
        $this->db->where('v.id', $version_id);
        $this->db->join('tipoannio as ta', 'ta.id = v.tipoannio_id', 'left');     

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

    public function listarClientes($sitio_id) {
        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio_id);

        $row = $this->db->get()->row();
        $razonsocial_id = $row->razonsocial_id;

        $this->db->select('id, nombre');
        $this->db->from('clientes');
        $this->db->where('razonsocial_id', $razonsocial_id);

        return $this->db->get()->result();
    }

    public function listarFormasPago() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 42);

        return $this->db->get()->result();
    }

    // Función para validar el formato del VIN
    private function esVinValido($vin) {
        // El VIN debe tener exactamente 17 caracteres alfanuméricos y no contener I, O, Q
        $regex = '/^[A-HJ-NPR-Z0-9]{17}$/';
        return preg_match($regex, $vin);
    }

    public function obtenerCorteId($sitio_id) {
        $this->db->select('MAX(id_interno) AS id, serie');
        $this->db->from('cortecaja');
        $this->db->where('sitio_id', $sitio_id);

        return $this->db->get()->row();
    }

    public function agregarVenta($data, $data_operacion_auto, $formas_pago) {
        // Validar que lo pagado sea igual al precio
        $acumular_importes = 0;
        foreach ($formas_pago as $forma_pago) {
            $acumular_importes += $forma_pago['importe'];
        }
        if ($acumular_importes != $data['importe']) {
            return false;
        }

        $this->db->select('COUNT(*) as id_interno');
        $this->db->from('operacion');
        $this->db->where('sitio_id', $data['sitio_id']);

        $id_interno_count = $this->db->get()->row()->id_interno;

        $id_interno = $id_interno_count + 1;
        $data['id_interno'] = $id_interno_count;

        $this->db->insert('operacion', $data);

        $data_operacion_auto['operacion_id'] = $id_interno_count;
        // Insertar los datos de la operación del vehículo
        $this->db->insert('operacion_auto', $data_operacion_auto);

        $this->db->select('COUNT(*) as total');
        $this->db->from('formapago');
        $this->db->where('sitio_id', $data['sitio_id']);

        $total = $this->db->get()->row()->total;

        // Insertar las formas de pago
        foreach ($formas_pago as $forma_pago) {
            $forma_pago_data = [
                'id_interno' => $total + 1,
                'operacion_id' => $id_interno_count,
                'formapago_id' => $forma_pago['formapago_id'],
                'referencia' => $forma_pago['referencia'] ? $forma_pago['referencia'] : 'N/A',
                'importe' => $forma_pago['importe'],
                'fechaexpedicion' => $forma_pago['fechaexpedicion'] ? $forma_pago['fechaexpedicion'] : date('Y-m-d'),
                'fechavencimiento' => $forma_pago['fechavencimiento'] ? $forma_pago['fechavencimiento'] : date('Y-m-d'),
                'tipostatus_id' => 5211,
                'serie' => 'A',
                'codigo' => 0,
                'sitio_id' => $data['sitio_id']
            ];
            $this->db->insert('formapago', $forma_pago_data);
            $total += 1;
        }

        $result = $this->db->affected_rows();

        return $result > 0 ? $id_interno : false;
    }

    public function obtenerOperacion($id_operacion, $sitio_id) {
        $this->db->select('*');
        $this->db->from('operacion');
        $this->db->where('id_interno', $id_operacion);
        $this->db->where('sitio_id', $sitio_id);
        $this->db->where('tipo_operacion', 3); // Tipo venta
        return $this->db->get()->row();
    }
    
    public function actualizarVenta($id_operacion, $sitio_id, $data_operacion, $data_operacion_auto, $formas_pago) {
        $this->db->trans_begin();
        
        try {
            // Actualizar operación principal
            $this->db->where('id_interno', $id_operacion);
            $this->db->where('sitio_id', $sitio_id);
            $this->db->update('operacion', $data_operacion);
            
            // Actualizar operación_auto
            $this->db->where('operacion_id', $id_operacion);
            $this->db->where('sitio_id', $sitio_id);
            $this->db->update('operacion_auto', $data_operacion_auto);
            
            // Manejar formas de pago
            $this->procesarFormasPago($id_operacion, $sitio_id, $formas_pago);
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            }
            
            $this->db->trans_commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error al actualizar venta: ' . $e->getMessage());
            return false;
        }
    }
    
    private function procesarFormasPago($id_operacion, $sitio_id, $formas_pago) {
        // Obtener pagos existentes para esta operación
        $this->db->select('id_interno');
        $this->db->from('formapago');
        $this->db->where('operacion_id', $id_operacion);
        $this->db->where('sitio_id', $sitio_id);
        $pagos_existentes = $this->db->get()->result_array();
        $ids_existentes = array_column($pagos_existentes, 'id_interno');
        
        $ids_recibidos = array_filter(array_column($formas_pago, 'id_interno'));
        
        // Eliminar pagos que ya no están en la solicitud
        $ids_eliminar = array_diff($ids_existentes, $ids_recibidos);
        if (!empty($ids_eliminar)) {
            $this->db->where_in('id_interno', $ids_eliminar);
            $this->db->where('operacion_id', $id_operacion);
            $this->db->where('sitio_id', $sitio_id);
            $this->db->delete('formapago');
        }
        
        // Actualizar/insertar pagos
        foreach ($formas_pago as $pago) {
            $pago_data = [
                'formapago_id' => $pago['formapago_id'],
                'referencia' => $pago['referencia'] ? $pago['referencia'] : 'N/A',
                'importe' => $pago['importe'],
                'fechaexpedicion' => $pago['fechaexpedicion'] ? $pago['fechaexpedicion'] : date('Y-m-d'),
                'fechavencimiento' => $pago['fechavencimiento'] ? $pago['fechavencimiento'] : date('Y-m-d'),
                'tipostatus_id' => 5211,
                'serie' => 'A',
                'codigo' => 0,
                'sitio_id' => $sitio_id
            ];
            
            if (isset($pago['id_interno']) && in_array($pago['id_interno'], $ids_existentes)) {
                // Actualizar pago existente
                $this->db->where('id_interno', $pago['id_interno']);
                $this->db->where('operacion_id', $id_operacion);
                $this->db->where('sitio_id', $sitio_id);
                $this->db->update('formapago', $pago_data);
            } else {
                // Insertar nuevo pago
                $this->db->select('MAX(id_interno) as max_id');
                $this->db->from('formapago');
                $this->db->where('sitio_id', $sitio_id);
                $max_id = $this->db->get()->row()->max_id;
                
                $pago_data['id_interno'] = $max_id + 1;
                $pago_data['operacion_id'] = $id_operacion;
                $this->db->insert('formapago', $pago_data);
            }
        }
    }

}