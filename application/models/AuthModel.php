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
    // Datos iniciales de registro
    function register_user_data($sitio, $rfc, $razon_social, 
        $representante_legal, $regimen_fiscal) {

        // Obtener razonsocial_id del sitio
        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $razonsocial_id = $query->row_array()['razonsocial_id'];
            
            $data = array(
                'rfc' => strtoupper($rfc),
                'nombre' => strtoupper($razon_social),
                'representante_legal' => $representante_legal,
                'regimen_fiscal' => $regimen_fiscal
            );

            $this->db->where('id', $razonsocial_id);
            $this->db->update('razon_social', $data);

            $data_caja = array(
                'tipo' => '1',
                'descripcion' => 'INICIO DE CUENTA',
                'valor' => '0',
                'razonsocial_id' => $razonsocial_id
            );

            $this->db->insert('operacion_caja', $data_caja);

            return $this->db->affected_rows() > 0;
        }
        return false;
    }
    // Datos de registro de usuario iniciales
    function register_data_pwd($sitio, $contrasenia) {
        $data = array(
            'pwd' => $contrasenia,
            'tipostatus_id' => 851
        );
    
        $this->db->where('sitio_id', $sitio);
        $this->db->update('usuario', $data);
    
        return $this->db->affected_rows() > 0;
    }    
}