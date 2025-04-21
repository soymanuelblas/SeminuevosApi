<?php

class ImagenesModel extends CI_Model {
    
    public function listarTipoImagen() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where_in('id', ['6400', '6401', '6402', '6403', '6404', '6405']);
        $query = $this->db->get();
        return $query->result();
    }

    public function agregarImagen($data, $sitio) {

        $this->db->select('sitio_id');
        $this->db->from('vehiculo');
        $this->db->where('id', $data['vehiculo_id']);

        $sitio_id = $this->db->get()->row()->sitio_id;

        if($sitio_id != $sitio) {
            return false;
        }

        $this->db->insert('imagen', $data);
        return $this->db->insert_id();
    }

    public function eliminarImagen($id, $sitio) {

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
            $this->db->delete('imagen');
            return true;
        } else {
            return false;
        }
    }

    public function listarImagen($vehiculo_id, $tipo, $sitio_id) {
        $this->db->select('sitio_id');
        $this->db->from('vehiculo');
        $this->db->where('id', $vehiculo_id);
        
        $sitio = $this->db->get()->row()->sitio_id;

        if($sitio != $sitio_id) {
            return false;
        }

        $this->db->select('id, vehiculo_id, tipo, archivo');
        $this->db->from('imagen');
        $this->db->where('vehiculo_id', $vehiculo_id);
        $this->db->where('tipo', $tipo);

        return $this->db->get()->result();
    }


}