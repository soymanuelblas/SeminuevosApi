<?php
include "variables.php";
include "funciones.php";
$sitioSession = $_SESSION["sitio"];


include 'conexion1.php';
$db_handle = new DBController();

if($sitioSession != "1"){
    $sqlMB = "SELECT * FROM `operacion_caja` WHERE `id` IN(3,5,7,9,10,45)
UNION
SELECT * FROM `operacion_caja` WHERE `id` IN(11,26,27)
UNION
SELECT * FROM `operacion_caja` WHERE `razonsocial_id` = $razon_social ";

    $vehiculo1 = "SELECT vehiculo.id AS id,
CONCAT('(',vehiculo.noexpediente,')',' ', tipomarca.descripcion,' ', tipomodelo.descripcion,' ',version.descripcion,' ', tipoannio.descripcion,' (',vehiculo.numeroserie,') ', tipostatus.descripcion ) AS vehiculo 
        FROM vehiculo
        LEFT JOIN version ON vehiculo.version_id = version.id
        LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id
        LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id
        LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id 
        LEFT JOIN tipostatus ON vehiculo.color_id = tipostatus.id ";
    if ($arr1[152] == 1) {
        $vehiculo1 .= " WHERE vehiculo.sitio_id = '$sitiocambio' ORDER BY vehiculo.noexpediente ASC";
    }else if ($arr1[153] == 1) {
        $vehiculo1 .= " WHERE vehiculo.sitio_id IN('".$sitios_razon_social."') ORDER BY vehiculo.noexpediente ASC";
    }

    $cliente1 = "SELECT clientes.id AS id, clientes.nombre AS nombre, tipostatus.descripcion AS tipocliente FROM `clientes` LEFT JOIN tipostatus ON clientes.tipocliente_id = tipostatus.id WHERE clientes.razonsocial_id = '$razon_social' AND clientes.tipostatus_id = '851' ORDER BY nombre ASC";

    $proveedor1 ="SELECT clientes.id  AS id, clientes.nombre AS nombre, tipostatus.descripcion AS tipocliente FROM `clientes` LEFT JOIN tipostatus ON clientes.tipocliente_id = tipostatus.id WHERE razonsocial_id = '$razon_social' AND clientes.tipostatus_id = '851'  OR nombre='PROMOTORA AUTOS DEL CAMPESTRE SA DE CV'  ORDER BY nombre ASC";
}
else {
    $sqlMB = "SELECT * FROM `operacion_caja` WHERE `valor` = 0 AND operacion_caja.descripcion != 'CORTE DE CAJA' AND operacion_caja.descripcion != 'INICIO DE CUENTA' ORDER BY descripcion ASC ";

    $vehiculo1 = "SELECT vehiculo.id AS id,
CONCAT('(',vehiculo.noexpediente,')',' ', tipomarca.descripcion,' ', tipomodelo.descripcion,' ',version.descripcion,' ', tipoannio.descripcion,' (',vehiculo.numeroserie,') ', tipostatus.descripcion ) AS vehiculo 
        FROM vehiculo
        LEFT JOIN version ON vehiculo.version_id = version.id
        LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id
        LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id
        LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id 
        LEFT JOIN tipostatus ON vehiculo.color_id = tipostatus.id ";

    $vehiculo1 .= "ORDER BY vehiculo.noexpediente ASC";

    $cliente1 = "SELECT clientes.id AS id, clientes.nombre AS nombre, tipostatus.descripcion AS tipocliente FROM `clientes` LEFT JOIN tipostatus ON clientes.tipocliente_id = tipostatus.id WHERE clientes.tipostatus_id = '851' ORDER BY nombre ASC";

    $proveedor1 ="SELECT clientes.id  AS id, clientes.nombre AS nombre, tipostatus.descripcion AS tipocliente FROM `clientes` LEFT JOIN tipostatus ON clientes.tipocliente_id = tipostatus.id WHERE clientes.tipostatus_id = '851'  OR nombre='PROMOTORA AUTOS DEL CAMPESTRE SA DE CV'  ORDER BY nombre ASC";
}

$operacion = $db_handle->runQuery($sqlMB);

$vehiculo = $db_handle->runQuery($vehiculo1);

$cliente = $db_handle->runQuery($cliente1);

$proveedor = $db_handle->runQuery($proveedor1);

$pago = $db_handle->runQuery("SELECT * FROM `tipostatus` WHERE tipo = 54 AND id != '5214' AND id != '5211'");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Tubisne</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />
    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <?= guiacss(); ?>
    <style>
        @media (orientation: landscape) {
            .div_margin {
                margin-left: -45px !important;
            }
    }
    </style>
</head>

<body class="theme-red">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Un momento, por favor...</p>
        </div>
    </div>
    <div class="overlay"></div>
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="index.php">Administración de 
                    <?php 
                    if ($sitiocambio != '' AND $sitioNombrecambio != '') { 
                        echo $sitioNombrecambio;
                    }else{
                        echo $sitioNombre;
                    }

                    ?>
                </a>
            </div>
        </div> 
    </nav>
    <section>
        <style type="text/css">
    
 
 .largo{
       width: 220%;
   }
.largo1{
       width: 920%;
   }

 @media only screen and (max-width: 900px) {
         .largo{
       width: 220%;
   }

   .largo1{
       width: 920%;
   }

  }

@media only screen and (min-width: 300px) and (max-width: 900px) {

    .ocultar{
        display: none;
    }
}

@media only screen and (min-width: 900px) and (max-width: 5080px) {

    .ocultar1{
        display: none;
    }
}

@media only screen and (max-width: 900px) {
            .filtros{
                display:none;
            }

            .agregar{
                display:none;
            }
        }



</style>
        <aside id="leftsidebar" class="sidebar">
            <?php
error_reporting(0);
     if(isset($_SESSION["nombre"])){
}        
            $active4 = 'active';
            $admincaja = 'active';
            $caja5 = 'active';
            include "menu.php";
?>
            <div class="legal">
                <div class="copyright">
                    &copy; 2019 <a href="javascript:void(0);">Developers - Autobahn</a>.
                </div>
            </div>
        </aside>
    </section>
    <section class="content">
        <div class="container-fluid">
            <!-- Basic Examples -->
            <div class="row clearfix div_margin" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <?php
                        //print_r($proveedor1);
                        ?>
                        <div class="header">
                            <h2>
                                MOVIMIENTOS
                            </h2>
                            <ul class="header-dropdown m-r--5" style="margin:-8px 0px;">
                                <li class="dropdown">
                                    <button type="button" class="btn btn-primary waves-effect" id="mas" name="mas">
                                        <i class="material-icons" style="color: white;">search</i>
                                        <span class="filtros">Buscar</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="body hoja1" style="display: none;">
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <p><b>Rango de Fechas</b></p>
                                            <input class="form-control" type="text" name="dates" id="dates" autocomplete="off" style="text-align: center;" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <p>
                                        <b>Operación</b>
                                    </p> 
                                    <select placeholder="Operacion" name="operacion[]" id="operacion"  class="select2" multiple data-live-search="true" style="width: 90%; border: none;" onchange="validar()">
                                             <?php
                                                if (! empty($operacion)) {
                                                    foreach ($operacion as $key => $value) {
                                                        echo '<option value="' . ($operacion[$key]['id']).'">'.utf8_encode($operacion[$key]['descripcion']).'</option>';
                                                    }
                                                }
                                            ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <p>
                                        <b>Vehiculo</b>
                                    </p>
                                     <select placeholder="Vehiculo" name="vehiculo[]" id="vehiculo" class="select2" multiple data-live-search="true" style="width: 90%;" onchange="validar()" >
                                             <?php
                                                if (! empty($vehiculo)) {
                                                    foreach ($vehiculo as $key => $value) {
                                                        echo '<option value="' . ($vehiculo[$key]['id']).'">'.utf8_encode($vehiculo[$key]['vehiculo']).'</option>';
                                                    }
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <p>
                                        <b>Cliente</b>
                                    </p>
                                     <select placeholder="Cliente" name="cliente[]" id="cliente" class="select2" multiple data-live-search="true" style="width: 90%;" onchange="validar()">
                                             <?php
                                                if (! empty($cliente)) {
                                                    foreach ($cliente as $key => $value) {
                                                        echo '<option value="' . ($cliente[$key]['id']).'">'.($cliente[$key]['nombre']).'('.($cliente[$key]['tipocliente']).')</option>';
                                                    }
                                                }
                                            ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <p>
                                        <b>Proveedor</b>
                                    </p>
                                     <select placeholder="Proveedor" name="proveedor[]" id="proveedor" class="select2" multiple data-live-search="true" style="width: 90%;" onchange="validar()">
                                             <?php
                                                if (! empty($proveedor)) {
                                                    foreach ($proveedor as $key => $value) {
                                                        echo '<option value="' . ($proveedor[$key]['id']).'">'.($proveedor[$key]['nombre']).'('.($proveedor[$key]['tipocliente']).')</option>';
                                                    }
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <p>
                                        <b>Status de Pago</b>
                                    </p>
                                     <select placeholder="Status Pago" name="pago[]" id="pago" class="select2" multiple data-live-search="true" style="width: 90%;" onchange="validar()">
                                             <?php
                                                if (! empty($pago)) {
                                                    foreach ($pago as $key => $value) {
                                                        echo '<option value="' . ($pago[$key]['id']).'">'.utf8_encode($pago[$key]['descripcion']).'</option>';
                                                    }
                                                }
                                            ?>
                                    </select>
                                </div>
                          </div>
                          <button class="btn btn-primary waves-effect" disabled="true" id="aplicar">Aplicar Filtro</button>
                          <button class="btn btn-primary waves-effect" disabled="true" id="borrar">Limpiar Filtros </button>

                            <span  class="ocultar_guia" style="color:black;"><?= btn2('guiaA') ?></span>
                        
                        </div>
                        <div class="movimientos" id="movimientos"></div>
                    </div>
                </div>
            <!-- #END# Basic Examples -->
        </div>
    </section>
      <script type="text/javascript">
        $('input[name="dates"]').daterangepicker({
      autoUpdateInput: false
  });

        $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
      aplicar.disabled = false;
  });

  $('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
      aplicar.disabled = true;
  });

  $(document).ready(function(){
            $('#operacion').select2();
    });
     $(document).ready(function(){
            $('#vehiculo').select2();
    });
      $(document).ready(function(){
            $('#cliente').select2();
    });
        $(document).ready(function(){
            $('#proveedor').select2();
    });
        $(document).ready(function(){
            $('#pago').select2();
    });

        $(document).ready(function(){
        inicio = 0;

        $('#mas').on('click',function(){
            if (inicio == 0) {
                
            $('.hoja1').slideDown();
            inicio++;
            }else{
            $('.hoja1').slideUp();
            inicio=0;
            }
        });
    });






  function validar(){

    dates = document.getElementById("dates").value;
    operacion = document.getElementById("operacion").value;
    vehiculo = document.getElementById("vehiculo").value;
    cliente = document.getElementById("cliente").value;
    proveedor = document.getElementById("proveedor").value;
    pago = document.getElementById("pago").value;
    

    if (dates != '' || operacion != '' || vehiculo != '' || cliente != '' || proveedor != '' || pago != '') {
        aplicar.disabled = false;
    }else{
        aplicar.disabled = true;
    }
}

$("#aplicar").click(function() {

  $('#movimientos').show();
  var dates = $('#dates').val();
  var operacion = $('#operacion').val();
  var vehiculo = $('#vehiculo').val();
  var cliente = $('#cliente').val();
  var proveedor = $('#proveedor').val();
  var pago = $('#pago').val();

  var array = [dates, operacion, vehiculo, cliente, proveedor, pago];
            //alert(array);
            $('#movimientos').html('<div id="loading">Cargando .....</div>');
            $.ajax({ 
                url: "cargarfiltromovimientos.php", //Archivo de servidor que inserta en la BD 
                method: "POST", 
                data: {'array': JSON.stringify(array)},
                  success: function(data){
                    $("#movimientos").html(data);
                    $("html, body").animate({
                        scrollTop: 380
                        }, 1000); 
                    $("#borrar").prop('disabled', false);
                   }
                }); 
});

$("#borrar").click(function() {
    
    $('#movimientos').hide();
    $('#dates').val('');
    $('#operacion').val('');
    $('#vehiculo').val('').trigger('change.select2');
    $('#cliente').val('').trigger('change.select2');
    $('#proveedor').val('').trigger('change.select2');
    $('#pago').val('').trigger('change.select2');

    $("#borrar").prop('disabled', true);
    $("html, body").animate({
        scrollTop: 0
    }, 1000); 

});

    </script>
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="js/select2.js"></script>

    <div class="modal fade" id="bootstrap-modal1" role="dialog">
        <div class="modal-dialog" role="document"> 
            <!-- Modal contenido-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Nueva Orden de Servicio</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 1cm; color: red;"> <span aria-hidden="true">&times;</span> </button>
                </div>
                <div class="modal-body">
                    <div id="conte-modal1"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function nuveaorden(modal){
    var options = {
        modal: true,
        height:300,
        width:800
      };
    $('#conte-modal1').load('nuevodiagnostico.php?my_modal='+modal, function() {
      $('#bootstrap-modal1').modal({show:true,backdrop: 'static', keyboard: false});
      });    
  }   
    </script>
    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>
    
    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>
    <!-- Jquery DataTable Plugin Js -->
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>
    <!-- Demo Js -->
    <script src="js/demo.js"></script>

    <?= guiascript(); ?>

    <script>
        $( "#guiaA" ).click(function() {
            myTour12();
            return false;
        });

        var preseOpt12 = {

            keyboard: false,
            tourMap: {
                open: false
            },
            lang: {
                introDialogBtnStart:'Finalizar',
                introDialogBtnCancel:'Cancelar',
            },
            intro: {
                title: 'BUSCAR UN MOVIMIENTO PASO A PASO ',
                content: '* Rango de fechas: Seleccionar un rango de fechas, para una búsqueda más precisa <br>' +
                    '* Operacion: Seleccionar multiples operaciones, para una búsqueda más precisa <br>' +
                    '* Vehículo: Selecionar multiples vehículos, para una búsqueda más precisa <br>' +
                    '* Cliente: Selecionar multiples clientes, para una búsqueda más precisa <br>' +
                    '* Proveedor: Selecionar multiples proveedores, para una búsqueda más precisa <br>' +
                    '* Status pago: Selecionar multiples status, para una búsqueda más precisa <br>' +
                    'Al tener algún campo completo, hacer click en Aplicar Filtros donde mostrara una tabla con el resultado ingresado ' +
                    '<br>' +
                    'Al finalizar las búsquedas podra limpiar los filtros dando click en Limpiar Filtros',
                cover:'./images/ayuda/movimientos.png',
                width: 900,
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
                title: 'BUSCADOR',
                content: '',
                target: '.xasxa',
            }],
        }

        function myTour12() {
            iGuider(preseOpt12);
        }

    </script>

</body>

</html>