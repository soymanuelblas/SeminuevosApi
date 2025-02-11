<?php
include "variables.php";

if($sitiocambio != "1"){
    $and = " AND operacion.sitio_id = '$sitiocambio' ";
}



?>
<!DOCTYPE html>
<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
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

    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
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
            $active4 = 'active';
            $admincaja = 'active';
            $caja5 = 'active';
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
                            <h2>
                                MOVIMIENTOS DEL DÍA
                            </h2>
                            <?php  if ($arr1[103] == 1) {?>
                            <ul class="header-dropdown m-r--5">
                                <li>
                                    <a href="movimientosimpresos.php">
                                    <button type="button" class="btn btn-primary waves-effect">
                                        <i class="material-icons" style="color: white;">view_headline</i>
                                        <span>Ver todos los movimientos</span>
                                    </button>
                                    </a>
                                </li>
                            </ul>
                            <?php } ?>
                        </div>

                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th style="border-radius:30px 0px 0px 30px"></th>
                                            <th style="border-radius:0px 0px 0px 0px">Documento</th>
                                            <th style="border-radius:0px 0px 0px 0px" width="55">Fecha</th>
                                            <th style="border-radius:0px 0px 0px 0px">Cliente</th>
                                            <th style="border-radius:0px 0px 0px 0px">Descripción</th>
                                            <th style="border-radius:0px 0px 0px 0px">Vehiculo</th>
                                            <th style="border-radius:0px 0px 0px 0px">Forma de pago</th>
                                            <th style="border-radius:0px 30px 30px 0px">Importe</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $mov = "SELECT MAX(operacion.id_interno) AS idope FROM operacion WHERE operacion.tipo_operacion = 1 AND operacion.sitio_id = '$sitiocambio'";
                                            $mov1 = $mysqli->query($mov);
                                            $mov2 = $mov1->fetch_array(MYSQLI_ASSOC);
                                            $movresultado  = $mov2["idope"];

                                            $iduno = "SELECT operacion.corte_id AS corteid FROM `operacion` WHERE id_interno ='$movresultado' AND operacion.sitio_id = '$sitiocambio'";
                                            $movdos = $mysqli->query($iduno);
                                            $movtres = $movdos->fetch_array(MYSQLI_ASSOC);
                                            $movresultadouno  = $movtres["corteid"];

                                        $sql27  = "SELECT (operacion.id_interno) AS idope, operacion.importe AS saldoanterior, operacion_caja.descripcion AS movimiento, clientes.nombre AS clienteventa,compra.nombre AS clientecompra, operacion.fecha AS fecha, formapago.referencia AS referencia, operacion.adicional_id AS adicional, tipostatus.descripcion AS formapago,
                                            formapago.fechaexpedicion AS fechaexpedicion, formapago.importe AS importe, clientes.id_interno AS idventa, compra.id_interno AS idcompra, CONCAT('( ',vehiculo.noexpediente,')',' ', marca.descripcion, ' ', modelo.descripcion, ' ' , annio.descripcion, ' ', version.descripcion ) AS vehiculo,cuenta.nombre AS cuenta
                                            FROM operacion
                                            LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion 
                                            LEFT JOIN clientes ON clientes.id = operacion.clienteventa_id
                                            LEFT JOIN clientes AS compra ON compra.id = operacion.clientecompra_id
                                            LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno AND 
                                            formapago.sitio_id = operacion.sitio_id
                                            LEFT JOIN tipostatus ON formapago.formapago_id = tipostatus.id
                                            LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id
                                            LEFT JOIN version ON vehiculo.version_id = version.id
                                            LEFT JOIN tipoannio AS annio on version.tipoannio_id = annio.id
                                            LEFT JOIN tipomodelo AS modelo ON version.tipomodelo_id = modelo.id
                                            LEFT JOIN tipomarca AS marca ON modelo.tipomarca_id = marca.id
                                            LEFT JOIN cuenta_banco ON cuenta_banco.formapago_id = formapago.id_interno
                                            AND cuenta_banco.sitio_id = formapago.sitio_id
                                            LEFT JOIN cuentas_bancarias AS cuenta ON cuenta.id = cuenta_banco.cuenta_id
                                            WHERE operacion.tipo_operacion = 1 AND operacion.id_interno = '$movresultado' $and 
                                            UNION 
                                            SELECT operacion.id_interno AS idope, 0 AS saldoanterior, operacion_caja.descripcion AS movimiento, clientes.nombre AS clienteventa,compra.nombre AS clientecompra, operacion.fecha AS fecha, formapago.referencia AS referencia, operacion.adicional_id AS adicional, tipostatus.descripcion AS formapago,
                                            formapago.fechaexpedicion AS fechaexpedicion,formapago.importe AS importe, clientes.id_interno AS idventa, compra.id_interno AS idcompra, CONCAT('( ',vehiculo.noexpediente,')',' ', marca.descripcion, ' ', modelo.descripcion, ' ' , annio.descripcion, ' ', version.descripcion ) AS vehiculo,cuenta.nombre AS cuenta
                                            FROM operacion
                                            LEFT JOIN operacion_caja ON operacion_caja.id = operacion.tipo_operacion 
                                             LEFT JOIN clientes ON clientes.id = operacion.clienteventa_id
                                            LEFT JOIN clientes AS compra ON compra.id = operacion.clientecompra_id
                                            LEFT JOIN formapago ON formapago.operacion_id = operacion.id_interno AND 
                                            formapago.sitio_id = operacion.sitio_id
                                            LEFT JOIN tipostatus ON formapago.formapago_id = tipostatus.id 
                                            LEFT JOIN vehiculo ON vehiculo.id = operacion.vehiculo_id
                                            LEFT JOIN version ON vehiculo.version_id = version.id
                                            LEFT JOIN tipoannio AS annio on version.tipoannio_id = annio.id
                                            LEFT JOIN tipomodelo AS modelo ON version.tipomodelo_id = modelo.id
                                            LEFT JOIN tipomarca AS marca ON modelo.tipomarca_id = marca.id
                                            LEFT JOIN cuenta_banco ON cuenta_banco.formapago_id = formapago.id_interno
                                            AND cuenta_banco.sitio_id = formapago.sitio_id
                                            LEFT JOIN cuentas_bancarias AS cuenta ON cuenta.id = cuenta_banco.cuenta_id
                                            WHERE operacion.tipo_operacion != 1 AND operacion.corte_id = '$movresultadouno' AND formapago.tipostatus_id != 5211 $and ";

                                        //print_r($sql27);

                                            $resvehi = mysqli_query($mysqli, $sql27);
                                            $tIng = 0; 
                                            while ($row = mysqli_fetch_array($resvehi)) {
                                                    
                                                 $a = $row['saldoanterior'] + $row['importe'];
                                                 $tIng = $tIng + $a;
                                                ?>
                                        <tr style="background:#EFEFEF;">
                                            <td style="border-radius:30px 0px 0px 30px"></td>
                                            <td style="border-radius:0px 0px 0px 0px"><?php echo utf8_encode($row['movimiento'])?></td>
                                            <?php
                                            if ($row['fechaexpedicion'] == '') { ?>
                                                <td style="border-radius:0px 0px 0px 0px"><?php echo date("d/m/Y",strtotime($row['fecha'])); ?></td>
                                            <?php    
                                            }elseif ($row['fechaexpedicion'] != '') {?>
                                                <td style="border-radius:0px 0px 0px 0px"><?php echo date("d/m/Y",strtotime($row['fechaexpedicion'])); ?></td>
                                            <?php
                                            }
                                             ?>   
                                            <?php if ($row['idventa'] == '1') {?>
                                            <td style="border-radius:0px 0px 0px 0px"><?php echo ($row['clientecompra']); ?></td>
                                            <?php 
                                            } elseif ($row['idcompra'] == '1') {
                                            ?>
                                            <td style="border-radius:0px 0px 0px 0px"><?php echo ($row['clienteventa']); ?></td>
                                            <?php 
                                            }elseif ($row['idcompra'] == '1' && $row['idventa'] == '1') { ?>
                                            <td style="border-radius:0px 0px 0px 0px">PROMOTORA AUTOS DEL CAMPESTRE SA de CV</td>
                                            <?php
                                            }
                                            ?>
                                            <td style="border-radius:0px 0px 0px 0px"><?php echo $row['referencia']; ?></td>
                                            <td style="border-radius:0px 0px 0px 0px"><?php echo $row['vehiculo']; ?></td>
                                            <td style="border-radius:0px 0px 0px 0px"><?php echo $row['formapago'].' '.$row['cuenta']; ?></td>
                                            <td style="border-radius:0px 30px 30px 0px">$<?php if ($row['importe'] == '') {
                                                echo number_format($row['saldoanterior'],2);
                                            }else {
                                                echo number_format($row['importe'],2);
                                            }  ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Examples -->
            
            <!-- #END# Exportable Table -->
        </div>
    </section>
    


<script>
            var pfHeaderImgUrl = '';var pfHeaderTagline = '';var pfdisableClickToDel = 0;var pfHideImages = 0;var pfImageDisplayStyle = 'right';var pfDisablePDF = 0;var pfDisableEmail = 0;var pfDisablePrint = 0;var pfCustomCSS = '';var pfBtVersion='2';(function(){var js,pf;pf=document.createElement('script');pf.type='text/javascript';pf.src='//cdn.printfriendly.com/printfriendly.js';document.getElementsByTagName('head')[0].appendChild(pf)})();
        </script>
        
            <script type="text/javascript">


     function MASK(form, n, mask, format) {



  if (format == "undefined") format = false;



  if (format || NUM(n)) {



    dec = 0, point = 0;



    x = mask.indexOf(".")+1;



    if (x) { dec = mask.length - x; }







    if (dec) {



      n = NUM(n, dec)+"";



      x = n.indexOf(".")+1;



      if (x) { point = n.length - x; } else { n += "."; }



    } else {



      n = NUM(n, 0)+"";



    } 



    for (var x = point; x < dec ; x++) {



      n += "0";



    }



    x = n.length, y = mask.length, XMASK = "";



    while ( x || y ) {



      if ( x ) {



        while ( y && "#0.".indexOf(mask.charAt(y-1)) == -1 ) {



          if ( n.charAt(x-1) != "-")



            XMASK = mask.charAt(y-1) + XMASK;



          y--;



        }



        XMASK = n.charAt(x-1) + XMASK, x--;



      } else if ( y && "0".indexOf(mask.charAt(y-1))+1 ) {



        XMASK = mask.charAt(y-1) + XMASK;



      }



      if ( y ) { y-- }



    }



  } else {



     XMASK="";



  }



  if (form) { 



    form.value = XMASK;



    if (NUM(n)<0) {



      form.style.color="#FF0000";



    } else {



      form.style.color="#000000";



    }



  }



  return XMASK;



}







// Convierte una cadena alfanumÃ©rica a numÃ©rica (incluyendo formulas aritmÃ©ticas)



//



// s   = cadena a ser convertida a numÃ©rica



// dec = numero de decimales a redondear



//



// La funciÃ³n devuelve el numero redondeado







function NUM(s, dec) {



  for (var s = s+"", num = "", x = 0 ; x < s.length ; x++) {



    c = s.charAt(x);



    if (".-+/*".indexOf(c)+1 || c != " " && !isNaN(c)) { num+=c; }



  }



  if (isNaN(num)) { num = eval(num); }



  if (num == "")  { num=0; } else { num = parseFloat(num); }



  if (dec != undefined) {



    r=.5; if (num<0) r=-r;



    e=Math.pow(10, (dec>0) ? dec : 0 );



    return parseInt(num*e+r) / e;



  } else {



    return num;



  }



}

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
</body>

</html>