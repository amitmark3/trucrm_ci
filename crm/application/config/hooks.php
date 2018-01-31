<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_system'] = function() {
    $dotenv = new Dotenv\Dotenv(APPPATH);
    $dotenv->load();
};

$hook['post_controller'][] = function() {

	$this->CI =& get_instance();
	$this->CI->load->config("site_settings");

    if ( ! in_array($_SERVER['REMOTE_ADDR'], config_item('maintenance_ips')) && config_item('maintenance_mode') )
    {
        $this->CI->output->set_status_header('503');
        echo $this->CI->load->view('partials/maintenance', NULL, TRUE);
        exit();
    }

};