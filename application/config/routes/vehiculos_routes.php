<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Vehiculos routes
$route['api/addvehiculo'] = 'Vehiculos/VehiculosController/addVehiculo';
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
// Vehiculos Marca routes
$route['api/listmarca'] = 'Vehiculos/VehiculosController/listMarca';
// Vehiculos Dueño routes
$route['api/listduenio'] = 'Vehiculos/VehiculosController/listDuenio';
// Vehiculos Duplicado routes
$route['api/listduplicado'] = 'Vehiculos/VehiculosController/listDuplicado';
// Vehiculos Modelo routes
$route['api/listmodelo'] = 'Vehiculos/VehiculosController/listModelo';
// Vehiculos Version routes
$route['api/listversion'] = 'Vehiculos/VehiculosController/listVersion';
// Vehiculos TipoAnio routes
$route['api/listannio'] = 'Vehiculos/VehiculosController/listAnnio';