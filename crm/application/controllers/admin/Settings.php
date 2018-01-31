<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/settings');
    }

    public function index()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->template->title(lang('heading_index'))
                       ->set_css(['formvalidation.min', 'bootstrap-checkbox-radio.min'])
                       ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                       ->build('admin/settings/index', $this->data);
    }

}

/* End of file Settings.php */
/* Location: ./application/controllers/admin/Settings.php */