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
            return false;
        }
    }

    function signup($data) {
        $this->db->insert('usuario', $data);
        return $this->db->insert_id();
    }

    function register_user(
        $id, $rfc, $razon_social, $representante_legal,
        $regimen_fiscal, $contrasenia) {
        
        $data = array(
            'rfc' => $rfc,
            'nombre' => $razon_social,
            'representante_legal' => $representante_legal,
            'regimen_fiscal' => $regimen_fiscal,
            'contrasenia' => $contrasenia
        );
        $this->db->set('rfc', 'nombre', 
        'representante_legal', 'regimen_fiscal', 
        'contrasenia');
        $this->db->where('id', $id);
        $this->db->update('razon_social', $data);

        // TODO - Verificar si se actualizó correctamente 
        // Y CAMBIAR EL STATUS DEL USUARIO
    }
}