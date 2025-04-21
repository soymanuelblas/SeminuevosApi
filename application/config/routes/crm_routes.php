<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// PagosCRM routes
$route['api/pagoscrm'] = 'PagosCRM/PagoscrmController/listPayments';
// CRM routes
$route['api/listcrmstatistics'] = 'CRM/CRMController/listStatistics';
// CRM routes - Prospectos
$route['api/listprospectos'] = 'CRM/ProspectosController/listProspectos';