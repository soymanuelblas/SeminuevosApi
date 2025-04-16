<?php
include "variables.php";
echo $usuario = $_SESSION["id"];
echo "<br>";
echo $id = $_GET['id'];

$sitio_nombre = $_SESSION["nombre"];
//$tipo1 = $_POST['tipo'];
echo $_FILES["file"]["type"];
if (($_FILES["file"]["type"] == "image/pjpeg")
    || ($_FILES["file"]["type"] == "image/jpeg")
    || ($_FILES["file"]["type"] == "image/png")
    || ($_FILES["file"]["type"] == "image/gif")
    || ($_FILES["file"]["type"] == "image/jpg")
    || ($_FILES["file"]["type"] == "image/JPG")) {
                    echo $temporal = $_FILES["file"]['tmp_name'];
                    $nombre = $_FILES["file"]['name'];
                    $estampa = imagecreatefrompng('images/nada.png');
                    $dir1 = $id;
                    $dir = $sitio_nombre."/".$expediente;
                    if(!is_dir("images/".$dir)){
                    mkdir("images/".$dir, 0777);
                }
                if (file_exists("images/".$dir."/".$_FILES["file"]["name"])) {     
                    echo '<div class="error">Ya hay un archivo con nombre '.$_FILES["file"]["name"].'. Renombralo y vuelve a subirlo.</div>';  
                    }else{
                    // Agregamos la imagen a la base de datos.
                    $consulta = "INSERT INTO imagenes_taller (id,vehiculotaller_id,tipo,ruta,usuario_id) VALUES (NULL,'$id','764','$dir/$nombre','$usuario')";
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
                    $margen_dcho = 530;
                    $margen_inf = -60;
                    $sx = imagesx($estampa);
                    $sy = imagesy($estampa);
                    $ancho_original = imagesx($original);
                    $alto_original = imagesy($original);
                    $ancho_nuevo = 800;
                    $alto_nuevo = 600; 
                    //crear un lienzo vacio (foto destino )
                    $copia = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);
                    //copiar original -> copia 
                    imagecopyresampled($copia, $original, 0, 0, 0, 0, $ancho_nuevo, $alto_nuevo, $ancho_original, $alto_original);
                    // Copiar la imagen de la estampa sobre nuestra foto usando los índices de márgen y el
                    // ancho de la foto para calcular la posición de la estampa. 
                    imagecopy($copia, $estampa, imagesx($copia) - $sx - $margen_dcho, imagesy($copia) - $sy - $margen_inf, 0, 0, imagesx($estampa), imagesy($estampa));
                    //exportar/guardar imagen 
                    imagejpeg($copia, "images/".$dir."/".$_FILES["file"]["name"],100);
                     } 
                }
 ?>
