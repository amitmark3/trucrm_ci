<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Price_plans extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('price_plan_model');
        $this->load->language('admin/price_plans');

        $this->breadcrumbs->push('Admin', 'admin');
        $this->breadcrumbs->push('Price Plans', 'admin/price_plans');
    }

    public function index()
    {
        $this->template->title(lang('heading_index'))
                       ->set_css('datatables.bootstrap.min')
                       ->set_js(['datatables.jquery.min', 'datatables.bootstrap.min', 'bootbox-4.4.0.min'])
                       ->set_partial('custom_js', 'admin/price_plans/datatables_js', ['url' => site_url('admin/price_plans/datatables')])
                       ->build('admin/price_plans/index', $this->data);
    }

    public function datatables()
    {
        $this->load->library("datatables");
        $this->datatables->select("id, name, description, price, space_allotted, space_unit");
        $this->datatables->from("price_plans");
        $this->datatables->add_column('actions', anchor('admin/price_plans/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>', ['title' => 'Edit Price Plan']) . ' ' . anchor('admin/price_plans/delete/$1', '<i class="fa fa-lg fa-times-circle-o"></i>', ['title' => 'Delete Price Plan', 'class' => 'confirm', 'id' => '$1']), 'id');
        echo $this->datatables->generate();
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('name', 'name', 'trim|required|callback_name_check');
        $this->form_validation->set_rules('description', 'description', 'trim');
        $this->form_validation->set_rules('price', 'price', 'trim|required');
        $this->form_validation->set_rules('space', 'space allotted', 'trim|required');

        if ($this->form_validation->run() == TRUE)
        {
            $name           = $this->input->post('name', TRUE);
            $description    = $this->input->post('description', TRUE);
            $price          = $this->input->post('price', TRUE);
            $space          = (int) $this->input->post('space', TRUE);
            $space_unit     = strtoupper($this->input->post('space_unit', TRUE));

            $price_plan_data = [
                'name'              => $name,
                'description'       => $description,
                'price'             => $price,
                'space_allotted'    => $space,
                'space_unit'        => $space_unit,
            ];

            if ($this->price_plan_model->insert($price_plan_data))
            {
                $this->flasher->set_success(lang('add_successful'), 'admin/price_plans', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('add_failed'), 'admin/price_plans/add', TRUE);
            }
        }
        else
        {
            $fields = ['name', 'description', 'price', 'space'];

            foreach ($fields as $field)
            {
                $this->data[$field] = [
                    'name'  => $field,
                    'id'    => $field,
                    'placeholder' => lang($field.'_placeholder'),
                    'value' => $this->form_validation->set_value($field),
                ];
            }

            $this->breadcrumbs->push('Add Price Plan', 'price_plans/add');

            $this->template->title(lang('heading_add'))
                           ->set_css(['formvalidation.min', 'bootstrap-checkbox-radio.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'admin/price_plans/custom_js', ['form_name' => 'add-price-plan-form'])
                           ->build('admin/price_plans/add', $this->data);
        }
    }

    public function edit()
    {
        $id = $this->uri->segment(4);

        if ( ! $id )
        {
            $this->flasher->set_warning_extra(lang('invalid_id'), 'admin/price_plans', TRUE);
        }

        $price_plan = $this->price_plan_model->get($id);

        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim');
        $this->form_validation->set_rules('price', 'price', 'trim|required');
        $this->form_validation->set_rules('space', 'space allotted', 'trim|required');

        if ($this->form_validation->run() == TRUE)
        {
            $name           = $this->input->post('name', TRUE);
            $description    = $this->input->post('description', TRUE);
            $price          = $this->input->post('price', TRUE);
            $space          = (int) $this->input->post('space', TRUE);
            $space_unit     = strtoupper($this->input->post('space_unit', TRUE));

            $price_plan_data = [
                'name'              => $name,
                'description'       => $description,
                'price'             => $price,
                'space_allotted'    => $space,
                'space_unit'        => $space_unit,
            ];

            if ($this->price_plan_model->update($price_plan_data, $id))
            {
                $this->flasher->set_success(lang('edit_successful'), 'admin/price_plans', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('edit_failed'), 'admin/price_plans/edit/'.$id, TRUE);
            }
        }
        else
        {
            $fields = ['name', 'description', 'price', 'space'];

            foreach ($fields as $field)
            {
                $this->data[$field] = [
                    'name'  => $field,
                    'id'    => $field,
                    'placeholder' => lang($field.'_placeholder'),
                ];
            }

            $this->breadcrumbs->push('Edit', 'price_plans/edit');

            $this->template->title(lang('heading_edit'))
                           ->set_css(['formvalidation.min', 'bootstrap-checkbox-radio.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'admin/price_plans/custom_js', ['form_name' => 'edit-price-plan-form'])
                           ->set('price_plan', $price_plan)
                           ->build('admin/price_plans/edit', $this->data);
        }
    }

    public function name_check($name)
    {
        $query = $this->price_plan_model->fields('name')->where('name', $name)->get();

        if ($query)
        {
            $this->form_validation->set_message('name_check', $this->lang->line('add_failed_duplicate_name'));

            return FALSE;
        }

        return TRUE;

        unset($query);
    }

    public function delete()
    {
        $id = $this->uri->segment(4);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('invalid_id'), 'admin/price_plans', TRUE);
        }
        else
        {
            if ($this->price_plan_model->delete($id))
            {
                $this->flasher->set_success(lang('delete_successful'), NULL, TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('delete_failed'), NULL, TRUE);
            }

            redirect('admin/price_plans');
        }
    }

}

/* End of file Price_plans.php */
/* Location: ./application/controllers/admin/Price_plans.php */