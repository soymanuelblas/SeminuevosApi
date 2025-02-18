<?php 
    include "variables.php";
    $pro = json_decode($_POST['array']);
    $marca   = implode ("','", $pro[0]);;
    $modelo   = implode ("','", $pro[1]);
    $annio = implode ("','", $pro[2]);
    $expediente = implode ("','", $pro[3]);
?>

<div class="body">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="border:0px;">
            <thead>
                <tr>
                    <th style="border-radius:30px 0px 0px 30px">No.Expediente</th>
                    <th style="border-radius:0px 0px 0px 0px">Vehiculo</th>
                    <th style="border-radius:0px 0px 0px 0px">Precio</th>
                    <th style="border-radius:0px 0px 0px 0px">Status Venta</th>
                    <th style="border-radius:0px 30px 30px 0px">No Serie</th>
                </tr>
            </thead>

            <tbody>
            <?php
            $sql3 = "SELECT vehiculo.id, version.descripcion as version, vehiculo.noexpediente, vehiculo.numeroserie,
                    venta.descripcion AS venta, vehiculo.precio,vehiculo.kilometraje, modelo.descripcion AS modelo, marca.descripcion, annio.descripcion AS annio, color.descripcion AS color,modelo.id AS modeloid, marca.id AS marcaid, annio.id AS annioid 
                    FROM vehiculo 
                    INNER JOIN tipostatus AS venta ON vehiculo.status_venta = venta.id 
                    INNER JOIN version AS version ON vehiculo.version_id = version.id 
                    INNER JOIN tipomodelo AS modelo ON version.tipomodelo_id = modelo.id 
                    INNER JOIN tipomarca AS marca ON marca.id = modelo.tipomarca_id 
                    INNER JOIN tipoannio AS annio ON version.tipoannio_id = annio.id
                    INNER JOIN tipostatus AS color ON color.id = vehiculo.color_id ";

            if($sitiocambio != 1){
                if ($arr1[24] == 1) {
                    $sql3 .= " WHERE vehiculo.sitio_id IN('".$sitios_razon_social."') ";
                }

                /*
                if ($arr1[152] == 1) {
                    $sql3 .= " WHERE vehiculo.sitio_id = '$sitiocambio' ";
                }else if ($arr1[153] == 1) {
                    $sql3 .= " WHERE vehiculo.sitio_id IN('".$sitios_razon_social."') ";
                }
                else{
                    //SIN EL PERMISO
                    $sql3 = "";
                }
                */
            }
            else{
                $sql3 .= " WHERE 1";
            }
                                            


            if(empty($marca) && empty($modelo) && empty($annio) && empty($expediente)){
                $sql3 .= " AND  venta.descripcion = 'DISPONIBLE' ";
            }

            if (! empty($marca)){
                $sql3 .= " AND marca.id IN('".$marca."')";
            }

            if (! empty($modelo)){
                $sql3 .= " AND modelo.id IN('".$modelo."')";
            }

            if (! empty($annio)){
                $sql3 .= " AND annio.id IN('".$annio."')";
            }

            if (! empty($expediente)){
                $sql3 .= " AND vehiculo.noexpediente IN('".$expediente."')";
            }

            $resultado3 = $mysqli->query($sql3);

                                            //echo $sql3;

                                            while($row = $resultado3->fetch_array(MYSQLI_ASSOC)) { 


                                        ?>
                                        <tr style="background:#EFEFEF;">
                                            <td style="border-radius:30px 0px 0px 30px"><?php echo $row['noexpediente']; ?></td>
                                                
                                            <?php if ($arr1[23] == 1 AND $sitiocambio == $sitioSession) { ?>
                                            <td style="border-radius:0px 0px 0px 0px"><a a href="modificarvehiculo.php?id=<?php echo $row['id']; ?>">
                                                <?php echo $row['descripcion']; ?> <?php echo $row['modelo']; ?> <?php echo $row['annio']; ?> <?php echo $row['version']; ?>(<?php echo $row['color']; ?>)</a>
                                            </td>
                                            <?php }else{  ?>
                                                <td style="border-radius:0px 0px 0px 0px"><?php echo $row['descripcion']; ?> <?php echo $row['modelo']; ?> <?php echo $row['annio']; ?> <?php echo $row['version']; ?>(<?php echo $row['color']; ?>)</a> </td>
                                            <?php }  ?>
                                            <td style="border-radius:0px 0px 0px 0px">$<?php echo number_format($row['precio'],2); ?></td>
                                            <td style="border-radius:0px 0px 0px 0px"><?php echo $row['venta']; ?></td>
                                            <td style="border-radius:0px 30px 30px 0px"><?php echo $row['numeroserie']; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
             

<script src="js/pages/tables/jquery-datatable.js"></script>
