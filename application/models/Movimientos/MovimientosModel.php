<?php

class MovimientosModel extends CI_Model {

    // Obtener el último ID de operación
    public function obtenerUltimoID($sitio_id) {
        $this->db->select_max('id_interno', 'idope');
        //$this->db->where('tipo_operacion', 1);
        $this->db->where('sitio_id', $sitio_id);
        $query = $this->db->get('operacion');
        return $query->row_array();
    }

    // Obtener el ID del corte
    public function obtenerCorteID($id_interno, $sitio_id) {
        $this->db->select('corte_id');
        $this->db->where('id_interno', $id_interno);
        $this->db->where('sitio_id', $sitio_id);
        $query = $this->db->get('operacion');
        return $query->row_array();
    }

    // Obtener movimientos del día
    public function obtenerMovimientos($sitio_id, $movresultado) {
        $this->db->select("
            operacion.id_interno AS idope, 
            operacion.importe AS saldoanterior, 
            operacion_caja.descripcion AS movimiento, 
            clientes.nombre AS clienteventa,
            compra.nombre AS clientecompra, 
            operacion.fecha AS fecha, 
            formapago.referencia AS referencia, 
            operacion.adicional_id AS adicional, 
            tipostatus.descripcion AS formapago,
            formapago.fechaexpedicion AS fechaexpedicion, 
            formapago.importe AS importe, 
            clientes.id_interno AS idventa, 
            compra.id_interno AS idcompra, 
            CONCAT('( ', vehiculo.noexpediente, ') ', marca.descripcion, ' ', modelo.descripcion, ' ' , annio.descripcion, ' ', version.descripcion ) AS vehiculo,
            cuenta.nombre AS cuenta
        ");
        $this->db->from('operacion');
        $this->db->join('operacion_caja', 'operacion_caja.id = operacion.tipo_operacion', 'left');
        $this->db->join('clientes', 'clientes.id = operacion.clienteventa_id', 'left');
        $this->db->join('clientes AS compra', 'compra.id = operacion.clientecompra_id', 'left');
        $this->db->join('formapago', 'formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id', 'left');
        $this->db->join('tipostatus', 'formapago.formapago_id = tipostatus.id', 'left');
        $this->db->join('vehiculo', 'vehiculo.id = operacion.vehiculo_id', 'left');
        $this->db->join('version', 'vehiculo.version_id = version.id', 'left');
        $this->db->join('tipoannio AS annio', 'version.tipoannio_id = annio.id', 'left');
        $this->db->join('tipomodelo AS modelo', 'version.tipomodelo_id = modelo.id', 'left');
        $this->db->join('tipomarca AS marca', 'modelo.tipomarca_id = marca.id', 'left');
        $this->db->join('cuenta_banco', 'cuenta_banco.formapago_id = formapago.id_interno AND cuenta_banco.sitio_id = formapago.sitio_id', 'left');
        $this->db->join('cuentas_bancarias AS cuenta', 'cuenta.id = cuenta_banco.cuenta_id', 'left');
        //$this->db->where('operacion.tipo_operacion', 1);
        //$this->db->where('operacion.id_interno', $movresultado);
        $this->db->where('operacion.sitio_id', $sitio_id);
        
        $query = $this->db->get();
        return $query->result_array();
    }
}