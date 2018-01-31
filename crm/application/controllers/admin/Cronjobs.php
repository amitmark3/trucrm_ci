<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjobs extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('cronjob_model');
        $this->load->language('admin/cronjobs');

        $this->breadcrumbs->push('Admin', 'admin');
        $this->breadcrumbs->push(lang('heading_index'), 'admin/cronjobs');
    }

    public function index()
    {
        $this->template->title(lang('heading_index'))
                       ->set_css('bootstrap-datatables.min')
                       ->set_js(['datatables.min', 'bootstrap-datatables.min', 'bootbox-4.4.0.min'])
                       ->set_partial('custom_js', 'admin/cronjobs/datatables_js', ['url' => site_url('admin/cronjobs/datatables')])
                       ->build('admin/cronjobs/index', $this->data);
    }

    public function datatables()
    {
        $this->load->library('datatables');
        $this->datatables->select("id, name, command, interval_sec, last_run_at, next_run_at, is_active");
        $this->datatables->from("cronjobs");
        $this->datatables->add_column('edit', anchor('admin/cronjobs/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>') . ' ' . anchor('admin/cronjobs/delete/$1', '<i class="fa fa-lg fa-times-circle-o"></i>', ['class' => 'confirm', 'id' => '$1']), 'id');
        echo $this->datatables->generate();
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('command', 'command', 'trim|required');
        $this->form_validation->set_rules('interval', 'interval', 'trim|required');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        if ($this->form_validation->run() == TRUE)
        {
            $cronjob_data = [
                'name'          => $this->input->post('name', TRUE),
                'command'       => $this->input->post('command', TRUE),
                'interval_sec'  => $this->input->post('interval', TRUE),
                'is_active'     => $this->input->post('active', TRUE),
            ];

            if ($this->cronjob_model->insert($cronjob_data))
            {
                $this->flasher->set_success(lang('insert_success'), 'admin/cronjobs', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('insert_failed'), 'admin/cronjobs/add', TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push(lang('heading_add'), 'cronjobs/add');

            $this->template->title(lang('heading_add'))
                           ->set_css(['formvalidation.min', 'bootstrap-checkbox-radio.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'admin/cronjobs/custom_js', ['form_name' => 'add-cron-job-form'])
                           ->build('admin/cronjobs/add', $this->data);
        }
    }

    public function edit()
    {
        $id = $this->uri->segment(4);

        if ( ! $id)
        {
            $this->flasher->set_warning_extra(lang('invalid_id'), 'admin/cronjobs', TRUE);
        }

        $cronjob = $this->cronjob_model->get($id);

        if ( ! $cronjob)
        {
            $this->flasher->set_warning_extra(lang('not_found'), 'admin/cronjobs', TRUE);
        }

        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('command', 'command', 'trim|required');
        $this->form_validation->set_rules('interval', 'interval', 'trim|required');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        if ($this->form_validation->run() == TRUE)
        {
            $cronjob_data = [
                'name'          => $this->input->post('name', TRUE),
                'command'       => $this->input->post('command', TRUE),
                'interval_sec'  => $this->input->post('interval', TRUE),
                'is_active'     => $this->input->post('active', TRUE),
            ];

            if ($this->cronjob_model->update($cronjob_data, $id))
            {
                $this->flasher->set_success(lang('update_success'), 'admin/cronjobs', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('update_failed'), 'admin/cronjobs/edit', TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push(lang('heading_edit'), 'cronjobs/edit');

            $this->template->title(lang('heading_edit'))
                           ->set_css(['formvalidation.min', 'bootstrap-checkbox-radio.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'admin/cronjobs/custom_js', ['form_name' => 'edit-cron-job-form'])
                           ->set('cronjob', $cronjob)
                           ->build('admin/cronjobs/edit', $this->data);
        }
    }

    public function delete()
    {
        $id = $this->uri->segment(4);

        if ( ! $id )
        {
            $this->flasher->set_warning_extra(lang('invalid_id'), 'admin/cronjobs', TRUE);
        }

        if ($this->cronjob_model->delete($id))
        {
            $this->flasher->set_success(lang('delete_success'), 'admin/cronjobs', TRUE);
        }
        else
        {
            $this->flasher->set_danger(lang('delete_failed'), 'admin/cronjobs', TRUE);
        }
    }

}

/* End of file Cronjobs.php */
/* Location: ./application/controllers/admin/Cronjobs.php */