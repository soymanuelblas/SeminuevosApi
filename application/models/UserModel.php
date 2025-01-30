<?php

class UserModel extends CI_Model {

    function get_user($id) {
        $id = (int)$id;
        log_message('info', "Buscando usuario ID: $id");

        $this->db->select('nombre'); // Siempre incluir el ID
        $this->db->from('usuario');
        $this->db->where('id', $id);
        $query = $this->db->get();

        log_message('info', "SQL ejecutado: " . $this->db->last_query());

        if($query->num_rows() > 0) {
            $result = $query->row_array();
            log_message('info', "Usuario encontrado: " . print_r($result, true));
            return $result;
        }

        log_message('error', "Usuario no encontrado para ID: $id");
        return false;
    }

}