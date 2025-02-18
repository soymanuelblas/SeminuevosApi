<?php
include "variables.php";
$pro = json_decode($_POST['array']);
$search   = $pro[0];

$porciones = explode(" - ", $search);
$fInicial = $porciones[0];
$fFinal = $porciones[1];

$fIni = explode("/", $fInicial);
$fFin = explode("/", $fFinal);

$fInicio = $fIni[2]."-".$fIni[0]."-".$fIni[1];
$fUltima = $fFin[2]."-".$fFin[0]."-".$fFin[1];

$fInicio = $fInicio." 00:00:00";
$fUltima = $fUltima." 23:59:59";

$sql = "SELECT pCRM.id as idPago, RS.nombre as nombre_razon, iMP.tittleItem as nombre_paquete, pCRM.idstatus as statusPago, apCRM.estadoPago as accesostatus,
       pCRM.numOrder as numero_orden, apCRM.estadoPago as estado_acceso
        FROM accesos_pagosCRM as apCRM
        LEFT JOIN pago_crm AS pCRM ON pCRM.id = apCRM.crm_id 
        LEFT JOIN razon_social AS RS ON RS.id = apCRM.razon_id 
        LEFT JOIN itemMercaLib AS iMP ON iMP.id = apCRM.item_id 
        LEFT JOIN tipostatus ts ON ts.id = apCRM.estadoPago 
        WHERE pCRM.fecha BETWEEN '$fInicio' AND '$fUltima'; ";
$resultado = $mysqli->query($sql);
?>

<div class="body">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
            <tr>
                <th style="border-radius:30px 0px 0px 30px"></th>
                <th style="border-radius:0px 0px 0px 0px">Razón Social</th>
                <th style="border-radius:0px 0px 0px 0px">Paquete</th>
                <th style="border-radius:0px 0px 0px 0px"># Orden</th>
                <th style="border-radius:0px 0px 0px 0px">Estado de Pago</th>
                <th style="border-radius:0px 0px 0px 0px">Estado de Acceso</th>
            </tr>
            </thead>

            <tbody>

            <?php while($row = $resultado->fetch_array(MYSQLI_ASSOC)) {
                ?>
                <tr style="background:#EFEFEF;">
                    <td style="border-radius:30px 0px 0px 30px">
                        <?php if ($arr1[67] == 1 AND $sitiocambio == $sitioSession) { ?>
                            <button type="button" class="btn btn-primary waves-effect" data-toggle="modal"
                                    data-target="#defaultModal" onClick="editar<?php echo $row['idPago']; ?>()">
                                <i class="material-icons" style="font-size: 15px;">create</i>
                            </button>
                        <?php } ?>
                    </td>
                    <td style="border-radius:0px 0px 0px 0px"><?php echo $row['nombre_razon']; ?></td>
                    <td style="border-radius:0px 0px 0px 0px"><?php echo $row['nombre_paquete']; ?></td>
                    <td style="border-radius:0px 0px 0px 0px"><?php echo $row['numero_orden']; ?></td>
                    <td style="border-radius:0px 0px 0px 0px"><?php
                        echo ($row['statusPago'] == "13800") ? '<span class="label bg-green">PAGADO</span>' : '<span class="label bg-yellow">EN PROCESO</span>';
                    ?></td>
                    <td style="border-radius:0px 0px 0px 0px"><?php
                        switch ($row['estado_acceso']) {
                            case 13800:
                                echo "<span class='label bg-green'>ACTIVO</span>";
                                break;
                            case 13801:
                                echo "<span class='label bg-yellow'>ESPERANDO PAGO</span>";
                                break;
                            case 13802:
                                echo "<span class='label bg-red'>INACTIVO</span>";
                                break;
                            case 13803:
                                echo "<span class='label bg-green'>ACTIVO (NUEVO USUARIO)</span>";
                                break;
                            case 13804:
                                echo "<span class='label bg-yellow'>ESPERANDO PAGO (NUEVO USUARIO)</span>";
                                break;
                            default:
                                echo "<span class='label bg-red'>INACTIVO</span>";
                        }
                        ?>
                    </td>
                    <script type="text/javascript">
                        function editar<?php echo $row['idPago']; ?>(modal) {
                            var options = {
                                modal: true,
                                height: 300,
                                width: 600
                            };
                            $('#conte-modal2').load('modificarPagoCRM.php?my_modal=' + modal +
                                '&id=<?php echo $row['idPago']; ?>',
                                function() {
                                    $('#bootstrap-modal2').modal({
                                        show: true,
                                        backdrop: 'static',
                                        keyboard: false
                                    });
                                });
                        }
                    </script>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>
</div>
<script src="js/pages/tables/jquery-datatable.js"></script>