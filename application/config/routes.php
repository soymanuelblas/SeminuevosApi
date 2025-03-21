<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'AuthController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
// Auth routes
$route['api/login'] = 'AuthController/login';
$route['api/signup'] = 'AuthController/signup';
$route['api/register'] = 'AuthController/register_user';

// User routes
$route['api/getuser'] = 'UserController/getuser';

// Bank routes
$route['api/bankaccount'] = 'BankController/add_bank_account';
$route['api/listbankaccounts'] = 'BankController/listBankAccounts';
$route['api/deletebankaccount'] = 'BankController/deleteBankAccount';

// Client/Provider routes
$route['api/clientprovider'] = 'ClienteProvedor/CliProvController/addClienteProvedor';
$route['api/listclientprovider'] = 'ClienteProvedor/CliProvController/listClientesProveedores';
// TODO - Update client/provider
$route['api/updateclientprovider'] = 'ClienteProvedor/CliProvController/updateClienteProveedor';

// Sucursal routes
$route['api/sucursal'] = 'Sucursales/SucursalController/add_sucursal';
$route['api/updatesucursal'] = 'Sucursales/SucursalController/update_sucursal';
$route['api/listsucursal'] = 'Sucursales/SucursalController/list_sucursal';

// Movimientos routes
$route['api/movimientos'] = 'Movimientos/MovimientosController/listMovimientos';


// PagosCRM routes
$route['api/pagoscrm'] = 'PagosCRM/PagoscrmController/listPayments';

// Vehiculos routes
$route['api/listvehiculos'] = 'Vehiculos/VehiculosController/listVehiculos';
$route['api/updatevehiculo'] = 'Vehiculos/VehiculosController/updateVehiculos';
$route['api/listvehiculo'] = 'Vehiculos/VehiculosController/listVehiculoById';

// Vehiculos Garantia routes
$route['api/listgarantia'] = 'Vehiculos/VehiculosController/listGarantia';

// Vehiculos TipoVehiculo routes
$route['api/listtipovehiculo'] = 'Vehiculos/VehiculosController/listTipoVehiculo';

// Vehiculos TipoStatus routes
$route['api/listtipostatus'] = 'Vehiculos/VehiculosController/listTipoStatus';

// Vehiculos Color routes
$route['api/listcolor'] = 'Vehiculos/VehiculosController/listColor';

// Facturas routes
$route['api/listfacturas'] = 'Facturas/FacturasController/listFacturas';

// Inicio routes
// $route['api/getvehiculosmes'] = 'Inicio/InicioController/getVehiculosPorMes';
// $route['api/getvehiculosanio'] = 'Inicio/InicioController/getVehiculosPorAnio';
// $route['api/getoportunidadesatrasadas'] = 'Inicio/InicioController/getOportunidadesAtrasadas';
// $route['api/getoportunidadesproceso'] = 'Inicio/InicioController/getOportunidadesProceso';
// $route['api/getoportunidadesnologradas'] = 'Inicio/InicioController/getOportunidadesNoLogradas';
// $route['api/getcobrartotales'] = 'Inicio/InicioController/getCobrarTotales';
// $route['api/getcobrarmes'] = 'Inicio/InicioController/getCobrarMes';
// $route['api/getcobrarvencidas'] = 'Inicio/InicioController/getCobrarVencidas';
// $route['api/getpagarvencidas'] = 'Inicio/InicioController/getPagarVencidas';
// $route['api/getpagarmes'] = 'Inicio/InicioController/getPagarMes';
// $route['api/getpagartotales'] = 'Inicio/InicioController/getPagarTotales';

$route['api/listdatastatistic'] = 'Inicio/InicioController/listDataStatistic';

// CRM routes
$route['api/listcrmstatistics'] = 'CRM/CRMController/listStatistics';
// CRM routes - Prospectos
$route['api/listprospectos'] = 'CRM/ProspectosController/listProspectos';

// Usuarios routes
$route['api/listusuarios'] = 'Usuarios/UsuarioController/listUsuarios';