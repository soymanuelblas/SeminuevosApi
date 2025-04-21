<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Vehiculos - Imagenes routes
$route['api/addimagen'] = 'Vehiculos/Imagenes/ImagenesController/addImagen';
$route['api/deleteimagen'] = 'Vehiculos/Imagenes/ImagenesController/deleteImagen';
$route['api/listimagen'] = 'Vehiculos/Imagenes/ImagenesController/listImagen';
$route['api/listtipoimagen'] = 'Vehiculos/Imagenes/ImagenesController/listTipoImagen';