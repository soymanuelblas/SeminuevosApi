<?php
session_start();
if (!isset($_SESSION["nombre"])) {
    header('Location:login.php');
}

error_reporting(0);
include("conexion.php");
$necesitas = $_POST['necesitas'];
$auxArray = [];

//print_r($_POST);

for($partida=1; $partida <= $necesitas; $partida++) {
    $var = $_POST['inhabilita'.$partida];
    array_push($auxArray,$var);
}

$contadorCheck = array_unique($auxArray);
$inhabilitaCount = count($contadorCheck);

if($inhabilitaCount != 1){
    $vehiculo = $_POST['vehiculo'];
    $site = $_POST['idsitio'];
    for($partida=1; $partida <= $necesitas; $partida++) {
        $inhabilita = $_POST['inhabilita'.$partida];

        $estado = $_POST['estado'.$partida];
        $annio = $_POST['annio'.$partida];
        $status = $_POST['status'.$partida];

        if($inhabilita == "false"){
            $img = $_POST['files'.$partida];
            if(isset($img)){
                $sql="INSERT into tenencia (id,vehiculo_id,estado_id,tipoannio_id,archivo,tipostatus_id) values (NULL,'$vehiculo','$estado','$annio','SIN IMAGEN','$status')";
                mysqli_query($mysqli,$sql);
            }
            else {
                $varfile = "files".$partida;
                $nombrefoto  = $_FILES[$varfile]["name"];

                $url_insert = dirname(__FILE__) . "/images/".$site."/".$vehiculo."/Tenencias";
                if (!file_exists($url_insert)) {
                    mkdir($url_insert, 0777, true);
                }
                $url_temp = $_FILES[$varfile]["tmp_name"]; //Ruta temporal a donde se carga el archivo
                $url_target = str_replace('\\', '/', $url_insert) . '/' .$partida. $nombrefoto; //Ruta donde se guardara el archivo, usamos str_replace para reemplazar los "\" por "/"

                if (move_uploaded_file($url_temp, $url_target)) {
                    $urlimgMB = "/images/".$site."/".$vehiculo."/Tenencias/".$partida.$nombrefoto;

                    $sql="INSERT into tenencia (id,vehiculo_id,estado_id,tipoannio_id,archivo,tipostatus_id) values (NULL,'$vehiculo','$estado','$annio','$urlimgMB','$status')";
                    mysqli_query($mysqli,$sql);
                } else {
                    echo 2;
                }
            }
        }
        else{
            $banderaMB = $_POST['inhabilitaMB'.$partida];

            if($banderaMB == "false"){
                $estado = 860;
                $annio = 38; //VALIDAR SI AGREGO TODO O SOLAMENTE LOS SELECCIONADOS MB
                $status = "5051";
                $sql="INSERT into tenencia (id,vehiculo_id,estado_id,tipoannio_id,archivo,tipostatus_id) values (NULL,'$vehiculo','$estado','$annio','SIN IMAGEN','$status')";
                //mysqli_query($mysqli,$sql);
            }
        }
    }
    echo "1";
}
else{
    if($contadorCheck[0] == true){
        echo 2;
    }
}
