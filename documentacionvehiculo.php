<?php
include "variables.php";
include "funciones.php";

error_reporting(0);
$id = $_REQUEST['id'];
$vehiculo = $_REQUEST['vehiculonom'];

$sql3="SELECT tenencia.id, estado.descripcion AS estado, annio.descripcion AS annio, statusfactura.descripcion AS factura, tenencia.archivo FROM `tenencia`
LEFT JOIN tipostatus AS estado ON estado.id = tenencia.estado_id 
LEFT JOIN tipoannio AS annio ON annio.id = tenencia.tipoannio_id
LEFT JOIN tipostatus AS statusfactura ON statusfactura.id = tenencia.tipostatus_id 
WHERE tenencia.vehiculo_id = '$id' ORDER BY  annio.descripcion ASC";
$resultado = $mysqli->query($sql3);
$contMB = 0;

$sqlfac="SELECT factura.id, factura.vehiculo_id, factura.expedidapor, factura.folio, factura.fecha, factura.archivo,tipofactura.descripcion AS tipofactura, statusfactura.descripcion AS statusfactura FROM factura
LEFT JOIN tipostatus AS tipofactura ON tipofactura.id = factura.tipofactura_id 
LEFT JOIN tipostatus AS statusfactura ON statusfactura.id = factura.tipostatus_id 
WHERE factura.vehiculo_id =  '$id'";
$resultadofac = $mysqli->query($sqlfac);

$sql1 = "SELECT COUNT(*) AS conteo 
    FROM `operacion` 
    LEFT JOIN clientes ON clientes.id = operacion.clienteventa_id WHERE operacion.tipo_operacion = '9' AND operacion.vehiculo_id = '$id' 
    AND operacion.sitio_id='$sitioSession'";

    $resultado1 = $mysqli->query($sql1);
    $row1 = $resultado1->fetch_array(MYSQLI_ASSOC);
    $consig = $row1['conteo'];

$sqlcarta = "SELECT COUNT(*) AS conteo 
    FROM factura WHERE factura.vehiculo_id = '$id' AND factura.tipofactura_id = '805'  ";

    $resultadocarta = $mysqli->query($sqlcarta);
    $rowcarta = $resultadocarta->fetch_array(MYSQLI_ASSOC);
    $cartafactura = $rowcarta['conteo'];

date_default_timezone_set('America/Mexico_City');
$mianioMB = date("Y");
?>
<style>
.color_active {
    background: #FF7800;
    color: #FFFFFF;
    border-radius: 30px;
    border: none;
    width: 100%;
}

.color_inactive {
    border: none;
    color: #FF7800;
    background: transparent;
}
</style>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
        <button type="button" id="facturas" class="color_active"
            onclick="event_documentos('facturas')">Facturas</button>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
        <button type="button" id="tenencias" class="color_inactive"
            onclick="event_documentos('tenencias')">Tenencias</button>
    </div>
    <?php if ($cartafactura != 0) {?>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
        <form id="formularioContacto" method="post" action="altacarta1.php" target="_blank">
            <input type="hidden" name="idvehi" id="idvehi" value="<?php echo $id ?>">
            <button type="submit" id="carta" onclick="event_documentos('carta')" class="color_inactive">Carta
                Factura</button>
        </form>
    </div>
    <?php } ?>
    <?php if ($consig == 1 AND $arr1[108] == 1) {?>
    <div class="col-lg-4 col-md-2 col-sm-2 col-xs-6">
        <button type="button" id="recibo" onclick="event_documentos('recibo')" class="color_inactive">Recibo
            Documentación</button>
    </div>
    <?php } ?>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div_facturas">
    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="border:0px;">
        <thead>
            <tr>
                <th style="border-radius:30px 0px 0px 30px"></th>
                <th style="border-radius:0px 0px 0px 0px">Expedida Por</th>
                <th style="border-radius:0px 0px 0px 0px">Tipo de Factura</th>
                <th style="border-radius:0px 0px 0px 0px">Folio</th>
                <th style="border-radius:0px 0px 0px 0px">Fecha</th>
                <th style="border-radius:0px 0px 0px 0px">Status</th>
                <th style="border-radius:0px 30px 30px 0px">archivo</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $resultadofac->fetch_array(MYSQLI_ASSOC)) {
                //print_r($row);
                //echo "<br>";
                ?>
            <tr style="background:#FFFFFF;">
                <td style="border-radius:30px 0px 0px 30px">
                    <?php if ($arr1[26] == 1 AND $sitiocambio == $sitioSession) { ?>
                    <button type="button" class="btn btn-primary waves-effect" data-toggle="modal"
                        data-target="#defaultModal" onClick="editarfac<?php echo $row['id']; ?>()">
                        <i class="material-icons" style="font-size: 15px;">create</i>
                    </button>
                    <?php } ?>
                </td>
                <td style="border-radius:0px 0px 0px 0px"><?php echo $row['expedidapor']; ?></td>
                <td style="border-radius:0px 0px 0px 0px"><?php echo $row['tipofactura']; ?></td>
                <td style="border-radius:0px 0px 0px 0px"><?php echo $row['folio']; ?></td>
                <td style="border-radius:0px 0px 0px 0px"><?php echo $row['fecha']; ?></td>
                <td style="border-radius:0px 0px 0px 0px"><?php echo $row['statusfactura']; ?></td>
                <td style="border-radius:0px 30px 30px 0px"><?php
                    if ($row['archivo'] == "SIN ARCHIVO") {
                        echo "SIN ARCHIVO";
                    } else{
                        echo '<a href="'. $row['archivo']. '" target=”_blank”>' .$row['expedidapor']. '</a>';
                    } ?>
                </td>
                <script type="text/javascript">
                function editarfac<?php echo $row['id']; ?>(modal) {
                    var options = {
                        modal: true,
                        height: 300,
                        width: 920
                    };
                    $('#conte-modal3').load('modificarfactura.php?my_modal=' + modal +
                        '&id=<?php echo $row['id'];?>',
                        function() {
                            $('#bootstrap-modal3').modal({
                                show: true,
                                backdrop: 'static',
                                keyboard: false
                            });
                            $("#bootstrap-modal3").on('hidden.bs.modal', function() {
                                $('.doc').click();
                            });
                        });
                }
                </script>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="actions clearfix">
        <div class="header" role="tab" role="button" data-toggle="collapse">
            <?php if ($arr1[28] == 1  AND $sitiocambio == $sitioSession) { ?>
            <br>
            <ul id="btn12" class="header-dropdown m-r--5" style="margin:-8px 0px;">
                <?php if ($publicacion_validar_result == 0) { ?>
                <li>
                    <button type="button" class="btn btn-default waves-effect" data-toggle="modal"
                        data-target="#defaultModal" onClick="Nuevafactura()">
                        <i class="material-icons" style="color: #FF7800;">add</i>
                        <span>Agregar</span>
                    </button>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div_tenencia" style="display:none;">
    <?php
    //print_r($_SESSION);
    ?>

    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="border:0px;">
        <thead>
            <tr>
                <th style="border-radius:30px 0px 0px 30px"></th>
                <th style="border-radius:0px 0px 0px 0px">Estado</th>
                <th style="border-radius:0px 0px 0px 0px">Año</th>
                <th style="border-radius:0px 0px 0px 0px">Status Tenencia</th>
                <th style="border-radius:0px 30px 30px 0px">Archivo</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $resultado->fetch_array(MYSQLI_ASSOC)) {
                //print_r($row);
                $contMB++
                ?>
            <tr style="background:#EFEFEF;">
                <td style="border-radius:30px 0px 0px 30px">
                    <?php if ($arr1[26] == 1 AND $sitiocambio == $sitioSession) { ?>
                    <button type="button" class="btn btn-primary waves-effect" data-toggle="modal"
                        data-target="#defaultModal" onClick="editartenencia<?php echo $row['id']; ?>()">
                        <i class="material-icons" style="font-size: 15px;">create</i>
                    </button>
                    <?php } ?>
                </td>
                <td style="border-radius:0px 0px 0px 0px"><?php echo utf8_encode($row['estado']); ?></td>
                <td style="border-radius:0px 0px 0px 0px"><?php echo $row['annio']; ?></td>
                <td style="border-radius:0px 0px 0px 0px"><?php echo $row['factura']; ?></td>
                <td style="border-radius:0px 30px 30px 0px"><?php
                    if ($row['archivo'] == "SIN IMAGEN") {
                        echo "SIN ARCHIVO";
                    } else{
                        echo '<a href="'. $row['archivo']. '" target=”_blank”>' .$row['annio']. '</a>';
                    } ?>
                </td>
                <script type="text/javascript">
                function editartenencia<?php echo $row['id']; ?>(modal) {
                    var options = {
                        modal: true,
                        height: 300,
                        width: 920
                    };
                    $('#conte-modal4').load('modificartenencia.php?my_modal=' + modal +
                        '&id=<?php echo $row['id'];?>',
                        function() {
                            $('#bootstrap-modal4').modal({
                                show: true,
                                backdrop: 'static',
                                keyboard: false
                            });
                            $("#bootstrap-modal4").on('hidden.bs.modal', function() {
                                $('.doc').click();
                            });
                        });
                }
                </script>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="actions clearfix">
        <div class="header" role="tab" role="button" data-toggle="collapse">
            <?php if ($arr1[25] == 1  AND $sitiocambio == $sitioSession) { ?>
            <br>
            <ul class="header-dropdown m-r--5" style="margin:-8px 0px;">
                <?php if ($publicacion_validar_result == 0) { ?>
                <li>
                    <button type="button" class="btn btn-default waves-effect" data-toggle="modal"
                            data-target="#defaultModal" onClick="Nuevatenenecia()">
                        <i class="material-icons" style="color: #FF7800;">add</i>
                        <span>Agregar</span>
                    </button>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div_recibo" style="display:none;">
    <div class="col-md-12 " style="text-align: right;">
        <span class="ocultar_guia" style="color: black;"><?=btn1('guia10')?></span>
    </div>
    <form id="formularioContacto" method="post" action="altadocumentacion.php" target="_blank">
        <input type="hidden" name="vehiculoid" id="vehiculoid" value="<?php echo $id ?>">
        <div class="col-sm-3">
            <div class="form-group zz1">
                <input type="checkbox" id="altaplaca" name="altaplaca" value="1" class="chk-col-red" />
                <label for="altaplaca">ALTA DE PLACA</label>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group zz2">
                <div class="form-line">
                    <select class="require" title="Estado" id="estadorec" name="estadorec" style="width: 95%;"
                        required="true" disabled="true">
                        <?php
                                        $query = $mysqli -> query ("SELECT * FROM tipostatus WHERE tipo = 20 order by descripcion ASC");
                                            echo'<option>Estado</option>';
                                            while ($valores = mysqli_fetch_array($query)) {
                                            echo '<option value="'.$valores[id].'">'.utf8_encode($valores[descripcion]).'</option>';
                                        }
                                    ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group zz3">
                <label>¿Cuenta con duplicado de llave?</label>
                <input name="group4" type="radio" id="radio_7" value="1" class="radio-col-red" />
                <label for="radio_7">SI</label>
                <input name="group4" type="radio" id="radio_8" value="0" class="radio-col-red" />
                <label for="radio_8">NO</label>
            </div>
        </div>
        <div class="row clearfix js-sweetalert">
            <?php if ($sitiocambio == $sitioSession) { ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <button id="pruebaa" type="submit" class="btn btn-primary m-t-15 col-md-offset-10 waves-effect zz4"><i
                        class="material-icons">save</i>Guardar</button>
            </div>
            <?php } ?>
        </div>
    </form>
</div>


<script src="js/select2.js"></script>

<div class="modal fade" id="bootstrap-modal1" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal contenido-->
        <div class="modal-content">
            <div class="modal-header"
                style="background:linear-gradient(#E64E1B, #FF7800); border-radius:30px 30px 0px 0px; ">
                <h4 class="modal-title" id="defaultModalLabel" style="color: white; font-size:17px; top: 50%; ">FACTURA
                    NUEVA DE
                    <?php echo $vehiculo ?> <span id="g1" class="ocultar_guia" style="color:
                      black;"><?=btn1('guia2')?></span></h4>
            </div>
            <form id="formularioContactoaltafac" method="post" action="altafactura.php" enctype="multipart/form-data">
                <div class="modal-body stylefac">
                    <div id="conte-modal1"></div>
                </div>
                <div class="modal-footer" style="background:transparent;">
                    <?php if ($sitiocambio == $sitioSession) { ?>
                    <button type="submit" class="btn btn-primary aa7"
                        onclick="envioAjax('altafactura.php','formularioContactoaltafac','post','.resultado')"><i
                            class="material-icons">save</i>Guardar</button>
                    <button id="c1" type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i
                            class="material-icons">clear</i>Cerrar</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bootstrap-modal2" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal contenido-->
        <div class="modal-content">
            <div class="modal-header"
                style="background:linear-gradient(#E64E1B, #FF7800); border-radius:30px 30px 0px 0px; ">
                <h4 class="modal-title" id="defaultModalLabel" style="color: white; font-size:17px; top: 50%; ">
                    TENENCIAS NUEVAS DE
                    <?php echo $vehiculo ?> <span class="ocultar_guia" style="color:
                      black;"><?=btn1('guia3')?></span></h4>
            </div>
            <form id="formularioContactoaltaTen" method="post" action="altatenencia.php" enctype="multipart/form-data">
                <div class="modal-body stylecontratos">
                    <div id="conte-modal2"></div>
                </div>
                <div class="modal-footer" style="background:transparent;">
                    <?php if ($sitiocambio == $sitioSession) { ?>
                    <button type="submit" id="guardartenencia" class="btn btn-primary aaa5" onclick="envioAjaxMB('altatenencia.php','formularioContactoaltaTen','post','.resultado')">
                        <i
                            class="material-icons">save</i>Guardar</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i
                            class="material-icons">clear</i>Cerrar</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bootstrap-modal3" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal contenido-->
        <div class="modal-content">
            <div class="modal-header"
                style="background:linear-gradient(#E64E1B, #FF7800); border-radius:30px 30px 0px 0px; ">
                <h4 class="modal-title" id="defaultModalLabel" style="color: white; font-size:20px; top: 50%; ">EDITAR
                    FACTURA DE
                    <?php echo $vehiculo ?> <span class="ocultar_guia" style="color:
                      black;"><?=btn1('guia4')?></span></h4>
            </div>
            <form id="formularioContactoaltafacedit" method="post" action="updatefactura.php"
                enctype="multipart/form-data">
                <div class="modal-body stylefac">
                    <div id="conte-modal3"></div>
                </div>
                <div class="modal-footer" style="background:transparent;">
                    <?php if ($sitiocambio == $sitioSession) { ?>
                    <button type="submit" class="btn btn-primary aaaa7" onclick="envioAjax('updatefactura.php',
                            'formularioContactoaltafacedit','post','.resultado')"><i
                            class="material-icons">save</i>Guardar</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i
                            class="material-icons">clear</i>Cerrar</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bootstrap-modal4" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal contenido-->
        <div class="modal-content">
            <div class="modal-header"
                style="background:linear-gradient(#E64E1B, #FF7800); border-radius:30px 30px 0px 0px; ">
                <h4 class="modal-title" id="defaultModalLabel" style="color: white; font-size:17px; top: 50%; ">EDITAR
                    TENENCIA DE
                    <?php echo $vehiculo ?> <span class="ocultar_guia" style="color:
                      black;"><?=btn1('guia5')?></span></h4>
            </div>
            <form id="formularioeditTenencia" method="post" action="updatetenencia.php" enctype="multipart/form-data">
                <div class="modal-body stylecontratos">
                    <div id="conte-modal4"></div>
                </div>
                <div class="modal-footer" style="background:transparent;">
                    <?php if ($sitiocambio == $sitioSession) { ?>
                    <button type="submit" class="btn btn-primary aaaaa5" onclick="envioAjaxMB('updatetenencia.php',
                            'formularioeditTenencia','post','.resultado')"><i
                            class="material-icons">save</i>Guardar</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i
                            class="material-icons">clear</i>Cerrar</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
function Nuevafactura(modal) {
    var options = {
        modal: true,
        height: 300,
        width: 800
    };
    $('#conte-modal1').load('factura.php?my_modal=' + modal + '&id=<?php echo $id ?>', function() {
        $('#bootstrap-modal1').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
        $("#bootstrap-modal1").on('hidden.bs.modal', function() {
            $('.doc').click();
        });
    });
}

function Nuevatenenecia(modal) {
    var options = {
        modal: true,
        height: 300,
        width: 800
    };
    $('#conte-modal2').load('tenencia.php?my_modal=' + modal + '&id=<?php echo $id ?>', function() {
        $('#bootstrap-modal2').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
        $("#bootstrap-modal2").on('hidden.bs.modal', function() {
            $('.doc').click();
        });
    });
}

$('input[type=checkbox]').on('change', function() {
    if ($(this).is(':checked')) {
        document.getElementById('estadorec').disabled = false;
    } else {
        document.getElementById('estadorec').disabled = true;
    }
});

$(document).ready(function() {
    $('#estadorec').select2();
});


function event_documentos(tipo) {
    //alert(tipo);
    if (tipo == 'facturas') {
        $("#facturas").removeClass("color_inactive");
        $("#facturas").addClass("color_active");
        $("#div_facturas").css("display", "");
        $("#div_tenencia").css("display", "none");
        $("#div_recibo").css("display", "none");
        $("#tenencias").removeClass("color_active");
        $("#tenencias").addClass("color_inactive");
        $("#carta").removeClass("color_active");
        $("#carta").addClass("color_inactive");
        $("#recibo").removeClass("color_active");
        $("#recibo").addClass("color_inactive");



    } else if (tipo == 'tenencias') {
        $("#tenencias").removeClass("color_inactive");
        $("#tenencias").addClass("color_active");
        $("#facturas").removeClass("color_active");
        $("#facturas").addClass("color_inactive");
        $("#carta").removeClass("color_active");
        $("#carta").addClass("color_inactive");
        $("#recibo").removeClass("color_active");
        $("#recibo").addClass("color_inactive");
        $("#div_facturas").css("display", "none");
        $("#div_facturas").css("display", "none");
        $("#div_tenencia").css("display", "");
        $("#div_recibo").css("display", "none");

    } else if (tipo == 'carta') {
        $("#carta").removeClass("color_inactive");
        $("#carta").addClass("color_active");
        $("#facturas").removeClass("color_active");
        $("#facturas").addClass("color_inactive");
        $("#tenencias").removeClass("color_active");
        $("#tenencias").addClass("color_inactive");
        $("#recibo").removeClass("color_active");
        $("#recibo").addClass("color_inactive");
        $("#div_facturas").css("display", "none");
        $("#div_tenencia").css("display", "none");
        $("#div_recibo").css("display", "none");

    } else if (tipo == 'recibo') {
        $("#recibo").removeClass("color_inactive");
        $("#recibo").addClass("color_active");
        $("#facturas").removeClass("color_active");
        $("#facturas").addClass("color_inactive");
        $("#tenencias").removeClass("color_active");
        $("#tenencias").addClass("color_inactive");
        $("#carta").removeClass("color_active");
        $("#carta").addClass("color_inactive");
        $("#div_facturas").css("display", "none");
        $("#div_tenencia").css("display", "none");
        $("#div_recibo").css("display", "");

    }
}
</script>


<script>
function guia2() {

    $(document).on('click', '.g-modal-close', function() {

        tab();

    });

    document.onkeydown = function(e) {
        console.log(e.which);
        if (e.which == 9) {
            return false;
        }
    };


    myTour2();
    return false;
}

var preseOpt = {

    keyboard: false,
    tourMap: {
        open: false
    },
    intro: {
        title: 'AGREGAR UN COMPROBANTE DE FACTURA PASO A PASO ',
        width: 500,
    },
    continue: { //Default the continue message settings
        enable: false,
    },
    create: function() {

        $('.g-modal-map').hide();
        $('.gMapAction').hide();
        $.checkId = function() {};
        $.checkValue = function(target, val) {
            if (jQuery.trim(target.val()) != val) {
                return false;
            } else {
                return true;
            }
        }
    },
    steps: [{
        before: function() {
            $(".status1").addClass("g-modal-next");
            $(".g-modal-next").removeClass("status1");
            $('#tipofac').select2('open');
            $(".g-modal-next").css("display", "inline");

        },
        title: 'TIPO',
        content: 'Selecciona tipo de factura <br> <span id="menu1" style="display: none"><button class="btn ' +
            'btn-primary" onclick="menu1()"> Abrir Menu </button></span>',
        target: '.select2-dropdown',
        event: ['change', '.aa1'],
        waitElementTime: 3000,
        position: 'rcc',
        autofocus: true
    }, {
        before: function() {
            $(".status1").addClass("g-modal-next");
            $(".g-modal-next").removeClass("status1");
        },
        title: 'EXPEDIDA POR ',
        content: 'Ingresa nombre de la razón social que emitió la factura ',
        target: '.aa2',
        checkNext: {
            func: function(target) {
                return !$.checkValue(target, '');
            },
            messageError: 'Campo Obligatorio!'
        },
        position: 'rcc',
        autofocus: true
    }, {
        before: function() {
            $(".status1").addClass("g-modal-next");
            $(".g-modal-next").removeClass("status1");
        },
        title: 'FOLIO',
        content: 'Ingresa número de folio de la factura ',
        target: '.aa3',
        checkNext: {
            func: function(target) {
                return !$.checkValue(target, '');
            },
            messageError: 'Campo Obligatorio!'
        },
        position: 'rcc',
        autofocus: true
    }, {
        before: function() {
            $(".status1").addClass("g-modal-next");
            $(".g-modal-next").removeClass("status1");
        },
        title: 'FECHA DE EXPEDICION',
        content: ' Ingresa la fecha de expedición de la factura ',
        target: '.aa4',
        checkNext: {
            func: function(target) {
                return !$.checkValue(target, '');
            },
            messageError: 'Campo Obligatorio!'
        },
        position: 'rcc',
        autofocus: true
    }, {
        before: function() {
            $('#statusfac').select2('open');
            $(".status1").addClass("g-modal-next");
            $(".g-modal-next").removeClass("status1");
        },
        title: 'STATUS',
        content: ' Selecciona el status de la factura <br> <span id="menu2" style="display: none"><button class="btn ' +
            'btn-primary" onclick="menu2()"> Abrir Menu </button></span>',
        target: '.select2-dropdown',
        event: ['change', '.aa5'],
        waitElementTime: 3000,
        position: 'rcc',
        autofocus: true
    }, {
        before: function() {
            $(".status1").addClass("g-modal-next");
            $(".g-modal-next").removeClass("status1");
        },
        title: 'ARCHIVO',
        content: 'Selecciona y Suba el comprobante de la factura ( No obligatorio ) ',
        target: '.aa6',
        position: 'rcc',
        autofocus: true
    }, {
        before: function() {

        },
        title: 'GUARDAR',
        content: '',
        target: '.aa7',
        position: 'rcc',

    }],
}


$('.aa1').on('select2:closing', function(evt) {

    if ($("#tipofac").val() == null || $("#tipofac").val() == '') {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');

        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu1").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $(".g-modal-next").removeClass("status1");

        $("#menu1").css("display", "none");
    }

});

$('.aa5').on('select2:closing', function(evt) {

    if ($("#statusfac").val() == null || $("#statusfac").val() == '') {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio  para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');

        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu2").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $(".g-modal-next").removeClass("status1");

        $("#menu2").css("display", "none");
    }

});


function myTour2() {
    iGuider(preseOpt);
}

function menu1() {
    $('#tipofac').select2('open');
}

function menu2() {
    $('#statusfac').select2('open');
}
</script>


<script>
function guia10() {

    $(document).on('click', '.g-modal-close', function() {

        tab();

    });


    document.onkeydown = function(e) {
        console.log(e.which);
        if (e.which == 9) {
            return false;
        }
    };

    myTour10();
    return false;
}

var preseOpt10 = {

    keyboard: false,
    tourMap: {
        open: false
    },
    intro: {
        width: 500,
    },
    continue: { //Default the continue message settings
        enable: false,
    },
    create: function() {

        $('.g-modal-map').hide();
        $('.gMapAction').hide();
        $.checkId = function() {};
        $.checkValue = function(target, val) {
            if (jQuery.trim(target.val()) != val) {
                return false;
            } else {
                return true;
            }
        }
    },
    steps: [{
        before: function() {
            $('#estadorec').select2();
        },
        title: 'ALTA DE PLACA',
        content: 'Marcar si realizara alta de placa',
        target: '.zz1',
        position: 'rcc',
        autofocus: true
    }, {
        before: function() {
            $('#estadorec').select2('open');
        },
        title: 'ESTADO ',
        content: 'Selecciona el estado ',
        target: '.select2-dropdown',
        event: ['change', '.zz2'],
        waitElementTime: 3000,
        position: 'rcc',
        autofocus: true
    }, {
        before: function() {
            $('#estadorec').select2();
        },
        title: 'DUPLICADO DE LLAVE',
        content: 'Selecciona si cuenta con duplicado de llaves ',
        target: '.zz3',
        autofocus: true
    }, {
        before: function() {
            document.getElementById("pruebaa").scrollIntoView();

        },
        title: 'GUARDAR',
        content: '',
        target: '.zz4',
        position: 'rcc',
    }],
}

$('.zz2').on('select2:closing', function(evt) {
    if ($("#estadorec").val() == null) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio!para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");

    } else {
        $(".status1").addClass("g-modal-next");
    }
});

function myTour10() {
    iGuider(preseOpt10);
}
</script>

<script>
function tab() {
    document.onkeydown = function(e) {
        var ev = document.all;
        if (ev.keyCode == 9) {

        }
    }
}
</script>
<script src="js/pages/tables/jquery-datatable.js"></script>