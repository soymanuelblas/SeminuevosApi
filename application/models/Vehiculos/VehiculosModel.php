<?php

class VehiculosModel extends CI_Model {

    public function obtenerVehiculos($sitio_id, $marca, $modelo, $annio, $expediente) {
        $this->db->select("vehiculo.id, version.descripcion as version, vehiculo.noexpediente, vehiculo.numeroserie,
                    venta.descripcion AS venta, vehiculo.precio, vehiculo.kilometraje, modelo.descripcion AS modelo, 
                    marca.descripcion AS marca, annio.descripcion AS annio, color.descripcion AS color, 
                    modelo.id AS modeloid, marca.id AS marcaid, annio.id AS annioid");
        $this->db->from("vehiculo");
        $this->db->join("tipostatus AS venta", "vehiculo.status_venta = venta.id");
        $this->db->join("version", "vehiculo.version_id = version.id");
        $this->db->join("tipomodelo AS modelo", "version.tipomodelo_id = modelo.id");
        $this->db->join("tipomarca AS marca", "marca.id = modelo.tipomarca_id");
        $this->db->join("tipoannio AS annio", "version.tipoannio_id = annio.id");
        $this->db->join("tipostatus AS color", "color.id = vehiculo.color_id");
        $this->db->where('vehiculo.sitio_id', $sitio_id);

        // Filtros opcionales
        if (!empty($marca)) {
            $this->db->where_in('marca.id', $marca);
        }
        if (!empty($modelo)) {
            $this->db->where_in('modelo.id', $modelo);
        }
        if (!empty($annio)) {
            $this->db->where_in('annio.id', $annio);
        }
        if (!empty($expediente)) {
            $this->db->where_in('vehiculo.noexpediente', $expediente);
        } else {
            $this->db->where('venta.descripcion', 'DISPONIBLE');
        }

        $query = $this->db->get();
        return $query->result_array();
    }
    
}