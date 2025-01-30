<?php

class UserModel extends CI_Model {

    function get_user($id) {
        // Verificar conexión a la base de datos
        if (!$this->db->conn_id) {
            log_message('error', 'No hay conexión a la base de datos');
            return false;
        }

        // Forzar tipo de dato y registrar el ID
        $id = (int)$id;
        log_message('info', "Buscando usuario ID: $id");

        $this->db->select('id, nombre'); // Siempre incluir el ID
        $this->db->from('usuario');
        $this->db->where('id', $id);
        $query = $this->db->get();

        // Registrar la consulta SQL generada
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