<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Cron extends MY_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        // if ( ! $this->input->is_cli_request() )
        // {
        //     die('Direct access is not allowed.');
        // }
    }

    public function run()
    {
        $this->load->library('cronrunner');
        $cron = new CronRunner();
        $cron->run();
    }
}

/* Jobs needed
 * SM Weekly Overview
 * Required training emails
 * Safety Walks recurring
*/