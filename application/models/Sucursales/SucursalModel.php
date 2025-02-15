<?php

class SucursalModel extends CI_Model {

    function add_sucursal($id, $data) {

        $id = (int) $id;
        $this->db->select('sitio_id');
        $this->db->from('usuario');
        $this->db->where('id', $id);
        $query = $this->db->get();

        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $query->row()->sitio_id);

        $query2 = $this->db->get();

        $razonsocial_id = $query2->row()->razonsocial_id;

        $data['razonsocial_id'] = $razonsocial_id;
        
        $this->db->insert('sitio', $data);
        return $this->db->insert_id();
    }

    function update_sucursal($data) {
        $this->db->where('id', $data['id']);
        return $this->db->update('sitio', $data);
    }
    
    function list_sucursal($sitio_id) {

        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio_id);

        $query2 = $this->db->get();

        $razonsocial_id = $query2->row()->razonsocial_id;

        $this->db->select('id, nombre, domicilio1, domicilio2, ciudad, estado, cp, pais, telefono1, telefono2, contacto, correo, pass_correo');
        $this->db->from('sitio');
        $this->db->where('razonsocial_id', $razonsocial_id);

        $query = $this->db->get();

        return $query->result();

    }

}