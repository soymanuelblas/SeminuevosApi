<?php
session_start();
include("conexion.php");
$tildes = $mysqli->query("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente //$conquery("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente
if(!isset($_SESSION["nombre"])){
    header('Location:login.php');
}
$annio =$_POST['annioeditenencia'];

$sqlannio = "SELECT * FROM `tipoannio` WHERE `descripcion` = '$annio' ";
$resultadoAnnio = $mysqli->query($sqlannio);
$rowAnnio = $resultadoAnnio->fetch_array(MYSQLI_ASSOC);

//print_r($_POST);

$id = $_POST['id'];
$vehiculo = $_POST['vehiculo'];
$estado = $_POST['estadoeditenencia'];
$status = $_POST['statuseditenencia'];
$idannio = $rowAnnio['id'];

$nombrefoto  = $_FILES["files"]["name"];

if ($nombrefoto != '') {
    //CON FOTO IMG
    $site = $_POST['misite'];

    $url_insert = dirname(__FILE__) . "/images/".$site."/".$vehiculo."/Tenencias";
    if (!file_exists($url_insert)) {
        mkdir($url_insert, 0777, true);
    }

    $url_temp = $_FILES["files"]["tmp_name"]; //Ruta temporal a donde se carga el archivo
    $url_target = str_replace('\\', '/', $url_insert) . '/REM'. $nombrefoto; //Ruta donde se guardara el archivo, usamos str_replace para reemplazar los "\" por "/"

    if (move_uploaded_file($url_temp, $url_target)) {
        $urlimgMB = "/images/".$site."/".$vehiculo."/Tenencias/REM".$nombrefoto;
        $sql = "UPDATE tenencia SET vehiculo_id='$vehiculo', estado_id='$estado', tipoannio_id='$idannio', archivo='$urlimgMB', tipostatus_id='$status' WHERE id = '$id' " ;
        $res = mysqli_query($mysqli,$sql);
        echo $res;
    } else {
        echo 2;
    }
}
else{
    //SIN FOTO;
    $sql = "UPDATE tenencia SET vehiculo_id='$vehiculo', estado_id='$estado', tipoannio_id='$idannio', tipostatus_id='$status' WHERE id = '$id' " ;
    $res = mysqli_query($mysqli,$sql);
    echo $res;
}

/*


 $tenenciaimg = $_FILES["tenenciaimg"]["tmp_name"];


if ($tenenciaimg != "") {
	
	$image = $_FILES['tenenciaimg']['tmp_name'];
    $imgContenido = addslashes(file_get_contents($image));
    
	$sql = "UPDATE tenencia SET vehiculo_id='$vehiculo', estado_id='$estado', tipoannio_id='$annio', archivo='$imgContenido', tipostatus_id='$status' WHERE id = '$id' " ;

	  mysqli_query($mysqli,$sql);
	 echo'<script type="text/javascript">
        alert("Registro Guardado con Exito!!");
        window.location.href="modificarvehiculo.php?id='.$vehiculo.'";
        </script>';
 
}else{
	
	$sql = "UPDATE tenencia SET vehiculo_id='$vehiculo', estado_id='$estado', tipoannio_id='$annio', tipostatus_id='$status' WHERE id = '$id' " ;

	  mysqli_query($mysqli,$sql);
	 echo'<script type="text/javascript">
        alert("Registro Guardado con Exito!!");
        window.location.href="modificarvehiculo.php?id='.$vehiculo.'";
        </script>';
 
}

*/