<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['api/listventas'] = 'Vehiculos/Contratos/VentaController/listVentas';
$route['api/addventa'] = 'Vehiculos/Contratos/VentaController/addVenta';
$route['api/updateventa'] = 'Vehiculos/Contratos/VentaController/updateVenta';

// Listar clientes
$route['api/listclientes'] = 'Vehiculos/Contratos/VentaController/listClientes';

// Listar formas de pago
$route['api/listformaspago'] = 'Vehiculos/Contratos/VentaController/listFormasPago';