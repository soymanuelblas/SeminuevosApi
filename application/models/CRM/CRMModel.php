<?php

class CRMModel extends CI_Model {

    /**
     * Obtiene el número de prospectos por estado.
     */
    private function obtenerProspectosPorEstado($estado, $fecha_ini, $fecha_fin, $sitio_id) {
        $this->db->select('prospecto.id');
        $this->db->from('prospecto');
        $this->db->join('oportunidad', 'oportunidad.prospecto_id = prospecto.id', 'left');
        $this->db->join('seguimiento', 'seguimiento.oportunidad_id = oportunidad.id', 'left');
        $this->db->where('oportunidad.tipostatus_id', $estado);
        $this->db->where('prospecto.sitio_id', $sitio_id);
        $this->db->where("seguimiento.fechacontacto BETWEEN '$fecha_ini 00:00:00' AND '$fecha_fin 23:59:59'", null, false);
        $this->db->group_by('prospecto.id');
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * Calcula los porcentajes de prospectos por estado.
     */
    private function calcularPorcentajes($total_count, $total_count1, $total_count2, $total_count3) {
        $total = $total_count + $total_count1 + $total_count2 + $total_count3;

        if ($total == 0) {
            return [
                'pausa' => 0,
                'proceso' => 0,
                'nolograda' => 0,
                'lograda' => 0
            ];
        }

        return [
            'pausa' => number_format(($total_count * 100) / $total, 2),
            'proceso' => number_format(($total_count1 * 100) / $total, 2),
            'nolograda' => number_format(($total_count2 * 100) / $total, 2),
            'lograda' => number_format(($total_count3 * 100) / $total, 2)
        ];
    }

    /**
     * Obtiene los IDs de seguimiento más recientes por prospecto.
     */
    private function obtenerFiltrosSeguimiento($fecha_ini, $fecha_fin, $sitio_id) {
        $this->db->select('MAX(seguimiento.id) AS filtro');
        $this->db->from('prospecto');
        $this->db->join('oportunidad', 'oportunidad.prospecto_id = prospecto.id', 'left');
        $this->db->join('seguimiento', 'seguimiento.oportunidad_id = oportunidad.id', 'left');
        $this->db->where("seguimiento.fechacontacto BETWEEN '$fecha_ini 00:00:00' AND '$fecha_fin 23:59:59'", null, false);
        $this->db->where('prospecto.sitio_id', $sitio_id);
        $this->db->group_by('prospecto.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Cuenta los seguimientos por estado.
     */
    private function contarSeguimientosPorEstado($filtros) {
    $totales = [
        'total1a' => 0, 'total1b' => 0, 'total1c' => 0,
        'total2a' => 0, 'total2b' => 0, 'total3a' => 0,
        'total3b' => 0, 'total3c' => 0, 'total3d' => 0,
        'total3e' => 0, 'total3f' => 0, 'total4a' => 0,
        'total4b' => 0, 'total5' => 0, 'total0a' => 0,
        'total0b' => 0, 'total0c' => 0, 'total0d' => 0,
        'total0e' => 0, 'total0f' => 0, 'total0g' => 0,
        'total0h' => 0, 'total0i' => 0, 'total0j' => 0,
        'total6a' => 0
    ];

    // Mapeo de status_id a claves del array $totales
    $mapeoStatus = [
        5508 => 'total1a',
        5184 => 'total1b',
        5185 => 'total1c',
        5186 => 'total2a',
        5187 => 'total2b',
        5188 => 'total3a',
        5189 => 'total3b',
        5190 => 'total3c',
        5191 => 'total3d',
        5192 => 'total3e',
        5193 => 'total3f',
        5194 => 'total4a',
        5195 => 'total4b',
        5196 => 'total5',
        5197 => 'total0a',
        5198 => 'total0b',
        5199 => 'total0c',
        5500 => 'total0d',
        5501 => 'total0e',
        5502 => 'total0f',
        5503 => 'total0g',
        5504 => 'total0h',
        5505 => 'total0i',
        5506 => 'total0j',
        5507 => 'total6a'
    ];

    if (!empty($filtros)) {
        foreach ($filtros as $filtro) {
            $this->db->select('tipostatus_id');
            $this->db->from('seguimiento');
            $this->db->where('id', $filtro['filtro']);
            $query = $this->db->get();
            $row = $query->row_array();

            if ($row) {
                $status_id = $row['tipostatus_id'];
                if (isset($mapeoStatus[$status_id])) {
                    $clave = $mapeoStatus[$status_id];
                    $totales[$clave]++;
                }
            }
        }
    }

    return $totales;
}

    /**
     * Función principal que obtiene todos los datos del reporte.
     */
    public function obtenerDatosProspectos($fecha_ini, $fecha_fin, $sitio_id) {
        // Obtener conteos de prospectos por estado
        $total_count = $this->obtenerProspectosPorEstado('5166', $fecha_ini, $fecha_fin, $sitio_id);
        $total_count1 = $this->obtenerProspectosPorEstado('5163', $fecha_ini, $fecha_fin, $sitio_id);
        $total_count2 = $this->obtenerProspectosPorEstado('5165', $fecha_ini, $fecha_fin, $sitio_id);
        $total_count3 = $this->obtenerProspectosPorEstado('5164', $fecha_ini, $fecha_fin, $sitio_id);

        // Calcular porcentajes
        $porcentajes = $this->calcularPorcentajes($total_count, $total_count1, $total_count2, $total_count3);

        // Obtener filtros de seguimiento
        $filtros = $this->obtenerFiltrosSeguimiento($fecha_ini, $fecha_fin, $sitio_id);

        // Contar seguimientos por estado
        $totales = $this->contarSeguimientosPorEstado($filtros);

        // Calcular el total de registros
        $totalregistros = array_sum($totales);

        // Retornar todos los datos
        return [
            'totales' => $totales,
            'porcentajes' => $porcentajes,
            'totalregistros' => $totalregistros
        ];
    }
}