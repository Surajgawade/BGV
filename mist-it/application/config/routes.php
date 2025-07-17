<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Home';
$route['404_override'] = 'my404';
$route['translate_uri_dashes'] = FALSE;

//admin
$route['admin'] = "admin/dashboard";
$route['admin/login'] = "admin/dashboard/login";
$route['admin/logout'] = "admin/dashboard/logout";
$route['admin/fake_university'] = "admin/universities/fake_university";
$route['admin/fake_university/view_fake_university/(:any)'] = "admin/universities/view_fake_university/$1";
// vendor
$route['vendor'] = "vendor/vendor_login";
$route['vendor/login'] = "vendor/vendor_login/login";
$route['vendor/logout'] = "vendor/vendor_login/logout";
$route['vendor/index'] = "vendor/vendor_login/index";

//executive
$route['executive'] = "executive/executive_login";
$route['executive/login'] = "executive/executive_login/login";
$route['executive/logout'] = "executive/executive_login/logout";
$route['executive/index'] = "executive/executive_login/index";


//client
$route['client/happilo'] = "client/client_login";
$route['client'] = "client/client_login";
$route['client/login'] = "client/client_login/login";
$route['client/logout'] = "client/client_login/logout";
$route['client/index'] = "client/client_login/index";


//candidate info
$route['candidate_info'] = "candidate_info/candidate_login/login";
$route['candidate_info/login'] = "candidate_info/candidate_login/login";
$route['candidate_info/logout'] = "candidate_info/candidate_login/logout";
$route['candidate_info/index'] = "candidate_info/candidate_login/index";

$route['ver/(:any)/(:any)'] = "client/candidate_mail/index/$1/$2";

$route['bgvclient.mistitservices.com'] = "admin/dashboard";

$route['av/(:any)'] = "addressverify/index/$1";
$route['av/(:any)/(:any)'] = "addressverify/add_verification/$1/$2";

