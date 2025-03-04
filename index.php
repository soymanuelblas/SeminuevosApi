<?php
error_reporting(0);
include('head.php');
//include ("vistasEstatus.php");
include('consultas.php');

?>

<link rel="stylesheet" type="text/css" href="css/select2.css">
<style>
    @media (orientation: landscape) {
        .div_margin {
            background: #FFFFFF; 
            border-radius: 30px;
            margin-left: 300px !important;
        }
    }
</style>
<script src="js/validarsesion.js"></script>
<script src="js/misesionMB.js"></script>
<section class="content div_margin">
    <div class="container-fluid">
        <?php //print_r($_SESSION['id']);

         ?>
        <div class="block-header">
            <h2>Bienvenido a
                <?php if ($sitiocambio != '' AND $sitioNombrecambio != '') { ?>
                <select class="" id="sitio" name="sitio" required="" style="width: 35%;" <?php
                    if ($arr1[154] == 0) { ?> disabled="true" <?php } ?>>
                    <?php
                        if($idusuario == 1){
                            $sqlSite = "SELECT * FROM `sitio` WHERE `id` != $sitiocambio;";
                            $query = $mysqli -> query ($sqlSite);

                            echo'<option value='.$sitiocambio.' selected="">'.strtoupper($sitioNombrecambio).'</option>';

                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value="'.$valores[id].'">'.strtoupper($valores[nombre]).'</option>';
                            }
                        }
                        else{
                            $query = $mysqli -> query ("SELECT * FROM `sitio` WHERE razonsocial_id = '$razon_social' AND nombre != '$sitioNombrecambio' ");
                            echo'<option value='.$sitiocambio.' selected="">'.$sitioNombrecambio.'</option>';
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value="'.$valores[id].'">'.$valores[nombre].'</option>';
                            }

                        }
                        mysqli_close($mysqli);
                        ?>

                </select>

                <?php }else{ ?>

                <select class="" id="sitio" name="sitio" required="" style="width: 35%;" <?php 

                    if ($arr1[154] == 0) { ?> disabled="true" <?php } ?>>

                    <?php

                            $query = $mysqli -> query ("SELECT * FROM `sitio` WHERE razonsocial_id = '$razon_social' AND nombre != '$sitioNombre' ");

                                echo'<option value='.$sitioSession.' selected="">'.$sitioNombre.'</option>';

                                     while ($valores = mysqli_fetch_array($query)) {

                                        echo '<option value="'.$valores[id].'">'.$valores[nombre].'</option>';

                                        }

                                        mysqli_close($mysqli);

                        ?>

                </select>

                <?php } ?>

            </h2>

        </div>

        <?php
        /*
        print_r($validar_contra);
        echo "<br>";
        print_r($row_contra);
        */

        ?>

        <div class="block-header">

            <h2>CUENTAS POR COBRAR</h2>

        </div>

        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">event_busy</i>
                    </div>
                    <div class="content">
                        <div class="text">VENCIDAS TOTALES</div>
                        <div class="number count-to" data-from="0"
                            data-to="<?php echo $row_cobrar_vencidas["vencidas"] ?>" data-speed="1000"
                            data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>



            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-toronja hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">event_note</i>
                    </div>
                    <div class="content">
                        <div class="text">MES DE <?php echo $mes ?></div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $row_cobrar_mes["mes"] ?>"
                            data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">attach_money</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTALES</div>
                        <div class="number count-to" data-from="0" data-to="" data-speed="1000"
                            data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="block-header">
            <h2>CUENTAS POR PAGAR </h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">event_busy</i>
                    </div>
                    <div class="content">
                        <div class="text">VENCIDAS TOTALES</div>
                        <div class="number count-to" data-from="0"
                            data-to="<?php echo $row_pagar_vencidas["vencidas_pagar"] ?>" data-speed="1000"
                            data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-toronja hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">event_note</i>
                    </div>
                    <div class="content">
                        <div class="text">MES DE <?php echo $mes ?></div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $row_pagar_mes["mes_pagar"] ?>"
                            data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">attach_money</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTALES</div>
                        <div class="number count-to" data-from="0"
                            data-to="<?php echo $row_pagar_totales["totales_pagar"] ?>" data-speed="1000"
                            data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="block-header">
            <h2>OPORTUNIDADES </h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">event_busy</i>
                    </div>
                    <div class="content">
                        <div class="text">ATRASADAS</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $row2["atrasadas"] ?>"
                            data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-toronja hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">sync</i>
                    </div>
                    <div class="content">
                        <div class="text">EN PROCESO</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $row3["proceso"] ?>"
                            data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">delete_forever</i>
                    </div>
                    <div class="content">
                        <div class="text">DESECHADAS</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $rownolograda["nolograda"] ?>"
                            data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="block-header">
            <h2>VEHICULOS </h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">drive_eta</i>
                    </div>
                    <div class="content">
                        <div class="text">VENDIDOS POR MES</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $row["vehiculos_mes"] ?>"
                            data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green hover-expand-effect" style="border-radius: 30px;">
                    <div class="icon">
                        <i class="material-icons">drive_eta</i>
                    </div>
                    <div class="content">
                        <div class="text">VENDIDOS POR AÑO</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $row1["vehiculos_annio"] ?>"
                            data-speed="1000" data-fresh-interval="20"></div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script src="plugins/jquery/jquery.min.js"></script>

<script src="js/select2.js"></script>



<script type="text/javascript">
$(document).ready(function() {

    $('#sitio').select2();

});
</script>



<script type="text/javascript">
$("#sitio").change(function() {

    m1 = $('#sitio option:selected').val();

    //alert(m1);

    $.ajax({

        url: "checksitio.php",

        method: "post",

        data: {
            'array': JSON.stringify(m1)
        },

        success: function(data)

        {

            location.reload();

        }

    });



});
</script>

<script src="js/ejecutar.js"></script>

<!-- Jquery Core Js -->





<!-- Bootstrap Core Js -->

<script src="plugins/bootstrap/js/bootstrap.js"></script>



<!-- Select Plugin Js -->

<script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>



<!-- Slimscroll Plugin Js -->

<script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>



<!-- Waves Effect Plugin Js -->

<script src="plugins/node-waves/waves.js"></script>



<!-- Jquery CountTo Plugin Js -->

<script src="plugins/jquery-countto/jquery.countTo.js"></script>



<!-- Morris Plugin Js -->

<script src="plugins/raphael/raphael.min.js"></script>

<script src="plugins/morrisjs/morris.js"></script>



<!-- ChartJs -->

<script src="plugins/chartjs/Chart.bundle.js"></script>



<!-- Flot Charts Plugin Js -->

<script src="plugins/flot-charts/jquery.flot.js"></script>

<script src="plugins/flot-charts/jquery.flot.resize.js"></script>

<script src="plugins/flot-charts/jquery.flot.pie.js"></script>

<script src="plugins/flot-charts/jquery.flot.categories.js"></script>

<script src="plugins/flot-charts/jquery.flot.time.js"></script>



<!-- Sparkline Chart Plugin Js -->

<script src="plugins/jquery-sparkline/jquery.sparkline.js"></script>



<!-- Custom Js -->

<script src="js/admin.js"></script>

<script src="js/pages/index.js"></script>



<!-- Demo Js -->

<script src="js/demo.js"></script>







</body>



</html>