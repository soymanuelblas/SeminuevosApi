<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include("conexion.php");

date_default_timezone_set("America/Mexico_City");

$name = $_GET['name'];                  //NOMBRE DE QUIEN REALIZA EL PAGO
$name = strtoupper($name);
$email = $_GET['email'];                //EMAIL DE QUIEN REALIZA EL PAGO
$payment = $_GET['payment_id'];         //ID DE PAGO
$status = $_GET['status'];              //ESTADO DEL PAGO
$payment_type = $_GET['payment_type'];  //TIPO DE PAGO (TDC, TDD, ETC.)
$order_id = $_GET['merchant_order_id']; //ID DE LA ORDEN
$idItemMB = $_GET['item'];              //ID DEL PAQUETE QUE REALIZO EL PAGO


$query = $mysqli -> query ("SELECT * FROM `itemMercaLib` WHERE `id` = $idItemMB AND `estado` = 1;");
$row = $query->fetch_array(MYSQLI_ASSOC);

$select = $mysqli -> query ("SELECT * FROM pago_crm WHERE payment_id = '$payment'");
$rowPago = $select->fetch_array(MYSQLI_ASSOC);


if(empty($rowPago)){
    $precio = $row['unit_priceItem'];
    $time = time();
    $fecha = date("Y-m-d H:i:s", $time);
    $mesesServicio = $row['meses_servicio'];

    $sumaMesvencimiento = "+".$mesesServicio. " month"; //MESES A SUMAR PARA VER EL VENCIMIENTO
    $actual = strtotime($fecha);

    $mesmas = date("Y-m-d H:i:s", strtotime($sumaMesvencimiento, $actual)); //SUMAR LOS MESES DE VENCIMIENTO CON LA INFO DE LA DB

    $insertNewPago = "INSERT INTO `pago_crm` (`id`, `payment_id`, `typepago`, `numOrder`, `precio`, `fecha`, `idstatus`) VALUES (NULL, '$payment', '$payment_type', '$order_id', $precio, '$fecha', '13801');";
    $insertNewPago = $mysqli -> query ($insertNewPago);

    if($insertNewPago){
        $rs = $mysqli -> query ("SELECT MAX(id) AS id FROM pago_crm");
        $rowPCRM = $rs->fetch_array(MYSQLI_ASSOC);
        $idPCRM = $rowPCRM['id'];

        $passTemp = generatePassword(6);
        $passTempEN = base64_encode($passTemp);
        $insertRazonSocial = "INSERT INTO `razon_social` (`id`, `nombre`, `RFC`, `ruta_certificado`, `ruta_llave`, `no_certificado`, `password`, `regimen_fiscal`, `representante_legal`, `plant_factura`) 
                                                VALUES (NULL, '$name', NULL, NULL, NULL, NULL, '$passTempEN', NULL, '$name', 1);";
        $insertRazonSocial = $mysqli -> query ($insertRazonSocial);

        if($insertRazonSocial){
            $rs = $mysqli -> query ("SELECT MAX(id) AS id FROM razon_social");
            $rowRS = $rs->fetch_array(MYSQLI_ASSOC);
            $idRS = $rowRS['id'];
            $idITEM = $row['id'];

            $insertAccesoPagos = "INSERT INTO `accesos_pagosCRM` (`id`, `crm_id`, `razon_id`, `item_id`, `estadoPago`,`fechaterminoContrato`) VALUES (NULL, $idPCRM, $idRS, $idITEM, '13804','$mesmas');";
            $insertAccesoPagos = $mysqli -> query ($insertAccesoPagos);

            if($insertAccesoPagos){
                $insertSitio = "INSERT INTO `sitio` (`id`, `nombre`, `domicilio1`, `domicilio2`, `ciudad`, `estado`, `cp`, `pais`, `telefono1`, `telefono2`, `contacto`, `razonsocial_id`, `correo`, `pass_correo`) 
                                                    VALUES (NULL, '$name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$name', '$idRS', '$email', '$passTemp');";
                $insertSitio = $mysqli -> query ($insertSitio);

                if($insertSitio){
                    $rs2 = $mysqli -> query ("SELECT MAX(id) AS id FROM sitio");
                    $rowS = $rs2->fetch_array(MYSQLI_ASSOC);
                    $idS = $rowS['id'];

                    $insertUser = "INSERT INTO `usuario` (`id`, `nombre`, `usr`, `pwd`, `permisos`, `rol_id`, `tipostatus_id`, `sitio_id`, `whatsapp`) 
                                                    VALUES (NULL, '$name', '$email', '$passTempEN', '1111111111111111111111111111111000000000000111111000000000000111111111000111111111100001111111001111111111111110000000000000000100000000000000000000000001111111111110000', '5800', '853', '$idS', NULL);";
                    $insertUser = $mysqli -> query ($insertUser);
                    if($insertUser){
                        //EMPEZAR A INSERTAR PARA LLEGAR A LA PÁGINA DE INICIO
                        $sqluser ="SELECT MAX(id) AS id FROM usuario WHERE sitio_id = '$idS'";
                        $resultadouser = $mysqli->query($sqluser);
                        $rowuser = $resultadouser->fetch_array(MYSQLI_ASSOC);
                        $usuario = $rowuser['id'];


                        $sql_cliente=" INSERT INTO `clientes` (`id`, `id_interno`, `rfc`, `nombre`, `domicilio`, `colonia`, `telefono1`, `telefono2`, `email`, `ciudad`, `estado`, `cp`, `usuario_id`, `tipostatus_id`, `razonsocial_id`, `tipocliente_id`, `password`) values (NULL,'1','NULL','$name','X','X','01234567891','01234567891','x@gmail.com','X','X','X','$usuario','851','$idRS','5300','X')";
                        $insertCliente = mysqli_query($mysqli,$sql_cliente);

                        if($insertCliente){
                            $sqlcliente ="SELECT MAX(id) AS id FROM clientes WHERE razonsocial_id = '$idRS' AND id_interno = 1";
                            $resultadocliente = $mysqli->query($sqlcliente);
                            $rowcliente = $resultadocliente->fetch_array(MYSQLI_ASSOC);

                            $cliente_insert = $rowcliente['id'];


                            $sqlcorte=" INSERT INTO `cortecaja`(`id`, `id_interno`, `serie`, `billete_1000`, `billete_500`, `billete_200`, `billete_100`, `billete_50`, `billete_20`, `modena_10`, `modena_5`, `modena_2`, `modena_1`, `moneda_50c`, `total_efectivo`, `fecha`, `usuario_id`, `sitio_id`)
                            values (NULL,'1','A','0','0','0','0','0','0','0','0','0','0','0','0',NULL,'$usuario','$idS')";
                            mysqli_query($mysqli,$sqlcorte);

                            $sqloperacion1=" INSERT INTO `operacion`(`id`, `id_interno`, `tipo_operacion`, `sitio_id`, `vehiculo_id`, `clientecompra_id`, `clienteventa_id`, `importe`, `fecha`, `tipostatus_id`, `usuario_id`, `corte_id`, `adicional_id`, `fecha_entrega`)
                            values (NULL,'1','1','$idS',NULL,'$cliente_insert','$cliente_insert','0.00','$fecha','5022','$usuario','A1','0',NULL)";
                            mysqli_query($mysqli,$sqloperacion1);

                            $sqloperacion2=" INSERT INTO `operacion`(`id`, `id_interno`, `tipo_operacion`, `sitio_id`, `vehiculo_id`, `clientecompra_id`, `clienteventa_id`, `importe`, `fecha`, `tipostatus_id`, `usuario_id`, `corte_id`, `adicional_id`, `fecha_entrega`)
                            values (NULL,'2','36','$idS',NULL,'$cliente_insert','$cliente_insert','0.00','$fecha','5022','$usuario','A1','0',NULL)";
                            mysqli_query($mysqli,$sqloperacion2);

                            include("sendEmailMB.php");
                        }
                        else{
                            echo "fallo";
                        }
                    }
                    else{
                        echo "error";
                    }
                }
                else{
                    echo "fallo";
                }
            }
            else{
                echo "NO SE AGREGO EL ACCESO AL PAGO";
            }
        }
        else{
            echo "NO SE AGREGO LA RAZON SOCIAL";
        }

    }
    else{
        echo "NO SE REGISTRO EL PAGO...";
    }
}
else{
    echo "PAGO REGISTRADO HACER UNA PAGINA QUE LA INDIQUE... MB";
}


function generatePassword($length)
{
    $key = "";
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
    $max = strlen($pattern)-1;
    for($i = 0; $i < $length; $i++){
        $key .= substr($pattern, mt_rand(0,$max), 1);
    }
    return $key;
}
