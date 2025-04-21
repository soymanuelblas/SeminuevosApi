<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Client/Provider routes
$route['api/clientprovider'] = 'ClienteProvedor/CliProvController/addClienteProvedor';
$route['api/listclientprovider'] = 'ClienteProvedor/CliProvController/listClientesProveedores';
$route['api/updateclientprovider'] = 'ClienteProvedor/CliProvController/updateClienteProveedor';