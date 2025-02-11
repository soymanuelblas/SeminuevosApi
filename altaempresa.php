<?php
include "variables.php";
date_default_timezone_set("America/Mexico_City");
$nombre = $_POST['nombre'];
$calle = $_POST['calle'];
$colonia = $_POST['colonia'];
$ciudad1 = $_POST['ciudad'];
$estado = $_POST['estado'];
$cp = $_POST['cp'];
$pais = $_POST['pais'];
$tel1 = $_POST['tel1'];
$tel2 = $_POST['tel2'];
$contacto = '';
$correo = $_POST['correo'];
$contra = $_POST['contra'];
$usuario = $_POST['usuario'];
$razon = $_POST['razon'];
$rfc = $_POST['rfc'];
$time = time();
$fecha = date("Y-m-d H:i:s", $time);
$sitio_nombre = $_SESSION["nombre"];

$sqlciudad ="SELECT nombre FROM municipios WHERE id = '$ciudad1'";
$resultadociudad = $mysqli->query($sqlciudad);
$rowciudad = $resultadociudad->fetch_array(MYSQLI_ASSOC);

$ciudad = $rowciudad['nombre'];



$sql=" INSERT INTO `sitio` (`id`, `nombre`, `domicilio1`, `domicilio2`, `ciudad`, `estado`, `cp`, `pais`, `telefono1`, `telefono2`, `contacto`, `razonsocial_id`, `correo`, `pass_correo`) 
	values (NULL,'$nombre','$calle','$colonia','$ciudad','$estado','$cp','$pais','$tel1','$tel2','$contacto','$razon','$correo','$contra')";
mysqli_query($mysqli,$sql);

$sqlsitio ="SELECT MAX(id) AS id FROM sitio";
$resultadositio = $mysqli->query($sqlsitio);
$rowsitio = $resultadositio->fetch_array(MYSQLI_ASSOC);

$sitioinsert = $rowsitio['id'];


$sqlcorte=" INSERT INTO `cortecaja`(`id`, `id_interno`, `serie`, `billete_1000`, `billete_500`, `billete_200`, `billete_100`, `billete_50`, `billete_20`, `modena_10`, `modena_5`, `modena_2`, `modena_1`, `moneda_50c`, `total_efectivo`, `fecha`, `usuario_id`, `sitio_id`) 
	values (NULL,'1','A','0','0','0','0','0','0','0','0','0','0','0','0',NULL,'$usuario','$sitioinsert')";
mysqli_query($mysqli,$sqlcorte);

$sql1 ="SELECT * FROM clientes WHERE razonsocial_id = '$razon_social' AND id_interno = 1 ";
$resultado = $mysqli->query($sql1);
$row = $resultado->fetch_array(MYSQLI_ASSOC);

if ($row['id_interno'] == 1) {

	$cliente_insert = $row['id'];

}else{

$sql=" INSERT INTO `clientes` (`id`, `id_interno`, `rfc`, `nombre`, `domicilio`, `colonia`, `telefono1`, `telefono2`, `email`, `ciudad`, `estado`, `cp`, `usuario_id`, `tipostatus_id`, `razonsocial_id`, `tipocliente_id`, `password`) values (NULL,'1','$rfc','$nombre','$calle','$colonia','$tel1','$tel2','$correo','$ciudad','$estado','$cp','$usuario','851','$razon','5300','X')";
mysqli_query($mysqli,$sql);

$sqlcliente ="SELECT MAX(id) AS id FROM clientes WHERE razonsocial_id = '$razon_social' AND id_interno = 1";
$resultadocliente = $mysqli->query($sqlcliente);
$rowcliente = $resultadocliente->fetch_array(MYSQLI_ASSOC);

$cliente_insert = $rowcliente['id'];

}

$sacar_sitios = "SELECT sitio.id AS sitioid FROM sitio LEFT JOIN razon_social ON razon_social.id = sitio.razonsocial_id WHERE razon_social.id = '$razon_social' ";
$sacar_sitios1 = $mysqli->query($sacar_sitios);
	
	while($row10 = $sacar_sitios1->fetch_array(MYSQLI_ASSOC)) {
        $sitios_razon[]= strtoupper($row10['sitioid']);
    }
$sitios_razon_social = implode("','", $sitios_razon);

$validarope = "SELECT COUNT(id) AS id FROM operacion WHERE sitio_id IN('".$sitios_razon_social."') AND tipo_operacion = 1" ;
$validarope1 = $mysqli->query($validarope);
$validacionpago = $validarope1->fetch_array(MYSQLI_ASSOC);
  
$id_validacion = $validacionpago['id'];

if ($id_validacion != 0) {

	$ultimooperacion = "SELECT MAX(id_interno)+1 AS id FROM operacion  WHERE sitio_id IN('".$sitios_razon_social."')" ;
	$pagoultimooperacion = $mysqli->query($ultimooperacion);
	$pago2pagoultimooperacion = $pagoultimooperacion->fetch_array(MYSQLI_ASSOC);
  
	$ultimaoperacion = $pago2pagoultimooperacion['id'];

	$sqloperacion1=" INSERT INTO `operacion`(`id`, `id_interno`, `tipo_operacion`, `sitio_id`, `vehiculo_id`, `clientecompra_id`, `clienteventa_id`, `importe`, `fecha`, `tipostatus_id`, `usuario_id`, `corte_id`, `adicional_id`, `fecha_entrega`)
	values (NULL,'$ultimaoperacion','1','$sitioinsert',NULL,'$cliente_insert','$cliente_insert','0.00','$fecha','5022','$usuario','A1','0',NULL)";
	mysqli_query($mysqli,$sqloperacion1);

}else if($id_validacion == 0){

	$sqloperacion1=" INSERT INTO `operacion`(`id`, `id_interno`, `tipo_operacion`, `sitio_id`, `vehiculo_id`, `clientecompra_id`, `clienteventa_id`, `importe`, `fecha`, `tipostatus_id`, `usuario_id`, `corte_id`, `adicional_id`, `fecha_entrega`)
	values (NULL,'1','1','$sitioinsert',NULL,'$cliente_insert','$cliente_insert','0.00','$fecha','5022','$usuario','A1','0',NULL)";
	mysqli_query($mysqli,$sqloperacion1);

}


$validarope2 = "SELECT COUNT(id) AS id FROM operacion WHERE sitio_id IN('".$sitios_razon_social."') AND tipo_operacion = 36" ;
$validarope12 = $mysqli->query($validarope2);
$validacionpago2 = $validarope12->fetch_array(MYSQLI_ASSOC);
  
$id_validacion2 = $validacionpago2['id'];

if ($id_validacion2 != 0) {

	$ultimooperacion2 = "SELECT MAX(id_interno)+1  AS id FROM operacion  WHERE sitio_id IN('".$sitios_razon_social."')" ;
	$pagoultimooperacion2 = $mysqli->query($ultimooperacion2);
	$pago2pagoultimooperacion2 = $pagoultimooperacion2->fetch_array(MYSQLI_ASSOC);
  
	$ultimaoperacion2 = $pago2pagoultimooperacion2['id'];

	$sqloperacion2=" INSERT INTO `operacion`(`id`, `id_interno`, `tipo_operacion`, `sitio_id`, `vehiculo_id`, `clientecompra_id`, `clienteventa_id`, `importe`, `fecha`, `tipostatus_id`, `usuario_id`, `corte_id`, `adicional_id`, `fecha_entrega`)
	values (NULL,'$ultimaoperacion2','36','$sitioinsert',NULL,'$cliente_insert','$cliente_insert','0.00','$fecha','5022','$usuario','A1','0',NULL)";
	mysqli_query($mysqli,$sqloperacion2);

}else if($id_validacion2 == 0){

	$sqloperacion2=" INSERT INTO `operacion`(`id`, `id_interno`, `tipo_operacion`, `sitio_id`, `vehiculo_id`, `clientecompra_id`, `clienteventa_id`, `importe`, `fecha`, `tipostatus_id`, `usuario_id`, `corte_id`, `adicional_id`, `fecha_entrega`)
	values (NULL,'2','36','$sitioinsert',NULL,'$cliente_insert','$cliente_insert','0.00','$fecha','5022','$usuario','A1','0',NULL)";
	mysqli_query($mysqli,$sqloperacion2);

}



echo "1";


 ?>


