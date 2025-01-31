<?php
include "conexion.php";
//include "variables.php";

session_start();
$sitioSession = $_SESSION["sitio"];
$razon_social = $_SESSION["razon_social"];
$idAccess = $_SESSION["idAcceso"];

$id = $_POST['id'];
$rfc = strtoupper($_POST['rfc']);
$rSocial = $_POST['rSocial'];
$nuevacontra = base64_encode($_POST['nuevacontra']);
$razonSocialMB = strtoupper($_POST['razonsocial']);
$representante = strtoupper($_POST['representante']);

$dir = 'Principales';
mkdir("images/".$sitioSession, 0777);
mkdir("images/".$sitioSession."/".$dir, 0777);

$sql = "SELECT * FROM `usuario` as us
         LEFT JOIN `sitio` AS sit ON us.sitio_id = sit.id
         WHERE us.id = $id ";
$resultado = $mysqli->query($sql);
$resultado = $resultado->fetch_array(MYSQLI_ASSOC);

$idRazon = $resultado['razonsocial_id'];
$updateR = "UPDATE razon_social SET `nombre` = '$razonSocialMB',`RFC` = '$rfc',`regimen_fiscal` = $rSocial,`representante_legal` = '$representante'  WHERE `razon_social`.`id` = $idRazon";
$updateR = $mysqli->query($updateR);

if($updateR){
    $updatePass = "UPDATE `usuario` SET `pwd` = '$nuevacontra',`tipostatus_id` = 851 WHERE `usuario`.`id` = $id; ";
    $updatePass = $mysqli->query($updatePass);
    if($updatePass){
        $_SESSION["estatus"] = 851;
        $_SESSION["sitiocambionombre"] = $razonSocialMB;

        $idSitio = $resultado['sitio_id'];

        $updateAccess = "UPDATE `accesos_pagosCRM` SET `estadoPago` = '13800' WHERE `accesos_pagosCRM`.`id` = $idAccess;";
        $updateAccess = $mysqli->query($updateAccess);

        $sqlCS="INSERT INTO `complemento_sitio`(`id`, `sitio_id`, `logo_url`, `email_contacto`, `vision`, `mision`, 
        `valores`, `plantilla_id`, `url_minisitio`, `ubicacion`, `url_facebook`, `url_instagram`, `url_twitter`, `url_youtube`, 
        `color_fondo`, `color_titulos`, `color_texto`, `horario_laboral`, `color_general`, `contra_contacto`) 
        VALUES (NULL, '$idSitio','x',NULL,NULL,NULL,NULL,'1','x',NULL,NULL
        ,NULL,NULL,NULL,'#ffffff','#000000','#000000',NULL,'#d1d1d1','X')";
        $sqlCS = mysqli_query($mysqli,$sqlCS);

        $razon_social = $_SESSION["razon_social"];
        $caja_tem="SELECT * FROM `ope_caja_temporal` WHERE razon_social = '$razon_social' ";
        $resultado_tem = $mysqli->query($caja_tem);
        while($row = $resultado_tem->fetch_array(MYSQLI_ASSOC)) {
            $id_ope = $row['id'];
            $tipo = $row['tipo'];
            $des = $row['descripcion'];
            $razon_social = $row['razon_social'];

            $sql="INSERT INTO `operacion_caja`(`id`, `tipo`, `descripcion`, `valor`, `razonsocial_id`) VALUES (NULL, '$tipo','$des','0','$razon_social')";
            $resultuser2 = mysqli_query($mysqli,$sql);

            if($resultuser2 == 1){
                $borrar = "DELETE FROM `ope_caja_temporal` WHERE id = '$id_ope' ";
                $resulteliminar= mysqli_query($mysqli,$borrar);
            }
        }

        $caja_cuentas="SELECT * FROM `cuenta_banco_temporal` WHERE sitio_id = '$sitioSession' ";
        $resultado_cuentas = $mysqli->query($caja_cuentas);
        while($row_cuenta = $resultado_cuentas->fetch_array(MYSQLI_ASSOC)) {
            $id_cuenta = $row_cuenta['id'];
            $nombre = $row_cuenta['nombre'];
            $sitio = $row_cuenta['sitio_id'];

            $suma1 = "SELECT MAX(id) AS id FROM `operacion_caja`";
            $resultadosuma1 = $mysqli->query($suma1);
            $rowsuma1 = $resultadosuma1->fetch_array(MYSQLI_ASSOC);
            $inicio = $rowsuma1['id']+1;

            $sql1 = "INSERT INTO operacion_caja (id,tipo, descripcion, valor, razonsocial_id) values (NULL,1,'INICIO DE CUENTA',0,'$razon_social')";
            $resultado = $mysqli->query($sql1);

            if ($resultado == 1) {
                $sql="INSERT INTO cuentas_bancarias (id,nombre,inicio_id,sitio_id) values (NULL,'$nombre','$inicio','$sitio')";
                $resultado3 = mysqli_query($mysqli,$sql);
                if($resultado3 == 1){
                    $borrar1 = "DELETE FROM `cuenta_banco_temporal` WHERE id = '$id_cuenta' ";
                    $resulteliminar1= mysqli_query($mysqli,$borrar1);
                }
            }
        }

        echo 1; //DATOS COMPLETADOS
    }
    else{
        echo 3; //NO SE PUDO ACTUALIZAR EL USUARIO
    }
}
else {
    echo 2; //NO SE PUDO ACTUALIZAR LA RAZON SOCIAL
}

//CODIGO PARA GUARDAR IMAGENES, HASTA AHORA NO SE NECESITA 29 DE JULIO 2024 MB.
/*
$sqlsitio = "SELECT * FROM `sitio` WHERE id = '$sitioSession' ";
$resultadositio = $mysqli->query($sqlsitio);
$rowsitio = $resultadositio->fetch_array(MYSQLI_ASSOC);
$name_sitio = $rowsitio["nombre"];
$id = $rowsitio["id"];



if (empty($logo)){}else{

     $sql1 = "SELECT * FROM `complemento_sitio` 
    LEFT JOIN sitio ON sitio.id = complemento_sitio.sitio_id
    WHERE complemento_sitio.sitio_id = '$sitioSession' ";
    $resultado1 = $mysqli->query($sql1);
    $row1 = $resultado1->fetch_array(MYSQLI_ASSOC);

    $rutacerti = $row1["logo_url"];
    $name = $row1["nombre"];

    unlink($rutacerti);
    move_uploaded_file($_FILES['file']['tmp_name'], 'images/'.$id.'/'. $_FILES['file']['name']);

    $nuevarutacerti = "images/".$id.'/'.$_FILES['file']['name'];
    $sqlupdatecerti="UPDATE complemento_sitio SET logo_url = '$nuevarutacerti' WHERE sitio_id = '$sitioSession' ";
    $reslut4 = mysqli_query($mysqli,$sqlupdatecerti);
}

if (empty($img1)){}else{

    $sql1 = "SELECT * FROM `img_dinamicas` 
    LEFT JOIN sitio ON sitio.id = img_dinamicas.sitio_id 
    WHERE img_dinamicas.sitio_id = '$sitioSession' AND img_dinamicas.tipo = 'SLIDER 1'";
    $resultado1 = $mysqli->query($sql1);
    $row1 = $resultado1->fetch_array(MYSQLI_ASSOC);

    $rutacerti = $row1["ruta"];
    $name = $row1["nombre"];

    if ($name == '') {
        move_uploaded_file($_FILES['file1']['tmp_name'], 'images/'.$id.'/'. $_FILES['file1']['name']);
        $nuevarutacerti = "images/".$id.'/'.$_FILES['file1']['name'];
        $sqlupdatecerti="INSERT INTO `img_dinamicas`(`id`, `tipo`, `ruta`, `sitio_id`) VALUES (NULL, 'SLIDER 1', '$nuevarutacerti','$sitioSession' )";
        $reslut4 = mysqli_query($mysqli,$sqlupdatecerti);
    }else{

    unlink($rutacerti);
    move_uploaded_file($_FILES['file1']['tmp_name'], 'images/'.$id.'/'. $_FILES['file1']['name']);

    $nuevarutacerti = "images/".$id.'/'.$_FILES['file1']['name'];
    $sqlupdatecerti="UPDATE img_dinamicas SET ruta = '$nuevarutacerti' WHERE img_dinamicas.sitio_id = '$sitioSession' AND img_dinamicas.tipo = 'SLIDER 1' ";
    $reslut4 = mysqli_query($mysqli,$sqlupdatecerti);
    }
}

if (empty($img2)){}else{

    $sql1 = "SELECT * FROM `img_dinamicas` 
    LEFT JOIN sitio ON sitio.id = img_dinamicas.sitio_id 
    WHERE img_dinamicas.sitio_id = '$sitioSession' AND img_dinamicas.tipo = 'SLIDER 2'";
    $resultado1 = $mysqli->query($sql1);
    $row1 = $resultado1->fetch_array(MYSQLI_ASSOC);

    $rutacerti = $row1["ruta"];
    $name = $row1["nombre"];

    if ($name == '') {
        move_uploaded_file($_FILES['file2']['tmp_name'], 'images/'.$id.'/'. $_FILES['file2']['name']);
        
        $nuevarutacerti = "images/".$id.'/'.$_FILES['file2']['name'];
        $sqlupdatecerti="INSERT INTO `img_dinamicas`(`id`, `tipo`, `ruta`, `sitio_id`) VALUES (NULL, 'SLIDER 2', '$nuevarutacerti','$sitioSession' )";
        $reslut4 = mysqli_query($mysqli,$sqlupdatecerti);
    }else{

    unlink($rutacerti);
    move_uploaded_file($_FILES['file2']['tmp_name'], 'images/'.$id.'/'. $_FILES['file2']['name']);

    $nuevarutacerti = "images/".$id.'/'.$_FILES['file2']['name'];
    $sqlupdatecerti="UPDATE img_dinamicas SET ruta = '$nuevarutacerti' WHERE img_dinamicas.sitio_id = '$sitioSession' AND img_dinamicas.tipo = 'SLIDER 2' ";
    $reslut4 = mysqli_query($mysqli,$sqlupdatecerti);
    }
}

if (empty($img3)){}else{

    $sql1 = "SELECT * FROM `img_dinamicas` 
    LEFT JOIN sitio ON sitio.id = img_dinamicas.sitio_id 
    WHERE img_dinamicas.sitio_id = '$sitioSession' AND img_dinamicas.tipo = 'SLIDER 3'";
    $resultado1 = $mysqli->query($sql1);
    $row1 = $resultado1->fetch_array(MYSQLI_ASSOC);

    $rutacerti = $row1["ruta"];
    $name = $row1["nombre"];

    if ($name == '') {
        move_uploaded_file($_FILES['file3']['tmp_name'], 'images/'.$id.'/'. $_FILES['file3']['name']);
        
        $nuevarutacerti = "images/".$id.'/'.$_FILES['file3']['name'];
        $sqlupdatecerti="INSERT INTO `img_dinamicas`(`id`, `tipo`, `ruta`, `sitio_id`) VALUES (NULL, 'SLIDER 3', '$nuevarutacerti','$sitioSession' )";
        $reslut4 = mysqli_query($mysqli,$sqlupdatecerti);
    }else{

    unlink($rutacerti);
    move_uploaded_file($_FILES['file3']['tmp_name'], 'images/'.$id.'/'. $_FILES['file3']['name']);

    $nuevarutacerti = "images/".$id.'/'.$_FILES['file3']['name'];
    $sqlupdatecerti="UPDATE img_dinamicas SET ruta = '$nuevarutacerti' WHERE img_dinamicas.sitio_id = '$sitioSession' AND img_dinamicas.tipo = 'SLIDER 3' ";
    $reslut4 = mysqli_query($mysqli,$sqlupdatecerti);
    }
}


if (empty($img4)){}else{

    $sql1 = "SELECT * FROM `img_dinamicas` 
    LEFT JOIN sitio ON sitio.id = img_dinamicas.sitio_id 
    WHERE img_dinamicas.sitio_id = '$sitioSession' AND img_dinamicas.tipo = 'VISION/MISION'";
    $resultado1 = $mysqli->query($sql1);
    $row1 = $resultado1->fetch_array(MYSQLI_ASSOC);

    $rutacerti = $row1["ruta"];
    $name = $row1["nombre"];

    if ($name == '') {
        move_uploaded_file($_FILES['file4']['tmp_name'], 'images/'.$id.'/'. $_FILES['file4']['name']);
        
        $nuevarutacerti = "images/".$id.'/'.$_FILES['file4']['name'];
        $sqlupdatecerti="INSERT INTO `img_dinamicas`(`id`, `tipo`, `ruta`, `sitio_id`) VALUES (NULL, 'VISION/MISION', '$nuevarutacerti','$sitioSession' )";
        $reslut4 = mysqli_query($mysqli,$sqlupdatecerti);
    }else{

    unlink($rutacerti);
    move_uploaded_file($_FILES['file4']['tmp_name'], 'images/'.$id.'/'. $_FILES['file4']['name']);

    $nuevarutacerti = "images/".$id.'/'.$_FILES['file4']['name'];
    $sqlupdatecerti="UPDATE img_dinamicas SET ruta = '$nuevarutacerti' WHERE img_dinamicas.sitio_id = '$sitioSession' AND img_dinamicas.tipo = 'VISION/MISION' ";
    $reslut4 = mysqli_query($mysqli,$sqlupdatecerti);
    }
}

$sqluser1="DELETE FROM `recuperar_contra` WHERE usuario_id = '$idusuario'";
echo $resultuser1 = mysqli_query($mysqli,$sqluser1);


mysqli_close($mysqli);

*/