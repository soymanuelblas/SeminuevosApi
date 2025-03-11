<?php
include "variables.php";

/*
if ($arr1[75] != 1 || $arr1[77] != 1 || $arr1[79] != 1 || $arr1[82] != 1 || $arr1[95] != 1) {
    header('Location: index.php');
}
*/

if ($arr1[75] != 1 || $arr1[77] != 1 || $arr1[79] != 1 || $arr1[82] != 1) {
    header('Location: index.php');
}

include 'conexion1.php';
$db_handle = new DBController();
$status = $db_handle->runQuery("SELECT * FROM tipostatus WHERE tipo = '51'");
$prioridadfiltro = $db_handle->runQuery("SELECT * FROM tipostatus WHERE tipo = '66'");
include "funciones.php";
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv                                                                                                                                                                                                                                                                                                    ="Content-Type" content="text/html; charset=utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Tubisne</title>
    <!-- Favicon-->
    <link rel="icon" href="images/clave.png" type="image/x-icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
        type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="plugins/nestable/jquery-nestable.css" rel="stylesheet" />
    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!--busqueda -->
    <link rel="stylesheet" type="text/css" href="buscar/css/select2.css">
    <script src="buscar/jquery-3.1.1.min.js"></script>
    <script src="buscar/js/select2.js"></script>

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <?= guiacss(); ?>


    <!-- Ajax js -->


    <script>
    function solonumeros(e) {

        key = e.keyCode || e.which;

        teclado = String.fromCharCode(key);

        numeros = "0123456789";

        especiales = "8-37-38-46"; //array

        teclado_especial = false;

        for (var i in especiales) {
            if (key == especiales[i]) {
                teclado_especial = true;
            }
        }

        if (numeros.indexOf(teclado) == -1 && !teclado_especial) {
            return false;
        }

    };
    </script>
    <style>
    .select1-editable {
        position: relative;
        border: none;
        width: 100%;
    }

    .select1-editable select {
        position: absolute;
        top: 0px;
        left: 0px;
        font-size: 14px;
        border: none;
        margin: 0;
    }

    .select1-editable input {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 95%;
        padding: 1px;
        font-size: 12px;
        border: none;
        text-align: left;
    }

    .select1-editable select:focus,
    .select1-editable input:focus {
        outline: none;
    }

    .modal-body {
        height: 500px;
        width: 100%;
        overflow-y: auto;
    }

    @media only screen and (max-width: 900px) {
        .filtros {
            display: none;
        }

        .agregar {
            display: none;
        }
    }

    @media (orientation: landscape) {
            .div_margin {
                margin-left: -45px !important;
            }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <p>Por Favor espera...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar-collapse" aria-expanded="false"></a>
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
            <?php
            include('navbarMBderecha.php');
            ?>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <?php
            error_reporting(0);
            if(isset($_SESSION["nombre"])){}
            $active2 = 'active';
            $prospectosmenu1 = 'active';
            $prospectossubmenu3 = 'active';
            include "menu.php";
            $idid  = $_SESSION["id"];
            ?>
            <!-- #User Info -->
            <!-- Menu -->
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2019 <a href="javascript:void(0);">Developers - Autobahn</a>.
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->

        <!-- #END# Right Sidebar -->
    </section>

    <section class="content b1">
        <div class="container-fluid">
            <!-- Basic Examples -->
            <div class="row clearfix div_margin" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>OPORTUNIDADES</h2>

                            <ul class="header-dropdown m-r--5" style="margin:-8px 0px;">
                                <li class="dropdown">
                                    <button type="button" class="btn btn-primary waves-effect" id="mas" name="mas">
                                        <i class="material-icons" style="color: white;">search</i>
                                        <span class="filtros">Buscar</span>
                                    </button>
                                </li>
                                <?php if ($arr1[77] == 1 AND $sitiocambio == $sitioSession) { ?>
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <button type="button" class="btn btn-default waves-effect" >
                                        <i class="material-icons" style="color: #FF7800;">add</i>
                                        <span class="agregar">Agregar</span>
                                    </button>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);" onClick="compra()">Cliente Comprar</a></li>
                                        <li><a href="javascript:void(0);" onClick="venta()">Cliente Vender</a></li>
                                    </ul>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>

                        <div class="body hoja1 " style="display: none;">
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <p><b>Rango de fechas Prox Contacto</b></p>
                                            <input class="form-control" type="text" name="dates" id="dates"
                                                autocomplete="off" style="text-align: center;" onclick="validar()">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <p> <b>Status</b> </p>
                                    <select placeholder="Operacion" name="status[]" id="status"
                                        class="selectpicker largo" multiple style="width: 100%;" onchange="validar()">
                                        <?php
                                         if (! empty($status)) {
                                            foreach ($status as $key => $value) {
                                                echo '<option value="' . ($status[$key]['id']).'">'.utf8_encode($status[$key]['descripcion']).'</option>';
                                            }
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <p> <b>Nombre o Teléfono</b> </p>
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control date" id="buscar" name="buscar"
                                                   placeholder="(Nombre, Telefono)" onchange="validar()">
                                        </div>
                                        <span class="input-group-addon">
                                            <i class="material-icons" style="color: #FF7800;">search</i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary waves-effect" disabled="true" id="aplicar">Aplicar
                                Filtro</button>
                            <!--</form> -->
                            <button class="btn btn-primary waves-effect" disabled="true" id="borrar">Limpiar Filtros
                            </button>

                            <!--<button class="btn btn-primary waves-effect" onclick="guiaA()"> Ayuda</button>-->
                            <span class="ocultar_guia" style="color:black;"><?= btn2('guiaA') ?></span>

                        </div>

                        <div class="oportunidad" id="oportunidad"></div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->
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
    </script>
    <?php 
        date_default_timezone_set("America/Mexico_City");
        $time = time();
    ?>


    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="js/select2.js"></script>



    <!--
    <div class="modal fade" id="bootstrap-modal1" role="dialog">
        <div class="modal-dialog" role="document"> 
            Modal contenido
            <div class="modal-content">
                <div class="modal-header" style="background:#9E9E9E;">
                    <h4 class="modal-title" id="defaultModalLabel" style="color: white;">NUEVO CLIENTE </h4>
                </div>
                <form id="formularioContactoalta" method="post" action="javascript:void(0)">
                    <div class="modal-body">
                        <div id="conte-modal1"></div>
                    </div>
                    <div class="modal-footer" style="background:transparent;">
                        <?php //if ($sitiocambio == $sitioSession) { ?>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <button type="submit" class="btn btn-success waves-effect" onclick="envioAjax('altacliente.php','formularioContactoalta','post','.resultado')"><i class="material-icons">save</i>Guardar</button>
                                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="material-icons">clear</i>Cerrar</button>
                            </div>
                        <?php //} ?> 
                    </div>
                </form>
            </div>
        </div>
    </div>
    -->

    <div class="modal fade" id="bootstrap-modal1" role="dialog">
        <div class="modal-dialog" role="document">
            <!-- Modal contenido-->
            <div class="modal-content">
                <div class="modal-header" style="background:linear-gradient(#E64E1B, #FF7800); border-radius:30px 30px 0px 0px; ">
                    <h4 class="modal-title" id="defaultModalLabel" style="color: white; font-size:20px; top: 50%; ">NUEVA OPORTUNIDAD (CLIENTE VENDER)<span
                                class="ocultar_guia" style="color:
                                  black;"><?=btn1('guia')?></span></h4>
                </div>
                <div class="modal-body">
                    <div id="conte-modal1"></div>
                </div>
                <div class="modal-footer" style="background:transparent;">
                    <?php if ($sitiocambio == $sitioSession) { ?>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <!--<button type="submit" class="btn btn-success waves-effect" onclick="envioAjax('altamoral.php','formularioContactoaltamoral','post','.resultado')"><i class="material-icons">save</i>Guardar</button>-->
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i
                                        class="material-icons">clear</i>Cerrar</button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bootstrap-modal2" role="dialog">
        <div class="modal-dialog" role="document">
            <!-- Modal contenido-->
            <div class="modal-content">
                <div class="modal-header" style="background:linear-gradient(#E64E1B, #FF7800); border-radius:30px 30px 0px 0px; ">
                    <h4 class="modal-title" id="defaultModalLabel" style="color: white; font-size:20px; top: 50%; ">NUEVA OPORTUNIDAD (CLIENTE COMPRAR)<span
                            class="ocultar_guia" style="color:
                                  black;"><?=btn1('guia')?></span></h4>
                </div>
                <div class="modal-body">
                    <div id="conte-modal2"></div>
                </div>
                <div class="modal-footer" style="background:transparent;">
                    <?php if ($sitiocambio == $sitioSession) { ?>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <!--<button type="submit" class="btn btn-success waves-effect" onclick="envioAjax('altamoral.php','formularioContactoaltamoral','post','.resultado')"><i class="material-icons">save</i>Guardar</button>-->
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i
                                class="material-icons">clear</i>Cerrar</button>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function() {
        $('#status').select2();
    });
    </script>

    <script type="text/javascript">
    function venta(modal){
    var options = {
        modal: true,
        height:300,
        width:800
      };
    /*
    $('#conte-modal1').load('agregarcliente.php?my_modal='+modal, function() {
      $('#bootstrap-modal1').modal({show:true,backdrop: 'static', keyboard: false});
      });
    */
        //2do Agregado
        $('#conte-modal1').load('comprar.php?my_modal='+modal, function() {
            $('#bootstrap-modal1').modal({show:true,backdrop: 'static', keyboard: false});
        });
  }

    function compra(modal) {
        var options = {
            modal: true,
            height: 300,
            width: 800
        };
        $('#conte-modal2').load('vender.php?my_modal=' + modal, function() {
            $('#bootstrap-modal2').modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
        });
    }

    $(document).ready(function() {
        inicio = 0;

        $('#mas').on('click', function() {
            if (inicio == 0) {

                $('.hoja1').slideDown();
                inicio++;
            } else {
                $('.hoja1').slideUp();
                inicio = 0;
            }
        });
    });

    function validar() {

        dates = document.getElementById("dates").value;
        status = document.getElementById("status").value;
        buscar = document.getElementById("buscar").value;


        if (dates != '' || status != '' || buscar != '') {
            aplicar.disabled = false;
        } else {
            aplicar.disabled = true;
        }
    }

    $("#aplicar").click(function() {

        $('#oportunidad').show();
        var dates = $('#dates').val();
        var status = $('#status').val();
        var buscar = $('#buscar').val();

        var array = [dates, status, buscar];
        //alert(array);
        $('#oportunidad').html('<div id="loading">Cargando .....</div>');
        $.ajax({
            url: "cargarfiltrooportunidad.php", //Archivo de servidor que inserta en la BD 
            method: "POST",
            data: {
                'array': JSON.stringify(array)
            },
            success: function(data) {
                $("#oportunidad").html(data);
                $("html, body").animate({
                    scrollTop: 100
                }, 1000);
                $("#borrar").prop('disabled', false);
            }
        });
    });

    $("#borrar").click(function() {

        $('#oportunidad').hide();
        $('#dates').val('');
        $('#status').val('').trigger('change.select2');
        $('#buscar').val('');

        $("#borrar").prop('disabled', true);
        $("html, body").animate({
            scrollTop: 0
        }, 1000);
        $("#aplicar").trigger("click");

    });
    </script>



    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
    <script src="plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
    <!-- Jquery Nestable -->
    <script src="plugins/nestable/jquery.nestable.js"></script>

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
    <script src="plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script type="text/javascript">
    $("#aplicar").trigger("click");
    </script>
    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>
    <script src="js/pages/ui/sortable-nestable.js"></script>
    <script src="plugins/nestable/jquery.nestable.js"></script>
    <script src="plugins/autosize/autosize.js"></script>
    <script src="js/pages/ui/notifications.js"></script>

    <!-- Demo Js -->
    <script src="js/demo.js"></script>

    <?= guiascript(); ?>

    <script>
    $("#guiaA").click(function() {
        myTour12();
        return false;
    });

    var preseOpt12 = {

        keyboard: false,
        tourMap: {
            open: false
        },
        lang: {
            introDialogBtnStart: 'Finalizar',
            introDialogBtnCancel: 'Cancelar',
        },
        intro: {
            title: 'BUSCAR OPORTUNIDADES DE PROSPECTO PASO A PASO ',
            content: '* Rango de fechas: Seleccionar un rango de fechas, para una búsqueda más precisa <br>' +
                '* Status: Seleccionar las opciones, para una búsqueda más precisa <br>' +
                '* Nombre, Telefono: Ingresa nombre o telefono del prospecto, para una búsqueda más precisa <br>' +
                'Al tener algún campo completo, hacer click en Aplicar Filtros donde mostrara una tabla con el resultado ingresado ' +
                '<br>' +
                'Al finalizar las búsquedas podra limpiar los filtros dando click en Limpiar Filtros',
            cover: './images/ayuda/op1.png',
            width: 900,
        },
        create: function() {
            /*$('.g-modal-map').hide();
            $('.gMapAction').hide();*/

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
            before: function() {},
            title: 'BUSCADOR',
            content: '',
            target: '.b1asxasxaa',

        }],
    }

    function myTour12() {
        iGuider(preseOpt12);
    }
    </script>

</body>

</html>