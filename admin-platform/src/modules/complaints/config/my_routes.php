<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['open_complaint/(:any)'] =  'admin/complaints/clients_complaints/open_complaints/$1';
$route['forms/complaints/(:any)'] =  'complaints/clients_complaints/complaint/$1';
