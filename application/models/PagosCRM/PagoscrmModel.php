<?php

class PagoscrmModel extends CI_Model {

    function obtener_pagos($fInicio, $fUltima) {
        $this->db->select("
            pCRM.id as idPago, 
            RS.nombre as nombre_razon, 
            iMP.tittleItem as nombre_paquete,
            pCRM.numOrder as numero_orden,
            CASE
                WHEN pCRM.idstatus = 13800 THEN 'PAGADO'
                WHEN pCRM.idstatus = 13801 THEN 'EN PROCESO'
                WHEN pCRM.idstatus = 13802 THEN 'SIN PAGO'
                WHEN pCRM.idstatus = 13803 THEN 'PAGADO NUEVO USUARIO'
                WHEN pCRM.idstatus = 13804 THEN 'EN PROCESO NUEVO USUARIO'
                ELSE 'DESCONOCIDO'
            END as status_pago, 
            CASE 
                WHEN apCRM.estadoPago = 13800 THEN 'PAGADO'
                WHEN apCRM.estadoPago = 13801 THEN 'EN PROCESO'
                WHEN apCRM.estadoPago = 13802 THEN 'SIN PAGO'
                WHEN apCRM.estadoPago = 13803 THEN 'PAGADO NUEVO USUARIO'
                WHEN apCRM.estadoPago = 13804 THEN 'EN PROCESO NUEVO USUARIO'
                ELSE 'DESCONOCIDO' 
            END as status_acceso, 
        ");
        $this->db->from('accesos_pagosCRM as apCRM');
        $this->db->join('pago_crm AS pCRM', 'pCRM.id = apCRM.crm_id', 'left');
        $this->db->join('razon_social AS RS', 'RS.id = apCRM.razon_id', 'left');
        $this->db->join('itemMercaLib AS iMP', 'iMP.id = apCRM.item_id', 'left');
        $this->db->join('tipostatus ts', 'ts.id = apCRM.estadoPago', 'left');
   
        $this->db->where('pCRM.fecha >=', $fInicio);
        $this->db->where('pCRM.fecha <=', $fUltima);

        $query = $this->db->get();
        return $query->result();
    }
}