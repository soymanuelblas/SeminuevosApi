<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Auth routes
$route['api/login'] = 'AuthController/login';
$route['api/signup'] = 'AuthController/signup';
$route['api/register'] = 'AuthController/register_user';