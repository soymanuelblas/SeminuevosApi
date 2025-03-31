<?php 
    include "variables.php";
    
    $venta1 = "SELECT * FROM clientes WHERE razonsocial_id = '$razon_social' AND clientes.id_interno = 1 ";
    $resultadoventa = $mysqli->query($venta1);
    $rowventa = $resultadoventa->fetch_array(MYSQLI_ASSOC);

    $clienteventa = $rowventa['id'];
    $pro = json_decode($_POST['array']);
    


            $porciones = explode("-",$pro[0]);
             $porciones[0] = str_replace("/","-",$porciones[0]); // porción1
             "<br>";
             $porciones[1] = str_replace("/","-",$porciones[1]); // porción2
            "<br>";
            $fecha1 = explode("-",$porciones[0]);
            $fecha_ini = str_replace(" ", "",$fecha1[2].'-'.$fecha1[0].'-'.$fecha1[1]);
            "<br>";
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

            $operacion = implode("','", $pro[1]);
            $vehiculo = implode("','", $pro[2]);
            $cliente = implode("','", $pro[3]);
            $proveedor = implode("','", $pro[4]);
            $pago = implode("','", $pro[5]);


?>
<div class="card">
    <div class="body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                    <tr>
                        <th style="border-radius:30px 0px 0px 30px"></th>
                        <th style="border-radius:0px 0px 0px 0px">Operacion</th>
                        <th style="border-radius:0px 0px 0px 0px">Cliente</th>
                        <th style="border-radius:0px 0px 0px 0px">Vehiculo</th>
                        <th style="border-radius:0px 0px 0px 0px">Forma de Pago</th>
                        <th style="border-radius:0px 0px 0px 0px">Descripción</th>
                        <th style="border-radius:0px 0px 0px 0px">Importe</th>
                        <th style="border-radius:0px 30px 30px 0px">Fecha</th>


                    </tr>
                </thead>
                <tbody>
                    <?php
                                        $consulta = "SELECT operacion.id_interno AS id,operacion.fecha AS fechamovimiento, CONCAT('(',vehiculo.noexpediente,')',' ', tipomarca.descripcion,' ', tipomodelo.descripcion,' ',version.descripcion,' ', tipoannio.descripcion) AS vehiculo, operacion_caja.descripcion AS operacion, tipostatus.descripcion AS formapago, DATE_FORMAT(formapago.fechaexpedicion,'%d-%m-%Y') AS fechaexpedicion,DATE_FORMAT(formapago.fechavencimiento,'%d-%m-%Y') AS fechavencimineto, formapago.importe AS importe, clientes.nombre AS clientecompra, clientes1.nombre AS clienteventa,  formapago.id_interno AS formapagoid, formapago.referencia AS descripcion, operacion.clientecompra_id AS compraid, operacion.clienteventa_id AS ventaid,cuenta.nombre AS cuenta, formapago.formapago_id AS tipopago, operacion_caja.tipo AS tipo 
                                                FROM operacion 
                                                LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion 
                                                LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id 
                                                LEFT JOIN version ON version.id = vehiculo.version_id 
                                                LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id 
                                                LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id 
                                                LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id 
                                                LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno 
                                                AND formapago.sitio_id = operacion.sitio_id 
                                                LEFT JOIN tipostatus ON formapago.formapago_id = tipostatus.id 
                                                LEFT JOIN clientes ON clientes.id = operacion.clientecompra_id
                                                LEFT JOIN clientes AS clientes1 ON clientes1.id = operacion.clienteventa_id
                                                LEFT JOIN cuenta_banco ON cuenta_banco.formapago_id = formapago.id_interno 
                                                AND cuenta_banco.sitio_id = formapago.sitio_id 
                                                LEFT JOIN cuentas_bancarias AS cuenta ON cuenta.id = cuenta_banco.cuenta_id
                                                WHERE operacion.sitio_id = '$sitiocambio' AND formapago.tipostatus_id != '5211'  ";


                                        if (! empty($operacion)){
                                            $consulta .= " AND operacion_caja.id IN('".$operacion."')"; 
                                        }

                                        if (! empty($vehiculo)){
                                            $consulta .= " AND vehiculo.id IN('".$vehiculo."')"; 
                                        }

                                        if (! empty($cliente)){
                                            $consulta .= " AND clientes.id IN('".$cliente."')"; 
                                        }

                                        if (! empty($proveedor)){
                                            $consulta .= " AND clientes1.id IN('".$proveedor."')"; 
                                        }

                                        if (! empty($pago)){
                                            $consulta .= " AND formapago.tipostatus_id IN('".$pago."')"; 
                                        }

                                        if (!empty($fecha_ini) && !empty($fecha_fin)) {  
                                            $consulta .= "  AND DATE_FORMAT(formapago.fechaexpedicion,'%Y-%m-%d') BETWEEN '".$fecha_ini."' AND '".$fecha_fin."' ";
                                        }

                                        $result = mysqli_query($mysqli, $consulta); 
                                        while($row = mysqli_fetch_array($result)){  
                                            
                                            $fechaex = $row['fechaexpedicion']; 
                                        ?>
                    <tr style="background:#EFEFEF;">
                        <td style="border-radius:30px 0px 0px 30px">
                            <?php if ($sitiocambio == $sitioSession) { ?>
                            <a href="consultarecibo.php?id=<?php echo $row['formapagoid']; ?>" target="_blank"><button
                                    type="button" class="btn btn-primary waves-effect">
                                    <i class="material-icons" style="font-size: 15px;">create</i></button></a>
                            <br>
                            <br>
                            <?php if ($row['tipopago'] == '5034' && $fechaex != '') {?>

                            <a href="imprimirpagare.php?id=<?php echo $row['formapagoid']; ?>" target="_blank"><button
                                    type="button" class="btn btn-primary waves-effect">
                                    <i class="material-icons" style="font-size: 15px;">credit_card</i></button></a>
                            <?php } } ?>

                        </td>
                        <td style="border-radius:0px 0px 0px 0px"><?php echo utf8_encode($row['operacion']) ?></td>
                        <?php 
                                                if ($row['tipo'] == "2") { ?>
                        <td style="border-radius:0px 0px 0px 0px"><?php echo ($row['clienteventa']) ?></td>
                        <?php
                                                }else if ($row['tipo'] == "1"){ ?>
                        <td style="border-radius:0px 0px 0px 0px"><?php echo ($row['clientecompra']) ?></td>
                        <?php } ?>
                        <td style="border-radius:0px 0px 0px 0px"><?php echo ($row['vehiculo'])?></td>
                        <td style="border-radius:0px 0px 0px 0px"><?php echo utf8_encode($row['formapago']).' '.$row['cuenta'].' '.$row['fechavencimineto']?>
                        </td>
                        <td style="border-radius:0px 0px 0px 0px"><?php echo $row['descripcion']?></td>
                        <td style="border-radius:0px 0px 0px 0px"><?php echo number_format($row['importe'],2)?></td>
                        <td style="border-radius:0px 30px 30px 0px"><?php echo date('Y-m-d', strtotime($row['fechaexpedicion'])) ?></td>
                    </tr>
                    <?php 
                                        }
                                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="js/pages/tables/jquery-datatable.js"></script>