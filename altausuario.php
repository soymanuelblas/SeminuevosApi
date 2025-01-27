<?php
include "variables.php";

$nombre = $_POST['nombre'];
$nombre = strtoupper($nombre);
$usuario = $_POST['usuario'];
$contra = $_POST['contra'];
$statuscliente = $_POST['statuscliente'];
$sitio = $_POST['sitio'];
$llave="autobahn1224";
$encriptado = base64_encode($contra);
$rol = $_POST['rol'];



///clientes///
$seccion1 = $_POST['seccion1']; "<br>";
if(isset($_POST['seccion1'])){ 
        $seccion1 = $_POST['seccion1']; "<br>";
        $alta = $_POST['alta']; "<br>";
if(isset($_POST['alta'])){ 
        $alta = $_POST['alta']; "<br>";
    }else{
	$alta = 0;
	"<br>";
} 
$modificacion = $_POST['modificacion'];"<br>";
if (isset($_POST['modificacion'])) {
	$modificacion = $_POST['modificacion'];"<br>";
}else{
	$modificacion = 0;
	"<br>";
}
$consulta = $_POST['consulta'];"<br>";
if (isset($_POST['consulta'])) {
	$consulta = $_POST['consulta'];"<br>";
}else{
	$consulta = 0;
	"<br>";
}
$consulta1 = $_POST['consulta1'];"<br>";
if (isset($_POST['consulta1'])) {
	$consulta1 = $_POST['consulta1'];"<br>";
}else{
	$consulta1 = 0;
	"<br>";
}
$reporteclientes = $_POST['reporteclientes'];"<br>";
if (isset($_POST['reporteclientes'])) {
	$reporteclientes = $_POST['reporteclientes'];"<br>";
}else{
	$reporteclientes = 0;
	"<br>";
}

    }else{
	$seccion1 = 0;
	$alta = 0;
	$modificacion = 0;
	$consulta = 0;
	$consulta1 = 0;
	$reporteclientes = 0;
	"<br>";
}

//operaciones//
$seccion2 = $_POST['seccion2']; "<br>";
if(isset($_POST['seccion2'])){ 
        $seccion2 = $_POST['seccion2']; "<br>";
        $altaventa = $_POST['altaventa'];"<br>";
if (isset($_POST['altaventa'])) {
	$altaventa = $_POST['altaventa'];"<br>";
}else{
	$altaventa = 0;
	"<br>";
}
 $modificacionventa = $_POST['modificacionventa'];"<br>";
if (isset($_POST['modificacionventa'])) {
	$modificacionventa = $_POST['modificacionventa'];"<br>";
}else{
	$modificacionventa = 0;
	"<br>";
}
 $consultaventa = $_POST['consultaventa'];"<br>";
if (isset($_POST['consultaventa'])) {
	$consultaventa = $_POST['consultaventa'];"<br>";
}else{
	$consultaventa = 0;
	"<br>";
}
 $altacompra = $_POST['altacompra'];"<br>";
if (isset($_POST['altacompra'])) {
	$altacompra = $_POST['altacompra'];"<br>";
}else{
	$altacompra = 0;
	"<br>";
}
 $modificacioncompra = $_POST['modificacioncompra'];"<br>";
if (isset($_POST['modificacioncompra'])) {
	$modificacioncompra = $_POST['modificacioncompra'];"<br>";
}else{
	$modificacioncompra = 0;
	"<br>";
}
 $consultacompra = $_POST['consultacompra'];"<br>";
if (isset($_POST['consultacompra'])) {
	$consultacompra = $_POST['consultacompra'];"<br>";
}else{
	$consultacompra = 0;
	"<br>";
}
////////////////////////////////////////////////////////////////////
 $altaconsignacion = $_POST['altaconsignacion'];"<br>";
if (isset($_POST['altaconsignacion'])) {
	$altaconsignacion = $_POST['altaconsignacion'];"<br>";
}else{
	$altaconsignacion = 0;
	"<br>";
}
 $modificacionconsignacion = $_POST['modificacionconsignacion'];"<br>";
if (isset($_POST['modificacionconsignacion'])) {
	$modificacionconsignacion = $_POST['modificacionconsignacion'];"<br>";
}else{
	$modificacionconsignacion = 0;
	"<br>";
}
 $consultaconsignacion = $_POST['consultaconsignacion'];"<br>";
if (isset($_POST['consultaconsignacion'])) {
	$consultaconsignacion = $_POST['consultaconsignacion'];"<br>";
}else{
	$consultaconsignacion = 0;
	"<br>";
}
 $altaintermediacion = $_POST['altaintermediacion'];"<br>";
if (isset($_POST['altaintermediacion'])) {
	$altaintermediacion = $_POST['altaintermediacion'];"<br>";
}else{
	$altaintermediacion = 0;
	"<br>";
}
 $modificacionintermediacion = $_POST['modificacionintermediacion'];"<br>";
if (isset($_POST['modificacionintermediacion'])) {
	$modificacionintermediacion = $_POST['modificacionintermediacion'];"<br>";
}else{
	$modificacionintermediacion = 0;
	"<br>";
}
 $consultaintermediacion = $_POST['consultaintermediacion'];"<br>";
if (isset($_POST['consultaintermediacion'])) {
	$consultaintermediacion = $_POST['consultaintermediacion'];"<br>";
}else{
	$consultaintermediacion = 0;
	"<br>";
}
$reporteventas = $_POST['reporteventas'];"<br>";
if (isset($_POST['reporteventas'])) {
	$reporteventas = $_POST['reporteventas'];"<br>";
}else{
	$reporteventas = 0;
	"<br>";
}
$reportecompras = $_POST['reportecompras'];"<br>";
if (isset($_POST['reportecompras'])) {
	$reportecompras = $_POST['reportecompras'];"<br>";
}else{
	$reportecompras = 0;
	"<br>";
}
$reporteconsignacion = $_POST['reporteconsignacion'];"<br>";
if (isset($_POST['reporteconsignacion'])) {
	$reporteconsignacion = $_POST['reporteconsignacion'];"<br>";
}else{
	$reporteconsignacion = 0;
	"<br>";
}
$reporteintermediacion = $_POST['reporteintermediacion'];"<br>";
if (isset($_POST['reporteintermediacion'])) {
	$reporteintermediacion = $_POST['reporteintermediacion'];"<br>";
}else{
	$reporteintermediacion = 0;
	"<br>";
}
$consultacartafactura = $_POST['consultacartafactura'];"<br>";
if (isset($_POST['consultacartafactura'])) {
	$consultacartafactura = $_POST['consultacartafactura'];"<br>";
}else{
	$consultacartafactura = 0;
	"<br>";
}
$consultarecibodoc = $_POST['consultarecibodoc'];"<br>";
if (isset($_POST['consultarecibodoc'])) {
	$consultarecibodoc = $_POST['consultarecibodoc'];"<br>";
}else{
	$consultarecibodoc = 0;
	"<br>";
}




    }else{
	$seccion2 = 0;
	$altaventa = 0;
 	$modificacionventa = 0;
 	$consultaventa = 0;
 	$altacompra = 0;
 	$modificacioncompra = 0;
	$consultacompra = 0;
	$altaconsignacion = 0;
	$modificacionconsignacion = 0;
	$consultaconsignacion = 0;
	$altaintermediacion = 0;
	$modificacionintermediacion = 0;
	$consultaintermediacion = 0;
	$reporteventas = 0;
	$reportecompras = 0;
	$reporteconsignacion = 0;
	$reporteintermediacion = 0;
	$consultacartafactura = 0;
	$consultarecibodoc = 0;
	"<br>";
}


//vehiculos//
$seccion3 = $_POST['seccion3']; "<br>";
if(isset($_POST['seccion3'])){ 
        $seccion3 = $_POST['seccion3']; "<br>";
        $altavehiculo = $_POST['altavehiculo'];"<br>";
if (isset($_POST['altavehiculo'])) {
	$altavehiculo = $_POST['altavehiculo'];"<br>";
}else{
	$altavehiculo = 0;
	"<br>";
}
 $modificacionvehiculo = $_POST['modificacionvehiculo'];"<br>";
if (isset($_POST['modificacionvehiculo'])) {
	$modificacionvehiculo = $_POST['modificacionvehiculo'];"<br>";
}else{
	$modificacionvehiculo = 0;
	"<br>";
}
 $consultavehiculo = $_POST['consultavehiculo'];"<br>";
if (isset($_POST['consultavehiculo'])) {
	$consultavehiculo = $_POST['consultavehiculo'];"<br>";
}else{
	$consultavehiculo = 0;
	"<br>";
}
 $altatenencia = $_POST['altatenencia'];"<br>";
if (isset($_POST['altatenencia'])) {
	$altatenencia = $_POST['altatenencia'];"<br>";
}else{
	$altatenencia = 0;
	"<br>";
}
 $modificaciontenencia = $_POST['modificaciontenencia'];"<br>";
if (isset($_POST['modificaciontenencia'])) {
	$modificaciontenencia = $_POST['modificaciontenencia'];"<br>";
}else{
	$modificaciontenencia = 0;
	"<br>";
}
 $consultatenencia = $_POST['consultatenencia'];"<br>";
if (isset($_POST['consultatenencia'])) {
	$consultatenencia = $_POST['consultatenencia'];"<br>";
}else{
	$consultatenencia = 0;
	"<br>";
}
 $altafactura = $_POST['altafactura'];"<br>";
if (isset($_POST['altafactura'])) {
	$altafactura = $_POST['altafactura'];"<br>";
}else{
	$altafactura = 0;
	"<br>";
}
 $modificacionfactura = $_POST['modificacionfactura'];"<br>";
if (isset($_POST['modificacionfactura'])) {
	$modificacionfactura = $_POST['modificacionfactura'];"<br>";
}else{
	$modificacionfactura = 0;
	"<br>";
}
 $consultafactura = $_POST['consultafactura'];"<br>";
if (isset($_POST['consultafactura'])) {
	$consultafactura = $_POST['consultafactura'];"<br>";
}else{
	$consultafactura = 0;
	"<br>";
}

$consultafacturaimagen1 = $_POST['consultafacturaimagen1'];"<br>";
if (isset($_POST['consultafacturaimagen1'])) {
	$consultafacturaimagen1 = $_POST['consultafacturaimagen1'];"<br>";
}else{
	$consultafacturaimagen1 = 0;
	"<br>";
}

$consultafacturaimagen = $_POST['consultafacturaimagen'];"<br>";
if (isset($_POST['consultafacturaimagen'])) {
	$consultafacturaimagen = $_POST['consultafacturaimagen'];"<br>";
}else{
	$consultafacturaimagen = 0;
	"<br>";
}
 
 $altapublicacion = $_POST['altapublicacion'];"<br>";
if (isset($_POST['altapublicacion'])) {
	$altapublicacion = $_POST['altapublicacion'];"<br>";
}else{
	$altapublicacion = 0;
	"<br>";
}
 $modificacionpublicacion = $_POST['modificacionpublicacion'];"<br>";
if (isset($_POST['modificacionpublicacion'])) {
	$modificacionpublicacion = $_POST['modificacionpublicacion'];"<br>";
}else{
	$modificacionpublicacion = 0;
	"<br>";
}
 $consultapublicacion = $_POST['consultapublicacion'];"<br>";
if (isset($_POST['consultapublicacion'])) {
	$consultapublicacion = $_POST['consultapublicacion'];"<br>";
}else{
	$consultapublicacion = 0;
	"<br>";
}
 $altaimagenen = $_POST['altaimagenen'];"<br>";
if (isset($_POST['altaimagenen'])) {
	$altaimagenen = $_POST['altaimagenen'];"<br>";
}else{
	$altaimagenen = 0;
	"<br>";
}
 $modificacionimagenen = $_POST['modificacionimagenen'];"<br>";
if (isset($_POST['modificacionimagenen'])) {
	$modificacionimagenen = $_POST['modificacionimagenen'];"<br>";
}else{
	$modificacionimagenen = 0;
	"<br>";
}
 $consultaimagenen = $_POST['consultaimagenen'];"<br>";
if (isset($_POST['consultaimagenen'])) {
	$consultaimagenen = $_POST['consultaimagenen'];"<br>";
}else{
	$consultaimagenen = 0;
	"<br>";
}
$eliminarimagenes = $_POST['eliminarimagenes'];"<br>";
if (isset($_POST['eliminarimagenes'])) {
	$eliminarimagenes = $_POST['eliminarimagenes'];"<br>";
}else{
	$eliminarimagenes = 0;
	"<br>";
}
 $altafina = $_POST['altafina'];"<br>";
if (isset($_POST['altafina'])) {
	$altafina = $_POST['altafina'];"<br>";
}else{
	$altafina = 0;
	"<br>";
}
 $modificacionfina = $_POST['modificacionfina'];"<br>";
if (isset($_POST['modificacionfina'])) {
	$modificacionfina = $_POST['modificacionfina'];"<br>";
}else{
	$modificacionfina = 0;
	"<br>";
}
 $consultafina = $_POST['consultafina'];"<br>";
if (isset($_POST['consultafina'])) {
	$consultafina = $_POST['consultafina'];"<br>";
}else{
	$consultafina = 0;
	"<br>";
}
 $altaatributo = $_POST['altaatributo'];"<br>";
if (isset($_POST['altaatributo'])) {
	$altaatributo = $_POST['altaatributo'];"<br>";
}else{
	$altaatributo = 0;
	"<br>";
}
 $modificacionatributo = $_POST['modificacionatributo'];"<br>";
if (isset($_POST['modificacionatributo'])) {
	$modificacionatributo = $_POST['modificacionatributo'];"<br>";
}else{
	$modificacionatributo = 0;
	"<br>";
}
 $consultaatributo = $_POST['consultaatributo'];"<br>";
if (isset($_POST['consultaatributo'])) {
	$consultaatributo = $_POST['consultaatributo'];"<br>";
}else{
	$consultaatributo = 0;
	"<br>";
}
 $altaextra = $_POST['altaextra'];"<br>";
if (isset($_POST['altaextra'])) {
	$altaextra = $_POST['altaextra'];"<br>";
}else{
	$altaextra = 0;
	"<br>";
}
 $modificacionextra = $_POST['modificacionextra'];"<br>";
if (isset($_POST['modificacionextra'])) {
	$modificacionextra = $_POST['modificacionextra'];"<br>";
}else{
	$modificacionextra = 0;
	"<br>";
}
 $consultaextra = $_POST['consultaextra'];"<br>";
if (isset($_POST['consultaextra'])) {
	$consultaextra = $_POST['consultaextra'];"<br>";
}else{
	$consultaextra = 0;
	"<br>";
}
 $altacaracteristica = $_POST['altacaracteristica'];"<br>";
if (isset($_POST['altacaracteristica'])) {
	$altacaracteristica = $_POST['altacaracteristica'];"<br>";
}else{
	$altacaracteristica = 0;
	"<br>";
}
 $modificacioncaracteristica = $_POST['modificacioncaracteristica'];"<br>";
if (isset($_POST['modificacioncaracteristica'])) {
	$modificacioncaracteristica = $_POST['modificacioncaracteristica'];"<br>";
}else{
	$modificacioncaracteristica = 0;
	"<br>";
}
 $consultacaracteristica = $_POST['consultacaracteristica'];"<br>";
if (isset($_POST['consultacaracteristica'])) {
	$consultacaracteristica = $_POST['consultacaracteristica'];"<br>";
}else{
	$consultacaracteristica = 0;
	"<br>";
}
 $reportevehiculos = $_POST['reportevehiculos'];"<br>";
if (isset($_POST['reportevehiculos'])) {
	$reportevehiculos = $_POST['reportevehiculos'];"<br>";
}else{
	$reportevehiculos = 0;
	"<br>";
}

    }else{
	$seccion3 = 0;
	 $altavehiculo = 0;
	 $modificacionvehiculo = 0;
	 $consultavehiculo = 0;
	 $altatenencia = 0;
	 $modificaciontenencia = 0;
	 $consultatenencia = 0;
	 $altafactura = 0;
	 $modificacionfactura = 0;
	 $consultafactura = 0;
	 $consultafacturaimagen1 = 0;
	 $consultafacturaimagen = 0;
	 $altapublicacion = 0;
	 $modificacionpublicacion = 0;
	 $consultapublicacion = 0;
	 $altaimagenen = 0;
	 $modificacionimagenen = 0;
	 $consultaimagenen = 0;
	 $altafina = 0;
	 $modificacionfina = 0;
	 $consultafina = 0;
	 $altaatributo = 0;
	 $modificacionatributo = 0;
	 $consultaatributo = 0;
	 $altaextra = 0;
	 $modificacionextra = 0;
	 $consultaextra = 0;
	 $altacaracteristica = 0;
	 $modificacioncaracteristica = 0;
	 $consultacaracteristica = 0;
	 $reportevehiculos = 0;
	 $eliminarimagenes = 0;
	"<br>";
}

//sitios// 
$seccion4 = $_POST['seccion4']; "<br>";
if(isset($_POST['seccion4'])){ 
        $seccion4 = $_POST['seccion4']; "<br>";
        $altasitio = $_POST['altasitio'];"<br>";
if (isset($_POST['altasitio'])) {
	$altasitio = $_POST['altasitio'];"<br>";
}else{
	$altasitio = 0;
	"<br>";
}
 $modificacionsitio = $_POST['modificacionsitio']; "<br>";
if (isset($_POST['modificacionsitio'])) {
	$modificacionsitio = $_POST['modificacionsitio'];"<br>";
}else{
	$modificacionsitio = 0;
	"<br>";
}
 $consultasitio = $_POST['consultasitio'];"<br>";
if (isset($_POST['consultasitio'])) {
	$consultasitio = $_POST['consultasitio'];"<br>";
}else{
	$consultasitio = 0;
	"<br>";
}


    }else{
	$seccion4 = 0;
	$altasitio = 0;
	$modificacionsitio = 0;
	$consultasitio = 0;
	"<br>";
}

//usuarios//
$seccion5 = $_POST['seccion5']; "<br>";
if(isset($_POST['seccion5'])){ 
        $seccion5 = $_POST['seccion5']; "<br>";
        $altausuario = $_POST['altausuario'];"<br>";
if (isset($_POST['altausuario'])) {
	$altausuario = $_POST['altausuario'];"<br>";
}else{
	$altausuario = 0;
	"<br>";
}
 $modificacionusuario = $_POST['modificacionusuario']; "<br>";
if (isset($_POST['modificacionusuario'])) {
	$modificacionusuario = $_POST['modificacionusuario'];"<br>";
}else{
	$modificacionusuario = 0;
	"<br>";
}
 $consultausuario = $_POST['consultausuario'];"<br>";
if (isset($_POST['consultausuario'])) {
	$consultausuario = $_POST['consultausuario'];"<br>";
}else{
	$consultausuario = 0;
	"<br>";
}
    }else{
	$seccion5 = 0;
	$altausuario = 0;
	$modificacionusuario = 0;
	$consultausuario = 0;
	"<br>";
}

//sucursales//
$seccion6 = $_POST['seccion6']; "<br>";
if(isset($_POST['seccion6'])){ 
        $seccion6 = $_POST['seccion6']; "<br>";
        $altasucursal = $_POST['altasucursal']; "<br>";
if(isset($_POST['altasucursal'])){ 
        $altasucursal = $_POST['altasucursal']; "<br>";
    }else{
	$altasucursal = 0;
	"<br>";
} 
$modificacionsucursal = $_POST['modificacionsucursal'];"<br>";
if (isset($_POST['modificacionsucursal'])) {
	$modificacionsucursal = $_POST['modificacionsucursal'];"<br>";
}else{
	$modificacionsucursal = 0;
	"<br>";
}
$consultasucursal = $_POST['consultasucursal'];"<br>";
if (isset($_POST['consultasucursal'])) {
	$sucursalsucursal = $_POST['consultasucursal'];"<br>";
}else{
	$consultasucursal = 0;
	"<br>";
}

 $cambiarsitio = $_POST['cambiarsitio'];"<br>";
if (isset($_POST['cambiarsitio'])) {
	$cambiarsitio = $_POST['cambiarsitio'];"<br>";
}else{
	$cambiarsitio = 0;
	"<br>";
}
    }else{
	$seccion6 = 0;
	$altasucursal = 0;
	$modificacionsucursal = 0;
	$consultasucursal = 0;
	$cambiarsitio = 0;
	"<br>";
}


//super usuario// 
$seccion7 = $_POST['seccion7']; "<br>";
if(isset($_POST['seccion7'])){ 
        $seccion7 = $_POST['seccion7']; "<br>";
        $altafinanciamiento = $_POST['altafinanciamiento'];"<br>";
if (isset($_POST['altafinanciamiento'])) {
	$altafinanciamiento = $_POST['altafinanciamiento'];"<br>";
}else{
	$altafinanciamiento = 0;
	"<br>";
}
 $modificacionfinanciamiento = $_POST['modificacionfinanciamiento']; "<br>";
if (isset($_POST['modificacionfinanciamiento'])) {
	$modificacionfinanciamiento = $_POST['modificacionfinanciamiento'];"<br>";
}else{
	$modificacionfinanciamiento = 0;
	"<br>";
}
 $consultafinanciamiento = $_POST['consultafinanciamiento'];"<br>";
if (isset($_POST['consultafinanciamiento'])) {
	$consultafinanciamiento = $_POST['consultafinanciamiento'];"<br>";
}else{
	$consultafinanciamiento = 0;
	"<br>";
}$altamodelo = $_POST['altamodelo'];"<br>";
if (isset($_POST['altamodelo'])) {
	$altamodelo = $_POST['altamodelo'];"<br>";
}else{
	$altamodelo = 0;
	"<br>";
}
 $modificacionmodelo = $_POST['modificacionmodelo'];"<br>";
if (isset($_POST['modificacionmodelo'])) {
	$modificacionmodelo = $_POST['modificacionmodelo'];"<br>";
}else{
	$modificacionmodelo = 0;
	"<br>";
}
$consultamodelo = $_POST['consultamodelo'];"<br>";
if (isset($_POST['consultamodelo'])) {
	$consultamodelo = $_POST['consultamodelo'];"<br>";
}else{
	$consultamodelo = 0;
	"<br>";
}
 $altaannio = $_POST['altaannio'];"<br>";
if (isset($_POST['altaannio'])) {
	$altaannio = $_POST['altaannio'];"<br>";
}else{
	$altaannio = 0;
	"<br>";
}
 $modificacionannio = $_POST['modificacionannio'];"<br>";
if (isset($_POST['modificacionannio'])) {
	$modificacionannio = $_POST['modificacionannio'];"<br>";
}else{
	$modificacionannio = 0;
	"<br>";
}
 $consultaannio = $_POST['consultaannio'];"<br>";
if (isset($_POST['consultaannio'])) {
	$consultaannio = $_POST['consultaannio'];"<br>";
}else{
	$consultaannio = 0;
	"<br>";
}
 $altamarca = $_POST['altamarca'];"<br>";
if (isset($_POST['altamarca'])) {
	$altamarca = $_POST['altamarca'];"<br>";
}else{
	$altamarca = 0;
	"<br>";
}
 $modificacionmarca = $_POST['modificacionmarca'];"<br>";
if (isset($_POST['modificacionmarca'])) {
	$modificacionmarca = $_POST['modificacionmarca'];"<br>";
}else{
	$modificacionmarca = 0;
	"<br>";
}
 $consultamarca = $_POST['consultamarca'];"<br>";
if (isset($_POST['consultamarca'])) {
	$consultamarca = $_POST['consultamarca'];"<br>";
}else{
	$consultamarca = 0;
	"<br>";
}
 $altaversion = $_POST['altaversion']; "<br>";
if (isset($_POST['altaversion'])) {
	$altaversion = $_POST['altaversion'];"<br>";
}else{
	$altaversion = 0;
	"<br>";
}
 $modificacionversion = $_POST['modificacionversion'];"<br>";
if (isset($_POST['modificacionversion'])) {
	$modificacionversion = $_POST['modificacionversion'];"<br>";
}else{
	$modificacionversion = 0;
	"<br>";
}
 $consultaversion = $_POST['consultaversion'];"<br>";
if (isset($_POST['consultaversion'])) {
	$consultaversion = $_POST['consultaversion'];"<br>";
}else{
	$consultaversion = 0;
	"<br>";
}

$verinfo = $_POST['verinfo'];"<br>";
if (isset($_POST['verinfo'])) {

	if ($_POST['verinfo'] == 1) {
		$porsitio = 1;
		$porrazon = 0;
	}else if ($_POST['verinfo'] == 2) {
		$porsitio = 0;
		$porrazon = 1;
	}
}else{
	$porsitio = 0;
	$porrazon = 0;
}

$altacuentas = $_POST['altacuentas']; "<br>";
if (isset($_POST['altacuentas'])) {
	$altacuentas = $_POST['altacuentas'];"<br>";
}else{
	$altacuentas = 0;
	"<br>";
}
 $modificarcuentas = $_POST['modificarcuentas'];"<br>";
if (isset($_POST['modificarcuentas'])) {
	$modificarcuentas = $_POST['modificarcuentas'];"<br>";
}else{
	$modificarcuentas = 0;
	"<br>";
}
 $consultacuentas = $_POST['consultacuentas'];"<br>";
if (isset($_POST['consultacuentas'])) {
	$consultacuentas = $_POST['consultacuentas'];"<br>";
}else{
	$consultacuentas = 0;
	"<br>";
}


    }else{
	$seccion7 = 0;
	$altafinanciamiento = 0;
	$modificacionfinanciamiento = 0;
	$consultafinanciamiento = 0;
	$altamodelo = 0;
	$modificacionmodelo = 0;
	$consultamodelo = 0;
	$altaannio = 0;
	$modificacionannio = 0;
	$consultaannio = 0;
	$altamarca = 0;
	$modificacionmarca = 0;
	$consultamarca = 0;
	$altaversion = 0;
	$modificacionversion = 0;
	$consultaversion = 0;
	$porsitio = 0;
	$porrazon = 0;
	$altacuentas = 0 ;
	$modificarcuentas = 0 ;
	$consultacuentas = 0 ;
	"<br>";
}



//Oportunidades/seguimientos/prospectos//
$seccion8 = $_POST['seccion8']; "<br>";
if(isset($_POST['seccion8'])){ 
        $seccion8 = $_POST['seccion8']; "<br>";
        $altaprospecto = $_POST['altaprospecto']; "<br>";
if(isset($_POST['altaprospecto'])){ 
        $altaprospecto = $_POST['altaprospecto']; "<br>";
    }else{
	$altaprospecto = 0;
	"<br>";
} 
$modificacionprospecto = $_POST['modificacionprospecto'];"<br>";
if (isset($_POST['modificacionprospecto'])) {
	$modificacionprospecto = $_POST['modificacionprospecto'];"<br>";
}else{
	$modificacionprospecto = 0;
	"<br>";
}
$consultaprospecto = $_POST['consultaprospecto'];"<br>";
if (isset($_POST['consultaprospecto'])) {
	$consultaprospecto = $_POST['consultaprospecto'];"<br>";
}else{
	$consultaprospecto = 0;
	"<br>";
}
$consultaprospectousuario = $_POST['consultaprospectousuario'];"<br>";
if (isset($_POST['consultaprospectousuario'])) {
	$consultaprospectousuario = $_POST['consultaprospectousuario'];"<br>";
}else{
	$consultaprospectousuario = 0;
	"<br>";
}
$altaseguimiento = $_POST['altaseguimiento'];"<br>";
if (isset($_POST['altaseguimiento'])) {
	$altaseguimiento = $_POST['altaseguimiento'];"<br>";
}else{
	$altaseguimiento = 0;
	"<br>";
}
$modificacionseguimiento = $_POST['modificacionseguimiento'];"<br>";
if (isset($_POST['modificacionseguimiento'])) {
	$modificacionseguimiento = $_POST['modificacionseguimiento'];"<br>";
}else{
	$modificacionseguimiento = 0;
	"<br>";
}
$consultaseguimiento = $_POST['consultaseguimiento'];"<br>";
if (isset($_POST['consultaseguimiento'])) {
	$consultaseguimiento = $_POST['consultaseguimiento'];"<br>";
}else{
	$consultaseguimiento = 0;
	"<br>";
}
$altaoportunidad = $_POST['altaoportunidad'];"<br>";
if (isset($_POST['altaoportunidad'])) {
	$altaoportunidad = $_POST['altaoportunidad'];"<br>";
}else{
	$altaoportunidad = 0;
	"<br>";
}
$modificacionoportunidad = $_POST['modificacionoportunidad'];"<br>";
if (isset($_POST['modificacionoportunidad'])) {
	$modificacionoportunidad = $_POST['modificacionoportunidad'];"<br>";
}else{
	$modificacionoportunidad = 0;
	"<br>";
}
$consultaoportunidad = $_POST['consultaoportunidad'];"<br>";
if (isset($_POST['consultaoportunidad'])) {
	$consultaoportunidad = $_POST['consultaoportunidad'];"<br>";
}else{
	$consultaoportunidad = 0;
	"<br>";
}
$consultaoportunidadusuario = $_POST['consultaoportunidadusuario'];"<br>";
if (isset($_POST['consultaoportunidadusuario'])) {
	$consultaoportunidadusuario = $_POST['consultaoportunidadusuario'];"<br>";
}else{
	$consultaoportunidadusuario = 0;
	"<br>";
}
$reportesegimiento = $_POST['reportesegimiento'];"<br>";
if (isset($_POST['reportesegimiento'])) {
	$reportesegimiento = $_POST['reportesegimiento'];"<br>";
}else{
	$reportesegimiento = 0;
	"<br>";
}
$reporteoportunidad = $_POST['reporteoportunidad'];"<br>";
if (isset($_POST['reporteoportunidad'])) {
	$reporteoportunidad = $_POST['reporteoportunidad'];"<br>";
}else{
	$reporteoportunidad = 0;
	"<br>";
}
    }else{
	$seccion8 = 0;
	$altaprospecto = 0;
	$modificacionprospecto = 0;
	$consultaprospecto = 0;
	$consultaprospectousuario = 0;
	$altaseguimiento = 0;
	$modificacionseguimiento = 0;
	$consultaseguimiento = 0;
	$altaoportunidad = 0;
	$modificacionoportunidad = 0;
	$consultaoportunidad = 0;
	$consultaoportunidadusuario = 0;
	$reportesegimiento = 0;
	$reporteoportunidad = 0;
	"<br>";
}

//cotizaciones
$seccion9 = $_POST['seccion9']; "<br>";
if(isset($_POST['seccion9'])){ 
        $seccion9 = $_POST['seccion9']; "<br>";
        $altacotizacion = $_POST['altacotizacion']; "<br>";
if(isset($_POST['altacotizacion'])){ 
        $altacotizacion = $_POST['altacotizacion']; "<br>";
    }else{
	$altacotizacion = 0;
	"<br>";
} 

    }else{
	$seccion9 = 0;
	$altacotizacion = 0;
	"<br>";
}

//Movimientos de Caja// 
$seccion10 = $_POST['seccion10']; "<br>";
if(isset($_POST['seccion10'])){ 
   $seccion10 = $_POST['seccion10']; "<br>";

        $altrecibo = $_POST['altrecibo'];"<br>";
if (isset($_POST['altrecibo'])) {
	$altrecibo = $_POST['altrecibo'];"<br>";
}else{
	$altrecibo = 0;
	"<br>";
}
 $modificacionrecibo = $_POST['modificacionrecibo']; "<br>";
if (isset($_POST['modificacionrecibo'])) {
	$modificacionrecibo = $_POST['modificacionrecibo'];"<br>";
}else{
	$modificacionrecibo = 0;
	"<br>";
}
 $consultarecibo = $_POST['consultarecibo'];"<br>";
if (isset($_POST['consultarecibo'])) {
	$consultarecibo = $_POST['consultarecibo'];"<br>";
}else{
	$consultarecibo = 0;
	"<br>";
}$modificacionmovimientos = $_POST['modificacionmovimientos'];"<br>";
if (isset($_POST['modificacionmovimientos'])) {
	$modificacionmovimientos = $_POST['modificacionmovimientos'];"<br>";
}else{
	$modificacionmovimientos = 0;
	"<br>";
}
 $consultarmovimientos = $_POST['consultarmovimientos'];"<br>";
if (isset($_POST['consultarmovimientos'])) {
	$consultarmovimientos = $_POST['consultarmovimientos'];"<br>";
}else{
	$consultarmovimientos = 0;
	"<br>";
}
$altacorte = $_POST['altacorte'];"<br>";
if (isset($_POST['altacorte'])) {
	$altacorte = $_POST['altacorte'];"<br>";
}else{
	$altacorte = 0;
	"<br>";
}
 $consultarcaja = $_POST['consultarcaja'];"<br>";
if (isset($_POST['consultarcaja'])) {
	$consultarcaja = $_POST['consultarcaja'];"<br>";
}else{
	$consultarcaja = 0;
	"<br>";
}
 $altamovimientos = $_POST['altamovimientos'];"<br>";
if (isset($_POST['altamovimientos'])) {
	$altamovimientos = $_POST['altamovimientos'];"<br>";
}else{
	$altamovimientos = 0;
	"<br>";
}
 $cuentaspagar = $_POST['cuentaspagar'];"<br>";
if (isset($_POST['cuentaspagar'])) {
	$cuentaspagar = $_POST['cuentaspagar'];"<br>";
}else{
	$cuentaspagar = 0;
	"<br>";
}
 $cuentascobrar = $_POST['cuentascobrar'];"<br>";
if (isset($_POST['cuentascobrar'])) {
	$cuentascobrar = $_POST['cuentascobrar'];"<br>";
}else{
	$cuentascobrar = 0;
	"<br>";
}

$estadoresultados = $_POST['estadoresultados'];"<br>";
if (isset($_POST['estadoresultados'])) {
	$estadoresultados = $_POST['estadoresultados'];"<br>";
}else{
	$estadoresultados = 0;
	"<br>";
}

 
    }else{

    $seccion10 = 0;
	$altrecibo = 0;
	$modificacionrecibo = 0;
	$consultarecibo = 0;
	$modificacionmovimientos = 0;
	$consultarmovimientos = 0;
	$altacorte = 0;
	$consultarcaja = 0;
	$altamovimientos = 0;
	$cuentaspagar = 0;
	$cuentascobrar = 0;
	$estadoresultados = 0;


}




//DIAGNOSTICOS// 
$seccion11 = $_POST['seccion11']; "<br>";
if(isset($_POST['seccion11'])){ 
   $seccion11 = $_POST['seccion11']; "<br>";

$altadiagnostico = $_POST['altadiagnostico'];"<br>";
if (isset($_POST['altadiagnostico'])) {
	$altadiagnostico = $_POST['altadiagnostico'];"<br>";
}else{
	$altadiagnostico = 0;
	"<br>";
}
 $modificaciondiagnostico = $_POST['modificaciondiagnostico']; "<br>";
if (isset($_POST['modificaciondiagnostico'])) {
	$modificaciondiagnostico = $_POST['modificaciondiagnostico'];"<br>";
}else{
	$modificaciondiagnostico = 0;
	"<br>";
}

$consultadiagnostico = $_POST['consultadiagnostico'];"<br>";
if (isset($_POST['consultadiagnostico'])) {

	if ($_POST['consultadiagnostico'] == 1) {

		$consultadiagnostico = 1;
		$consultadiagnosticorecibidos = 0;
		$consultadiagnosticorepara = 0;

	}else if ($_POST['consultadiagnostico'] == 2) {

		$consultadiagnostico = 0;
		$consultadiagnosticorecibidos = 1;
		$consultadiagnosticorepara = 0;

	}else if ($_POST['consultadiagnostico'] == 3) {

		$consultadiagnostico = 0;
		$consultadiagnosticorecibidos = 0;
		$consultadiagnosticorepara = 1;
	}

}else{
		$consultadiagnostico = 0;
		$consultadiagnosticorecibidos = 0;
		$consultadiagnosticorepara = 0;
}


$imprimirdiagnostico = $_POST['imprimirdiagnostico'];"<br>";
if (isset($_POST['imprimirdiagnostico'])) {
	$imprimirdiagnostico = $_POST['imprimirdiagnostico'];"<br>";
}else{
	$imprimirdiagnostico = 0;
	"<br>";
}
 $agregarartipresu = $_POST['agregarartipresu'];"<br>";
if (isset($_POST['agregarartipresu'])) {
	$agregarartipresu = $_POST['agregarartipresu'];"<br>";
}else{
	$agregarartipresu = 0;
	"<br>";
}
$autorizarrefacciones = $_POST['autorizarrefacciones'];"<br>";
if (isset($_POST['autorizarrefacciones'])) {
	$autorizarrefacciones = $_POST['autorizarrefacciones'];"<br>";
}else{
	$autorizarrefacciones = 0;
	"<br>";
}
 $pedirrefacciones = $_POST['pedirrefacciones'];"<br>";
if (isset($_POST['pedirrefacciones'])) {
	$pedirrefacciones = $_POST['pedirrefacciones'];"<br>";
}else{
	$pedirrefacciones = 0;
	"<br>";
}
 $recibirrefacciones = $_POST['recibirrefacciones'];"<br>";
if (isset($_POST['recibirrefacciones'])) {
	$recibirrefacciones = $_POST['recibirrefacciones'];"<br>";
}else{
	$recibirrefacciones = 0;
	"<br>";
}
 $repararfallas = $_POST['repararfallas'];"<br>";
if (isset($_POST['repararfallas'])) {
	$repararfallas = $_POST['repararfallas'];"<br>";
}else{
	$repararfallas = 0;
	"<br>";
}
 $reimprimirrecepcion = $_POST['reimprimirrecepcion'];"<br>";
if (isset($_POST['reimprimirrecepcion'])) {
	$reimprimirrecepcion = $_POST['reimprimirrecepcion'];"<br>";
}else{
	$reimprimirrecepcion = 0;
	"<br>";
}

$reimprimirentrega = $_POST['reimprimirentrega'];"<br>";
if (isset($_POST['reimprimirentrega'])) {
	$reimprimirentrega = $_POST['reimprimirentrega'];"<br>";
}else{
	$reimprimirentrega = 0;
	"<br>";
}

$guardardiagnostico = $_POST['guardardiagnostico'];"<br>";
if (isset($_POST['guardardiagnostico'])) {
	$guardardiagnostico = $_POST['guardardiagnostico'];"<br>";
}else{
	$guardardiagnostico = 0;
	"<br>";
}

$regresarstatus = $_POST['regresarstatus'];"<br>";
if (isset($_POST['regresarstatus'])) {
	$regresarstatus = $_POST['regresarstatus'];"<br>";
}else{
	$regresarstatus = 0;
	"<br>";
}

$deleteimgdiag = $_POST['deleteimgdiag'];"<br>";
if (isset($_POST['deleteimgdiag'])) {
	$deleteimgdiag = $_POST['deleteimgdiag'];"<br>";
}else{
	$deleteimgdiag = 0;
	"<br>";
}

$deleteimgrecepcion = $_POST['deleteimgrecepcion'];"<br>";
if (isset($_POST['deleteimgrecepcion'])) {
	$deleteimgrecepcion = $_POST['deleteimgrecepcion'];"<br>";
}else{
	$deleteimgrecepcion = 0;
	"<br>";
}

$deleteimgreparacion = $_POST['deleteimgreparacion'];"<br>";
if (isset($_POST['deleteimgreparacion'])) {
	$deleteimgreparacion = $_POST['deleteimgreparacion'];"<br>";
}else{
	$deleteimgreparacion = 0;
	"<br>";
}

$verdiagcompleto = $_POST['verdiagcompleto'];"<br>";
if (isset($_POST['verdiagcompleto'])) {

	if ($_POST['verdiagcompleto'] == 1) {
		$verdiagcompleto = 1;
		$verdiagusuario = 0;
	}else if ($_POST['verdiagcompleto'] == 2) {
		$verdiagcompleto = 0;
		$verdiagusuario = 1;
	}
}else{
	$verdiagcompleto = 0;
	$verdiagusuario = 0;
}

$altarefacciones = $_POST['altarefacciones'];"<br>";
if (isset($_POST['altarefacciones'])) {
	$altarefacciones = $_POST['altarefacciones'];"<br>";
}else{
	$altarefacciones = 0;
	"<br>";
}

$modificarrefacciones = $_POST['modificarrefacciones'];"<br>";
if (isset($_POST['modificarrefacciones'])) {
	$modificarrefacciones = $_POST['modificarrefacciones'];"<br>";
}else{
	$modificarrefacciones = 0;
	"<br>";
}



$verpresucompleto = $_POST['verpresucompleto'];"<br>";
if (isset($_POST['verpresucompleto'])) {
	if ($_POST['verpresucompleto'] == 1) {
		$verpresucompleto = 1;
		$verpresuusuario = 0;
	}else if ($_POST['verpresucompleto'] == 2) {
		$verpresucompleto = 0;
		$verpresuusuario = 1;
	}
}else{
	$verpresucompleto = 0;
	$verpresuusuario = 0;
}


$imgcompletasdiag = $_POST['imgcompletasdiag'];"<br>";
if (isset($_POST['imgcompletasdiag'])) {

	if ($_POST['imgcompletasdiag'] == 1) {
		$imgcompletasdiag = 1;
		$imgusuariodiag = 0;
	}else if ($_POST['imgcompletasdiag'] == 2) {
		$imgcompletasdiag = 0;
		$imgusuariodiag = 1;
	}
}else{
	$imgcompletasdiag = 0;
	$imgusuariodiag = 0;
	"<br>";
}


$imgcompletasrepa = $_POST['imgcompletasrepa'];"<br>";
if (isset($_POST['imgcompletasrepa'])) {

	if ($_POST['imgcompletasrepa'] == 1) {
		$imgcompletasrepa = 1;
		$imgusuariorepa = 0;
	}else if ($_POST['imgcompletasrepa'] == 2) {
		$imgcompletasrepa = 0;
		$imgusuariorepa = 1;
	}
}else{
	$imgcompletasrepa = 0;
	$imgusuariorepa = 0;
	"<br>";
}


$entregarvehiculo = $_POST['entregarvehiculo'];"<br>";
if (isset($_POST['entregarvehiculo'])) {
	$entregarvehiculo = $_POST['entregarvehiculo'];"<br>";
}else{
	$entregarvehiculo = 0;
	"<br>";
}

$agregarmodulosdiag = $_POST['agregarmodulosdiag'];"<br>";
if (isset($_POST['agregarmodulosdiag'])) {
	$agregarmodulosdiag = $_POST['agregarmodulosdiag'];"<br>";
}else{
	$agregarmodulosdiag = 0;
	"<br>";
}

$enviarcotizacion = $_POST['enviarcotizacion'];"<br>";
if (isset($_POST['enviarcotizacion'])) {
	$enviarcotizacion = $_POST['enviarcotizacion'];"<br>";
}else{
	$enviarcotizacion = 0;
	"<br>";
}

$pedirrefausuario = $_POST['pedirrefausuario'];"<br>";
if (isset($_POST['pedirrefausuario'])) {

		if ($_POST['pedirrefausuario'] == 1) {
			$pedirrefausuario1 =1;
			$pedirrefatodas = 0;
		}else if ($_POST['pedirrefausuario'] == 2) {
			$pedirrefausuario1 =0;
			$pedirrefatodas = 1;
		}
	
}else{
	$pedirrefausuario1 = 0;
	$pedirrefatodas = 0;
	"<br>";
}


$recibirrefausuario = $_POST['recibirrefausuario'];"<br>";
if (isset($_POST['recibirrefausuario'])) {

	if ($_POST['recibirrefausuario'] == 1) {
		$recibirrefausuario = 1;
		$recibirrefacompletas = 0;
	}else if ($_POST['recibirrefausuario'] == 2) {
		$recibirrefausuario = 0;
		$recibirrefacompletas = 1;
	}
}else{
	$recibirrefausuario = 0;
	$recibirrefacompletas = 0;
	"<br>";
}

$repafallas = $_POST['repafallas'];"<br>";
if (isset($_POST['repafallas'])) {

	if ($_POST['repafallas'] == 1) {
		$repafallas = 1;
		$repararfallasusuario = 0;
	}else if ($_POST['repafallas'] == 2) {
		$repafallas = 0;
		$repararfallasusuario = 1;
	}
}else{
	$repafallas = 0;
	$repararfallasusuario = 0;
	"<br>";
}




 
    }else{

   $seccion11 = 0;
$altadiagnostico = 0;
$modificaciondiagnostico = 0;
$consultadiagnostico = 0;
$imprimirdiagnostico = 0;
$agregarartipresu = 0;
$autorizarrefacciones = 0;
$pedirrefacciones = 0;
$recibirrefacciones = 0;
$repararfallas = 0;
$reimprimirrecepcion = 0;
$reimprimirentrega = 0;
$guardardiagnostico = 0;
$regresarstatus = 0;
$deleteimgdiag = 0;
$deleteimgrecepcion = 0;
$deleteimgreparacion = 0;
$verdiagcompleto = 0;
$verdiagusuario = 0;
$verpresucompleto = 0;
$verpresuusuario = 0;
$imgcompletasdiag = 0;
$imgusuariodiag = 0;
$imgcompletasrepa = 0;
$imgusuariorepa = 0;
$entregarvehiculo = 0;
$agregarmodulosdiag = 0;
$enviarcotizacion = 0;
$pedirrefausuario1 = 0;
$pedirrefatodas = 0;
$recibirrefausuario = 0;
$recibirrefacompletas = 0;
$consultadiagnosticorecibidos = 0;
$consultadiagnosticorepara = 0;
$repafallas = 0;
$repararfallasusuario = 0;
$altarefacciones = 0;
$modificarrefacciones = 0;
}




///TAREAS///
$seccion12 = $_POST['seccion12']; "<br>";
if(isset($_POST['seccion12'])){ 
$seccion12 = $_POST['seccion12']; "<br>";

$altatareas = $_POST['altatareas']; "<br>";
if(isset($_POST['altatareas'])){ 
        $altatareas = $_POST['altatareas']; "<br>";
    }else{
	$altatareas = 0;
	"<br>";
} 


    }else{
	$seccion12 = 0;
	$altatareas = 0;
}

///FACTURACION///
$seccion13 = $_POST['seccion13']; "<br>";

if(isset($_POST['seccion13'])){
	$seccion13 = $_POST['seccion13']; "<br>";
	$altafacturacion = $_POST['altafacturacion']; "<br>";
	if(isset($_POST['altafacturacion'])){
		$altafacturacion = $_POST['altafacturacion']; "<br>";
    }
	else{
		$altafacturacion = 0;
		"<br>";
	}

	$consultafacturas = $_POST['consultafacturas']; "<br>";
	if(isset($_POST['consultafacturas'])){
		$consultafacturas = $_POST['consultafacturas']; "<br>";
	}
	else{
		$consultafacturas = 0;
		"<br>";
	}

	$cancelarfacturas = $_POST['cancelarfacturas']; "<br>";
	if(isset($_POST['cancelarfacturas'])){
		$cancelarfacturas = $_POST['cancelarfacturas']; "<br>";
	}
	else{
		$cancelarfacturas = 0;
		"<br>";
	}

	$agregarcomplementos = $_POST['agregarcomplementos']; "<br>";
	if(isset($_POST['agregarcomplementos'])){
		$agregarcomplementos = $_POST['agregarcomplementos']; "<br>";
	}
	else{
		$agregarcomplementos = 0;
		"<br>";
	}

	$verpdf = $_POST['verpdf']; "<br>";
	if(isset($_POST['verpdf'])){
		$verpdf = $_POST['verpdf']; "<br>";
	}
	else{
		$verpdf = 0;
		"<br>";
	}

	$descargarxml = $_POST['descargarxml']; "<br>";
	if(isset($_POST['descargarxml'])){
		echo "declarado";
		$descargarxml = $_POST['descargarxml']; "<br>";
	}
	else{
		echo "no entra";
		$descargarxml = 0;
		"<br>";
	}
}
else{
	$seccion13 = 0;
	$altafacturacion = 0;
	$consultafacturas = 0;
	$cancelarfacturas = 0;
	$agregarcomplementos = 0;
	$verpdf = 0;
	$descargarxml = 0;
}

//RAZON SOCIAL//
$seccion14 = $_POST['seccion14']; "<br>";
if(isset($_POST['seccion14'])){ 
        $seccion14 = $_POST['seccion14']; "<br>";

        $altarazon = $_POST['altarazon']; "<br>";
if(isset($_POST['altarazon'])){ 
        $altarazon = $_POST['altarazon']; "<br>";
    }else{
	$altarazon = 0;
	"<br>";
} 
$consultarazon = $_POST['consultarazon'];"<br>";
if (isset($_POST['consultarazon'])) {
	$consultarazon = $_POST['consultarazon'];"<br>";
}else{
	$consultarazon = 0;
	"<br>";
}
$modificarrazon = $_POST['modificarrazon'];"<br>";
if (isset($_POST['modificarrazon'])) {
	$modificarrazon = $_POST['modificarrazon'];"<br>";
}else{
	$modificarrazon = 0;
	"<br>";
}
    }else{
	$seccion14 = 0;
	$altarazon = 0;
	$consultarazon = 0;
	$modificarrazon = 0;

}


$permisos= $seccion1 . $alta . $modificacion . $consulta 
.$seccion6
.$altasucursal 
.$modificacionsucursal
.$consultasucursal
.$seccion2 
.$altaventa
.$modificacionventa
.$consultaventa
.$altacompra
.$modificacioncompra
.$consultacompra
.$altaconsignacion
.$modificacionconsignacion
.$consultaconsignacion
.$altaintermediacion
.$modificacionintermediacion
.$consultaintermediacion
.$seccion3
.$altavehiculo
.$modificacionvehiculo
.$consultavehiculo
.$altatenencia
.$modificaciontenencia
.$consultatenencia
.$altafactura
.$modificacionfactura
.$consultafactura
.$altamodelo
.$modificacionmodelo
.$consultamodelo
.$altaannio
.$modificacionannio
.$consultaannio
.$altamarca
.$modificacionmarca
.$consultamarca
.$altaversion
.$modificacionversion
.$consultaversion
.$altapublicacion
.$modificacionpublicacion
.$consultapublicacion
.$altaimagenen
.$modificacionimagenen
.$consultaimagenen
.$altafina
.$modificacionfina
.$consultafina
.$altaatributo
.$modificacionatributo
.$consultaatributo
.$altaextra
.$modificacionextra
.$consultaextra
.$altacaracteristica
.$modificacioncaracteristica
.$consultacaracteristica
.$seccion4
.$altasitio
.$modificacionsitio
.$consultasitio
.$seccion5
.$altausuario
.$modificacionusuario
.$consultausuario
.$seccion7
.$altafinanciamiento
.$modificacionfinanciamiento
.$consultafinanciamiento
.$seccion8
.$altaprospecto
.$modificacionprospecto
.$consultaprospecto
.$altaoportunidad
.$modificacionoportunidad
.$consultaoportunidad
.$altaseguimiento
.$modificacionseguimiento
.$consultaseguimiento
.$consulta1
.$consultafacturaimagen1
.$consultafacturaimagen
.$reporteclientes
.$reporteventas
.$reportecompras
.$reporteconsignacion
.$reporteintermediacion
.$reportevehiculos
.$reportesegimiento
.$reporteoportunidad
.$consultaprospectousuario
.$consultaoportunidadusuario
.$seccion9
.$altacotizacion
.$seccion10
.$altrecibo
.$modificacionrecibo
.$consultarecibo
.$modificacionmovimientos
.$consultarmovimientos
.$altacorte
.$consultarcaja
.$altamovimientos
.$consultacartafactura
.$consultarecibodoc
.$cuentaspagar
.$cuentascobrar
.$seccion11
.$altadiagnostico
.$consultadiagnostico
.$imprimirdiagnostico
.$agregarartipresu
.$autorizarrefacciones
.$pedirrefacciones
.$recibirrefacciones
.$repararfallas
.$reimprimirrecepcion
.$reimprimirentrega
.$guardardiagnostico
.$modificaciondiagnostico
.$regresarstatus
.$seccion12
.$altatareas
.$eliminarimagenes
.$deleteimgdiag
.$verdiagcompleto
.$verdiagusuario
.$imgcompletasdiag
.$imgusuariodiag
.$verpresucompleto
.$verpresuusuario
.$enviarcotizacion
.$deleteimgrecepcion
.$deleteimgreparacion
.$entregarvehiculo
.$agregarmodulosdiag
.$imgcompletasrepa
.$imgusuariorepa
.$pedirrefausuario1
.$pedirrefatodas
.$recibirrefausuario
.$recibirrefacompletas
.$consultadiagnosticorecibidos
.$consultadiagnosticorepara
.$repafallas
.$repararfallasusuario
.$altarefacciones
.$modificarrefacciones
.$porsitio
.$porrazon
.$cambiarsitio
.$seccion13
.$altafacturacion
.$consultafacturas
.$cancelarfacturas
.$agregarcomplementos
.$verpdf
.$descargarxml
.$altacuentas
.$modificarcuentas
.$consultacuentas
.$seccion14
.$altarazon
.$consultarazon
.$modificarrazon
.$estadoresultados;

$sql = "INSERT INTO usuario (id,nombre,usr,pwd,permisos,rol_id,tipostatus_id,sitio_id) VALUES
(NULL,'$nombre','$usuario', '$encriptado','$permisos','$rol','$statuscliente','$sitio')";

echo mysqli_query($mysqli,$sql);