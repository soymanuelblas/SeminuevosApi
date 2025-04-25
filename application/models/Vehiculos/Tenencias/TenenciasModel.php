<?php

class TenenciasModel extends CI_Model {

    public function agregarTenencia($data) {
        $this->db->insert('tenencia', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function obtenerTenenciaPorId($tenencia_id, $sitio_id) {
        $this->db->select('t.vehiculo_id as vehiculo_id');
        $this->db->from('tenencia t');
        $this->db->where('t.id', $tenencia_id);
        $tenencia = $this->db->get()->row_array(); // Cambiado a row_array()
    
        if (!$tenencia) {
            return false;
        }
    
        $vehiculo_id = $tenencia['vehiculo_id'];
    
        $this->db->select('sitio_id');
        $this->db->from('vehiculo');
        $this->db->where('id', $vehiculo_id);
        $vehiculo = $this->db->get()->row();
    
        if (!$vehiculo || $vehiculo->sitio_id != $sitio_id) {
            return false;
        }

        $this->db->select('t.archivo as archivo');
        $this->db->from('tenencia t');
        $this->db->where('t.id', $tenencia_id);
        $this->db->where('t.vehiculo_id', $vehiculo_id);

        $result = $this->db->get()->row_array();
    
        return $result ? $result['archivo'] : false;
    }

    public function actualizarTenencia($tenencia_id, $sitio_id, $data) {

        // Verificar si el vehículo existe y obtener su sitio_id
        $this->db->select('sitio_id');
        $this->db->from('vehiculo');
        $this->db->where('id', $data['vehiculo_id']);
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            return false;
        }

        $sitio = $query->row()->sitio_id;
        // Comparar sitio_id
        if ($sitio != $sitio_id) {
            return false;
        }

        $this->db->where('id', $tenencia_id);
        return $this->db->update('tenencia', $data);
    }

    public function obtenerEstados() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 20);
        $this->db->order_by('descripcion', 'ASC');

        return $this->db->get()->result();
    }

    public function obtenerAnnios() {
        $this->db->select('id, descripcion');
        $this->db->from('tipoannio');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(15);

        return $this->db->get()->result();
    }

    public function obtenerTenencias($sitio_id, $vehiculo_id) {
        try {
            // Verificar si el vehículo existe y obtener su sitio_id
            $this->db->select('sitio_id');
            $this->db->from('vehiculo');
            $this->db->where('id', $vehiculo_id);
            $query = $this->db->get();

            if ($query->num_rows() == 0) {
                return false;
            }

            $sitio = $query->row()->sitio_id;
            // Comparar sitio_id
            if ($sitio != $sitio_id) {
                return false;
            }

            // Obtener tenencias
            $this->db->select('t.id, t.vehiculo_id, t.archivo, st.descripcion as estado, ta.descripcion as tipoannio, ts.descripcion as tipostatus');
            $this->db->from('tenencia t');
            $this->db->join('tipoannio ta', 'ta.id = t.tipoannio_id', 'left');
            $this->db->join('tipostatus ts', 'ts.id = t.tipostatus_id', 'left');
            $this->db->join('tipostatus st', 'st.id = t.estado_id', 'left');
            $this->db->where('t.vehiculo_id', $vehiculo_id);

            $result = $this->db->get()->result();

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Excepción capturada en obtenerTenencias: ' . $e->getMessage());
            return false;
        }
    }

}