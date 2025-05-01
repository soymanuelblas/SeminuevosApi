<?php

class IntermediacionModel extends CI_Model {

    public function agregarIntermediacion($data, $sitio_id) {
        $this->db->insert('consignacion', $data);
        return $this->db->insert_id();
    }

    public function actualizarIntermediacion($id, $sitio_id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('consignacion', $data);
    }
}