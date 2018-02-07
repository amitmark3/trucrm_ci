<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin'] = 'admin/admin';
$route['admin/(:any)'] = 'admin/$1';

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['signup'] = 'auth/register';
$route['forgot_password'] = 'auth/forgot_password';
$route['reset_password/(:any)'] = 'auth/reset_password/$1';

$route['notifications/mark_as_read'] = 'notifications/mark_as_read';
$route['notifications/mark_selected_as_read'] = 'notifications/mark_selected_as_read';
$route['notifications/view/(:num)'] = 'notifications/view/$1';
$route['notifications/get'] = 'notifications/get';
$route['notifications/get/(:num)'] = 'notifications/get/$1';
$route['notifications/(.+)'] = 'notifications/index';

$route['actions/user/(:num)'] = 'actions/user';
$route['actions/status/(:any)'] = 'actions/index';

$route['admin/companies/delete/(:num)/confirmation'] = 'admin/companies/confirm_delete/$1';

 // Calling Status
$route['calling/status'] = 'calling/callingstatus/index'; 
$route['calling/status/add'] = 'calling/callingstatus/add'; 
$route['calling/status/edit'] = 'calling/callingstatus/edit'; 
$route['calling/status/edit/(:any)'] = 'calling/callingstatus/edit/$1'; 
/*$route['calling/callingstatus/(:num)'] = 'actions/user';
$route['actions/status/(:any)'] = 'actions/index';*/
 // Master Data
$route['masterdata'] = 'masterdata/masterdata/index'; 
$route['masterdata/add'] = 'masterdata/masterdata/add'; 
$route['masterdata/edit'] = 'masterdata/masterdata/edit';
$route['masterdata/edit/(:any)'] = 'masterdata/masterdata/edit/$1'; 
$route['masterdata/view'] = 'masterdata/masterdata/view';
$route['masterdata/view/(:any)'] = 'masterdata/masterdata/view/$1'; 
$route['masterdata/delete'] = 'masterdata/masterdata/delete';
$route['masterdata/delete/(:any)'] = 'masterdata/masterdata/delete/$1'; 
$route['masterdata/mdcdelete'] = 'masterdata/masterdata/mdcdelete';
$route['masterdata/mdcdelete/(:any)/(:any)'] = 'masterdata/masterdata/mdcdelete/$1/$1'; 
$route['masterdata/mdcedit'] = 'masterdata/masterdata/mdcedit';
$route['masterdata/mdcedit/(:any)'] = 'masterdata/masterdata/mdcedit/$1'; 
$route['masterdata/mdcedit/(:any)/(:any)'] = 'masterdata/masterdata/mdcedit/$1/$1'; 

// Project
$route['project'] = 'project/project/index'; 
$route['project/add'] = 'project/project/add'; 
$route['project/edit'] = 'project/project/edit'; 
$route['project/edit/(:any)'] = 'project/project/edit/$1'; 

