<?php
include "variables.php";
include "funciones.php";

$id = $_GET['id'];

$sql = "SELECT vehiculo.id,vehiculo.version_id AS idversion, version.descripcion as version, vehiculo.noexpediente, vehiculo.numeroserie, 
     vehiculo.tipostatus_id AS statusvehiculoid, statusvehiculo.descripcion AS statusvehiculo , venta.id AS ventaid , venta.descripcion AS venta, vehiculo.precio,vehiculo.kilometraje, vehiculo.sitio_id AS sitioid, sitio.domicilio1 AS domicilio, vehiculo.tipo_vehiculo AS tipoid, tipo.descripcion AS tipovehiculo, vehiculo.color_id AS colorid, color.descripcion AS color, vehiculo.nomotor AS motor, vehiculo.fecha AS fecha, vehiculo.duenio AS duenioid, duenio.descripcion AS duenio, vehiculo.garantia AS garantiaid, garantia.descripcion AS garantia, vehiculo.precio_contado AS contado, vehiculo.numero_placa AS placa, vehiculo.duplicado AS duplicadoid, duplicado.descripcion AS duplicado,  vehiculo.observaciones, tipomarca.descripcion AS marca, tipoannio.descripcion AS annio ,tipomodelo.descripcion AS modelo 
        FROM vehiculo 
        LEFT JOIN tipostatus AS venta ON vehiculo.status_venta = venta.id
        LEFT JOIN version AS version ON vehiculo.version_id = version.id
        LEFT JOIN sitio AS sitio ON vehiculo.sitio_id = sitio.id
        LEFT JOIN tipostatus AS tipo ON vehiculo.tipo_vehiculo = tipo.id
        LEFT JOIN tipostatus AS color ON vehiculo.color_id = color.id
        LEFT JOIN tipostatus AS statusvehiculo ON vehiculo.tipostatus_id = statusvehiculo.id
        LEFT JOIN tipostatus AS duenio ON vehiculo.duenio = duenio.id 
        LEFT JOIN tipostatus AS garantia ON vehiculo.garantia = garantia.id
        LEFT JOIN tipostatus AS duplicado ON vehiculo.duplicado = duplicado.id
        LEFT JOIN tipomodelo ON version.tipomodelo_id = tipomodelo.id
        LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id
        LEFT JOIN tipoannio ON version.tipoannio_id = tipoannio.id WHERE vehiculo.id = '$id'";
$resultado = $mysqli->query($sql);
$row = $resultado->fetch_array(MYSQLI_ASSOC);

$vehiculo = $row[noexpediente].' '.$row[marca].' '.$row[modelo].' '.$row[annio].' '.$row[version];
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tubisne</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Sweet Alert Css -->
    <link href="plugins/sweetalert/sweetalert.css" rel="stylesheet" />
    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />
    <!-- Bootstrap Tagsinput Css -->
    <link href="plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <!-- Wait Me Css -->
    <link href="plugins/waitme/waitMe.css" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />
    <!-- Ajax js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="js/ajax.js"></script>
    <!--busqueda -->
    <link rel="stylesheet" type="text/css" href="buscar/css/select2.css">
    <script src="buscar/jquery-3.1.1.min.js"></script>
    <script src="buscar/js/select2.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script
            src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js">
    </script>
    <!-- Morris Chart Css-->
    <link href="plugins/morrisjs/morris.css" rel="stylesheet" />
    <!-- Dropzone Css -->
    <link href="plugins/dropzone/dropzone.css" rel="stylesheet">
    <!-- Bootstrap Spinner Css -->
    <link href="plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">
    <!-- noUISlider Css -->
    <link href="plugins/nouislider/nouislider.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="js/jquery.maxlength.css">
    <script type="text/javascript" src="js/jquery.plugin.js"></script>
    <script type="text/javascript" src="js/jquery.maxlength.js"></script>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
          media="screen">
    <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    <link href="css/modals.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        @media (orientation: landscape) {
            .div_margin {
                margin-left: -30px !important;
            }
        }
    </style>
    <?= guiacss(); ?>


    </section>

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
        if(isset($_SESSION["nombre"])){
        }
        $active3 = 'active';
        $vehiculomenu1 = 'active';
        $vehiculosubmenu1 = 'active';
        include "menu.php";

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


</section>

<section class="content">
    <div class="container-fluid div_margin">
        <!-- Example Tab -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <?php //print_r($_SESSION); ?>
                        <h2>VEHICULO <?php echo $vehiculo ?>
                            <!--<span class="ocultar_guia" style="color:
                      black;"><?/*=btn4('guiavehiculo')*/?></span>-->
                        </h2>
                    </div>
                    <div class="body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-col-orange" role="tablist">
                            <li id="info" role="presentation"><a href="#editar" data-toggle="tab" class="seg"
                                                                 onclick="funciones(1)
">Información </a></li>
                            <li id="doc" role="presentation"><a href="#documentos" data-toggle="tab" class="doc"
                                                                onclick="funciones(2)
">Documentación</a></li>
                            <li id="img" role="presentation"><a href="#imagenes" data-toggle="tab" class="img"
                                                                onclick="funciones(3)
">Imagenes</a></li>
                            <li id="con" role="presentation"><a href="#contratos" data-toggle="tab" class="contra"
                                                                onclick="funciones(4)
">Contratos</a></li>
                            <li id="pu" role="presentation"><a href="#publicacion" data-toggle="tab" class="pu"
                                                               onclick="funciones(6)
">Publicación</a></li>
                            <li id="mar" role="presentation"><a href="marbete.php?id=<?php echo $id?>"
                                                                target="_blank">Marbete</a></li>
                            <li id="est" role="presentation"><a href="#estadisticas" data-toggle="tab" onclick="funciones(7)
">Estadisticas</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content"
                             style="height: 480px; overflow-y: auto; border: 2px solid #FF7800; border-radius:0px 0px 30px 30px; border-top:none; margin: -2px;">
                            <div role="tabpanel" class="tab-pane fade" id="editar">
                                <br>
                                <div id="scroll" hidden>
                                    <br>
                                </div>
                                <div id="result"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="documentos">
                                <div id="documentos"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="imagenes">
                                <div id="images"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="contratos">
                                <div id="contratosdiv"></div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="publicacion">
                                <div id="divpublicacion"></div>

                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="estadisticas">
                                <div id="divestadisticas"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Example Tab -->

    </div>
</section>
<script type="text/javascript">
    function funciones(valor) {
        if (valor == 1) {
            //alert("si entra");
            $.ajax({
                url: "editarvehiculo.php?id=<?php echo $id ?>&vehiculonom=<?php echo $vehiculo ?>",
                method: "post",
                data: {
                    'array': JSON.stringify(valor)
                },
                success: function(data) {
                    $('#result').html(data);
                }
            });
        } else if (valor == 2) {
            //alert("si entra");
            $.ajax({
                url: "documentacionvehiculo.php?id=<?php echo $id ?>&vehiculonom=<?php echo $vehiculo ?>",
                method: "post",
                data: {
                    'array': JSON.stringify(valor)
                },
                success: function(data) {
                    $('#documentos').html(data);
                }
            });
        } else if (valor == 3) {
            //alert("si entra");
            $.ajax({
                url: "imagesvehiculo.php?id=<?php echo $id ?>&vehiculonom=<?php echo $vehiculo ?>",
                method: "post",
                data: {
                    'array': JSON.stringify(valor)
                },
                success: function(data) {
                    $('#images').html(data);
                }
            });
        } else if (valor == 4) {
            //alert("si entra");
            $.ajax({
                url: "contratosvehiculo.php?id=<?php echo $id ?>&vehiculonom=<?php echo $vehiculo ?>",
                method: "post",
                data: {
                    'array': JSON.stringify(valor)
                },
                success: function(data) {
                    $('#contratosdiv').html(data);
                }
            });
        } else if (valor == 5) {
            //alert("si entra");
            $.ajax({
                url: "verfinanciamiento.php?id=<?php echo $id ?>&vehiculonom=<?php echo $vehiculo ?>",
                method: "post",
                data: {
                    'array': JSON.stringify(valor)
                },
                success: function(data) {
                    $('#divfina').html(data);
                }
            });
        } else if (valor == 6) {
            //alert("si entra");
            $.ajax({
                url: "verpublicacion.php?id=<?php echo $id ?>&vehiculonom=<?php echo $vehiculo ?>",
                method: "post",
                data: {
                    'array': JSON.stringify(valor)
                },
                success: function(data) {
                    $('#divpublicacion').html(data);
                }
            });
        } else if (valor == 7) {
            //alert("si entra");
            $.ajax({
                url: "verestadisticas.php?id=<?php echo $id ?>&vehiculonom=<?php echo $vehiculo ?>",
                method: "post",
                data: {
                    'array': JSON.stringify(valor)
                },
                success: function(data) {
                    $('#divestadisticas').html(data);
                }
            });
        }
    }
    $(document).ready(function(e) {
        // Simular click
        $('.seg').click();
    });
</script>



<!-- Bootstrap Core Js -->
<script src="plugins/bootstrap/js/bootstrap.js"></script>

<!-- Select Plugin Js -->
<script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

<!-- Slimscroll Plugin Js -->
<script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

<!-- Bootstrap Notify Plugin Js -->
<script src="plugins/bootstrap-notify/bootstrap-notify.js"></script>

<!-- Waves Effect Plugin Js -->
<script src="plugins/node-waves/waves.js"></script>

<!-- SweetAlert Plugin Js -->
<script src="plugins/sweetalert/sweetalert.min.js"></script>

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
<script src="js/pages/ui/dialogs.js"></script>
<script src="js/pages/tables/jquery-datatable.js"></script>
<script src="js/pages/ui/notifications.js"></script>

<!-- Demo Js -->
<script src="js/demo.js"></script>

<?= guiascript(); ?>

<script>
    $("#guiavehiculo").click(function() {
        myTourguiavehiculo();
        return false;
    });

    var preseOptguiavehiculo = {

        keyboard: false,
        tourMap: {
            open: false
        },
        lang: {

        },
        intro: {
            title: 'DESCRIPCION GENERAL DE LA VISTA',
            content: '',
            cover: '',
            width: 450,
        },
        continue: { //Default the continue message settings
            enable: false, //This parameter add the ability to continue the unfinished tour.
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
            title: 'INFORMACION',
            content: 'Da click en el marco blanco ',
            target: '#info',
            event: 'click'
        }, {
            title: '',
            content: 'Vista modificar vehículo',
            target: '#ve',
        }, {
            title: 'DOCUMENTACION',
            content: 'Da click en el marco blanco',
            target: '#doc',
            event: 'click'
        }, {
            title: '',
            content: 'Vista documentación vehiculo ',
        }, {
            title: 'IMAGENES',
            content: 'Da click en el marco blanco',
            target: '#img',
            event: 'click'
        }, {
            title: '',
            content: 'Vista imágenes vehiculo ',
        }, {
            title: 'CONTRATOS',
            content: 'Da click en el marco blanco',
            target: '#con',
            event: 'click'
        }, {
            title: '',
            content: 'Vista contratos vehiculo ',
        }, {
            title: 'PUBLICACION',
            content: 'Da click en el marco blanco',
            target: '#pu',
            event: 'click'
        }, {
            title: '',
            content: 'Vista publicidad vehiculo ',
        }, {
            title: 'MARBETE',
            content: 'Da click en el marco blanco',
            target: '#mar',
            event: 'click'
        }, {
            title: '',
            content: 'Vista marbete vehiculo ',
        }, {
            title: 'ESTADISTICAS',
            content: 'Da click en el marco blanco',
            target: '#est',
            event: 'click'
        }, {
            title: '',
            content: 'Vista estadísticas vehiculo ',
        }],
    }

    function myTourguiavehiculo() {
        iGuider(preseOptguiavehiculo);
    }
</script>

</body>

</html>