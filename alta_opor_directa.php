<?php
ob_start();
    include "variables.php";

    date_default_timezone_set("America/Mexico_City");
    
    
    $idname= $_POST['idname'];
    $f = $_POST['fecha'];
    $observacion = utf8_decode($_POST['observacion']);
    $vehiculoselect = $_POST['vehiculonuevo'];
    
    $sql_vehiculo = "SELECT * FROM vehiculo WHERE id = '$vehiculoselect' ";
    $resultado_vehiculo = $mysqli->query($sql_vehiculo);
    $row_vehiculo = $resultado_vehiculo->fetch_array(MYSQLI_ASSOC);
    
    $idversion = $row_vehiculo['version_id'];
    $color = $row_vehiculo['color_id'];
    $premin = str_replace(",","",($row_vehiculo['precio']));
	$premax = str_replace(",","",($row_vehiculo['precio'] + 30000));
	

	
	$operacion = '5060';
	$status = '5163';
	
	$usuario = $_SESSION['id'];
	
    
    $sql = "SELECT version.tipoannio_id AS annioid,tipoannio.descripcion AS annio, version.transmision AS transid, version.pasajeros AS pasajerosid, tipomodelo.tipomarca_id AS marcaid, tipomodelo.tipovehiculo_id AS tipovehi
    FROM version 
    LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id 
    LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id
    WHERE version.id = '$idversion' ";
    $resultado = $mysqli->query($sql);
    $row = $resultado->fetch_array(MYSQLI_ASSOC);
    
    $marca = $row['marcaid'];
    $anniodesde = $row['annio'];
    $anniohasta = $row['annio']+2;
	$transmicion = $row['transid'];
	$pasaje = $row['pasajerosid'];
	$vehiculo = $row['tipovehi'];
	
	$sqldesde = "SELECT * FROM tipoannio WHERE descripcion = '$anniodesde' ";
    $resultadodesde = $mysqli->query($sqldesde);
    $rowdesde = $resultadodesde->fetch_array(MYSQLI_ASSOC);
    
    $inianio = $rowdesde['id'];
    
    $sqlhasta = "SELECT * FROM tipoannio WHERE descripcion = '$anniohasta' ";
    $resultadohasta = $mysqli->query($sqlhasta);
    $rowhasta = $resultadohasta->fetch_array(MYSQLI_ASSOC);
    
    $finanio = $rowhasta['id'];
    
    if($finanio != ''){}else{
        $finanio = $inianio;
    }
    
	$num7 = 'tipov';
	$num6 = 'marca';
	$num5 = 'annio';
	$num4 = 'trans';
	$num3 = 'preci';
	$num2 = 'color';
	$num1 = 'cadpa';
	
	//$pieces = explode("-", $f);
	//list($dia, $numdia, $mes, $anio, $gion, $hora) = explode(" ",$f);
	list($anio,$mes,$dia_1) = explode("-",$f);
	$dia_1;
	$mes;
	$anio;
	
	
	list($dia,$hora) = explode("T",$dia_1);
	$dia;
	$hora;
	
	$fecha_actual = $anio.'-'.$mes.'-'.$dia.' '.$hora.':00';

    
    
	$p = $_POST['idnom'];
	$t = $_POST['tele1'];
	$con = "SELECT id FROM prospecto WHERE id = '$idname'";
	$res = mysqli_query($mysqli, $con);
	while ($ver = mysqli_fetch_array($res)){
		$prospecto = $ver['id'];
	}
     $fechaactual = date('Y-m-d H:i:s');
     if($vehiculoselect == 0){
        $consulta = "INSERT INTO oportunidad (id, prospecto_id, tipooperacion_id, tipovehiculo_id, tipomarca_id, tipomodelo_id, anodesde, anohasta, transmision_id,kilometraje, observacion,idFecha,idHora, proximocontacto, tipostatus_id, color_id, preciomin, preciomax, pasajeros_id, factor1, factor2, factor3, factor4, factor5, factor6, factor7,enganche,vehiculo_id,uso_id,financiamiento_id,meses,historial_id,fecha_llegada,version_id) 
	VALUES (NULL, '$prospecto', '$operacion', '$vehiculo', '$marca', '0', '$inianio', '$finanio', '$transmicion','0', '$observacion',NULL,NULL,'$fecha_actual', '$status', '$color', '$premin', '$premax', '$pasaje', '$num7', '$num6', '$num5', '$num4', '$num3', '$num2', '$num1',NULL,NULL,NULL,NULL,NULL,NULL,'$fechaactual','$idversion')";
     }else{
	    $consulta = "INSERT INTO oportunidad (id, prospecto_id, tipooperacion_id, tipovehiculo_id, tipomarca_id, tipomodelo_id, anodesde, anohasta, transmision_id,kilometraje, observacion,idFecha,idHora, proximocontacto, tipostatus_id, color_id, preciomin, preciomax, pasajeros_id, factor1, factor2, factor3, factor4, factor5, factor6, factor7,enganche,vehiculo_id,uso_id,financiamiento_id,meses,historial_id,fecha_llegada,version_id) 
    	VALUES (NULL, '$prospecto', '$operacion', '$vehiculo', '$marca', '0', '$inianio', '$finanio', '$transmicion','0', '$observacion',NULL,NULL,'$fecha_actual', '$status', '$color', '$premin', '$premax', '$pasaje', '$num7', '$num6', '$num5', '$num4', '$num3', '$num2', '$num1',NULL,'$vehiculoselect',NULL,NULL,NULL,NULL,'$fechaactual','$idversion')";
     }
	 "$p <br>";
	 "$t <br>";
	 "id: $prospecto<br>";
	 "$operacion<br>";
	 "$vehiculo<br>";
	 "$marca<br>";
	 "$inianio <br>";
	 "$finanio <br>";
	 "$transmicion <br>";
	 "$observacion <br>";
	 "$fecha_actual <br>";
	 "$f <br>";
	 "$status <br>";
	 "$color <br>";
	 "$premin <br>";
	 "$premax <br>";
	 "$pasaje <br>";
	 "$d <br><br>";
	 "$do <br><br>";
	 "$don <br><br>";
	 "$dona <br><br>";
	 "$donnas <br><br>";
	 "$donas <br><br>";
	 "$num7 <br>$num6 <br>$num5 <br>$num4 <br>$num3 <br>$num2 <br>$num1 <br>";
	$result = mysqli_query($mysqli, $consulta);
	
	$sql3="INSERT INTO seguimiento (oportunidad_id, canal_id, tipostatus_id,usuario_id,fechacontacto,fechacontactoesperado,observacion) 
           SELECT MAX(id), '5124','5184','$usuario','$fechaactual','$fechaactual','Se registro correctamente el prospecto' FROM oportunidad";
    $result1 = mysqli_query($mysqli,$sql3);

	//verificamos la ejecuion
	if(!$result && !$result1){ 
	 "<br>ocurrio un error";
	}else{
		mysqli_close($mysqli);
		if($transmicion == 5060){
		// header("location:guardayenvia.php?nombre=$name&prospecto=$tel1&vehiculo=$tel2&marca=$correo&modelo=$medio&ianio//=$canal&fanio=$estatus&transmicion=$usuario&observacion=$algo&contacto=$algo2&status=$algo3");
		}else{
		header('location:tabaloportunidad.php');
		}
	}
ob_end_flush();
?>