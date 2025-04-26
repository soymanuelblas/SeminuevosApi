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

    public function obtenerImagenPorId($imagen_id, $sitio_id) {
        // Obtener la imagen con verificación de pertenencia al sitio
        $this->db->select('i.*, v.sitio_id');
        $this->db->from('imagen i');
        $this->db->join('vehiculo v', 'i.vehiculo_id = v.id');
        $this->db->where('i.id', $imagen_id);
        $query = $this->db->get();
    
        if ($query->num_rows() === 0) {
            return false;
        }
    
        $imagen = $query->row_array();
    
        // Verificar que pertenece al sitio correcto
        if ($imagen['sitio_id'] != $sitio_id) {
            return false;
        }
    
        return $imagen;
    }

    public function eliminarImagen($imagen_id, $sitio_id) {
        // Verificar que la imagen pertenece al sitio antes de eliminar
        $this->db->select('i.id, v.sitio_id');
        $this->db->from('imagen i');
        $this->db->join('vehiculo v', 'i.vehiculo_id = v.id');
        $this->db->where('i.id', $imagen_id);
        $query = $this->db->get();
    
        if ($query->num_rows() === 0 || $query->row()->sitio_id != $sitio_id) {
            return false;
        }
    
        // Eliminar el registro
        $this->db->where('id', $imagen_id);
        return $this->db->delete('imagen');
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