<?php

include "variables.php";
date_default_timezone_set("America/Mexico_City");
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$calle = $_POST['calle'];
$colonia = $_POST['colonia'];
$ciudad1 = $_POST['ciudad'];
$estado = $_POST['estado'];
$cp = $_POST['cp'];
$pais = $_POST['pais'];
$tel1 = $_POST['tel1'];
$tel2 = $_POST['tel2'];
$contacto = $_POST['contacto'];
$correo = $_POST['correo'];
$contra = $_POST['contra'];
$usuario = $_POST['usuario'];
$razon = $_POST['razon'];
$rfc = $_POST['rfc'];


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

$sqlestado = "SELECT * FROM `tipostatus` WHERE id = '$estado' ";
$resultado = $mysqli->query($sqlestado);
$row = $resultado->fetch_array(MYSQLI_ASSOC);
$estado = $row["id"];

$sqlciudad ="SELECT nombre FROM municipios WHERE id = '$ciudad1'";
$resultadociudad = $mysqli->query($sqlciudad);
$rowciudad = $resultadociudad->fetch_array(MYSQLI_ASSOC);

$ciudad = $rowciudad['nombre'];

$sql=" UPDATE `sitio` SET `nombre`= '$nombre', `domicilio1`= '$calle',`domicilio2`='$colonia',`ciudad`='$ciudad',`estado`='$estado',`cp`='$cp',`pais`='$pais', `telefono1`='$tel1',`telefono2`= '$tel2',`contacto`='$contacto',`correo`='$correo',`pass_correo`='$contra'  WHERE id = '$id'";

$reslut3 = mysqli_query($mysqli,$sql);

if(!$reslut3){
    echo "2";
}
else{
    echo "1";
}