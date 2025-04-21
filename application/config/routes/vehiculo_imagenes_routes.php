<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Vehiculos - Imagenes routes
$route['api/addprincipal'] = 'Vehiculos/Imagenes/ImagenesController/addPrincipal';
$route['api/deleteprincipal'] = 'Vehiculos/Imagenes/ImagenesController/deletePrincipal';

$route['api/listtipoimagen'] = 'Vehiculos/Imagenes/ImagenesController/listTipoImagen';