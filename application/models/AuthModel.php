<?php

class AuthModel extends CI_Model{

    function check_login($email, $password) {
        $this->db->select('id, nombre, usr, permisos,
        tipostatus_id, rol_id, sitio_id');
        $this->db->from('usuario');
        $this->db->where('usr', $email);
        $this->db->where('pwd', $password);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

    function signup($data) {
        $this->db->insert('usuario', $data);
        return $this->db->insert_id();
    }
}