<?php
include "variables.php";


$sql1 ="SELECT MAX(id_interno) AS sitio FROM clientes WHERE razonsocial_id = '$razon_social' ";
$resultado = $mysqli->query($sql1);
$row = $resultado->fetch_array(MYSQLI_ASSOC);

$nombre =($_POST['nombre']);
$rfc =$_POST['rfc'];
$calle =($_POST['calle']);
$colonia =($_POST['colonia']);
$cp =$_POST['cp'];
$ciudad =$_POST['ciudad'];
$estado =$_POST['estado'];
$tel1 =$_POST['tel1'];
$tel2 =$_POST['tel2'];
$email =$_POST['email'];
$usuario = $_POST['usuario'];
$cliente =$_POST['clientep'];
$sitio =$_POST['sitio'];
$a=$row['sitio'] + 1;
$tipo =$_POST['tipo'];
$password = X;
$razon = $_POST['razon'];


	echo $sql="INSERT into clientes (id,id_interno,rfc,nombre,domicilio,colonia,telefono1,telefono2,email,ciudad,estado,cp,usuario_id,tipostatus_id,razonsocial_id, tipocliente_id,password)
	values (NULL,'$a','$rfc','$nombre','$calle','$colonia','$tel1','$tel2','$email','$ciudad','$estado','$cp','$usuario','$cliente','$razon','$tipo','$password')";
	 echo mysqli_query($mysqli,$sql);
 ?>