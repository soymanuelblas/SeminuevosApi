<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Vehiculos - Tenencias routes
$route['api/addtenencias'] = 'Vehiculos/Tenencias/TenenciasController/addTenencias';
$route['api/listtenencias'] = 'Vehiculos/Tenencias/TenenciasController/listTenencias';
$route['api/updatetenencias'] = 'Vehiculos/Tenencias/TenenciasController/updateTenencias';
$route['api/listestados'] = 'Vehiculos/Tenencias/TenenciasController/listEstados';
$route['api/listannios'] = 'Vehiculos/Tenencias/TenenciasController/listAnnios';