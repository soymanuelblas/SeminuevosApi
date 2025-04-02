<?php


session_start();
        include("conexion.php");
            $tildes = $mysqli->query("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente //$conquery("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente
if(!isset($_SESSION["nombre"])){
header('Location:login.php');
}

$id = $_POST['id'];
$sitio = $_POST['site'];
$tipo = $_POST['tipo'];
$version = $_POST['version'];
$color = $_POST['color'];
$status = $_POST['status'];
$expediente = $_POST['expediente'];
$serie = $_POST['serie'];
$motor = $_POST['motor'];
$km = str_replace(",","",$_POST['km']);
$precio = str_replace(",","",$_POST['precio']);
$fecha = $_POST['fecha'];
$duenio = $_POST['duenio'];
$garantia = $_POST['garantia'];
$Observaciones = $_POST['obs'];
$statusventa= $_POST['venta'];
$contado = str_replace(",","",$_POST['contado']);
$placa = $_POST['placa'];
$duplicado = $_POST['duplicado'];

$nuevo_annio=mysqli_query($mysqli,"SELECT noexpediente FROM vehiculo WHERE noexpediente = '$expediente' AND sitio_id = '$sitio' AND id NOT LIKE $id");

if(mysqli_num_rows($nuevo_annio)>0) {
    echo "2";
}
else{
    $sql="UPDATE vehiculo SET version_id = '$version', color_id = '$color', noexpediente = '$expediente',numeroserie = '$serie', nomotor = '$motor', observaciones = '$Observaciones',kilometraje = '$km' ,tipo_vehiculo = '$tipo', precio = '$precio',duenio = '$duenio', status_venta = '$statusventa' ,garantia = '$garantia',precio_contado = '$contado', duplicado = '$duplicado',numero_placa = '$placa' WHERE id = '$id' ";
    echo mysqli_query($mysqli,$sql);
}