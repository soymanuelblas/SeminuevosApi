<?php
session_start();
        include("conexion.php");
            $tildes = $mysqli->query("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente //$conquery("SET NAMES 'utf8'"); //Para que se inserten las tildes correctamente
if(!isset($_SESSION["nombre"])){
header('Location:login.php');
}
$sitioSession = $_SESSION["sitio"];
$usuario = $_SESSION["id"];
mysqli_close($mysqli);

$fecha_ini = $_REQUEST["fechaini"];
$fecha_fin = $_REQUEST["fechafin"];
include("conexion.php");
$sql9 = "CALL reporte_oportunidad_2('$fecha_ini','$fecha_fin','$sitioSession');";
$resultad9 = mysqli_query($mysqli, $sql9);
mysqli_close($mysqli);


include 'pdf_plantilla_oportunidades.php';
    $pdf = new myPDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetMargins(5,5,5);
    $pdf->SetFillColor(232,232,232);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetXY(60, 10);
    $pdf->SetFont('Arial','B',11);
    $pdf->MultiCell(90,5, 'Reporte Detallado de Oportunidades', 0, 'C');
    $pdf->Ln();
    $pdf->SetFont('Arial','I',7);
    $pdf->Cell(2,6,'');
    $pdf->Cell(27,5, 'Contacto esperado' ,1,0,'C',0);
    $pdf->Cell(27,5, 'Contacto' ,1,0,'C',0);
    $pdf->Cell(40,5, 'Dias de atraso' ,1,0,'C',0);
    $pdf->Cell(60,5, 'Status' ,1,0,'C',0);
    $pdf->Cell(22,5, 'Canal' ,1,0,'C',0);
    $pdf->Cell(22,5, 'Asesor' ,1,0,'C',0);
    $pdf->Ln();
    $pdf->Cell(2,6,'');
    $pdf->Cell(198,5, 'Observacion' ,1,0,'C',0);
    $pdf->Ln();
    $pdf->Ln();
    while ($row = mysqli_fetch_array($resultad9)) 
	{

	$primerstatusid = $row['primerstatusid'];
    $ultimostatusid = $row['ultimostatusid'];
    $valor = $row['resta'];

	$oportunidadid = $row['id'];
	include("conexion.php");
	$sql10 = "SELECT s.id AS id, p.nombre AS nombre, t.descripcion AS canal, tt.descripcion AS status, u.nombre AS usuario, s.fechacontacto AS fecha1, s.fechacontactoesperado AS fecha2, s.observacion AS observacion, ti.descripcion AS tipov, tm.descripcion AS marca, tmo.descripcion AS modelo
		FROM seguimiento AS s
		LEFT JOIN oportunidad AS o ON o.id = s.oportunidad_id
		LEFT JOIN prospecto AS p ON p.id = o.prospecto_id
		LEFT JOIN tipostatus AS t ON t.id = s.canal_id
		LEFT JOIN tipostatus AS tt ON tt.id = s.tipostatus_id
		LEFT JOIN usuario AS u ON u.id = s.usuario_id
		LEFT JOIN tipostatus AS ti ON ti.id =o.tipovehiculo_id
		LEFT JOIN tipomarca AS tm ON tm.id = o.tipomarca_id
		LEFT JOIN tipomodelo AS tmo ON tmo.id = o.tipomodelo_id
		WHERE o.id = '$oportunidadid' AND s.id BETWEEN '$primerstatusid' AND '$ultimostatusid' ORDER BY s.id ASC ";
		$resultad10 = mysqli_query($mysqli, $sql10);

		
		if ($row['prospecto']  == 'VENTA') {
			$pdf->SetFont('Arial','B',7);
			if ($row['vehiculo'] != '') {
				$pdf->Cell(180,6,'('.$row['statusoportunidad'].') '.strtoupper(($row['nombre'])).' COMPRA UN '. $row['vehiculo'],0);
			}else{
				$pdf->Cell(180,6,'('.$row['statusoportunidad'].') '.strtoupper(($row['nombre'])).' COMPRA UN '. $row['opcion'],0);
			}
			$pdf->Cell(20,6,'AV: '.$valor,0,0,'C',0);
			$pdf->Ln();
			while ($row1 = mysqli_fetch_array($resultad10)) 
			{	

				$fecha1 = new DateTime($row1['fecha2']);//fecha inicial
				$fecha2 = new DateTime($row1['fecha1']);//fecha de cierre

				$intervalo = $fecha1->diff($fecha2);

				if ($row1['fecha2'] >= $row1['fecha1']) {
					$diferencia = $intervalo->format('%d dias %H horas %i minutos antes');//00 años 0 meses 0 días 08 horas 0 minutos 0 segundos
				}else{
					$diferencia = $intervalo->format('%d dias %H horas %i minutos');//00 años 0 meses 0 días 08 horas 0 minutos 0 segundos
				}
				$pdf->SetFont('Arial','I',7);				
				$pdf->Cell(3,4,'');
				$pdf->Cell(25,4,'('.$fecha1->format('Y-m-d H:i').')' ,0);
				$pdf->Cell(25,4,'('.$fecha2->format('Y-m-d H:i').')' ,0);
				$pdf->Cell(40,4,$diferencia ,0);
				$pdf->Cell(60,4,$row1['status'] ,0);
				$pdf->Cell(22,4,$row1['canal'] ,0);
				$pdf->Cell(22,4,$row1['usuario'] ,0);
				$pdf->Ln();
				$pdf->Cell(3,4,'');
				$pdf->SetFont('Arial','I',6);
				$pdf->MultiCell(197,3,utf8_decode($row1['observacion']) ,0,1);
				$pdf->Ln(2);
				
			}
				
		}elseif ($row['prospecto']  == 'COMPRA') {
			$pdf->SetFont('Arial','B',7);
			if ($row['vehiculo'] != '') {
				$pdf->Cell(180,6,'('.$row['statusoportunidad'].') '.strtoupper(utf8_encode($row['nombre'])).' VENDE UN '. $row['vehiculo'],0);
			}else{
				$pdf->Cell(180,6,'('.$row['statusoportunidad'].') '.strtoupper(utf8_encode($row['nombre'])).' VENDE UN '. $row['opcion'],0);
			}
			$pdf->Cell(20,6,'AV: '.$valor,0,0,'C',0);
			$pdf->Ln();
			while ($row1 = mysqli_fetch_array($resultad10)) 
			{	

				$fecha1 = new DateTime($row1['fecha2']);//fecha inicial
				$fecha2 = new DateTime($row1['fecha1']);//fecha de cierre

				$intervalo = $fecha1->diff($fecha2);

				if ($row1['fecha2'] >= $row1['fecha1']) {
					$diferencia = $intervalo->format('%d dias %H horas %i minutos antes');//00 años 0 meses 0 días 08 horas 0 minutos 0 segundos
				}else{
					$diferencia = $intervalo->format('%d dias %H horas %i minutos');//00 años 0 meses 0 días 08 horas 0 minutos 0 segundos
				}
				$pdf->SetFont('Arial','I',7);				
				$pdf->Cell(3,4,'');
				$pdf->Cell(25,4,'('.$fecha1->format('Y-m-d H:i').')' ,0);
				$pdf->Cell(25,4,'('.$fecha2->format('Y-m-d H:i').')' ,0);
				$pdf->Cell(40,4,$diferencia ,0);
				$pdf->Cell(60,4,$row1['status'] ,0);
				$pdf->Cell(22,4,$row1['canal'] ,0);
				$pdf->Cell(22,4,$row1['usuario'] ,0);
				$pdf->Ln();
				$pdf->Cell(3,4,'');
				$pdf->SetFont('Arial','I',6);
				$pdf->MultiCell(197,3,$row1['observacion'] ,0,1);
				$pdf->Ln();	
			}
		}
		$pdf->MultiCell(0,3, $pdf->Image('images/linea1.png', $pdf->GetX()+1, $pdf->GetY()+3, 198),0,"C");
		$pdf->Ln();
			

	}
    $modo="I";
    $nombre_archivo="Corte de Caja ". $usuario.".pdf"; 
    $pdf->Output($nombre_archivo,$modo);
    $doc = $pdf->Output($nombre_archivo, 'D');  


?>