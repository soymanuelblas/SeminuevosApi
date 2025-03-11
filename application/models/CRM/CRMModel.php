<?php

class CRMModel extends CI_Model {

    private function obtenerProspectosPorEstado($estado, $fecha_ini, $fecha_fin, $sitio_id) {
        $sql = "SELECT prospecto.id 
                FROM prospecto 
                LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id 
                LEFT JOIN seguimiento ON seguimiento.oportunidad_id = oportunidad.id 
                WHERE oportunidad.tipostatus_id = ? 
                AND prospecto.sitio_id = ? 
                AND DATE(seguimiento.fechacontacto) BETWEEN ? AND ? 
                GROUP BY prospecto.id";
        $query = $this->db->query($sql, [$estado, $sitio_id, $fecha_ini, $fecha_fin]);
        return $query->num_rows();
    }

    /**
     * Calcula los porcentajes de prospectos por estado.
     */
    private function calcularPorcentajes($total_count, $total_count1, $total_count2, $total_count3) {
        $total = $total_count + $total_count1 + $total_count2 + $total_count3;

        if($total == 0) {
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
        $sql = "SELECT MAX(seguimiento.id) AS filtro
                FROM prospecto 
                LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id 
                LEFT JOIN seguimiento ON seguimiento.oportunidad_id = oportunidad.id
                WHERE DATE(seguimiento.fechacontacto) BETWEEN ? AND ? 
                AND prospecto.sitio_id = ? 
                GROUP BY prospecto.id";
        $query = $this->db->query($sql, [$fecha_ini, $fecha_fin, $sitio_id]);
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

        // Obtener todos los IDs de los filtros
        $ids = array_column($filtros, 'filtro');

        if (!empty($ids)) {
            // Construir la consulta SQL para obtener todos los tipostatus_id de una vez
            $sql = "SELECT tipostatus_id FROM seguimiento WHERE id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")";
            $query = $this->db->query($sql, $ids);
            $result = $query->result_array();

            // Contar las ocurrencias de cada tipostatus_id
            foreach ($result as $row) {
                $status_id = $row['tipostatus_id'];
                if (isset($totales["total$status_id"])) {
                    $totales["total$status_id"]++;
                }
            }
        }

        return $totales;
    }

    /**
     * Ejecuta el procedimiento almacenado para el reporte de seguimientos.
     */
    private function ejecutarProcedimientoAlmacenado($fecha_ini, $fecha_fin, $sitio_id) {
        $sql = "CALL reporte_seguimientos(?, ?, ?)";
        $this->db->query($sql, [$fecha_ini, $fecha_fin, $sitio_id]);
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

        // Ejecutar el procedimiento almacenado
        $this->ejecutarProcedimientoAlmacenado($fecha_ini, $fecha_fin, $sitio_id);

        // Retornar todos los datos
        return [
            'totales' => $totales,
            'porcentajes' => $porcentajes,
            'totalregistros' => $totalregistros
        ];
    }
}