<?php
class EstadisticasModel extends CI_Model {

    public function obtenerEstadisticas($vehiculo_id, $sitio_id) {
        $conteos = [
            'en_pausa' => $this->contarProspectos($vehiculo_id, $sitio_id, '5166'),
            'en_proceso' => $this->contarProspectos($vehiculo_id, $sitio_id, '5163'),
            'no_lograda' => $this->contarProspectos($vehiculo_id, $sitio_id, '5165'),
            'lograda' => $this->contarProspectos($vehiculo_id, $sitio_id, '5164')
        ];

        $total = $conteos['en_pausa'] + $conteos['en_proceso'] + 
                $conteos['no_lograda'] + $conteos['lograda'];

        $porcentajes = [
            'en_pausa' => $total > 0 ? number_format(($conteos['en_pausa'] * 100) / $total, 2) : 0,
            'en_proceso' => $total > 0 ? number_format(($conteos['en_proceso'] * 100) / $total, 2) : 0,
            'no_lograda' => $total > 0 ? number_format(($conteos['no_lograda'] * 100) / $total, 2) : 0,
            'lograda' => $total > 0 ? number_format(($conteos['lograda'] * 100) / $total, 2) : 0
        ];

        // $gastos = $this->obtenerGastos($vehiculo_id, $sitio_id);

        return [
            'conteos' => $conteos,
            'porcentajes' => $porcentajes,
            'total' => $total,
            'gastos' => $gastos,
            // 'detalle_gastos' => $this->obtenerDetalleGastos($vehiculo_id, $sitio_id)
        ];
    }

    private function contarProspectos($vehiculo_id, $sitio_id, $status_id) {
        $this->db->select('COUNT(prospecto.id) as total');
        $this->db->from('prospecto');
        $this->db->join('oportunidad', 'oportunidad.prospecto_id = prospecto.id', 'left');
        
        $this->db->where('oportunidad.tipostatus_id', $status_id);
        $this->db->where('oportunidad.vehiculo_id', $vehiculo_id);
        
        if ($sitio_id) {
            $this->db->where('prospecto.sitio_id', $sitio_id);
        }

        $query = $this->db->get();
        return $query->row()->total;
    }

    // private function obtenerGastos($vehiculo_id, $sitio_id) {
    //     $this->db->select('SUM(formapago.importe) AS gastos_totales');
    //     $this->db->from('operacion');
    //     $this->db->join('formapago', 'formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id', 'left');
    //     $this->db->join('operacion_caja', 'operacion_caja.id = operacion.tipo_operacion', 'left');
        
    //     $this->db->where('operacion_caja.tipo', 2);
    //     $this->db->where('formapago.formapago_id !=', '5034');
    //     $this->db->where('formapago.tipostatus_id', '5210');
    //     $this->db->where('operacion.vehiculo_id', $vehiculo_id);
        
    //     if ($sitio_id) {
    //         $this->db->where('operacion.sitio_id', $sitio_id);
    //     }

    //     $query = $this->db->get();
    //     return $query->row()->gastos_totales ?: 0;
    // }

    // private function obtenerDetalleGastos($vehiculo_id, $sitio_id) {
    //     $this->db->select('operacion.id_interno AS id, formapago.importe, formapago.referencia AS descripcion, 
    //                      DATE(formapago.fechaexpedicion) AS fecha, operacion_caja.descripcion AS operacion');
    //     $this->db->from('operacion');
    //     $this->db->join('formapago', 'formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id', 'left');
    //     $this->db->join('operacion_caja', 'operacion_caja.id = operacion.tipo_operacion', 'left');
        
    //     $this->db->where('operacion_caja.tipo', 2);
    //     $this->db->where('formapago.formapago_id !=', '5034');
    //     $this->db->where('formapago.tipostatus_id', '5210');
    //     $this->db->where('operacion.vehiculo_id', $vehiculo_id);
        
    //     if ($sitio_id) {
    //         $this->db->where('operacion.sitio_id', $sitio_id);
    //     }

    //     $this->db->order_by('formapago.fechaexpedicion', 'ASC');
        
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }
}