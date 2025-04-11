<?php

class RolesModel extends CI_Model {

    public function listRoles() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', '57');
        $this->db->order_by('id', 'ASC');
    }

}