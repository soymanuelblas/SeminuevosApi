<?php

class BankModel extends CI_Model {

    function add_bank_account($sitio, $nombre) {
        $this->db->select('id');
        $this->db->from('operacion_caja');
        $this->db->where('razonsocial_id', $sitio);
        $query = $this->db->get();
    
        if ($query->num_rows() == 0) {
            return false;
        }
    
        $inicio_id = $query->row_array()['id'];
    
        $data = array(
            'nombre' => $nombre,
            'inicio_id' => $inicio_id,
            'sitio_id' => $sitio
        );

        $this->db->trans_start();
        $this->db->insert('cuentas_bancarias', $data);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            return ['error' => $this->db->error()];
        }
        return $insert_id ? $insert_id : false;
    }

    function listBankAccounts($sitio) {
        $this->db->select('id, nombre');
        $this->db->from('cuentas_bancarias');
        $this->db->where('sitio_id', $sitio);
        $query = $this->db->get();
    
        if ($query->num_rows() == 0) {
            return false;
        }
        return $query->result_array();
    }
}