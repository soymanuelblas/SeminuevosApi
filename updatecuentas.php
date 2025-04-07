<?php
session_start();
        include("conexion.php");
            $tildes = $mysqli->query("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente //$conquery("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente
if(!isset($_SESSION["nombre"])){
header('Location:login.php');
}

//$codigo = $_POST['duplicado'];
$descripcion = ($_POST['descripcion']);
$id = $_POST['id'];

$sql="UPDATE cuentas_bancarias SET nombre = '$descripcion' WHERE id = '$id' ";
echo mysqli_query($mysqli,$sql);


mysqli_close($mysqli);

?>