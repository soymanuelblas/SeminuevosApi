<?php
ob_start();
include "variables.php";
date_default_timezone_set("America/Mexico_City");

$sitio = $_SESSION['sitiocambio'];
$tel1 = $_POST['tele1C'];

function eliminar_acentos($cadena){
    //Reemplazamos la A y a
    $cadena = str_replace(
        array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $cadena);

    //Reemplazamos la E y e
    $cadena = str_replace(
        array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $cadena );

    //Reemplazamos la I y i
    $cadena = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena );

    //Reemplazamos la O y o
    $cadena = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena );

    //Reemplazamos la U y u
    $cadena = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena );
    return $cadena;
}

if($sitio == "1"){
    $consu1 = "SELECT * FROM prospecto 
    WHERE prospecto.telefono1 = '$tel1'";
}
else{
    $consu1 = "SELECT * FROM prospecto 
    WHERE prospecto.sitio_id = '$sitio' AND prospecto.telefono1 = '$tel1'";
    /*
    $consu1 = "SELECT usuario_id, usuario.nombre 
    FROM prospecto 
	LEFT JOIN usuario ON usuario.id = prospecto.usuario_id 
	LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id
	WHERE prospecto.sitio_id = '$sitio' AND prospecto.telefono1 = '$tel1' AND oportunidad.tipostatus_id IN('5163','5166') AND oportunidad.tipooperacion_id IN('5061') ";
    */
}

$resultado = $mysqli->query($consu1);
$row = $resultado->fetch_array(MYSQLI_ASSOC);

if(empty($row['id'])){
    //echo "entra";
    $name = $_POST['nombreAux'];
    $tel2 = $_POST['tele2C'];
    $correo = $_POST['emaylC'];
    $medio = $_POST['medC'];
    $canal = $_POST['canC'];
    $estatus = $_POST['stat'];
    $usuario = $_POST['asesorC'];

    $consulta = "INSERT INTO prospecto (id, nombre, telefono1, telefono2, email,password, medio_id, canal_id, tipostatus_id, sitio_id, usuario_id,fecha_nacimiento,estado_civil,domicilio,ciudad,fecha_viviendo) 
		    VALUES (NULL, '$name', '$tel1', '$tel2', '$correo',NULL, '$medio', '$canal', '$estatus', '$sitio', '$usuario',NULL,NULL,NULL,NULL,NULL)";
    $result = mysqli_query($mysqli, $consulta);

    if($sitio == "1"){
        $consu1 = "SELECT * FROM prospecto 
    WHERE prospecto.telefono1 = '$tel1'";
    }
    else{
        $consu1 = "SELECT * FROM prospecto 
    WHERE prospecto.sitio_id = '$sitio' AND prospecto.telefono1 = '$tel1'";
    }

    $resultado = $mysqli->query($consu1);
    $row = $resultado->fetch_array(MYSQLI_ASSOC);
}

$name = eliminar_acentos($row["nombre"]);

$tel2 = $row["telefono2"];
$correo = $row["email"];
$medio = $_POST["medC"];
$canal = $_POST["canC"];
$estatus = $_POST["stat"];
$usuario = $_POST["asesorC"];


/*
echo $name;
echo "<br><br>";
print_r($consu1);
echo "<br><br>";
print_r($_SESSION);
echo "<br><br>";
print_r($_POST);
echo "<br><br>";
var_dump($row);
echo "<br><br>";
*/

/*
$consulta = "INSERT INTO prospecto (id, nombre, telefono1, telefono2, email,password, medio_id, canal_id, tipostatus_id, sitio_id, usuario_id,fecha_nacimiento,estado_civil,domicilio,ciudad,fecha_viviendo)
		    VALUES (NULL, '$name', '$tel1', '$tel2', '$correo',NULL, '$medio', '$canal', '$estatus', '$sitio', '$usuario',NULL,NULL,NULL,NULL,NULL)";
$result = mysqli_query($mysqli, $consulta);
*/

$result = true;
if($result){
    $ultIngreso = "SELECT MAX(id) AS id FROM prospecto";
    $rs = mysqli_query($mysqli, $ultIngreso);
    while ($ver = mysqli_fetch_array($rs)){
        $prospecto = $ver['id'];
    }
    $fechaactual = date('Y-m-d H:i:s');
    $modelo = $_POST["modelo"];
    $consultaCoche = "SELECT * FROM `tipomodelo` WHERE `id` = $modelo";
    $resultadoAuto = $mysqli->query($consultaCoche);
    $rowA = $resultadoAuto->fetch_array(MYSQLI_ASSOC);

    $version = $_POST["version"];
    $consultaCoche2 = "SELECT * FROM `version` WHERE `id` = $version";
    $resultadoAuto2 = $mysqli->query($consultaCoche2);
    $rowB = $resultadoAuto2->fetch_array(MYSQLI_ASSOC);

    //print_r($rowA);
    //echo "<br><br>";
    //print_r($rowB);
    //echo "<br><br>";

    //

    $f = $_POST['fechaC'];
    list($anio,$mes,$dia_1) = explode("-",$f);
    $dia_1;
    $mes;
    $anio;


    list($dia,$hora) = explode("T",$dia_1);
    $dia;
    $hora;

    $fecha_actual = $anio.'-'.$mes.'-'.$dia.' '.$hora.':00';

    $operacion = '5061';
    $status = '5163';
    $num7 = 'tipov';
    $num6 = 'marca';
    $num5 = 'annio';
    $num4 = 'trans';
    $num3 = 'preci';
    $num2 = 'color';
    $num1 = 'cadpa';
    $marca = $_POST["marca"];
    $mimodelo = $rowA['id'];


    $transmision = $_POST["transvehiculo"];
    $observacion = $_POST["observacionC"];
    $color = $_POST["col"];
    $f = $_POST['fechaC'];
    $vehiculo = $rowA['tipovehiculo_id'];
    $idversion = $_POST["version"];

    $premin = str_replace(",","",($_POST['precioVenta']));
    $premax = $premin;
    $pasaje = $rowB['pasajeros'];
    $inianio = $rowB['tipoannio_id'];
    $finanio = $inianio;

    //

    $consulta = "INSERT INTO oportunidad (id, prospecto_id, tipooperacion_id, tipovehiculo_id, tipomarca_id, tipomodelo_id, anodesde, anohasta, transmision_id,kilometraje, observacion,idFecha,idHora, proximocontacto, tipostatus_id, color_id, preciomin, preciomax, pasajeros_id, factor1, factor2, factor3, factor4, factor5, factor6, factor7,enganche,vehiculo_id,uso_id,financiamiento_id,meses,historial_id,fecha_llegada,version_id) 
	VALUES (NULL, '$prospecto', '$operacion', '$vehiculo', '$marca', '$mimodelo', '$inianio', '$finanio', '$transmision','0', '$observacion',NULL,NULL,'$fecha_actual', '$status', '$color', '$premin', '$premax', '$pasaje', '$num7', '$num6', '$num5', '$num4', '$num3', '$num2', '$num1',NULL,NULL,NULL,NULL,NULL,NULL,'$fechaactual','$idversion')";

    $result2 = mysqli_query($mysqli, $consulta);

    //$result2 = true;

    //print_r($inianio);

    //var_dump($consulta);

    if($result2){
        $sql3="INSERT INTO seguimiento (oportunidad_id, canal_id, tipostatus_id,usuario_id,fechacontacto,fechacontactoesperado,observacion) 
           SELECT MAX(id), '5124','5184','$usuario','$fechaactual','$fechaactual','Se registro correctamente el prospecto' FROM oportunidad";
        $result1 = mysqli_query($mysqli,$sql3);

        //$result1 = true;

        echo $result1;
    }
}
else{
    echo 0;
}