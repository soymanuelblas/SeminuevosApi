<?php
include "variables.php";
include "funciones.php";
date_default_timezone_set("America/Mexico_City");

?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Tubisne</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

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
    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">
    <!-- JQuery Nestable Css -->
    <link href="plugins/nestable/jquery-nestable.css" rel="stylesheet" />
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />
    <link href="plugins/light-gallery/css/lightgallery.css" rel="stylesheet">
    <link href="plugins/morrisjs/morris.css" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <?= guiacss(); ?>
    <style type="text/css">
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
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->

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
            if(isset($_SESSION["nombre"])){}
            $active2 = 'active';
            //$adminreportes = 'active';
            $reportes9 = 'active';
            include "menu.php"; 
            ?>
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
    <section class="content">
        <div class="container-fluid">
            <!-- Basic Examples -->
            <div class="row clearfix div_margin" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2> OPORTUNIDAD</h2>
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
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <p><b>Rango de fechas</b></p>
                                            <input class="form-control" type="text" name="dates" id="dates"
                                                autocomplete="off" style="text-align: center;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary waves-effect" disabled="true" id="aplicar">Aplicar
                                Filtro</button>
                            <!--</form> -->
                            <button class="btn btn-primary waves-effect" disabled="true" id="borrar">Limpiar Filtros
                            </button>

                            <span class="ocultar_guia" style="color:black;"><?= btn2('guiaA') ?></span>
                            <br>
                            <br>
                            <div class="oporcompleto" id="oporcompleto"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Basic Examples -->


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
        proveedor = document.getElementById("proveedor").value;


        if (dates != '') {
            aplicar.disabled = false;
        } else {
            aplicar.disabled = true;
        }
    }

    $("#aplicar").click(function() {

        $('#oporcompleto').show();
        var dates = $('#dates').val();

        var array = [dates];
        //alert(array);
        $('#oporcompleto').html('<div id="loading">Cargando .....</div>');
        $.ajax({
            url: "cargarfiltrooporcompleto.php", //Archivo de servidor que inserta en la BD 
            method: "POST",
            data: {
                'array': JSON.stringify(array)
            },
            success: function(data) {
                $("#oporcompleto").html(data);
                $("html, body").animate({
                    scrollTop: 280
                }, 1000);
                $("#borrar").prop('disabled', false);
            }
        });
    });

    $("#borrar").click(function() {

        $('#oporcompleto').hide();
        $('#dates').val('');

        $("#borrar").prop('disabled', true);
        $("html, body").animate({
            scrollTop: 0
        }, 1000);

    });
    </script>



    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Light Gallery Plugin Js -->
    <script src="plugins/light-gallery/js/lightgallery-all.js"></script>
    <script src="js/pages/medias/image-gallery.js"></script>

    <!-- Morris Plugin Js -->
    <script src="plugins/raphael/raphael.min.js"></script>
    <!-- Chart Plugins Js -->
    <script src="plugins/chartjs/Chart.bundle.js"></script>
    <script src="plugins/morrisjs/morris.js"></script>


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
    $("#guiaA").click(function() {
        myTour();
        return false;
    });


    var preseOpt = {

        keyboard: false,
        tourMap: {
            open: false
        },
        lang: {
            introDialogBtnStart: 'Finalizar',
            introDialogBtnCancel: 'Cancelar',
        },
        intro: {
            title: 'BUSCAR OPORTUNIDAD PASO A PASO ',
            content: '* Rango de fechas: Seleccionar un rango de fechas, para una búsqueda más precisa <br>' +
                'Al tener algún campo completo, hacer click en Aplicar Filtros donde mostrara una tabla con el resultado ingresado ' +
                '<br>' +
                'Al finalizar las búsquedas podra limpiar los filtros dando click en Limpiar Filtros',
            cover: './images/ayuda/oportunidad.png',
            width: 900,
        },
        create: function() {

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
            title: 'BUSCADOR',
            content: '',
            target: '.xasxa',
        }],
    }

    function myTour() {
        iGuider(preseOpt);
    }
    </script>

</body>

</html>