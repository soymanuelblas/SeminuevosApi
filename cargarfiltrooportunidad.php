<?php 
include "variables.php";
$idid =  $_SESSION["id"];
$pro = json_decode($_POST['array']);

$porciones   = explode("-",$pro[0]);
$status   = implode ("','", $pro[1]);
$search = $pro[2];

$porciones[0] = str_replace("/","-",$porciones[0]); // porción1
$porciones[1] = str_replace("/","-",$porciones[1]); // porción2
$fecha1 = explode("-",$porciones[0]);
$fecha_ini = str_replace(" ", "",$fecha1[2].'-'.$fecha1[0].'-'.$fecha1[1]);
$fecha2 = explode("-",$porciones[1]);
$fecha_fin = str_replace(" ", "",$fecha2[2].'-'.$fecha2[0].'-'.$fecha2[1]);

if ($fecha_ini != '--') {
    $fecha1 = explode("-",$porciones[0]);
    $fecha_ini = str_replace(" ", "",$fecha1[2].'-'.$fecha1[0].'-'.$fecha1[1]);
}else{
    $fecha_ini = '';
}

if ($fecha_fin != '--') {
    $fecha2 = explode("-",$porciones[1]);
    $fecha_fin = str_replace(" ", "",$fecha2[2].'-'.$fecha2[0].'-'.$fecha2[1]);
}else{
    $fecha_fin = '';
}

$fecha_ini;
$fecha_fin;
?>

<div class="body">
    <div class="table-responsive">
        <?php
        //print_r($arr1[95]);
        //print_r($_SESSION);
        echo "<br>";
        //print_r($arr1[79]);
        ?>
        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th style="border-radius:30px 0px 0px 30px">Consultar</th>
                    <th style="border-radius:0px 0px 0px 0px">Status - Proximo Contacto</th>
                    <th style="border-radius:0px 0px 0px 0px">Prospecto</th>
                    <th style="border-radius:0px 0px 0px 0px">Vehiculo</th>
                    <th style="border-radius:0px 0px 0px 0px">Observacion</th>
                    <th style="border-radius:0px 30px 30px 0px">Asesor</th>
                </tr>
            </thead>

            <tbody>
                <?php

                if ($arr1[95] == 1) {
                    if ((!empty($status)) OR (!empty($fecha_ini)) OR (!empty($fecha_fin)) OR (!empty($search)) ) {
                        $consulta = "SELECT o.id AS id, pro.id AS idnom, pro.nombre AS nombre, pro.telefono1 AS telefono, ope.descripcion AS prospecto, mar.descripcion AS marca, moe.descripcion AS modelo, ti.descripcion AS tvhiculo, ian.descripcion AS ianio, fan.descripcion AS fanio, o.observacion AS observacion, o.proximocontacto AS contacto, sta.descripcion AS status, o.factor1 AS f1, o.factor2 AS f2, o.factor3 AS f3, o.factor4 AS f4, o.factor5 AS f5, o.factor6 AS f6, o.factor7 AS f7,MAX(seguimiento.fechacontacto) as fecha, MAX(seguimiento.tipostatus_id),obtener_segumiento(o.id) AS seguimiento, usuario.nombre AS asesor, CONCAT(tipomarca.descripcion,' ', tipomodelo.descripcion,' ',tipoannio.descripcion,' ',version.descripcion) AS vehiculo, o.version_id AS version_prueba 
                        FROM oportunidad AS o
                        LEFT JOIN prospecto AS pro ON pro.id = o.prospecto_id 
                        LEFT JOIN tipostatus AS ope ON ope.id = o.tipooperacion_id 
                        LEFT JOIN tipomarca AS mar ON mar.id = o.tipomarca_id 
                        LEFT JOIN tipomodelo AS moe ON moe.id = o.tipomodelo_id 
                        LEFT JOIN tipoannio AS ian ON ian.id = o.anodesde 
                        LEFT JOIN tipoannio AS fan ON fan.id = o.anohasta 
                        LEFT JOIN tipostatus AS sta ON sta.id = o.tipostatus_id 
                        LEFT JOIN tipostatus AS ti ON ti.id = o.tipovehiculo_id
                        LEFT JOIN seguimiento ON seguimiento.oportunidad_id = o.id
                        LEFT JOIN tipostatus AS seg ON seguimiento.tipostatus_id = (seg.id)
                        LEFT JOIN usuario ON usuario.id = pro.usuario_id 
                        LEFT JOIN version ON o.version_id = version.id
                        LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id
                        LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id
                        LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id";

                        $sitioconsulta = " WHERE pro.usuario_id = '$idid' AND pro.sitio_id = '$sitiocambio' ";

                        if (!empty($status)){
                            $status = "AND o.tipostatus_id IN('" . $status . "')";
                        }
                        $rango="";
                            if (!empty($fecha_ini) && !empty($fecha_fin)) {
                                $rango="  AND DATE_FORMAT(o.proximocontacto,'%Y-%m-%d') BETWEEN '".$fecha_ini."' AND '".$fecha_fin."' ";
                            }
                        $buscar="";
                        if (! empty($search)){
                            $buscar="  AND (pro.nombre LIKE '%".$search."%' OR pro.telefono1 LIKE '%".$search."%') ";
                        }

                        $consulta = $consulta." $sitioconsulta $status $rango $buscar group by o.id ORDER BY o.proximocontacto DESC";
                    }
                    else{
                        $consulta = "SELECT o.id AS id, pro.id AS idnom, pro.nombre AS nombre, pro.telefono1 AS telefono, ope.descripcion AS prospecto, mar.descripcion AS marca, moe.descripcion AS modelo, ti.descripcion AS tvhiculo, ian.descripcion AS ianio, fan.descripcion AS fanio, o.observacion AS observacion, o.proximocontacto AS contacto, sta.descripcion AS status, o.factor1 AS f1, o.factor2 AS f2, o.factor3 AS f3, o.factor4 AS f4, o.factor5 AS f5, o.factor6 AS f6, o.factor7 AS f7,MAX(seguimiento.fechacontacto) as fecha, MAX(seguimiento.tipostatus_id), obtener_segumiento(o.id) AS seguimiento, usuario.nombre AS asesor, CONCAT(tipomarca.descripcion,' ', tipomodelo.descripcion,' ',tipoannio.descripcion,' ',version.descripcion) AS vehiculo, o.version_id AS version_prueba
                        FROM oportunidad AS o
                        LEFT JOIN prospecto AS pro ON pro.id = o.prospecto_id 
                        LEFT JOIN tipostatus AS ope ON ope.id = o.tipooperacion_id 
                        LEFT JOIN tipomarca AS mar ON mar.id = o.tipomarca_id 
                        LEFT JOIN tipomodelo AS moe ON moe.id = o.tipomodelo_id 
                        LEFT JOIN tipoannio AS ian ON ian.id = o.anodesde 
                        LEFT JOIN tipoannio AS fan ON fan.id = o.anohasta 
                        LEFT JOIN tipostatus AS sta ON sta.id = o.tipostatus_id 
                        LEFT JOIN tipostatus AS ti ON ti.id = o.tipovehiculo_id
                        LEFT JOIN seguimiento ON seguimiento.oportunidad_id = o.id
                        LEFT JOIN tipostatus AS seg ON seguimiento.tipostatus_id = (seg.id)
                        LEFT JOIN usuario ON usuario.id = pro.usuario_id
                        LEFT JOIN version ON o.version_id = version.id
                        LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id
                        LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id
                        LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id";

                        //if ($arr1[152] == 1) {
                          //      if ($sitiocambio != '') {
                          //          $consulta .= " WHERE pro.usuario_id = '$idid' AND sta.id IN('5163','5166') AND pro.sitio_id = '$sitiocambio' group by o.id";
                            //    }else{
                                    $consulta .= " WHERE pro.usuario_id = '$idid' AND sta.id IN('5163','5166') AND pro.sitio_id = '$sitiocambio' group by o.id ORDER BY o.proximocontacto ASC";
                            //    }
                        //}else if ($arr1[153] == 1) {
                             //$consulta .= " WHERE pro.usuario_id = '$idid' AND sta.id IN('5163','5166') AND pro.sitio_id IN('".$sitios_razon_social."') group by o.id";
                        //}
                    }
                }
                elseif ($arr1[79] == 1) {
                    if ((!empty($status)) OR (!empty($fecha_ini)) OR (!empty($fecha_fin)) OR (!empty($search)) ) {
                        $consulta = "SELECT o.id AS id, pro.id AS idnom, pro.nombre AS nombre, pro.telefono1 AS telefono, ope.descripcion AS prospecto, mar.descripcion AS marca, moe.descripcion AS modelo, ti.descripcion AS tvhiculo, ian.descripcion AS ianio, fan.descripcion AS fanio, o.observacion AS observacion, o.proximocontacto AS contacto, sta.descripcion AS status, o.factor1 AS f1, o.factor2 AS f2, o.factor3 AS f3, o.factor4 AS f4, o.factor5 AS f5, o.factor6 AS f6, o.factor7 AS f7,MAX(seguimiento.fechacontacto) as fecha, MAX(seguimiento.tipostatus_id), obtener_segumiento(o.id) AS seguimiento, usuario.nombre AS asesor,CONCAT(tipomarca.descripcion,' ', tipomodelo.descripcion,' ',tipoannio.descripcion,' ',version.descripcion) AS vehiculo, o.version_id AS version_prueba 
                        FROM oportunidad AS o
                        LEFT JOIN prospecto AS pro ON pro.id = o.prospecto_id 
                        LEFT JOIN tipostatus AS ope ON ope.id = o.tipooperacion_id 
                        LEFT JOIN tipomarca AS mar ON mar.id = o.tipomarca_id 
                        LEFT JOIN tipomodelo AS moe ON moe.id = o.tipomodelo_id 
                        LEFT JOIN tipoannio AS ian ON ian.id = o.anodesde 
                        LEFT JOIN tipoannio AS fan ON fan.id = o.anohasta 
                        LEFT JOIN tipostatus AS sta ON sta.id = o.tipostatus_id 
                        LEFT JOIN tipostatus AS ti ON ti.id = o.tipovehiculo_id
                        LEFT JOIN seguimiento ON seguimiento.oportunidad_id = o.id
                        LEFT JOIN tipostatus AS seg ON seguimiento.tipostatus_id = (seg.id)
                        LEFT JOIN usuario ON usuario.id = pro.usuario_id
                        LEFT JOIN version ON o.version_id = version.id
                        LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id
                        LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id
                        LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id";

                        if($sitiocambio != "1"){
                            $sitioconsulta = " WHERE pro.sitio_id = '$sitiocambio' ";
                        }
                        else{
                            $sitioconsulta = " WHERE 1";
                        }

                        if (! empty($status)){
                            $status="AND o.tipostatus_id IN('".$status."')";
                        }
                        $rango="";
                        if (!empty($fecha_ini) && !empty($fecha_fin)) {
                            $rango="  AND DATE_FORMAT(o.proximocontacto,'%Y-%m-%d') BETWEEN '".$fecha_ini."' AND '".$fecha_fin."' ";
                        }
                        $buscar="";
                        if (! empty($search)){
                            $buscar="  AND (pro.nombre LIKE '%".$search."%' OR pro.telefono1 LIKE '%".$search."%') ";
                        }
                        $consulta = $consulta."  $sitioconsulta $status $rango $buscar group by o.id ORDER BY o.proximocontacto ASC";
                    }
                    else{
                        if($sitiocambio != "1"){
                            $consulta = "SELECT o.id AS id, pro.id AS idnom, pro.nombre AS nombre, pro.telefono1 AS telefono, ope.descripcion AS prospecto, mar.descripcion AS marca, moe.descripcion AS modelo, ti.descripcion AS tvhiculo, ian.descripcion AS ianio, fan.descripcion AS fanio, o.observacion AS observacion, o.proximocontacto AS contacto, sta.descripcion AS status, o.factor1 AS f1, o.factor2 AS f2, o.factor3 AS f3, o.factor4 AS f4, o.factor5 AS f5, o.factor6 AS f6, o.factor7 AS f7,MAX(seguimiento.fechacontacto) as fecha, MAX(seguimiento.tipostatus_id), obtener_segumiento(o.id) AS seguimiento, usuario.nombre AS asesor, CONCAT(tipomarca.descripcion,' ', tipomodelo.descripcion,' ',tipoannio.descripcion,' ',version.descripcion) AS vehiculo, o.version_id AS version_prueba
                            FROM oportunidad AS o
                            LEFT JOIN prospecto AS pro ON pro.id = o.prospecto_id 
                            LEFT JOIN tipostatus AS ope ON ope.id = o.tipooperacion_id 
                            LEFT JOIN tipomarca AS mar ON mar.id = o.tipomarca_id 
                            LEFT JOIN tipomodelo AS moe ON moe.id = o.tipomodelo_id 
                            LEFT JOIN tipoannio AS ian ON ian.id = o.anodesde 
                            LEFT JOIN tipoannio AS fan ON fan.id = o.anohasta 
                            LEFT JOIN tipostatus AS sta ON sta.id = o.tipostatus_id 
                            LEFT JOIN tipostatus AS ti ON ti.id = o.tipovehiculo_id
                            LEFT JOIN seguimiento ON seguimiento.oportunidad_id = o.id
                            LEFT JOIN tipostatus AS seg ON seguimiento.tipostatus_id = (seg.id) 
                            LEFT JOIN usuario ON usuario.id = pro.usuario_id
                            LEFT JOIN version ON o.version_id = version.id
                            LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id
                            LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id
                            LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id
                            WHERE sta.id IN('5163','5166') AND pro.sitio_id = '$sitiocambio' group by o.id ORDER BY o.proximocontacto ASC  ";
                        }
                        else{
                            $consulta = "SELECT o.id AS id, pro.id AS idnom, pro.nombre AS nombre, pro.telefono1 AS telefono, ope.descripcion AS prospecto, mar.descripcion AS marca, moe.descripcion AS modelo, ti.descripcion AS tvhiculo, ian.descripcion AS ianio, fan.descripcion AS fanio, o.observacion AS observacion, o.proximocontacto AS contacto, sta.descripcion AS status, o.factor1 AS f1, o.factor2 AS f2, o.factor3 AS f3, o.factor4 AS f4, o.factor5 AS f5, o.factor6 AS f6, o.factor7 AS f7,MAX(seguimiento.fechacontacto) as fecha, MAX(seguimiento.tipostatus_id), obtener_segumiento(o.id) AS seguimiento, usuario.nombre AS asesor, CONCAT(tipomarca.descripcion,' ', tipomodelo.descripcion,' ',tipoannio.descripcion,' ',version.descripcion) AS vehiculo, o.version_id AS version_prueba
                            FROM oportunidad AS o
                            LEFT JOIN prospecto AS pro ON pro.id = o.prospecto_id 
                            LEFT JOIN tipostatus AS ope ON ope.id = o.tipooperacion_id 
                            LEFT JOIN tipomarca AS mar ON mar.id = o.tipomarca_id 
                            LEFT JOIN tipomodelo AS moe ON moe.id = o.tipomodelo_id 
                            LEFT JOIN tipoannio AS ian ON ian.id = o.anodesde 
                            LEFT JOIN tipoannio AS fan ON fan.id = o.anohasta 
                            LEFT JOIN tipostatus AS sta ON sta.id = o.tipostatus_id 
                            LEFT JOIN tipostatus AS ti ON ti.id = o.tipovehiculo_id
                            LEFT JOIN seguimiento ON seguimiento.oportunidad_id = o.id
                            LEFT JOIN tipostatus AS seg ON seguimiento.tipostatus_id = (seg.id) 
                            LEFT JOIN usuario ON usuario.id = pro.usuario_id
                            LEFT JOIN version ON o.version_id = version.id
                            LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id
                            LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id
                            LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id
                            WHERE sta.id IN('5163','5166') GROUP BY o.id ORDER BY o.proximocontacto ASC  ";
                        }
                    }
                }

                //print_r($consulta);

                $result =  $mysqli->query($consulta);
                $fila = mysqli_free_result($consulta);
                while($mostrar = mysqli_fetch_array($result)){
                    $iidd = $mostrar['id'];
                    $nomb = $mostrar['nombre']. ' ( ' .$mostrar['telefono'] .')' ;
                    $pros = $mostrar['prospecto'];
                    $marc = $mostrar['marca'];
                    $mode = $mostrar['modelo'];
                    $iani = $mostrar['ianio'];
                    $fani = $mostrar['fanio'];
                    $obse = utf8_encode($mostrar['observacion']);
                    $cont = $mostrar['contacto'];
                    $stat = $mostrar['status'];
                    $seg = $mostrar['seguimiento'];
                    $tipv = $mostrar['tvhiculo'];
                    $fac1 = $mostrar['f1'];
                    $fac2 = $mostrar['f2'];
                    $fac3 = $mostrar['f3'];
                    $fac4 = $mostrar['f4'];
                    $fac5 = $mostrar['f5'];
                    $fac6 = $mostrar['f6'];
                    $fac7 = $mostrar['f7'];
                    $version = $mostrar['version_prueba'];

                    if ($version != '') {
                        $vehiculo = $mostrar['vehiculo'];
                    } else {
                        $vehiculo = $marc . " " . $mode . ", " . $tipv . ", " . $iani . " - " . $fani;
                    }

                    date_default_timezone_set('America/Mexico_city');
                    $fecha_hoy=date("Y-m-d");

                    list($fecha_cont, $horaguardada) = explode(" ",$cont);
                    if ($stat == '1. EN PROCESO' OR $stat == '1. EN PAUSA') { ?>
                        <?php
                        if($fecha_cont > $fecha_hoy){ ?>
                            <tr>
                            <?php
                            if($pros!="COMPRA"){ ?>
                                <td class="success" style="border-radius:30px 0px 0px 30px">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                        <?php

                                        echo '<a target="_blank" href="consultar.php?id='.$iidd.'">
                                        <button type="submit" class="btn btn-primary btn-lg m-l-15 waves-effect"><i class="material-icons" style="font-size: 15px;">search</i> <span class="icon-name">Buscar</span>
                                        </button>
                                        </a>';
                                        ?>
                                    </div>
                                </td>
                            <?php
                            }
                            else{
                                ?>
                                <td class="success" style="border-radius:30px 0px 0px 30px">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                        <?php
                                        echo '<a target="_blank" href="buscar.php?id='.$iidd.'">
                                        <button type="submit" class="btn bg-cyan btn-lg m-l-15 waves-effect"><i class="material-icons">search</i> <span class="icon-name">Buscar</span>
                                        </button>
                                        </a>';
                                        ?>
                                    </div>
                                </td>

                            <?php } ?>

                            <td class="success" style="border-radius:0px 0px 0px 0px"><?php echo $stat." ".$seg." ".$cont; ?></td>
                            <td class="success" style="border-radius:0px 0px 0px 0px"><?php
                    if ($arr1[82] == 1){
                    $idpo = $mostrar['id'];
                    echo '<a target="_blank" href="tabladeunseguimiento.php?id='.$idpo.'">';
                    echo "<b>".mb_strtoupper($nomb)."</b> ";
                    echo "</a>";
                    if($pros == "VENTA"){
                            echo "COMPRA UN:";
                        }else{
                            echo "VENDE UN: ";
                        }
                }else{
                    $idpo = $mostrar['id'];
                    echo "<b>".mb_strtoupper($nomb)."</b> ";
                    if($pros == "VENTA"){
                            echo "COMPRA UN:";
                        }else{
                            echo "VENDE UN: ";
                        }
                }
                    ?></td>
                    <td class="success" style="border-radius:0px 0px 0px 0px"><?php echo mb_strtoupper($vehiculo); ?></td>
                    <td class="success" style="border-radius:0px 0px 0px 0px"><?php echo mb_strtoupper($mostrar['observacion']) ?></td>
                    <td class="success" style="border-radius:0px 30px 30px 0px"><?php echo mb_strtoupper($mostrar['asesor']) ?></td></tr>

            <?php
                    }
                        elseif($fecha_cont == $fecha_hoy){
                        ?>
                <tr>
                    <?php
                        if($pros!="COMPRA"){
                    ?>
                    <td class="warning" style="border-radius:30px 0px 0px 30px">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                            <?php

                            echo '<a target="_blank"  href="consultar.php?id='.$iidd.'">
                            <button type="submit" class="btn btn-primary btn-lg m-l-15 waves-effect"><i class="material-icons" style="font-size: 15px;">search</i> <span class="icon-name">Buscar</span>
                            </button>
                            </a>';
                            ?>
                        </div>
                    </td>
                    <?php
                        }else{
                    ?>
                    <td class="warning" style="border-radius:30px 0px 0px 30px">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                            <?php

                            echo '<a target="_blank" href="buscar.php?id='.$iidd.'">
                            <button type="submit" class="btn bg-cyan btn-lg m-l-15 waves-effect"><i class="material-icons">search</i> <span class="icon-name">Buscar</span>
                            </button>
                            </a>';
                            ?>
                        </div>
                    </td>

            <?php } ?>
                    <td class="warning" style="border-radius:0px 0px 0px 0px"><?php echo $stat." ".$seg." ".$cont; ?></td>
                    <td class="warning" style="border-radius:0px 0px 0px 0px"><?php
                    if ($arr1[82] == 1){
                    $idpo = $mostrar['id'];
                    echo '<a target="_blank" href="tabladeunseguimiento.php?id='.$idpo.'">';
                    echo "<b>".mb_strtoupper($nomb)."</b> ";
                    echo "</a>";
                    if($pros == "VENTA"){
                            echo "COMPRA UN:";
                        }else{
                            echo "VENDE UN: ";
                        }
                }else{
                    $idpo = $mostrar['id'];
                    echo "<b>".mb_strtoupper($nomb)."</b> ";
                    if($pros == "VENTA"){
                            echo "COMPRA UN:";
                        }else{
                            echo "VENDE UN: ";
                        }
                }
                    ?></td>
                    <td class="warning" style="border-radius:0px 0px 0px 0px"><?php echo mb_strtoupper($vehiculo); ?></td>
                    <td class="warning" style="border-radius:0px 0px 0px 0px"><?php echo mb_strtoupper($mostrar['observacion']) ?></td>
                    <td class="warning" style="border-radius:0px 30px 30px 0px"><?php echo mb_strtoupper($mostrar['asesor']) ?></td></tr>

            <?php
                    }
                        elseif($fecha_cont < $fecha_hoy){ ?>
                            <tr>
                                <?php
                                    if($pros!="COMPRA"){
                                ?>
                                <td class="danger" style="border-radius:30px 0px 0px 30px">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                        <?php

                                        echo '<a target="_blank" href="consultar.php?id='.$iidd.'">
                                        <button type="submit" class="btn btn-primary btn-lg m-l-15 waves-effect"><i class="material-icons" style="font-size: 15px;">search</i>Buscar <span class="icon-name"></span>
                                        </button>
                                        </a>';
                                        ?>
                                    </div>
                                </td>
                                <?php
                                    }
                                    else{
                                ?>
                                <td class="danger" style="border-radius:30px 0px 0px 30px">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                        <?php

                                        echo '<a target="_blank" href="buscar.php?id='.$iidd.'">
                                        <button type="submit" class="btn bg-cyan btn-lg m-l-15 waves-effect"><i class="material-icons" style="font-size: 15px;" >search</i> <span class="icon-name">Buscar</span>
                                        </button>
                                        </a>';
                                        ?>
                                    </div>
                                </td>

                                <?php } ?>
                                <td class="danger" style="border-radius:0px 0px 0px 0px"><?php echo $stat." ".$seg." ".$cont; ?></td>

                                <?php if ($arr1[80] == 1) { ?>
                                <?php } ?>

                                <td class="danger" style="border-radius:0px 0px 0px 0px"><?php
                                if ($arr1[82] == 1){
                                    $idpo = $mostrar['id'];
                                    echo '<a target="_blank" href="tabladeunseguimiento.php?id='.$idpo.'">';
                                    echo "<b>".mb_strtoupper($nomb)."</b> ";
                                    echo "</a>";
                                    if($pros == "VENTA"){
                                        echo "COMPRA UN:";
                                    }else{
                                        echo "VENDE UN: ";
                                    }
                                }else{
                                    $idpo = $mostrar['id'];
                                    echo "<b>".mb_strtoupper($nomb)."</b> ";
                                    if($pros == "VENTA"){
                                            echo "COMPRA UN:";
                                        }else{
                                            echo "VENDE UN: ";
                                        }
                                } ?></td>



                                <td class="danger" style="border-radius:0px 0px 0px 0px"><?php echo mb_strtoupper($vehiculo); ?></td>
                                <td class="danger" style="border-radius:0px 0px 0px 0px"><?php echo mb_strtoupper($mostrar['observacion']) ?></td>
                                <td class="danger" style="border-radius:0px 30px 30px 0px"><?php echo mb_strtoupper($mostrar['asesor']) ?></td>

                            </tr>
                        <?php
                        }
                    }

                    else{ ?>
                        <tr>
                        <?php
                        if($pros!="COMPRA"){ ?>
                            <td class="info" style="border-radius:30px 0px 0px 30px">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                    <?php
                                    echo 'Ha concluido la busqueda';
                                    ?>
                                </div>
                            </td>
                        <?php
                        }
                        else{ ?>
                            <td class="info" style="border-radius:30px 0px 0px 30px">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                    <?php
                                    echo 'Ha concluido la busqueda';
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                        <td class="info" style="border-radius:0px 0px 0px 0px"><?php echo $stat." ".$seg." ".$cont; ?></td>
                        <td class="info" style="border-radius:0px 0px 0px 0px"><?php
                        if ($arr1[82] == 1){
                            $idpo = $mostrar['id'];
                            echo '<a target="_blank" href="tabladeunseguimiento.php?id='.$idpo.'">';
                            echo "<b>".mb_strtoupper($nomb)."</b> ";
                            echo "</a>";
                            if($pros == "VENTA"){
                                echo "COMPRA UN:";
                            }else{
                                echo "VENDE UN: ";
                            }
                        }
                        else{
                            $idpo = $mostrar['id'];
                            echo "<b>".mb_strtoupper($nomb)."</b> ";
                            if($pros == "VENTA"){
                            echo "COMPRA UN:";
                            }
                            else{
                                echo "VENDE UN: ";
                            }
                        } ?>
                        </td>
                        <td class="info" style="border-radius:0px 0px 0px 0px"><?php echo mb_strtoupper($vehiculo); ?></td>
                        <td class="info" style="border-radius:0px 0px 0px 0px"><?php echo mb_strtoupper($mostrar['observacion']) ?></td>
                        <td class="info" style="border-radius:0px 30px 30px 0px"><?php echo mb_strtoupper($mostrar['asesor']) ?></td>
                    <?php
                    }
                }

                mysqli_close($mysqli); ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/pages/tables/jquery-datatable.js"></script>