<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Packages */
$autoload['packages'] = array();

/* Libraries */
$autoload['libraries'] = array('breadcrumbs', 'database', 'session', 'flasher', 'template', 'ion_auth', 'company_lib', 'email_lib', 'notify_lib');

/* Drivers */
$autoload['drivers'] = array();

/* Helper Files */
$autoload['helper'] = array('cookie', 'date', 'email', 'html', 'url');

/* Config files */
$autoload['config'] = array('site_settings');

/* Language files */
$autoload['language'] = array();

/* Models */
$autoload['model'] = array('company_model', 'notification_model', 'profile_model', 'user_model');
