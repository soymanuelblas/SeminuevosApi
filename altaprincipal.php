<?php
include "variables.php";
$id = $_GET['id'];
$tipo1 = '6400';
$sitio_nombre = $_SESSION["nombre"];

$_FILES["file"]["type"];

if (($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/gif")) {
    $temporal = $_FILES["file"]['tmp_name'];
    $nombre = $_FILES["file"]['name'];
    $tipo = $_FILES["file"]["type"];
    $estampa = imagecreatefrompng('images/nada.png');

    $idVehiculo = $_POST['idvehiculo'];

    $dir = $sitioSession."/".$idVehiculo."/Principales";

    if (file_exists("images/".$dir."/".$_FILES["file"]["name"])) {
        echo '0';
    }
    else{
        $url_insert = dirname(__FILE__) . "/images/".$sitioSession."/".$idVehiculo."/Principales";
        if (!file_exists($url_insert)) {
            mkdir($url_insert, 0777, true);
        }

        // Agregamos la imagen a la base de datos.
        $consulta = "INSERT INTO imagen (vehiculo_id,tipo,titulo,archivo) values ('$id','$tipo1','principal','$dir/$nombre')";
        // Se ejecuta la consulta.
        $mysqli->query($consulta);
        //abrir la foto original
        if ($_FILES["file"]["type"] == "image/jpeg") {
            $original = imagecreatefromjpeg($temporal);
        }
        else if ($_FILES["file"]["type"] == "image/png") {
            $original = imagecreatefrompng($temporal);
        }
        else if ($_FILES["file"]["type"] == "image/jpg") {
            $original = imagecreatefromjpg($temporal);
        }
        //marca agua
        $margen_dcho = 470;
        $margen_inf = 10;
        $sx = imagesx($estampa);
        $sy = imagesy($estampa);
        $ancho_original = imagesx($original);//4032
        $alto_original = imagesy($original);//3024
        $ancho_nuevo = 700;
        $alto_nuevo = round($ancho_nuevo * 3024 / 4032);
        //crear un lienzo vacio (foto destino)
        $copia = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);
        //copiar original -> copia
        imagecopyresampled($copia, $original, 0, 0, 0, 0, $ancho_nuevo, $alto_nuevo, $ancho_original, $alto_original);
        // Copiar la imagen de la estampa sobre nuestra foto usando los índices de márgen y el
        // ancho de la foto para calcular la posición de la estampa.
        imagecopy($copia, $estampa, imagesx($copia) - $sx - $margen_dcho, imagesy($copia) - $sy - $margen_inf, 0, 0, imagesx($estampa), imagesy($estampa));
        //exportar/guardar imagen
       imagejpeg($copia, "images/".$dir."/".$_FILES["file"]["name"],100);
        //insertamos el nuevo vehiculo
        echo "images/".$dir."/".$nombre;
    }
}
else{
    echo '2';
}
mysqli_close($mysqli);
