<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Sucursal routes
$route['api/sucursal'] = 'Sucursales/SucursalController/add_sucursal';
$route['api/updatesucursal'] = 'Sucursales/SucursalController/update_sucursal';
$route['api/listsucursal'] = 'Sucursales/SucursalController/list_sucursal';