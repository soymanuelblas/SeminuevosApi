<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CompraModel extends CI_Model {

    public function listarCompras($vehiculo_id, $sitio_id) {
        $this->db->select('o.id, o.id_interno, o.fecha, o.importe as precio, 
                          v.id as vehiculo_id,
                          cli_compra.nombre as cliente_compra,
                          cli_venta.nombre as cliente_venta,
                          m.descripcion as marca, mod.descripcion as modelo');
        $this->db->from('operacion o');
        $this->db->join('vehiculo v', 'v.id = o.vehiculo_id', 'left');
        $this->db->join('clientes cli_compra', 'cli_compra.id = o.clientecompra_id', 'left');
        $this->db->join('clientes cli_venta', 'cli_venta.id = o.clienteventa_id', 'left');
        $this->db->join('version ver', 'ver.id = v.version_id', 'left');
        $this->db->join('tipomodelo mod', 'mod.id = ver.tipomodelo_id', 'left');
        $this->db->join('tipomarca m', 'm.id = mod.tipomarca_id', 'left');
        $this->db->where('o.sitio_id', $sitio_id);
        $this->db->where('o.tipo_operacion', 11); // 11 = Compra
        $this->db->order_by('o.fecha', 'DESC');
        

        $this->db->where('o.vehiculo_id', $vehiculo_id);

        return $this->db->get()->result();
    }

    public function listarPagos($vehiculo_id, $sitio_id) {
        $this->db->select('id_interno');
        $this->db->from('operacion');
        $this->db->where('vehiculo_id', $vehiculo_id);
        $this->db->where('tipo_operacion', 11); // Compras

        $row = $this->db->get()->row();
        if (!$row) return [];
        
        $id_interno = $row->id_interno;

        $this->db->select('fp.id, fp.id_interno, 
        ts.descripcion as tipostatus, fp.referencia as referencia,
        fp.importe as importe, fp.fechavencimiento as fechavencimiento');
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

    public function obtenerCorteId($sitio_id) {
        $this->db->select('MAX(id_interno) AS id, serie');
        $this->db->from('cortecaja');
        $this->db->where('sitio_id', $sitio_id);

        return $this->db->get()->row();
    }

    public function obtenerClienteVentaDefault($sitio_id) {
        $this->db->select('id');
        $this->db->from('clientes');
        $this->db->where('razonsocial_id', $sitio_id);
        $this->db->where('id_interno', 1); // Cliente interno por defecto
        $cliente = $this->db->get()->row();

        return $cliente ? $cliente->id : null;
    }

    public function agregarCompra($dataCompra, $data_operacion_auto, $formas_pago) {
        $this->db->trans_begin();

        try {
            // Validar que lo pagado sea igual al precio
            $acumular_importes = 0;
            foreach ($formas_pago as $forma_pago) {
                // Validar que 'importe' exista y sea numérico
                if (isset($forma_pago['importe']) && is_numeric($forma_pago['importe'])) {
                    $acumular_importes += $forma_pago['importe'];
                } else {
                    log_message('error', 'Valor no numérico encontrado en forma_pago: ' . json_encode($forma_pago));
                    return false; // O maneja el error según sea necesario
                }
            }

            if ($acumular_importes != $dataCompra['importe']) {
                return false;
            }

            // Obtener el siguiente id_interno
            $this->db->select('MAX(id_interno) as max_id');
            $this->db->from('operacion');
            $this->db->where('sitio_id', $dataCompra['sitio_id']);
            $max_id = $this->db->get()->row()->max_id;
            
            $dataCompra['id_interno'] = $max_id + 1;

            // Insertar la operación de compra
            $this->db->insert('operacion', $dataCompra);
            $operacion_id = $dataCompra['id_interno'];

            // Insertar datos de operacion_auto
            $data_operacion_auto['operacion_id'] = $operacion_id;
            $this->db->insert('operacion_auto', $data_operacion_auto);

            // Insertar formas de pago
            $this->db->select('MAX(id_interno) as max_id');
            $this->db->from('formapago');
            $this->db->where('sitio_id', $dataCompra['sitio_id']);
            $max_fp_id = $this->db->get()->row()->max_id;
            
            foreach ($formas_pago as $pago) {
                $pago_data = [
                    'id_interno' => ++$max_fp_id,
                    'operacion_id' => $operacion_id,
                    'formapago_id' => $pago['formapago_id'],
                    'importe' => $pago['importe'],
                    'fechaexpedicion' => $pago['fechaexpedicion'] ? $pago['fechaexpedicion'] : date('Y-m-d'),
                    'fechavencimiento' => $pago['fechavencimiento'] ? $pago['fechavencimiento'] : date('Y-m-d'),
                    'tipostatus_id' => 5211,
                    'serie' => 'A',
                    'codigo' => 0,
                    'sitio_id' => $dataCompra['sitio_id']
                ];
                $this->db->insert('formapago', $pago_data);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            }

            $this->db->trans_commit();
            return ['operacion_id' => $operacion_id];

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error al agregar compra: ' . $e->getMessage());
            return false;
        }
    }

    public function obtenerOperacion($id_operacion, $sitio_id) {
        $this->db->select('*');
        $this->db->from('operacion');
        $this->db->where('id_interno', $id_operacion);
        $this->db->where('sitio_id', $sitio_id);
        $this->db->where('tipo_operacion', 11); // Tipo compra
        return $this->db->get()->row();
    }

    public function actualizarCompra($id_operacion, $sitio_id, $dataCompra, $data_operacion_auto, $formas_pago) {
        $this->db->trans_begin();
        
        try {
            // Validar que la suma de los pagos coincida con el importe
            $suma_pagos = array_reduce($formas_pago, function($carry, $item) {
                return $carry + $item['importe'];
            }, 0);
            
            if ($suma_pagos != $dataCompra['importe']) {
                throw new Exception('La suma de los métodos de pago no coincide con el importe total');
            }

            // Actualizar operación principal
            $this->db->where('id_interno', $id_operacion);
            $this->db->where('sitio_id', $sitio_id);
            $this->db->update('operacion', $dataCompra);
            
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
            log_message('error', 'Error al actualizar compra: ' . $e->getMessage());
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