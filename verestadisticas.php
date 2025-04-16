<?php
include "variables.php";
include "funciones.php";

error_reporting(0);
$id       = $_REQUEST['id'];
$vehiculo = $_REQUEST['vehiculonom'];

$sql3 = "SELECT prospecto.id
                      FROM prospecto
                      LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id
                      WHERE oportunidad.tipostatus_id = '5166' AND prospecto.sitio_id = '$sitiocambio' AND oportunidad.vehiculo_id = '$id' ";
$reslu3      = mysqli_query($mysqli, $sql3);
$total_count = mysqli_num_rows($reslu3);

$sql4 = "SELECT prospecto.id
                      FROM prospecto
                      LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id
                      WHERE oportunidad.tipostatus_id = '5163' AND prospecto.sitio_id = '$sitiocambio' AND oportunidad.vehiculo_id = '$id'";

$reslu4       = mysqli_query($mysqli, $sql4);
$total_count1 = mysqli_num_rows($reslu4);

$sql5 = "SELECT prospecto.id
                      FROM prospecto
                      LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id
                      WHERE oportunidad.tipostatus_id = '5165' AND prospecto.sitio_id = '$sitiocambio' AND oportunidad.vehiculo_id = '$id'";

$reslu5       = mysqli_query($mysqli, $sql5);
$total_count2 = mysqli_num_rows($reslu5);

$sqllograda = "SELECT prospecto.id
                      FROM prospecto
                      LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id
                      WHERE oportunidad.tipostatus_id = '5164' AND prospecto.sitio_id = '$sitiocambio' AND oportunidad.vehiculo_id = '$id' ";
$reslulograda = mysqli_query($mysqli, $sqllograda);
$total_count3 = mysqli_num_rows($reslulograda);

$total_count;
$total_count1;
$total_count2;
$total_count3;
$total = $total_count + $total_count1 + $total_count2 + $total_count3;

$porcentajepausa     = number_format(($total_count * 100) / $total, 2);
$porcentajeproceso   = number_format(($total_count1 * 100) / $total, 2);
$porcentajenolograda = number_format(($total_count2 * 100) / $total, 2);
$porcentajelograda   = number_format(($total_count3 * 100) / $total, 2);

$gastos = "SELECT SUM(formapago.importe) AS gastos_totales
FROM operacion
LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno
AND formapago.sitio_id = operacion.sitio_id
LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion
WHERE operacion_caja.tipo = 2 AND formapago.formapago_id != '5034' AND formapago.tipostatus_id = '5210'
AND operacion.sitio_id = '$sitiocambio' AND operacion.vehiculo_id = '$id'
ORDER BY formapago.fechaexpedicion ASC";
$resultadoegresos = $mysqli->query($gastos);
$rowegresos       = $resultadoegresos->fetch_array(MYSQLI_ASSOC);

$total_egresos1 = $rowegresos['gastos_totales'];
?>
<link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="header" role="tab" id="headingOneinteresados_19" role="button" data-toggle="collapse" href="#collapseOneinteresados_19" aria-expanded="true" aria-controls="collapseOneinteresados_19">
                <h2> INTERESADOS (<span style="color:red;"><?php echo $total; ?></span>) <span class="ocultar_guia" style="color:
                      black;"><?=btn4('guiaestadis')?></span></h2>
                <small>Da click para información</small>

            </div>
            <div id="collapseOneinteresados_19" class="panel-collapse collapse body table-responsive " role="tabpanel" aria-labelledby="headingOneinteresados_19">
                <div class="body">
                    <div id="table" class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th style="border-radius:30px 0px 0px 30px">Status</th>
                                        <th style="border-radius:0px 0px 0px 0px">Total</th>
                                        <th style="border-radius:0px 30px 30px 0px">Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="background:#EFEFEF;">
                                        <td style="border-radius:30px 0px 0px 30px">1. EN PAUSA</td>
                                        <td style="border-radius:0px 0px 0px 0px"><?php echo $total_count; ?></td>
                                        <td style="border-radius:0px 30px 30px 0px"><?php echo $porcentajepausa; ?>%</td>
                                    </tr>
                                    <tr style="background:#EFEFEF;">
                                        <td style="border-radius:30px 0px 0px 30px">1. EN PROCESO</td>
                                        <td style="border-radius:0px 0px 0px 0px"><?php echo $total_count1; ?></td>
                                        <td style="border-radius:0px 30px 30px 0px"><?php echo $porcentajeproceso; ?>%</td>
                                    </tr>
                                    <tr style="background:#EFEFEF;">
                                        <td style="border-radius:30px 0px 0px 30px">2. NO LOGRADA</td>
                                        <td style="border-radius:0px 0px 0px 0px"><?php echo $total_count2; ?></td>
                                        <td style="border-radius:0px 30px 30px 0px"><?php echo $porcentajenolograda; ?>%</td>
                                    </tr>
                                    <tr style="background:#EFEFEF;">
                                        <td style="border-radius:30px 0px 0px 30px">2. LOGRADA</td>
                                        <td style="border-radius:0px 0px 0px 0px"><?php echo $total_count3; ?></td>
                                        <td style="border-radius:0px 30px 30px 0px"><?php echo $porcentajelograda; ?>%</td>
                                    </tr>
                                    <tr style="background:#EFEFEF;">
                                        <td style="border-radius:30px 0px 0px 30px">TOTAL</td>
                                        <td style="border-radius:0px 0px 0px 0px"><?php echo $total; ?></td>
                                        <td style="border-radius:0px 30px 30px 0px">100%</td>
                                    </tr>
                                </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    <?php if ($arr1[170] == 1) {
    ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="header" role="tab" id="headingOnegastos_19" role="button" data-toggle="collapse" href="#collapseOnegastos_19" aria-expanded="true" aria-controls="collapseOnegastos_19">
                <h2>GASTOS (<span style="color:red;">$<?php echo number_format($total_egresos1, 2); ?></span>)</h2>
                <small>Da click para informaci��n</small>

            </div>
            <div id="collapseOnegastos_19" class="panel-collapse collapse body table-responsive " role="tabpanel" aria-labelledby="headingOnegastos_19">
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Operacion</th>
                                            <th>Importe</th>
                                            <th>Descripcion</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
$sql17 = "SELECT operacion.id_interno AS id, formapago.importe AS importe, formapago.referencia AS descripcion, DATE(formapago.fechaexpedicion) AS fecha, operacion_caja.descripcion AS operacion
                                            FROM operacion
                                            LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno
                                            AND formapago.sitio_id = operacion.sitio_id
                                            LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion
                                            WHERE operacion_caja.tipo = 2 AND formapago.formapago_id != '5034' AND formapago.tipostatus_id = '5210'
                                            AND operacion.sitio_id = '$sitiocambio' AND operacion.vehiculo_id = '$id'
                                            ORDER BY formapago.fechaexpedicion ASC ";

    $resultado17 = $mysqli->query($sql17);

    while ($row = $resultado17->fetch_array(MYSQLI_ASSOC)) {?>
                                        <tr>
                                            <td><?php echo $row['fecha']; ?></td>
                                            <td><?php echo $row['operacion']; ?></td>
                                            <td><?php echo number_format($row['importe'], 2); ?></td>
                                            <td><?php echo $row['descripcion']; ?></td>
                                        </tr>
                                        <?php }?>
                                    </tbody>

                        </table>
                    </div>
                </div>
            </div>
    </div>
<?php }?>
<!-- Jquery DataTable Plugin Js -->
 <script src="js/pages/tables/jquery-datatable.js"></script>

<script>

    $( "#guiaestadis" ).click(function() {

        $('#collapseOneinteresados_19').collapse({
            show: true
        });

        myTourestadis();
        return false;
    });

    var preseOptestadis = {

        keyboard: false,
        tourMap: {
            open: false
        },
        lang: {
        },
        intro: {
            title: 'ESTADISTICAS',
            content: '',
            cover:'',
            width: 450,
        }, continue:{
            enable:false,
        },
        create: function () {

            $.checkId = function () {
            };
            $.checkValue = function (target, val) {
                if (jQuery.trim(target.val()) != val) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        steps: [{
            title: 'INTERESADOS',
            content: 'Tabla de resultados de los prospectos interesados en adquirir un vehiculo o solicitar información ',
            target: '#table',
        }],
    }

    function myTourestadis() {
        iGuider(preseOptestadis);
    }

</script>
