<?php 

include "variables.php";
date_default_timezone_set("America/Mexico_City");
$today = date("Y-m-d");
$today1 = date("d-m-Y");

$mes = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"]
[date("n") - 1];

$mes;
$fecha = new DateTime();
$fecha->modify('last day of this month');
$fecha_ini = new DateTime();
$fecha_ini->modify('first day of this month');
$fecha_ini_ano = date("Y").'-01-01';
$fecha_fin_ano = date("Y").'-12-31';
$sql1 ="SELECT  count(*) as vehiculos_mes

FROM operacion

LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id

LEFT JOIN version ON vehiculo.version_id = version.id

LEFT JOIN tipomodelo ON version.tipomodelo_id = tipomodelo.id

LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id

LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id

LEFT JOIN tipostatus ON tipostatus.id = operacion.tipostatus_id

LEFT JOIN clientes AS compra ON operacion.clientecompra_id = compra.id

LEFT JOIN clientes AS venta ON operacion.clienteventa_id = venta.id 

LEFT JOIN operacion_caja AS nombreoperacion ON operacion.tipo_operacion = nombreoperacion.id

LEFT JOIN operacion_auto AS contrato ON contrato.operacion_id = operacion.id_interno 

WHERE operacion.sitio_id = '$sitiocambio' AND operacion.tipo_operacion IN('3') AND DATE_FORMAT(operacion.fecha,'%Y-%m-%d') BETWEEN '".$fecha_ini->format('Y-m-d')."' AND '".$fecha->format('Y-m-d')."' ";

$resultado1 = $mysqli->query($sql1);

$row = $resultado1->fetch_array(MYSQLI_ASSOC);



$sql2 = "SELECT  count(*) as vehiculos_annio

FROM operacion

LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id

LEFT JOIN version ON vehiculo.version_id = version.id

LEFT JOIN tipomodelo ON version.tipomodelo_id = tipomodelo.id

LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id

LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id

LEFT JOIN tipostatus ON tipostatus.id = operacion.tipostatus_id

LEFT JOIN clientes AS compra ON operacion.clientecompra_id = compra.id

LEFT JOIN clientes AS venta ON operacion.clienteventa_id = venta.id 

LEFT JOIN operacion_caja AS nombreoperacion ON operacion.tipo_operacion = nombreoperacion.id

LEFT JOIN operacion_auto AS contrato ON contrato.operacion_id = operacion.id_interno 

WHERE operacion.sitio_id = '$sitiocambio' AND operacion.tipo_operacion IN('3') AND DATE_FORMAT(operacion.fecha,'%Y-%m-%d') BETWEEN '".$fecha_ini_ano."' AND '".$fecha_fin_ano."' ";

$resultado2 = $mysqli->query($sql2);

$row1 = $resultado2->fetch_array(MYSQLI_ASSOC);





    $sql9 = "SELECT COUNT(*) AS atrasadas 

    FROM oportunidad 

    LEFT JOIN prospecto ON prospecto.id = oportunidad.prospecto_id

    WHERE prospecto.sitio_id = '$sitiocambio' AND oportunidad.tipostatus_id = '5163' AND DATE(proximocontacto) < '$today'";

    $resultado9 = $mysqli->query($sql9);

    $row2 = $resultado9->fetch_array(MYSQLI_ASSOC);



    $sql10 = "SELECT COUNT(*) AS proceso 

    FROM oportunidad 

    LEFT JOIN prospecto ON prospecto.id = oportunidad.prospecto_id

    WHERE prospecto.sitio_id = '$sitiocambio' AND oportunidad.tipostatus_id = '5163' ";

    $resultado10 = $mysqli->query($sql10);

    $row3 = $resultado10->fetch_array(MYSQLI_ASSOC);

    

    $sqlnolograda = "SELECT COUNT(*) AS nolograda 

    FROM oportunidad 

    LEFT JOIN prospecto ON prospecto.id = oportunidad.prospecto_id

    WHERE prospecto.sitio_id = '$sitiocambio' AND oportunidad.tipostatus_id = '5165' ";

    $resultadonolograda = $mysqli->query($sqlnolograda);

    $rownolograda = $resultadonolograda->fetch_array(MYSQLI_ASSOC);







$sql19 = "SELECT tipoatributo.id, tipostatus.descripcion AS categoria, tipoatributo.descripcion AS des 

FROM `tipoatributo`

LEFT JOIN tipostatus ON tipoatributo.tipocategoria_id = tipostatus.valor 

WHERE tipostatus.tipo = 0 ";

$resultado19 = $mysqli->query($sql19);



$sql20 = "SELECT equipamiento.id, tipomarca.descripcion AS marca, tipomodelo.descripcion AS modelo, tipoannio.descripcion AS annio, version.descripcion, tipoatributo.descripcion AS atributo FROM version INNER JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id INNER JOIN tipoannio ON tipoannio.id = version.tipoannio_id INNER JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id INNER JOIN equipamiento ON equipamiento.version_id = version.id INNER JOIN tipoatributo ON tipoatributo.id = equipamiento.tipoatributo_id ORDER BY tipomarca.descripcion ASC";

$resultado20 = $mysqli->query($sql20);





$sql22 = "SELECT clientes.id AS id,clientes.rfc,clientes.nombre,clientes.domicilio,clientes.colonia,clientes.cp,clientes.ciudad,clientes.estado,clientes.telefono1, clientes.telefono2,clientes.email,tipostatus.descripcion, usuario.nombre AS usuario, clientes.razonsocial_id 

    FROM clientes 

    LEFT JOIN tipostatus ON tipostatus.id = clientes.tipostatus_id 

    LEFT JOIN usuario ON usuario.id = clientes.usuario_id

    WHERE clientes.razonsocial_id = '$razon_social' AND clientes.id_interno > 1 ";

    $resultado22 = $mysqli->query($sql22);





$sql23 = "SELECT vehiculo.id, version.descripcion as version, vehiculo.noexpediente, vehiculo.numeroserie, 

venta.descripcion AS venta, vehiculo.precio,vehiculo.kilometraje, modelo.descripcion AS modelo,marca.id AS marcaid, marca.descripcion, annio.id AS annioid, annio.descripcion AS annio, trans.id AS transid, trans.descripcion AS transmision, color.descripcion AS color, motor.descripcion AS motor, factura.expedidapor AS factura, factura.folio AS foliofactura, factura.fecha AS facturafecha, duenio.descripcion AS duenio, statusventa.descripcion AS statusventa, fina1.nombre AS financiamiento, garantia.descripcion AS garantia

FROM vehiculo 

LEFT JOIN tipostatus AS venta ON vehiculo.status_venta = venta.id 

LEFT JOIN version AS version ON vehiculo.version_id = version.id 

LEFT JOIN tipomodelo AS modelo ON version.tipomodelo_id = modelo.id 

LEFT JOIN tipomarca AS marca ON marca.id = modelo.tipomarca_id 

LEFT JOIN tipoannio AS annio ON version.tipoannio_id = annio.id

LEFT JOIN tipostatus AS trans ON version.transmision = trans.id

LEFT JOIN tipostatus AS color on color.id = vehiculo.color_id

LEFT JOIN tipostatus AS motor ON version.motor = motor.id

LEFT JOIN factura AS factura ON factura.vehiculo_id = vehiculo.id

LEFT JOIN tipostatus AS duenio ON duenio.id = vehiculo.duenio

LEFT JOIN tipostatus AS statusventa ON statusventa.id = vehiculo.status_venta 

LEFT JOIN vehi_fin AS fina ON fina.vehiculo_id = vehiculo.id

LEFT JOIN financiamiento AS fina1 ON fina1.id =fina.id

LEFT JOIN tipostatus AS garantia ON garantia.id = vehiculo.garantia

WHERE vehiculo.sitio_id = '$sitioSession' ORDER BY noexpediente ASC";

    $resultado23 = $mysqli->query($sql23);





$sql25 = "SELECT operacion.id_interno AS id,operacion.fecha AS fechamovimiento, CONCAT('(',vehiculo.noexpediente,')',' ', tipomarca.descripcion,' ', tipomodelo.descripcion,' ',version.descripcion,' ', tipoannio.descripcion) AS vehiculo, operacion_caja.descripcion AS operacion, tipostatus.descripcion AS formapago, formapago.fechaexpedicion AS fechaexpedicion, DATE_FORMAT(formapago.fechavencimiento,'%d/%m/%Y') AS fechavencimineto, formapago1.descripcion AS status, formapago.importe AS importe, clientes.nombre AS clientecompra, clientes1.nombre AS clienteventa, operacion_caja.descripcion AS operacion1, formapago.id_interno AS formapagoid, formapago.referencia AS descripcion, operacion.clientecompra_id AS compraid, operacion.clienteventa_id AS ventaid,formapago.tipostatus_id AS statusidd,operacion_caja.tipo AS tipo 

FROM operacion

LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion

LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id

LEFT JOIN version ON version.id = vehiculo.version_id

LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id

LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id

LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id

LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id

LEFT JOIN tipostatus ON formapago.formapago_id = tipostatus.id

LEFT JOIN tipostatus AS formapago1 ON formapago1.id = formapago.tipostatus_id 

LEFT JOIN clientes ON clientes.id = operacion.clientecompra_id

LEFT JOIN clientes AS clientes1 ON clientes1.id = operacion.clienteventa_id

WHERE formapago.tipostatus_id = '5211' AND operacion.sitio_id = '$sitioSession' ";

    $resultado25 = $mysqli->query($sql25);









 $mi_consulta1="SELECT id, descripcion FROM tipostatus WHERE tipo = 55 ORDER BY id ASC";       

$result1 = $mysqli->query($mi_consulta1);





$cobrar_vencidas = "SELECT count(*) as vencidas

FROM operacion 

LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion 

LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id 

LEFT JOIN version ON version.id = vehiculo.version_id 

LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id 

LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id 

LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id 

LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id 

LEFT JOIN tipostatus ON formapago.formapago_id = tipostatus.id 

LEFT JOIN tipostatus AS formapago1 ON formapago1.id = formapago.tipostatus_id

LEFT JOIN clientes ON clientes.id = operacion.clientecompra_id

LEFT JOIN clientes AS clientes1 ON clientes1.id = operacion.clienteventa_id

LEFT JOIN cuenta_banco ON cuenta_banco.formapago_id = formapago.id_interno  

AND cuenta_banco.sitio_id = formapago.sitio_id

LEFT JOIN cuentas_bancarias AS cuenta ON cuenta.id = cuenta_banco.cuenta_id

LEFT JOIN tipostatus AS statuspago ON statuspago.id = formapago.tipostatus_id

LEFT JOIN tipostatus AS formapagostatus ON formapagostatus.id = formapago.tipostatus_id 

WHERE operacion_caja.tipo = '1' AND formapago.sitio_id = '$sitiocambio' AND formapago.tipostatus_id IN('5212') AND

DATE_FORMAT(formapago.fechavencimiento,'%Y-%m-%d') < '$today' ";

$resultado_cobrarvencidas = $mysqli->query($cobrar_vencidas);

$row_cobrar_vencidas = $resultado_cobrarvencidas->fetch_array(MYSQLI_ASSOC);



$cobrar_mes = "SELECT count(*) as mes

FROM operacion 

LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion 

LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id 

LEFT JOIN version ON version.id = vehiculo.version_id 

LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id 

LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id 

LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id 

LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id 

LEFT JOIN tipostatus ON formapago.formapago_id = tipostatus.id 

LEFT JOIN tipostatus AS formapago1 ON formapago1.id = formapago.tipostatus_id

LEFT JOIN clientes ON clientes.id = operacion.clientecompra_id

LEFT JOIN clientes AS clientes1 ON clientes1.id = operacion.clienteventa_id

LEFT JOIN cuenta_banco ON cuenta_banco.formapago_id = formapago.id_interno  

AND cuenta_banco.sitio_id = formapago.sitio_id

LEFT JOIN cuentas_bancarias AS cuenta ON cuenta.id = cuenta_banco.cuenta_id

LEFT JOIN tipostatus AS statuspago ON statuspago.id = formapago.tipostatus_id

LEFT JOIN tipostatus AS formapagostatus ON formapagostatus.id = formapago.tipostatus_id 

WHERE operacion_caja.tipo = '1' AND formapago.sitio_id = '$sitiocambio' AND formapago.tipostatus_id IN('5212') AND

DATE_FORMAT(formapago.fechavencimiento,'%Y-%m-%d') BETWEEN '".$fecha_ini->format('Y-m-d')."' AND '".$fecha->format('Y-m-d')."'  ";

$resultado_cobrarmes = $mysqli->query($cobrar_mes);

$row_cobrar_mes = $resultado_cobrarmes->fetch_array(MYSQLI_ASSOC);



$cobrar_totales = "SELECT count(*) as totales

FROM operacion 

LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion 

LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id 

LEFT JOIN version ON version.id = vehiculo.version_id 

LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id 

LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id 

LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id 

LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id 

LEFT JOIN tipostatus ON formapago.formapago_id = tipostatus.id 

LEFT JOIN tipostatus AS formapago1 ON formapago1.id = formapago.tipostatus_id

LEFT JOIN clientes ON clientes.id = operacion.clientecompra_id

LEFT JOIN clientes AS clientes1 ON clientes1.id = operacion.clienteventa_id

LEFT JOIN cuenta_banco ON cuenta_banco.formapago_id = formapago.id_interno  

AND cuenta_banco.sitio_id = formapago.sitio_id

LEFT JOIN cuentas_bancarias AS cuenta ON cuenta.id = cuenta_banco.cuenta_id

LEFT JOIN tipostatus AS statuspago ON statuspago.id = formapago.tipostatus_id

LEFT JOIN tipostatus AS formapagostatus ON formapagostatus.id = formapago.tipostatus_id 

WHERE operacion_caja.tipo = '1' AND formapago.sitio_id = '$sitiocambio' AND formapago.tipostatus_id IN('5212')";

$resultado_cobrartotales = $mysqli->query($cobrar_totales);

$row_cobrar_totales = $resultado_cobrartotales->fetch_array(MYSQLI_ASSOC);





//Cuentas por pagar



$pagar_vencidas = "SELECT count(*) as vencidas_pagar

FROM operacion 

LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion 

LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id 

LEFT JOIN version ON version.id = vehiculo.version_id 

LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id 

LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id 

LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id 

LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id 

LEFT JOIN tipostatus ON formapago.formapago_id = tipostatus.id 

LEFT JOIN tipostatus AS formapago1 ON formapago1.id = formapago.tipostatus_id

LEFT JOIN clientes ON clientes.id = operacion.clientecompra_id

LEFT JOIN clientes AS clientes1 ON clientes1.id = operacion.clienteventa_id

LEFT JOIN cuenta_banco ON cuenta_banco.formapago_id = formapago.id_interno 

AND cuenta_banco.sitio_id = formapago.sitio_id

LEFT JOIN cuentas_bancarias AS cuenta ON cuenta.id = cuenta_banco.cuenta_id

LEFT JOIN tipostatus AS statuspago ON statuspago.id = formapago.tipostatus_id

LEFT JOIN tipostatus AS formapagostatus ON formapagostatus.id = formapago.tipostatus_id 

WHERE operacion_caja.tipo = '2' AND formapago.sitio_id = '$sitiocambio' AND formapago.tipostatus_id IN('5212') AND

DATE_FORMAT(formapago.fechavencimiento,'%Y-%m-%d') < '$today' ";

$resultado_pagarvencidas = $mysqli->query($pagar_vencidas);

$row_pagar_vencidas = $resultado_pagarvencidas->fetch_array(MYSQLI_ASSOC);



$pagar_mes = "SELECT count(*) as mes_pagar

FROM operacion 

LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion 

LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id 

LEFT JOIN version ON version.id = vehiculo.version_id 

LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id 

LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id 

LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id 

LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id 

LEFT JOIN tipostatus ON formapago.formapago_id = tipostatus.id 

LEFT JOIN tipostatus AS formapago1 ON formapago1.id = formapago.tipostatus_id

LEFT JOIN clientes ON clientes.id = operacion.clientecompra_id

LEFT JOIN clientes AS clientes1 ON clientes1.id = operacion.clienteventa_id

LEFT JOIN cuenta_banco ON cuenta_banco.formapago_id = formapago.id_interno 

AND cuenta_banco.sitio_id = formapago.sitio_id

LEFT JOIN cuentas_bancarias AS cuenta ON cuenta.id = cuenta_banco.cuenta_id

LEFT JOIN tipostatus AS statuspago ON statuspago.id = formapago.tipostatus_id

LEFT JOIN tipostatus AS formapagostatus ON formapagostatus.id = formapago.tipostatus_id 

WHERE operacion_caja.tipo = '2' AND formapago.sitio_id = '$sitiocambio' AND formapago.tipostatus_id IN('5212') AND

DATE_FORMAT(formapago.fechavencimiento,'%Y-%m-%d') BETWEEN '".$fecha_ini->format('Y-m-d')."' AND '".$fecha->format('Y-m-d')."'  ";

$resultado_pagarmes = $mysqli->query($pagar_mes);

$row_pagar_mes = $resultado_pagarmes->fetch_array(MYSQLI_ASSOC);



$pagar_totales = "SELECT count(*) as totales_pagar

FROM operacion 

LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion 

LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id 

LEFT JOIN version ON version.id = vehiculo.version_id 

LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id 

LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id 

LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id 

LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno AND formapago.sitio_id = operacion.sitio_id 

LEFT JOIN tipostatus ON formapago.formapago_id = tipostatus.id 

LEFT JOIN tipostatus AS formapago1 ON formapago1.id = formapago.tipostatus_id

LEFT JOIN clientes ON clientes.id = operacion.clientecompra_id

LEFT JOIN clientes AS clientes1 ON clientes1.id = operacion.clienteventa_id

LEFT JOIN cuenta_banco ON cuenta_banco.formapago_id = formapago.id_interno 

AND cuenta_banco.sitio_id = formapago.sitio_id

LEFT JOIN cuentas_bancarias AS cuenta ON cuenta.id = cuenta_banco.cuenta_id

LEFT JOIN tipostatus AS statuspago ON statuspago.id = formapago.tipostatus_id

LEFT JOIN tipostatus AS formapagostatus ON formapagostatus.id = formapago.tipostatus_id 

WHERE operacion_caja.tipo = '2' AND formapago.sitio_id = '$sitiocambio' AND formapago.tipostatus_id IN('5212')";
$resultado_pagartotales = $mysqli->query($pagar_totales);
$row_pagar_totales = $resultado_pagartotales->fetch_array(MYSQLI_ASSOC);





?>