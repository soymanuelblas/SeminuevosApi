<?php

class InicioModel extends CI_Model {

    public function obtenerVehiculosPorMes($sitio_id, $fecha_ini, $fecha_fin) {
        $this->db->select('count(*) as vehiculos_mes');
        $this->db->from('operacion');
        $this->db->join('vehiculo', 'vehiculo.id = operacion.vehiculo_id', 'left');
        $this->db->join('version', 'vehiculo.version_id = version.id', 'left');
        $this->db->join('tipomodelo', 'version.tipomodelo_id = tipomodelo.id', 'left');
        $this->db->join('tipoannio', 'tipoannio.id = version.tipoannio_id', 'left');
        $this->db->join('tipomarca', 'tipomarca.id = tipomodelo.tipomarca_id', 'left');
        $this->db->join('tipostatus', 'tipostatus.id = operacion.tipostatus_id', 'left');
        $this->db->join('clientes as compra', 'operacion.clientecompra_id = compra.id', 'left');
        $this->db->join('clientes as venta', 'operacion.clienteventa_id = venta.id', 'left');
        $this->db->join('operacion_caja as nombreoperacion', 'operacion.tipo_operacion = nombreoperacion.id', 'left');
        $this->db->join('operacion_auto as contrato', 'contrato.operacion_id = operacion.id_interno', 'left');
        $this->db->where('operacion.sitio_id', $sitio_id);
        $this->db->where_in('operacion.tipo_operacion', [3]);
        if($fecha_ini != '' && $fecha_fin != '') {
            $this->db->where("DATE_FORMAT(operacion.fecha, '%Y-%m-%d') BETWEEN '$fecha_ini' AND '$fecha_fin'", NULL, FALSE);
        }
        
        return $this->db->get()->row_array();
    }

    public function obtenerVehiculosPorAnio($sitio_id, $fecha_ini, $fecha_fin) {
        $this->db->select('count(*) as vehiculos_annio');
        $this->db->from('operacion');
        $this->db->join('vehiculo', 'vehiculo.id = operacion.vehiculo_id', 'left');
        $this->db->join('version', 'vehiculo.version_id = version.id', 'left');
        $this->db->join('tipomodelo', 'version.tipomodelo_id = tipomodelo.id', 'left');
        $this->db->join('tipoannio', 'tipoannio.id = version.tipoannio_id', 'left');
        $this->db->join('tipomarca', 'tipomarca.id = tipomodelo.tipomarca_id', 'left');
        $this->db->join('tipostatus', 'tipostatus.id = operacion.tipostatus_id', 'left');
        $this->db->join('clientes as compra', 'operacion.clientecompra_id = compra.id', 'left');
        $this->db->join('clientes as venta', 'operacion.clienteventa_id = venta.id', 'left');
        $this->db->join('operacion_caja as nombreoperacion', 'operacion.tipo_operacion = nombreoperacion.id', 'left');
        $this->db->join('operacion_auto as contrato', 'contrato.operacion_id = operacion.id_interno', 'left');
        $this->db->where('operacion.sitio_id', $sitio_id);
        $this->db->where_in('operacion.tipo_operacion', [3]);
        if($fecha_ini != '' && $fecha_fin != '') {
            $this->db->where("DATE_FORMAT(operacion.fecha, '%Y-%m-%d') BETWEEN '$fecha_ini' AND '$fecha_fin'", NULL, FALSE);
        }
        return $this->db->get()->row_array();
    }

    public function obtenerOportunidadesAtrasadas($sitio_id, $today) {
        $this->db->select('COUNT(*) as atrasadas');
        $this->db->from('oportunidad');
        $this->db->join('prospecto', 'prospecto.id = oportunidad.prospecto_id', 'left');
        $this->db->where('prospecto.sitio_id', $sitio_id);
        $this->db->where('oportunidad.tipostatus_id', 5163);
        if($today != '') {
            $this->db->where("DATE_FORMAT(oportunidad.fecha, '%Y-%m-%d') < '$today'", NULL, FALSE);
        }
        
        return $this->db->get()->row_array();
    }

    public function obtenerOportunidadesProceso($sitio_id) {
        $this->db->select('COUNT(*) as proceso');
        $this->db->from('oportunidad');
        $this->db->join('prospecto', 'prospecto.id = oportunidad.prospecto_id', 'left');
        $this->db->where('prospecto.sitio_id', $sitio_id);
        $this->db->where('oportunidad.tipostatus_id', 5163);
        return $this->db->get()->row_array();
    }

    public function obtenerOportunidadesNoLogradas($sitio_id) {
        $this->db->select('COUNT(*) as nolograda');
        $this->db->from('oportunidad');
        $this->db->join('prospecto', 'prospecto.id = oportunidad.prospecto_id', 'left');
        $this->db->where('prospecto.sitio_id', $sitio_id);
        $this->db->where('oportunidad.tipostatus_id', 5165);
        return $this->db->get()->row_array();
    }
    
    public function obtenerCobrarTotales($sitio_id) {
        $this->db->select('count(*) as totales');
        $this->db->from('operacion');
        $this->db->join('operacion_caja', 'operacion_caja.id = operacion.tipo_operacion', 'left');
        $this->db->join('vehiculo', 'vehiculo.id = operacion.vehiculo_id', 'left');
        $this->db->join('version', 'version.id = vehiculo.version_id', 'left');
        $this->db->join('tipomodelo', 'tipomodelo.id = version.tipomodelo_id', 'left');
        $this->db->join('tipomarca', 'tipomarca.id = tipomodelo.tipomarca_id', 'left');
        $this->db->join('tipoannio', 'tipoannio.id = version.tipoannio_id', 'left');
        $this->db->join('formapago', 'formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id', 'left');
        $this->db->join('tipostatus', 'formapago.formapago_id = tipostatus.id', 'left');
        $this->db->join('tipostatus AS formapago1', 'formapago1.id = formapago.tipostatus_id', 'left');
        $this->db->join('clientes', 'clientes.id = operacion.clientecompra_id', 'left');
        $this->db->join('clientes AS clientes1', 'clientes1.id = operacion.clienteventa_id', 'left');
        $this->db->join('cuenta_banco', 'cuenta_banco.formapago_id = formapago.id_interno AND cuenta_banco.sitio_id = formapago.sitio_id', 'left');
        $this->db->join('cuentas_bancarias AS cuenta', 'cuenta.id = cuenta_banco.cuenta_id', 'left');
        $this->db->join('tipostatus AS statuspago', 'statuspago.id = formapago.tipostatus_id', 'left');
        $this->db->join('tipostatus AS formapagostatus', 'formapagostatus.id = formapago.tipostatus_id', 'left');
        $this->db->where('operacion_caja.tipo', '1');
        $this->db->where('formapago.sitio_id', $sitio_id);
        $this->db->where_in('formapago.tipostatus_id', [5212]);
        return $this->db->get()->row_array();
    }

    public function obtenerCobrarMes($sitio_id, $fecha_ini, $fecha_fin) {
        $this->db->select('count(*) as mes');
        $this->db->from('operacion');
        $this->db->join('operacion_caja', 'operacion_caja.id = operacion.tipo_operacion', 'left');
        $this->db->join('vehiculo', 'vehiculo.id = operacion.vehiculo_id', 'left');
        $this->db->join('version', 'version.id = vehiculo.version_id', 'left');
        $this->db->join('tipomodelo', 'tipomodelo.id = version.tipomodelo_id', 'left');
        $this->db->join('tipomarca', 'tipomarca.id = tipomodelo.tipomarca_id', 'left');
        $this->db->join('tipoannio', 'tipoannio.id = version.tipoannio_id', 'left');
        $this->db->join('formapago', 'formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id', 'left');
        $this->db->join('tipostatus', 'formapago.formapago_id = tipostatus.id', 'left');
        $this->db->join('tipostatus AS formapago1', 'formapago1.id = formapago.tipostatus_id', 'left');
        $this->db->join('clientes', 'clientes.id = operacion.clientecompra_id', 'left');
        $this->db->join('clientes AS clientes1', 'clientes1.id = operacion.clienteventa_id', 'left');
        $this->db->join('cuenta_banco', 'cuenta_banco.formapago_id = formapago.id_interno AND cuenta_banco.sitio_id = formapago.sitio_id', 'left');
        $this->db->join('cuentas_bancarias AS cuenta', 'cuenta.id = cuenta_banco.cuenta_id', 'left');
        $this->db->join('tipostatus AS statuspago', 'statuspago.id = formapago.tipostatus_id', 'left');
        $this->db->join('tipostatus AS formapagostatus', 'formapagostatus.id = formapago.tipostatus_id', 'left');
        $this->db->where('operacion_caja.tipo', '1');
        $this->db->where('formapago.sitio_id', $sitio_id);
        $this->db->where_in('formapago.tipostatus_id', [5212]);
        if($fecha_ini != '' && $fecha_fin != '') {
            $this->db->where("DATE_FORMAT(formapago.fechavencimiento, '%Y-%m-%d') BETWEEN '$fecha_ini' AND '$fecha_fin'", NULL, FALSE);
        }
        return $this->db->get()->row_array();
    }

    public function obtenerCobrarVencidas($sitio_id, $today) {
        $this->db->select('count(*) as vencidas');
        $this->db->from('operacion');
        $this->db->join('operacion_caja', 'operacion_caja.id = operacion.tipo_operacion', 'left');
        $this->db->join('vehiculo', 'vehiculo.id = operacion.vehiculo_id', 'left');
        $this->db->join('version', 'version.id = vehiculo.version_id', 'left');
        $this->db->join('tipomodelo', 'tipomodelo.id = version.tipomodelo_id', 'left');
        $this->db->join('tipomarca', 'tipomarca.id = tipomodelo.tipomarca_id', 'left');
        $this->db->join('tipoannio', 'tipoannio.id = version.tipoannio_id', 'left');
        $this->db->join('formapago', 'formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id', 'left');
        $this->db->join('tipostatus', 'formapago.formapago_id = tipostatus.id', 'left');
        $this->db->join('tipostatus AS formapago1', 'formapago1.id = formapago.tipostatus_id', 'left');
        $this->db->join('clientes', 'clientes.id = operacion.clientecompra_id', 'left');
        $this->db->join('clientes AS clientes1', 'clientes1.id = operacion.clienteventa_id', 'left');
        $this->db->join('cuenta_banco', 'cuenta_banco.formapago_id = formapago.id_interno AND cuenta_banco.sitio_id = formapago.sitio_id', 'left');
        $this->db->join('cuentas_bancarias AS cuenta', 'cuenta.id = cuenta_banco.cuenta_id', 'left');
        $this->db->join('tipostatus AS statuspago', 'statuspago.id = formapago.tipostatus_id', 'left');
        $this->db->join('tipostatus AS formapagostatus', 'formapagostatus.id = formapago.tipostatus_id', 'left');
        $this->db->where('operacion_caja.tipo', '1');
        $this->db->where('formapago.sitio_id', $sitio_id);
        $this->db->where_in('formapago.tipostatus_id', [5212]);
        if($today != '') {
            $this->db->where("DATE_FORMAT(formapago.fechavencimiento, '%Y-%m-%d') < '$today'", NULL, FALSE);
        }
        return $this->db->get()->row_array();
    }

    public function obtenerPagarVencidas($sitio_id, $today) {
        $this->db->select('count(*) as vencidas_pagar');
        $this->db->from('operacion');
        $this->db->join('operacion_caja', 'operacion_caja.id = operacion.tipo_operacion', 'left');
        $this->db->join('vehiculo', 'vehiculo.id = operacion.vehiculo_id', 'left');
        $this->db->join('version', 'version.id = vehiculo.version_id', 'left');
        $this->db->join('tipomodelo', 'tipomodelo.id = version.tipomodelo_id', 'left');
        $this->db->join('tipomarca', 'tipomarca.id = tipomodelo.tipomarca_id', 'left');
        $this->db->join('tipoannio', 'tipoannio.id = version.tipoannio_id', 'left');
        $this->db->join('formapago', 'formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id', 'left');
        $this->db->join('tipostatus', 'formapago.formapago_id = tipostatus.id', 'left');
        $this->db->join('tipostatus AS formapago1', 'formapago1.id = formapago.tipostatus_id', 'left');
        $this->db->join('clientes', 'clientes.id = operacion.clientecompra_id', 'left');
        $this->db->join('clientes AS clientes1', 'clientes1.id = operacion.clienteventa_id', 'left');
        $this->db->join('cuenta_banco', 'cuenta_banco.formapago_id = formapago.id_interno AND cuenta_banco.sitio_id = formapago.sitio_id', 'left');
        $this->db->join('cuentas_bancarias AS cuenta', 'cuenta.id = cuenta_banco.cuenta_id', 'left');
        $this->db->join('tipostatus AS statuspago', 'statuspago.id = formapago.tipostatus_id', 'left');
        $this->db->join('tipostatus AS formapagostatus', 'formapagostatus.id = formapago.tipostatus_id', 'left');
        $this->db->where('operacion_caja.tipo', '2');
        $this->db->where('formapago.sitio_id', $sitio_id);
        $this->db->where_in('formapago.tipostatus_id', [5212]);
        if($today != '') {
            $this->db->where("DATE_FORMAT(formapago.fechavencimiento, '%Y-%m-%d') < '$today'", NULL, FALSE);
        }
        return $this->db->get()->row_array();
    }

    public function obtenerPagarMes($sitio_id, $fecha_ini, $fecha_fin) {
        $this->db->select('count(*) as mes_pagar');
        $this->db->from('operacion');
        $this->db->join('operacion_caja', 'operacion_caja.id = operacion.tipo_operacion', 'left');
        $this->db->join('vehiculo', 'vehiculo.id = operacion.vehiculo_id', 'left');
        $this->db->join('version', 'version.id = vehiculo.version_id', 'left');
        $this->db->join('tipomodelo', 'tipomodelo.id = version.tipomodelo_id', 'left');
        $this->db->join('tipomarca', 'tipomarca.id = tipomodelo.tipomarca_id', 'left');
        $this->db->join('tipoannio', 'tipoannio.id = version.tipoannio_id', 'left');
        $this->db->join('formapago', 'formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id', 'left');
        $this->db->join('tipostatus', 'formapago.formapago_id = tipostatus.id', 'left');
        $this->db->join('tipostatus AS formapago1', 'formapago1.id = formapago.tipostatus_id', 'left');
        $this->db->join('clientes', 'clientes.id = operacion.clientecompra_id', 'left');
        $this->db->join('clientes AS clientes1', 'clientes1.id = operacion.clienteventa_id', 'left');
        $this->db->join('cuenta_banco', 'cuenta_banco.formapago_id = formapago.id_interno AND cuenta_banco.sitio_id = formapago.sitio_id', 'left');
        $this->db->join('cuentas_bancarias AS cuenta', 'cuenta.id = cuenta_banco.cuenta_id', 'left');
        $this->db->join('tipostatus AS statuspago', 'statuspago.id = formapago.tipostatus_id', 'left');
        $this->db->join('tipostatus AS formapagostatus', 'formapagostatus.id = formapago.tipostatus_id', 'left');
        $this->db->where('operacion_caja.tipo', '2');
        $this->db->where('formapago.sitio_id', $sitio_id);
        $this->db->where_in('formapago.tipostatus_id', [5212]);
        if($fecha_ini != '' && $fecha_fin != '') {
            $this->db->where("DATE_FORMAT(formapago.fechavencimiento, '%Y-%m-%d') BETWEEN '$fecha_ini' AND '$fecha_fin'", NULL, FALSE);
        }
        return $this->db->get()->row_array();
    }

    public function obtenerPagarTotales($sitio_id) {
        $this->db->select('count(*) as totales_pagar');
        $this->db->from('operacion');
        $this->db->join('operacion_caja', 'operacion_caja.id = operacion.tipo_operacion', 'left');
        $this->db->join('vehiculo', 'vehiculo.id = operacion.vehiculo_id', 'left');
        $this->db->join('version', 'version.id = vehiculo.version_id', 'left');
        $this->db->join('tipomodelo', 'tipomodelo.id = version.tipomodelo_id', 'left');
        $this->db->join('tipomarca', 'tipomarca.id = tipomodelo.tipomarca_id', 'left');
        $this->db->join('tipoannio', 'tipoannio.id = version.tipoannio_id', 'left');
        $this->db->join('formapago', 'formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id', 'left');
        $this->db->join('tipostatus', 'formapago.formapago_id = tipostatus.id', 'left');
        $this->db->join('tipostatus AS formapago1', 'formapago1.id = formapago.tipostatus_id', 'left');
        $this->db->join('clientes', 'clientes.id = operacion.clientecompra_id', 'left');
        $this->db->join('clientes AS clientes1', 'clientes1.id = operacion.clienteventa_id', 'left');
        $this->db->join('cuenta_banco', 'cuenta_banco.formapago_id = formapago.id_interno AND cuenta_banco.sitio_id = formapago.sitio_id', 'left');
        $this->db->join('cuentas_bancarias AS cuenta', 'cuenta.id = cuenta_banco.cuenta_id', 'left');
        $this->db->join('tipostatus AS statuspago', 'statuspago.id = formapago.tipostatus_id', 'left');
        $this->db->join('tipostatus AS formapagostatus', 'formapagostatus.id = formapago.tipostatus_id', 'left');
        $this->db->where('operacion_caja.tipo', '2');
        $this->db->where('formapago.sitio_id', $sitio_id);
        $this->db->where_in('formapago.tipostatus_id', [5212]);
        return $this->db->get()->row_array();
    }

}