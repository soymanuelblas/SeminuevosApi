<?php
include "variables.php";
include "funciones.php";

if($_SESSION['estatus'] != 853){
    header('Location: '."index.php");
    die();
}

$sql_cuentas = "SELECT * FROM `cuenta_banco_temporal` WHERE sitio_id = '$sitioSession' ";
$resultado_cuentas = $mysqli->query($sql_cuentas);

$sql_movimientos = "SELECT operacion_caja.id AS id,0 AS id_dos, operacion_caja.descripcion AS descripcion  
        FROM operacion_caja WHERE operacion_caja.id IN(3,5,7,9,10,45)
        UNION 
        SELECT 0 AS id,ope_caja_temporal.id AS id_dos, ope_caja_temporal.descripcion AS descripcion FROM `ope_caja_temporal` WHERE razon_social = '$razon_social' AND tipo = '1' ";
$resultado_movimientos = $mysqli->query($sql_movimientos);

$sql_movimientos_egreso = "SELECT operacion_caja.id AS id,0 AS id_dos, operacion_caja.descripcion AS descripcion  
        FROM operacion_caja WHERE operacion_caja.id IN(11,26,27)
        UNION 
        SELECT 0 AS id,ope_caja_temporal.id AS id_dos, ope_caja_temporal.descripcion AS descripcion FROM `ope_caja_temporal` WHERE razon_social = '$razon_social' AND tipo = '2' ";
$resultado_movimientos_egreso = $mysqli->query($sql_movimientos_egreso);

/*
$sql_razon = "SELECT * FROM `razon_social` WHERE `id` = $razon_social ";
$resultado_razon = $mysqli->query($sql_razon);
$resultado_razon = $resultado_razon -> fetch_all(MYSQLI_ASSOC);
$resultado_razon = $resultado_razon[0];
*/

/*
$sql = "SELECT * FROM `plantillas`";
$resultado = $mysqli->query($sql);

$sql1 = "SELECT * FROM `complemento_sitio` WHERE sitio_id = '$sitioSession' ";
$resultado1 = $mysqli->query($sql1);
$rowcomplemento = $resultado1->fetch_array(MYSQLI_ASSOC);

$id_plantilla = $rowcomplemento["plantilla_id"];
$fondo = $rowcomplemento["color_fondo"];
$titulos = $rowcomplemento["color_titulos"];
$texto = $rowcomplemento["color_texto"]; 
$general = $rowcomplemento["color_general"];
*/


$sql_operaciones = "SELECT * FROM `ope_caja_temporal` WHERE razon_social = '$razon_social' ";
$resultado_operaciones = $mysqli->query($sql_operaciones);


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
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
            type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

        <!-- Bootstrap Core Css -->
        <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

        <!-- Waves Effect Css -->
        <link href="plugins/node-waves/waves.css" rel="stylesheet" />

        <!-- Animation Css -->
        <link href="plugins/animate-css/animate.css" rel="stylesheet" />

        <!-- Colorpicker Css -->
        <link href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />

        <!-- Dropzone Css -->
        <link href="plugins/dropzone/dropzone.css" rel="stylesheet">

        <!-- Multi Select Css -->
        <link href="plugins/multi-select/css/multi-select.css" rel="stylesheet">

        <!-- Bootstrap Spinner Css -->
        <link href="plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

        <!-- Bootstrap Tagsinput Css -->
        <link href="plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

        <!-- Bootstrap Select Css -->
        <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

        <!-- noUISlider Css -->
        <link href="plugins/nouislider/nouislider.min.css" rel="stylesheet" />

        <!-- Custom Css -->
        <link href="css/style.css" rel="stylesheet">

        <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
        <link href="css/themes/all-themes.css" rel="stylesheet" />

        <link href="css/plantillas.css" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="css/select2.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?= guiacss(); ?>
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
        <!-- Top Bar -->
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-collapse" aria-expanded="false"></a>
                    <a href="javascript:void(0);" class="bars"></a>
                    <a class="navbar-brand" href="index.php">Administración de
                        <?php
                    if ($sitiocambio != '' and $sitioNombrecambio != '') {
                        echo $sitioNombrecambio;
                    } else {
                        echo $sitioNombre;
                    }

                    ?>
                    </a>
                </div>
            </div>
        </nav>
        <!-- #Top Bar -->

        <section>
            <!-- Left Sidebar -->
            <aside id="leftsidebar" class="sidebar">
                <!-- User Info -->
                <?php
                error_reporting(0);
                if (isset($_SESSION["nombre"])) {
                }
                $active6 = 'active';
                $plantillamenu = 'active';
                $sucursales2 = 'active';
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

            <!-- #END# Right Sidebar -->
        </section>

        <input type="hidden" name="idsitio" id="idsitio" value="<?php echo $sitioSession ?>">
        <div id="scroll"></div>
        <section class="content">
            <?php
            //var_dump($razon_social);
            //print_r($_SESSION);
            //print_r($resultado_razon);
            ?>
            <div class="container-fluid">
                <!-- Color Pickers -->
                <form id="frmajax" method="post" action="javascript:void(0)" enctype="multipart/form-data">
                    <div class="row clearfix" style="margin-left: -45px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="header">
                                    <h2>COMPLETA LA SIGUIENTE INFORMACIÓN</h2>
                                </div>

                                <?php
                                //print_r($_SESSION['id']);
                                ?>

                                <div class="body">
                                    <label>La siguiente información es necesaria para el correcto funcionamiento del sistema administrativo.</label>

                                    <div class="header aaa00" role="tab" id="headingOneRazon_1" role="button"
                                         data-toggle="collapse" href="#collapseOneRazon_1" aria-expanded="true"
                                         aria-controls="collapseOneRazon_1">
                                        <h5 style="color: black;" data-target="aa19MB">AGREGA LOS DATOS FALTANTES DE LA RAZÓN SOCIAL</h5>
                                        <ul class="header-dropdown m-r--5">
                                            <li class="dropdown">
                                                <i class="material-icons idrazon" id="masMB" name="masMB"
                                                   style="color: black;"><span>add_circle_outline</span></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="collapseOneRazon_1" class="panel-collapse collapse body table-responsive "
                                         role="tabpanel" aria-labelledby="headingOneRazon_1"
                                         style="width: 85%; margin: 0 auto; box-shadow:none;">
                                        <div id="scroll50" class="row clearfix">
                                            <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                     style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                    <label for="email_address_2">
                                                        <p><b>Ingresa el RFC <span style="color:red;">*</span> </b></p>
                                                    </label>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                                                    <div class="form-group">
                                                        <div class="input-group ">
                                                            <div class="form-line" style="border:none;">
                                                                <input type="text"
                                                                       style="border:none; height:30px; border-radius:0px; text-transform:uppercase;"
                                                                       maxlength="13" onkeypress="return check(event)"
                                                                       id="newRFC" name="newRFC" class="form-control" placeholder="INGRESE SU RFC">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                     style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                    <label for="email_address_2">
                                                        <p><b>Nombre de la Razón Social <span style="color:red;">*</span></b></p>
                                                    </label>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                                                    <div class="form-group">
                                                        <div class="input-group ">
                                                            <div class="form-line" style="border:none;">
                                                                <input type="text"
                                                                       style="border:none; height:30px; border-radius:0px; text-transform:uppercase;"
                                                                       id="nameRS" name="nameRS" class="form-control" placeholder="INGRESE EL NOMBRE DE LA RAZÓN SOCIAL">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                     style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                    <label for="email_address_2">
                                                        <p><b>Representante Legal <span style="color:red;">*</span></b></p>
                                                    </label>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                                                    <div class="form-group">
                                                        <div class="input-group ">
                                                            <div class="form-line" style="border:none;">
                                                                <input type="text"
                                                                       style="border:none; height:30px; border-radius:0px; text-transform:uppercase;"
                                                                       id="nameRLRS" name="nameRLRS" class="form-control" placeholder="INGRESE EL NOMBRE DEL REPRESENTANTE LEGAL">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                     style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                    <label for="email_address_2">
                                                        <p><b>Régimen Fiscal <span style="color:red;">*</span></b></p>
                                                    </label>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                                                    <div class="form-group">
                                                        <select class="require col-lg-12 col-md-12 col-sm-12 col-xs-12 rSocial" style="width: 100%" id="rSocial" name="rSocial">
                                                            <?php
                                                            $query = $mysqli->query("SELECT descripcion,id FROM tipostatus WHERE tipo = 72 ORDER BY descripcion ASC");
                                                            echo '<option value="" disabled="" selected="">SELECCIONE UNA RAZÓN SOCIAL</option>';
                                                            while ($valores = mysqli_fetch_array($query)) {
                                                                echo '<option select value="' . $valores['id'] . '">' . $valores['descripcion'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="header aaa00" role="tab" id="headingOneContra_1" role="button"
                                        data-toggle="collapse" href="#collapseOneContra_1" aria-expanded="true"
                                        aria-controls="collapseOneContra_1">
                                        <h5 style="color: black;" data-target="aa19">GENERA UNA NUEVA CONTRASEÑA</h5>
                                        <ul class="header-dropdown m-r--5">
                                            <li class="dropdown">
                                                <i class="material-icons idcontra" id="mas2" name="mas2"
                                                    style="color: black;"><span>add_circle_outline</span></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="collapseOneContra_1" class="panel-collapse collapse body table-responsive "
                                        role="tabpanel" aria-labelledby="headingOneContra_1"
                                        style="width: 85%; margin: 0 auto; box-shadow:none;">
                                        <div id="scroll50" class="row clearfix">
                                            <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                    style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                    <label for="email_address_2">
                                                        <p><b>Nueva Contraseña<span style="color:red;">*</span> </b></p>
                                                    </label>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                                                    <div class="form-group">
                                                        <div class="input-group ">
                                                            <div class="form-line" style="border:none;">
                                                                <input type="password"
                                                                    style="border:none; height:30px; border-radius:0px;"
                                                                    id="nuevacontra" name="nuevacontra" class="form-control"
                                                                    onchange="validar_contra();">
                                                            </div>
                                                            <span class="input-group-addon" id="nueva_contra">
                                                                <i class="material-icons idcontra_nueva"
                                                                    style="color: #FF7800;"><span>visibility</span></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="row clearfix">
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Vuelve a escribir tu Contraseña<span
                                                                style="color:red;">*</span></b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                                                <div class="form-group">
                                                    <div class="input-group ">
                                                        <div class="form-line" style="border:none;">
                                                            <input type="password"
                                                                style="border:none; height:30px; border-radius:0px;"
                                                                id="nuevacontra_repit" name="nuevacontra_repit"
                                                                class="form-control" onchange="validar_contra();">
                                                        </div>
                                                        <span class="input-group-addon" id="nueva_contra_repit">
                                                            <i class="material-icons idcontra_nueva_repit"
                                                                style="color: #FF7800;"><span>visibility</span></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <!--
                                    <div class="header aaa0" role="tab" id="headingOnesitios_1" role="button"
                                        data-toggle="collapse" href="#collapseOnesitios_1" aria-expanded="true"
                                        aria-controls="collapseOnesitios_1">
                                        <h5 style="color: black;" data-target="aa2">INFORMACIÓN DE TU SITIO WEB</h5>
                                        <ul class="header-dropdown m-r--5">
                                            <li class="dropdown">
                                                <i class="material-icons idprecio1" id="mas1" name="mas1"
                                                    style="color: black;"><span>add_circle_outline</span></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="collapseOnesitios_1" class="panel-collapse collapse body table-responsive "
                                        role="tabpanel" aria-labelledby="headingOnesitios_1"
                                        style="width: 85%; margin: 0 auto; box-shadow:none;">
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div id="scroll1" class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Logo</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="file"
                                                            style="border:none; height:30px; border-radius:0px;" name="logo"
                                                            id="logo" class="form-control" data-target="aa3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Img Carusel 1</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="file"
                                                            style="border:none; height:30px; border-radius:0px;" name="img1"
                                                            id="img1" class="form-control" data-target="aa4">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Img Carusel 2</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="file"
                                                            style="border:none; height:30px; border-radius:0px;" name="img2"
                                                            id="img2" class="form-control" data-target="aa5">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Img Carusel 3</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="file"
                                                            style="border:none; height:30px; border-radius:0px;" name="img3"
                                                            id="img3" class="form-control" data-target="aa6">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Img Misión y Visión</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="file"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="imgvis" id="imgvis" class="form-control"
                                                            data-target="aa7">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Correo Contacto<span style="color:red;">*</span></b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="text"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="contactocorreo" id="contactocorreo"
                                                            class="form-control require" required="true"
                                                            value="<?php echo $rowcomplemento["email_contacto"]; ?>"
                                                            data-target="aa8" autocomplete="false">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Contra Correo Contacto<span style="color:red;">*</span></b>
                                                    </p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="password"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="contracontactocorreo" id="contracontactocorreo"
                                                            class="form-control require" required="true"
                                                            value="<?php echo $rowcomplemento["contra_contacto"]; ?>"
                                                            data-target="aa9" autocomplete="false">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Horario Laboral<span style="color:red;">*</span></b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="text"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="horariolaboral" id="horariolaboral"
                                                            class="form-control require" required="true"
                                                            value="<?php echo $rowcomplemento["horario_laboral"]; ?>"
                                                            data-target="aa10">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Visión</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="text"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="vision" id="vision" class="form-control" required="true"
                                                            value="<?php echo $rowcomplemento["vision"]; ?>"
                                                            data-target="aa11">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Misión</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="text"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="mision" id="mision" class="form-control" required="true"
                                                            value="<?php echo $rowcomplemento["mision"]; ?>"
                                                            data-target="aa12">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Valores</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="text"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="valores" id="valores" class="form-control" required="true"
                                                            value="<?php echo $rowcomplemento["valores"]; ?>"
                                                            data-target="aa13">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:56px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:56px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Url Maps<span style="color:red;">*</span></b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 70px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <textarea name="maps"
                                                            style="border:none; height:53px; border-radius:0px;" id="maps"
                                                            class="form-control require" required="true" data-target="aa14">
                                                    <?php echo $rowcomplemento["ubicacion"]; ?>
                                                 </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Url Facebook</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="text"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="urlface" id="urlface" class="form-control" value="<?php echo
                                                       $rowcomplemento["url_facebook"]; ?>" data-target="aa15">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Url Instagram</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="text"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="urlinsta" id="urlinsta" class="form-control " value="<?php echo
                                                       $rowcomplemento["url_instagram"]; ?>" data-target="aa16">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Url Twitter</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="text"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="urltwitter" id="urltwitter" class="form-control" value="<?php echo
                                                $rowcomplemento["url_twitter"]; ?>" data-target="aa17">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <label for="email_address_2">
                                                    <p><b>Url Youtube</b></p>
                                                </label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height: 50px;">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="text"
                                                            style="border:none; height:30px; border-radius:0px;"
                                                            name="urlyoutube" id="urlyoutube" class="form-control" value="<?php echo
                                                $rowcomplemento["url_youtube"]; ?>" data-target="aa18">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    -->

                                    <div class="header aaa00" role="tab" id="headingOnecuentas_1" role="button"
                                        data-toggle="collapse" href="#collapseOnecuentas_1" aria-expanded="true"
                                        aria-controls="collapseOnecuentas_1">
                                        <h5 style="color: black;" data-target="aa19">CUENTAS DE BANCO</h5>
                                        <ul class="header-dropdown m-r--5">
                                            <li class="dropdown">
                                                <i class="material-icons idcuentas" id="mas2" name="mas2"
                                                    style="color: black;"><span>add_circle_outline</span></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <input type="hidden" name="necesitas" id="necesitas" value="1">
                                    <div id="collapseOnecuentas_1" class="panel-collapse collapse body table-responsive "
                                        role="tabpanel" aria-labelledby="headingOnecuentas_1"
                                        style="width: 80%; margin: 0 auto; box-shadow:none;">
                                        <div id="contenido">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                <thead>
                                                <tr>
                                                    <th width="100" style="border-radius:30px 0px 0px 30px"></th>
                                                    <th style="border-radius:0px 30px 30px 0px">Nombre</th>

                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php while($row = $resultado_cuentas->fetch_array(MYSQLI_ASSOC)) { ?>
                                                    <tr style="background:#EFEFEF;">
                                                        <td style="border-radius:30px 0px 0px 30px">
                                                            <button type="button" class="btn btn-danger waves-effect"
                                                                    onClick="eliminar(<?php echo $row['id']; ?>);">
                                                                <i class="material-icons" style="font-size: 18px;">delete</i>
                                                            </button>
                                                        </td>
                                                        <td style="border-radius:0px 30px 30px 0px; text-align:center;"><?php echo $row['nombre']?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>

                                            </table>
                                        </div>
                                        <div id="scroll50" class="row clearfix">
                                            <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                    style="height:33px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                    <div class="form-group">
                                                        <label for="email_address_2">
                                                            <p><b>Cuenta</b></p>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6 form-control-label">
                                                    <div class="form-group">
                                                        <div class="form-line" style="border:none;">
                                                            <input type="text"
                                                                style="border:none; width:100%; height:30px; border-radius:0px;"
                                                                   placeholder="EJEMPLO: CUENTA BBVA 1234567890"
                                                                id="cuenta" name="cuenta" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                                    style="text-align:right">
                                                    <button id="agregarcuenta" type="button"
                                                        class="btn btn-default waves-effect">
                                                        <i class="material-icons" style="color: #FF7800;">add</i>
                                                        <span>Agregar</span>

                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="header aaa00" role="tab" id="headingOnemovimientos_1" role="button"
                                        data-toggle="collapse" href="#collapseOnemovimientos_1" aria-expanded="true"
                                        aria-controls="collapseOnemovimientos_1">
                                        <h5 style="color: black;" data-target="aa19">MOVIMIENTOS DE CAJA</h5>
                                        <ul class="header-dropdown m-r--5">
                                            <li class="dropdown">
                                                <i class="material-icons idmovimientos" id="mas2" name="mas2"
                                                    style="color: black;"><span>add_circle_outline</span></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="collapseOnemovimientos_1"
                                        class="panel-collapse collapse body table-responsive " role="tabpanel"
                                        aria-labelledby="headingOnemovimientos_1"
                                        style="width: 85%; margin: 0 auto; box-shadow:none;">

                                        <div class="header" role="tab" id="headingOneingresos_1" role="button"
                                            data-toggle="collapse" href="#collapseOneingresos_1" aria-expanded="true"
                                            aria-controls="collapseOneingresos_1">
                                            <h5 style="color: black;" data-target="aa19">INGRESOS</h5>
                                            <ul class="header-dropdown m-r--5">
                                                <li class="dropdown">
                                                    <i class="material-icons idingresos" id="mas2" name="mas2"
                                                        style="color: black;"><span>add_circle_outline</span></i>
                                                </li>
                                            </ul>
                                        </div>
                                        <div id="collapseOneingresos_1"
                                            class="panel-collapse collapse body table-responsive " role="tabpanel"
                                            aria-labelledby="headingOneingresoS_1"
                                            style="width: 85%; margin: 0 auto; box-shadow:none;">
                                            <div id="contenido_ingreso">
                                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                    <thead>
                                                    <tr>
                                                        <th width="100" style="border-radius:30px 0px 0px 30px">Acciones</th>
                                                        <th style="border-radius:0px 30px 30px 0px">Movimiento</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php while($row = $resultado_movimientos->fetch_array(MYSQLI_ASSOC)) { ?>
                                                        <tr style="background:#EFEFEF;">
                                                            <td style="border-radius:30px 0px 0px 30px">
                                                                <?php if($row['id_dos'] != '0'){ ?>
                                                                    <button type="button" class="btn btn-danger waves-effect"
                                                                            onClick="eliminaringreso(<?php echo $row['id_dos']; ?>);">
                                                                        <i class="material-icons" style="font-size: 18px;">delete</i>
                                                                    </button>
                                                                <?php } ?>
                                                            </td>
                                                            <td style="border-radius:0px 30px 30px 0px; text-align:center;"><?php echo $row['descripcion']?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                    style="height:33px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                    <div class="form-group">
                                                        <label for="email_address_2">
                                                            <p><b>Descripción</b></p>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6 form-control-label">
                                                    <div class="form-group">
                                                        <div class="form-line" style="border:none;">
                                                            <input type="text"
                                                                style="border:none; width:100%; height:30px; border-radius:0px;"
                                                                id="ingreso" name="ingreso" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right">
                                                <button id="agregaringreso" type="button"
                                                    class="btn btn-default waves-effect">
                                                    <i class="material-icons" style="color: #FF7800;">add</i>
                                                    <span>Agregar</span>

                                                </button>
                                            </div>

                                        </div>

                                    <div class="header" role="tab" id="headingOneegresos_1" role="button"
                                        data-toggle="collapse" href="#collapseOneegresos_1" aria-expanded="true"
                                        aria-controls="collapseOneegresos_1">
                                        <h5 style="color: black;" data-target="aa19">EGRESOS</h5>
                                        <ul class="header-dropdown m-r--5">
                                            <li class="dropdown">
                                                <i class="material-icons idegreso" id="mas2" name="mas2"
                                                    style="color: black;"><span>add_circle_outline</span></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="collapseOneegresos_1"
                                        class="panel-collapse collapse body table-responsive " role="tabpanel"
                                        aria-labelledby="headingOneegresos_1"
                                        style="width: 85%; margin: 0 auto; box-shadow:none;">

                                        <div id="contenido_egreso">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                <thead>
                                                <tr>
                                                    <th width="100" style="border-radius:30px 0px 0px 30px">Acciones</th>
                                                    <th style="border-radius:0px 30px 30px 0px">Movimiento</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php while($row = $resultado_movimientos_egreso->fetch_array(MYSQLI_ASSOC)) { ?>
                                                    <tr style="background:#EFEFEF;">
                                                        <td style="border-radius:30px 0px 0px 30px">
                                                            <?php if($row['id_dos'] != '0'){ ?>
                                                                <button type="button" class="btn btn-danger waves-effect"
                                                                        onClick="eliminaregreso(<?php echo $row['id_dos']; ?>);">
                                                                    <i class="material-icons" style="font-size: 18px;">delete</i>
                                                                </button>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="border-radius:0px 30px 30px 0px; text-align:center;"><?php echo $row['descripcion']?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 form-control-label"
                                                style="height:33px; border-right: 1px solid #FF7800; border-radius: 0px;">
                                                <div class="form-group">
                                                    <label for="email_address_2">
                                                        <p><b>Descripción</b></p>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6 form-control-label">
                                                <div class="form-group">
                                                    <div class="form-line" style="border:none;">
                                                        <input type="text"
                                                            style="border:none; width:100%; height:30px; border-radius:0px;"
                                                            id="egreso" name="egreso" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right">
                                            <button id="agregaregreso" type="button"
                                                class="btn btn-default waves-effect">
                                                <i class="material-icons" style="color: #FF7800;">add</i>
                                                <span>Agregar</span>

                                            </button>
                                        </div>

                                    </div>

                                </div>

                                    <div class="actions clearfix">
                                        <div class="header" role="tab" role="button" data-toggle="collapse">
                                            <br>
                                            <ul class="header-dropdown m-r--5" style="margin:-8px 0px;">
                                                <li>
                                                    <button id="guardar" type="button" class="btn btn-primary waves-effect"
                                                        data-toggle="modal" data-target="#defaultModal"
                                                        onclick="envioAjax('update_sitio_nuevo.php','frmajax','post','.resultado','<?php echo $_SESSION['id'];?>')">
                                                        <i class="material-icons" style="color: #FFFFFF;">save</i>
                                                        <span>Guardar</span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Jquery Core Js -->
        <script src="plugins/jquery/jquery.min.js"></script>

        <script src="js/completar_info.js"></script>

        <!-- Bootstrap Core Js -->
        <script src="plugins/bootstrap/js/bootstrap.js"></script>

        <!-- Select Plugin Js -->
        <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

        <!-- Slimscroll Plugin Js -->
        <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

        <script src="plugins/bootstrap-notify/bootstrap-notify.js"></script>

        <!-- Bootstrap Colorpicker Js -->
        <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

        <!-- Dropzone Plugin Js -->
        <script src="plugins/dropzone/dropzone.js"></script>

        <!-- Input Mask Plugin Js -->
        <script src="plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

        <!-- Multi Select Plugin Js -->
        <script src="plugins/multi-select/js/jquery.multi-select.js"></script>

        <!-- Jquery Spinner Plugin Js -->
        <script src="plugins/jquery-spinner/js/jquery.spinner.js"></script>

        <!-- Bootstrap Tags Input Plugin Js -->
        <script src="plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

        <!-- noUISlider Plugin Js -->
        <script src="plugins/nouislider/nouislider.js"></script>

        <!-- Waves Effect Plugin Js -->
        <script src="plugins/node-waves/waves.js"></script>

        <!-- Custom Js -->
        <script src="js/admin.js"></script>
        <script src="js/pages/forms/advanced-form-elements.js"></script>
        <script src="js/pages/ui/notifications.js"></script>

        <!-- Demo Js -->
        <script src="js/demo.js"></script>

        <!-- Select 2 -->
        <script src="js/select2.js"></script>
</body>
</html>