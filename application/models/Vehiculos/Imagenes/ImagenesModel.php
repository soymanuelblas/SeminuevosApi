<?php

class ImagenesModel extends CI_Model {
    
    public function listarTipoImagen() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where_in('id', ['6400', '6401', '6402', '6403', '6404', '6405']);
        $query = $this->db->get();
        return $query->result();
    }

    public function agregarPrincipal($data) {
        $this->db->insert('imagen', $data);
        return $this->db->insert_id();
    }

    public function eliminarPrincipal($id, $sitio) {

        $this->db->select('vehiculo_id');
        $this->db->from('imagen');
        $this->db->where('id', $id);

        $vehiculo_id = $this->db->get()->row()->vehiculo_id;

        $this->db->select('sitio_id');
        $this->db->from('vehiculo');
        $this->db->where('id', $vehiculo_id);

        $sitio_id = $this->db->get()->row()->sitio_id;

        if($sitio_id == $sitio) {
            $this->db->where('vehiculo_id', $vehiculo_id);
            $this->db->where('tipo', 6400);
            $this->db->delete('imagen');
            return true;
        } else {
            return false;
        }
    }

    
}