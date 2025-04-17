<?php
session_start();
include("conexion.php");
$tildes = $mysqli->query("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente //$conquery("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente
if(!isset($_SESSION["nombre"])){
    header('Location:login.php');
}

$vehiculo = $_POST['idvehiculo'];
$tipo = $_POST['tipofac'];
$expedida = $_POST['expedida'];
$folio = $_POST['folio'];
$fecha = $_POST['fecha'];
$facturaimg = $_POST['facturaimg'];
$status = $_POST['statusfac'];
$id = $_POST['idFac'];

$nombrefoto  = $_FILES["file"]["name"];

if ($nombrefoto != '') {
    $selectMB = "SELECT vh.noexpediente as expediente, s.id as idSitio FROM vehiculo AS vh LEFT JOIN sitio AS s ON s.id = vh.sitio_id WHERE vh.id = $vehiculo";
    $selectMB = mysqli_query($mysqli,$selectMB);
    $row = $selectMB->fetch_array(MYSQLI_ASSOC);

    $site = $row['idSitio'];
    //$exp = $row['expediente'];

    $url_insert = dirname(__FILE__) . "/images/".$site."/".$vehiculo;
    if (!file_exists($url_insert)) {
        mkdir($url_insert, 0777, true);
    }

    $url_temp = $_FILES["file"]["tmp_name"]; //Ruta temporal a donde se carga el archivo
    $url_target = str_replace('\\', '/', $url_insert) . '/REM' . $nombrefoto; //Ruta donde se guardara el archivo, usamos str_replace para reemplazar los "\" por "/"

    if (move_uploaded_file($url_temp, $url_target)) {
        $urlimgMB = "/images/".$site."/".$vehiculo."/REM".$nombrefoto;

        $sql = "UPDATE factura SET vehiculo_id='$vehiculo', tipofactura_id='$tipo', expedidapor='$expedida', folio='$folio', fecha='$fecha', archivo='$urlimgMB', tipostatus_id='$status' WHERE id = '$id' " ;
        $consult = mysqli_query($mysqli,$sql);

        if($consult){
            echo 1;
        }
        else{
            echo 0;
        }
    } else {
        echo 2;
    }
}
else{
    $sql = "UPDATE factura SET vehiculo_id='$vehiculo', tipofactura_id='$tipo', expedidapor='$expedida', folio='$folio', fecha='$fecha', tipostatus_id='$status' WHERE id = '$id' " ;
    $consult = mysqli_query($mysqli,$sql);

    if($consult){
        echo 1;
    }
    else{
        echo 0;
    }
}

/*

$id = $_POST['id'];
$vehiculo =$_POST['vehiculo'];
$tipo =$_POST['tipoeditfac'];
$expedida =$_POST['expedidaeditfac'];
$folio =$_POST['folioeditfac'];
$fecha =$_POST['fechaeditfac'];
$tenenciaimg = $_FILES["facturaimg"]["tmp_name"];
$status =$_POST['statuseditfac'];

if ($tenenciaimg != "") {
	
	$image = $_FILES['facturaimg']['tmp_name'];
    $imgContenido = addslashes(file_get_contents($image));
        
	$sql = "UPDATE factura SET vehiculo_id='$vehiculo', tipofactura_id='$tipo', expedidapor='$expedida', folio='$folio', fecha='$fecha', archivo='$imgContenido', tipostatus_id='$status' WHERE id = '$id' " ;

	  mysqli_query($mysqli,$sql);
	 echo'<script type="text/javascript">
        alert("Registro Guardado con Exito!!");
        window.location.href="modificarvehiculo.php?id='.$vehiculo.' ";
        </script>';
 
}
*/