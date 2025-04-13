<?php

class BankModel extends CI_Model {

    function add_bank_account($sitio, $nombre, $numero) {

        $this->db->select('razonsocial_id');
        $this->db->from('sitio');
        $this->db->where('id', $sitio);

        $query = $this->db->get();

        $razonsocial_id = $query->row_array()['razonsocial_id'];
        
        // Seleccionar el ID de la tabla 'operacion_caja' basado en el 'razonsocial_id' proporcionado
        $this->db->select('MIN(id) AS id'); // Cambiado a MIN(id) para obtener el primer registro
        $this->db->from('operacion_caja');
        $this->db->where('razonsocial_id', $razonsocial_id);
        $this->db->limit(1);
        $query = $this->db->get();

        // Verificar si se encontró algún registro
        if ($query->num_rows() == 0) {
            // Si no hay registros, retornar false indicando que no se puede continuar
            log_message('error', 'No se encontró un registro en operacion_caja para el sitio: ' . $sitio);
            return false;
        }

        // Obtener el ID del registro encontrado
        $inicio_id = $query->row_array()['id'];
        log_message('debug', 'ID de inicio obtenido: ' . $inicio_id);

        // Concatenar el nombre y el número para formar el nombre de la cuenta
        $cuenta = $nombre . ' ' . strval($numero);
        log_message('debug', 'Nombre de la cuenta generado: ' . $cuenta);

        // Preparar los datos para insertar en la tabla 'cuentas_bancarias'
        $data = array(
            'nombre' => $cuenta,
            'inicio_id' => $inicio_id,
            'sitio_id' => $sitio
        );
        log_message('debug', 'Datos preparados para insertar: ' . json_encode($data));

        // Iniciar una transacción para garantizar la consistencia de los datos
        $this->db->trans_start();

        // Insertar los datos en la tabla 'cuentas_bancarias'
        $this->db->insert('cuentas_bancarias', $data);

        // Obtener el ID del registro recién insertado
        $insert_id = $this->db->insert_id();
        log_message('debug', 'ID insertado en cuentas_bancarias: ' . $insert_id);

        // Completar la transacción
        $this->db->trans_complete();

        // Verificar si la transacción fue exitosa
        if ($this->db->trans_status() === FALSE) {
            // Si hubo un error, registrar el error y devolverlo
            $error = $this->db->error();
            log_message('error', 'Error al insertar en cuentas_bancarias: ' . json_encode($error));
            return ['error' => $error];
        }

        // Retornar el ID del registro insertado si fue exitoso, o false si no
        if ($insert_id) {
            log_message('debug', 'Cuenta bancaria agregada exitosamente con ID: ' . $insert_id);
            return $insert_id;
        } else {
            log_message('error', 'No se pudo insertar la cuenta bancaria');
            return false;
        }
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

    public function updateBankAccount($id, $data, $sitio_id) {
        $this->db->where('id', $id);
        $this->db->where('sitio_id', $sitio_id);
        $this->db->update('cuentas_bancarias', $data);
    
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function deleteBankAccount($id, $sitio_id) {
        $this->db->where('id', $id);
        $this->db->where('sitio_id', $sitio_id);
        return $this->db->delete('cuentas_bancarias');
    }
}