<?php
include "variables.php";
date_default_timezone_set("America/Mexico_City");
$time = time();

$sitio = $_POST['sitio'];
$tipo = $_POST['tipo'];
$version = $_POST['version'];
$color = $_POST['color'];
$expediente = $_POST['expediente'];
$serie = $_POST['serie'];
$motor = $_POST['motor'];
$km = str_replace(',', '',$_POST['km']);
$venta = str_replace(',', '',$_POST['venta']);
$fecha =  date("Y-m-d", $time);
$duenio = $_POST['duenio'];
$garantia = $_POST['garantia'];
$Observaciones = $_POST['obs'];
$statusventa= 4081;
$contado = str_replace(',', '',$_POST['contado']);
$placa = $_POST['placa'];
$duplicado = $_POST['duplicado'];

$nuevo_annio=mysqli_query($mysqli,"SELECT noexpediente FROM vehiculo WHERE noexpediente = '$expediente' AND sitio_id = '$sitio' ");

if(mysqli_num_rows($nuevo_annio)>0) {
    echo "2";
}
else{
    $sql="INSERT into vehiculo (id,sitio_id,version_id,color_id,noexpediente,numeroserie,nomotor,observaciones,kilometraje,tipostatus_id,tipo_vehiculo,fecha,precio,duenio,status_venta,garantia,precio_contado,duplicado,numero_placa)
	values (NULL,'$sitio','$version','$color','$expediente','$serie','$motor','$Observaciones','$km','751','$tipo','$fecha','$venta','$duenio','$statusventa','$garantia','$contado','$duplicado','$placa')";

    $newV = mysqli_query($mysqli,$sql);

    if($newV){
        $idNewV = $mysqli->insert_id;
        $dir = "images/".$sitioSession."/".$idNewV;
        if(!is_dir($dir)){
            mkdir($dir, 0777,true);
            echo true;
        }
    }
    else{
        echo "3";
    }
}