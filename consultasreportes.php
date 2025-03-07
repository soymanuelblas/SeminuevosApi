<?php
include "variables.php";
$sitioSession = $_SESSION["sitio"];
$usuario = $_SESSION["id"];

$total1a = 0;
$total1b = 0;
$total1c = 0;
$total2a = 0;
$total2b = 0;
$total3a = 0;
$total3b = 0;
$total3c = 0;
$total3d = 0;
$total3e = 0;
$total3f = 0;


$sql3 = "SELECT prospecto.id 
                      FROM prospecto 
                      LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id 
                      LEFT JOIN seguimiento ON seguimiento.oportunidad_id = oportunidad.id 
                      WHERE oportunidad.tipostatus_id = '5166' AND prospecto.sitio_id = '$sitiocambio' AND DATE(seguimiento.fechacontacto)
                      BETWEEN '$fecha_ini' AND '$fecha_fin' GROUP BY prospecto.id";
$reslu3 = mysqli_query($mysqli, $sql3);
$total_count =mysqli_num_rows($reslu3);

$sql4 = "SELECT prospecto.id  
                      FROM prospecto 
                      LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id 
                      LEFT JOIN seguimiento ON seguimiento.oportunidad_id = oportunidad.id 
                      WHERE oportunidad.tipostatus_id = '5163' AND prospecto.sitio_id = '$sitiocambio' AND DATE(seguimiento.fechacontacto) 
                      BETWEEN '$fecha_ini' AND '$fecha_fin' GROUP BY prospecto.id";

$reslu4 = mysqli_query($mysqli, $sql4);
$total_count1 =mysqli_num_rows($reslu4);

$sql5 = "SELECT prospecto.id
                      FROM prospecto 
                      LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id 
                      LEFT JOIN seguimiento ON seguimiento.oportunidad_id = oportunidad.id
                      WHERE oportunidad.tipostatus_id = '5165' AND prospecto.sitio_id = '$sitiocambio' AND DATE(seguimiento.fechacontacto) BETWEEN '$fecha_ini' AND '$fecha_fin' GROUP BY prospecto.id";

$reslu5 = mysqli_query($mysqli, $sql5);
$total_count2 =mysqli_num_rows($reslu5);

$sqllograda = "SELECT prospecto.id
                      FROM prospecto 
                      LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id 
                      LEFT JOIN seguimiento ON seguimiento.oportunidad_id = oportunidad.id 
                      WHERE oportunidad.tipostatus_id = '5164' AND prospecto.sitio_id = '$sitiocambio' AND DATE(seguimiento.fechacontacto) BETWEEN '$fecha_ini' AND '$fecha_fin' GROUP BY prospecto.id ";
$reslulograda = mysqli_query($mysqli, $sqllograda);
$total_count3 =mysqli_num_rows($reslulograda);

$total_count;
$total_count1;
$total_count2;
$total_count3;
$total = $total_count + $total_count1 + $total_count2+ $total_count3;

$porcentajepausa = number_format(($total_count * 100) / $total,2);
$porcentajeproceso = number_format(($total_count1 * 100) / $total,2);
$porcentajenolograda = number_format(($total_count2 * 100) / $total,2);
$porcentajelograda = number_format(($total_count3 * 100) / $total,2);

$sql6 = "SELECT MAX(seguimiento.id) AS filtro
                  FROM prospecto 
                  LEFT JOIN oportunidad ON oportunidad.prospecto_id = prospecto.id 
                  LEFT JOIN seguimiento ON seguimiento.oportunidad_id = oportunidad.id
                  WHERE DATE(seguimiento.fechacontacto) BETWEEN '$fecha_ini' AND '$fecha_fin' AND prospecto.sitio_id = '$sitiocambio' GROUP BY prospecto.id";
$resultado = $mysqli->query($sql6);
  while($row = $resultado->fetch_array(MYSQLI_ASSOC)) {
      $filtro = $row['filtro'];

$sql7 = "SELECT tipostatus_id FROM seguimiento WHERE id = '$filtro'";
                $resultado7 = $mysqli->query($sql7);
  while($row7 = $resultado7->fetch_array(MYSQLI_ASSOC)) {
                  
      $filtro7 = $row7['tipostatus_id'];
            
        if ($filtro7 == '5508') {
            $total1a ++;
        }
                   
        if ($filtro7 == '5184') {
            $total1b ++;
        }
                   
        if ($filtro7 == '5185') {
            $total1c ++;
        }
                   
        if ($filtro7 == '5186') {
            $total2a ++;
        }
                   
        if ($filtro7 == '5187') {
            $total2b ++;
        }
                   
        if ($filtro7 == '5188') {
          $total3a ++;
        }
                   
        if ($filtro7 == '5189') {
          $total3b ++;
        }
                   
        if ($filtro7 == '5190') {
          $total3c ++;
        }
                   
        if ($filtro7 == '5191') {
          $total3d ++;
        }
                   
        if ($filtro7 == '5192') {
          $total3e ++;
        }
                   
        if ($filtro7 == '5193') {
          $total3f ++;
        }

        if ($filtro7 == '5194') {
          $total4a ++;
        }

        if ($filtro7 == '5195') {
         $total4b ++;
        }

        if ($filtro7 == '5196') {
          $total5 ++;
        }

        if ($filtro7 == '5197') {
          $total0a ++;
        }

        if ($filtro7 == '5198') {
          $total0b ++;
        }

        if ($filtro7 == '5199') {
          $total0c ++;
        }

        if ($filtro7 == '5500') {
          $total0d ++;
        }

        if ($filtro7 == '5501') {
          $total0e ++;
        }

        if ($filtro7 == '5502') {
          $total0f ++;
        }

        if ($filtro7 == '5503') {
          $total0g ++;
        }

        if ($filtro7 == '5504') {
          $total0h ++;
        }

        if ($filtro7 == '5505') {
          $total0i ++;
        }

        if ($filtro7 == '5506') {
          $total0j ++;
        }

        if ($filtro7 == '5507') {
          $total6a ++;
        }

     }
 }

$totalregistros = $total1a + $total1b+ $total1c+ $total2a+ $total2b+ $total3a+ $total3b+ $total3c+ $total3d+ $total3e+ $total3f + + $total4a + $total4b + $total5 + $total0a + $total0b + $total0c + $total0d + $total0e + $total0f + $total0g + $total0h + $total0i + $total0j + $total6a;
mysqli_close($mysqli);


include("conexion.php");
$sql7 = "CALL reporte_seguimientos('$fecha_ini','$fecha_fin','$sitiocambio');";
$resultad7 = mysqli_query($mysqli, $sql7);
mysqli_close($mysqli);


?>


