<?php

class UserModel extends CI_Model {

    function get_user($id) {
        $id = (int)$id;
        $this->db->select('id, nombre, usr, tipostatus_id');
        $this->db->from('usuario');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $result = $query->row_array();
            return $result;
        }
        return false;
    }

}