<?php

class ProspectosModel extends CI_Model {


    public function obtenerProspectos($sitio_id) {
        $this->db->select('
            op.id,
            tp.descripcion as status,
            tposeg.descripcion as seguimiento,
            tpo.descripcion as operacion,
            prono.nombre,
            prono.telefono1 as telefono,
            op.fecha_llegada as fecha,
            UPPER(tm.descripcion) as marca,
            version.descripcion as version,
            UPPER(op.observacion) as observacion,
            tmo.descripcion as modelo,
            tad.descripcion as aniodesde,
            taa.descripcion as aniohasta,
            us.nombre as asesor
            ');
        $this->db->from('oportunidad as op');
        $this->db->join('tipostatus as tp', 'op.tipostatus_id = tp.id');
        $this->db->join('prospecto as prono', 'op.prospecto_id = prono.id');
        $this->db->join('tipostatus as tpo', 'op.tipooperacion_id = tpo.id');
        $this->db->join('prospecto as pro', 'pro.id = op.prospecto_id');
        $this->db->join('seguimiento as seg', 'seg.oportunidad_id = op.id');
        $this->db->join('tipostatus as tposeg', 'tposeg.id = seg.tipostatus_id');
        $this->db->join('tipomarca as tm', 'tm.id = op.tipomarca_id');
        $this->db->join('tipoannio as tad', 'tad.id = op.anodesde');
        $this->db->join('tipoannio as taa', 'taa.id = op.anohasta');
        $this->db->join('tipomodelo as tmo', 'tmo.id = op.tipomodelo_id', 'left');
        $this->db->join('version as version', 'version.id = op.version_id', 'left');
        $this->db->join('usuario as us', 'us.id = pro.usuario_id');
        $this->db->where('pro.sitio_id', $sitio_id);
        $query = $this->db->get();

        return $query->result();
    }



}