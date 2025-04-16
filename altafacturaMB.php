<?php 
error_reporting(0);
include("variables.php");

$vehiculo = $_POST['idvehiculo'];
$tipo = $_POST['tipofac'];
$expedida = $_POST['expedida'];
$folio = $_POST['folio'];
$fecha = $_POST['fecha'];
$facturaimg = $_POST['facturaimg'];
$status = $_POST['statusfac'];

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
    $url_target = str_replace('\\', '/', $url_insert) . '/' . $nombrefoto; //Ruta donde se guardara el archivo, usamos str_replace para reemplazar los "\" por "/"

    if (move_uploaded_file($url_temp, $url_target)) {
        $urlimgMB = "/images/".$site."/".$vehiculo."/".$nombrefoto;

        $sql="INSERT into factura (id,vehiculo_id,tipofactura_id,expedidapor,folio,fecha,archivo,tipostatus_id)
		values (NULL,'$vehiculo','$tipo','$expedida','$folio','$fecha','$urlimgMB','$status')";

        $consult = mysqli_query($mysqli,$sql);
        //$consult = true;

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
    $sql="INSERT into factura (id,vehiculo_id,tipofactura_id,expedidapor,folio,fecha,archivo,tipostatus_id)
		values (NULL,'$vehiculo','$tipo','$expedida','$folio','$fecha','SIN ARCHIVO','$status')";
    $consult = mysqli_query($mysqli,$sql);
    //$consult = true;

    if($consult){
        echo 1;
    }
    else{
        echo 0;
    }
}
 /*

if ($nombrefoto != '') {
        $image = $_FILES['facturaimg']['tmp_name'];
        $imgContenido = addslashes(file_get_contents($image));
        
        
       $sql="INSERT into factura (id,vehiculo_id,tipofactura_id,expedidapor,folio,fecha,archivo,tipostatus_id)
		values (NULL,'$vehiculo','$tipo','$expedida','$folio','$fecha','$imgContenido','$status')";
	   mysqli_query($mysqli,$sql);
	   echo'<script type="text/javascript">
        alert("Registro Guardado con Exito!!");
        window.location.href="modificarvehiculo.php?id='.$vehiculo.' ";
        </script>';
   	
       }else{
         'hola no';
       $sql="INSERT into factura (id,vehiculo_id,tipofactura_id,expedidapor,folio,fecha,archivo,tipostatus_id)
		values (NULL,'$vehiculo','$tipo','$expedida','$folio','$fecha','','$status')";
	  mysqli_query($mysqli,$sql);
	  echo'<script type="text/javascript">
        alert("Registro Guardado con Exito!!");
        window.location.href="modificarvehiculo.php?id='.$vehiculo.' ";
        </script>';
        	
       }

*/
