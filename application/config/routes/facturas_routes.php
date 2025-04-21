<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Vehiculos - Facturas routes
$route['api/addfactura'] = 'Vehiculos/Facturas/FacturasController/addFactura';
$route['api/listfacturas'] = 'Vehiculos/Facturas/FacturasController/listFacturas';
// Lista de checkboxes para el formulario de agregar factura
$route['api/listtipofacturas'] = 'Vehiculos/Facturas/FacturasController/listTipoFacturas';
$route['api/liststatusfacturas'] = 'Vehiculos/Facturas/FacturasController/listStatusFacturas';