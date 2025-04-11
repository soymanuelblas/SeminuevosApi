<?php

class AuthModel extends CI_Model {

    function check_login($email, $password) {
        $this->db->select('id, nombre, usr,
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

    function register_bank_account($banco) {

        // Obtener razonsocial_id del sitio
        // Obtener razonsocial_id del sitio
        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $banco['sitio_id']);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $razonsocial_id = $query->row_array()['razonsocial_id'];

            $this->db->select('id');
            $this->db->from('operacion_caja');
            $this->db->where('razonsocial_id', $razonsocial_id);
            $query = $this->db->get();
            
            if ($query->num_rows() == 0) {
                return false;
            }

            $inicio_id = $query->row_array()['id'];

            $banco['inicio_id'] = $inicio_id;

            $this->db->insert('cuentas_bancarias', $banco);
            return $this->db->insert_id();
        } else {
            return false; // No se encontró el sitio
        }
    }

    function register_data_cash_ingresos($ingresos, $sitio) {

        // Obtener razonsocial_id del sitio
        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $razonsocial_id = $query->row_array()['razonsocial_id'];
            
            // Asignar razonsocial_id a los ingresos
            $ingresos['razonsocial_id'] = $razonsocial_id;
        } else {
            return false; // No se encontró el sitio
        }
        
        $this->db->insert('operacion_caja', $ingresos);
        return $this->db->insert_id();
    }
    
    function register_data_cash_egresos($egresos, $sitio) {

        // Obtener razonsocial_id del sitio
        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $razonsocial_id = $query->row_array()['razonsocial_id'];
            
            // Asignar razonsocial_id a los egresos
            $egresos['razonsocial_id'] = $razonsocial_id;
        } else {
            return false; // No se encontró el sitio
        }
        
        $this->db->insert('operacion_caja', $egresos);
        return $this->db->insert_id();
    }

    function register_complemento_sitio($sitio_id) {
        $data = [
            'sitio_id' => $sitio_id,
            'logo_url' => 'x',
            'email_contacto' => '',
            'vision' => '',
            'mision' => '',
            'valores' => '',
            'plantilla_id' => '1',
            'url_minisitio' => 'x',
            'ubicacion' => '',
            'url_facebook' => '',
            'url_instagram' => '',
            'url_twitter' => '',
            'url_youtube' => '',
            'color_fondo' => '#ffffff',
            'color_titulos' => '#000000',
            'color_texto' => '#000000',
            'horario_laboral' => '',
            'color_general' => '#d1d1d1',
            'contra_contacto' => 'X'
        ];
        $this->db->insert('complemento_sitio', $data);
        return $this->db->insert_id();
    }
}