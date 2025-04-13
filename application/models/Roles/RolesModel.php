<?php

class RolesModel extends CI_Model {

    public function listRoles() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 57);

        return $this->db->get()->result_array();
    }

}