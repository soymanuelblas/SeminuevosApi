<?php

class UsuariosModel extends CI_Model {

    public function obtenerUsuarios($sitio_id) {
        $this->db->select('usuario.nombre, usuario.usr, t.descripcion as status_descripcion');
        $this->db->from('usuario');
        $this->db->join('tipostatus as t', 'usuario.tipostatus_id = t.id'); // Ajuste en la condición de la unión
        $this->db->where('usuario.sitio_id', $sitio_id);
        $query = $this->db->get();
        return $query->result();
    }

}