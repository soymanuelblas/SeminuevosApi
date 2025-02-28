<?php

class VehiculosModel extends CI_Model {

    public function obtenerVehiculos($sitio_id, $marca, $modelo, $annio, $expediente) {
        $this->db->select(
            "vehiculo.id, 
            version.descripcion as version, 
            vehiculo.noexpediente,
            vehiculo.duplicado,
            vehiculo.numeroserie,
            venta.descripcion AS venta, 
            vehiculo.garantia,
            vehiculo.status_venta as status_venta,
            vehiculo.duenio as duenio,
            vehiculo.tipo_vehiculo as tipo_vehiculo,
            vehiculo.tipostatus_id as tipostatus_id,
            vehiculo.color_id as color_id,
            vehiculo.precio,
            vehiculo.kilometraje, 
            modelo.descripcion AS modelo, 
            marca.descripcion AS marca, 
            annio.descripcion AS annio, 
            color.descripcion AS color, 
            modelo.id AS modeloid, 
            marca.id AS marcaid, 
            annio.id AS annioid");
        $this->db->from("vehiculo");
        $this->db->join("tipostatus AS venta", "vehiculo.status_venta = venta.id");
        $this->db->join("version", "vehiculo.version_id = version.id");
        $this->db->join("tipomodelo AS modelo", "version.tipomodelo_id = modelo.id");
        $this->db->join("tipomarca AS marca", "marca.id = modelo.tipomarca_id");
        $this->db->join("tipoannio AS annio", "version.tipoannio_id = annio.id");
        $this->db->join("tipostatus AS color", "color.id = vehiculo.color_id");
        $this->db->join("tipostatus AS duplicado", "duplicado.id = vehiculo.duplicado");
        $this->db->join("tipostatus AS garantia", "garantia.id = vehiculo.garantia");
        $this->db->join("tipostatus AS status_venta", "status_venta.id = vehiculo.status_venta");
        $this->db->join("tipostatus AS duenio", "duenio.id = vehiculo.duenio");
        $this->db->join("tipostatus AS tipo_vehiculo", "tipo_vehiculo.id = vehiculo.tipo_vehiculo");
        $this->db->join("tipostatus AS tipostatus_id", "tipostatus_id.id = vehiculo.tipostatus_id");
        $this->db->join("tipostatus AS color_id", "color_id.id = vehiculo.color_id");
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

    public function actualizarVehiculo($id, $sitio_id, $data) {
        // Registrar los valores de entrada
        log_message('debug', 'ID: ' . $id);
        log_message('debug', 'Sitio ID: ' . $sitio_id);
        log_message('debug', 'Data: ' . print_r($data, true));
    
        // Establecer las condiciones de la consulta
        $this->db->where('id', $id);
        $this->db->where('sitio_id', $sitio_id);
    
        // Ejecutar la actualización
        $result = $this->db->update('vehiculo', $data);
    
        // Registrar el resultado de la actualización
        if ($result) {
            log_message('debug', 'Actualización exitosa');
        } else {
            log_message('error', 'Error en la actualización: ' . $this->db->last_query());
        }
    
        return $result;
    }

    public function obtenerGarantia() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 39);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function obtenerTipoVehiculo() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 2);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function obtenerTipoStatus() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 17);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function obtenerColor() {
        $this->db->select('id, descripcion');
        $this->db->from('tipostatus');
        $this->db->where('tipo', 16);
        $query = $this->db->get();
        return $query->result_array();
    }
    
}