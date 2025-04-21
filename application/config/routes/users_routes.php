<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Usuarios routes
$route['api/listusuarios'] = 'Usuarios/UsuarioController/listUsuarios';
$route['api/updateusuario'] = 'Usuarios/UsuarioController/updateUsuario';

// Usuarios routes - Status
$route['api/liststatus'] = 'Usuarios/UsuarioController/listStatus';
$route['api/listsitios'] = 'Usuarios/UsuarioController/listSitios';