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

    public function actualizarUsuario($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('usuario', $data);
    }

    // public function detalleUsuario($id) {
    //     $this-db->select(
    //         'id, nombre, usr, ts.descripcion as status
    //         ');
    //     $this->db->from('usuario as us');
    //     $this->db->join('tipostatus as ts', 'us.tipostatus_id = ts.id', 'left');
    //     $this->db->where('id', $id);
    // }

}