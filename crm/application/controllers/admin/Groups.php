<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('group_model');
        $this->load->language('admin/groups');
        $this->breadcrumbs->push('Admin', 'admin');
    }

    public function index()
    {
        $this->breadcrumbs->push('Groups', 'groups');
        $this->template->title(lang('groups_heading_index'))
                       ->build('admin/companies/index', $this->data);
    }

    public function datatables()
    {
        $this->load->library('datatables');
        $this->datatables->select("companies.id, companies.name, companies.phone_number, price_plans.name as price_plan_name, companies.active, companies.setup");
        $this->datatables->from("companies");
        $this->datatables->join("price_plans", "companies.price_plan_id = price_plans.id", "left");
        // $this->datatables->edit_column('id', '<a href="companies/view/$1" title="View Role"><i class="fa fa-eye"></i></a>', 'id');
        $this->datatables->add_column('actions', anchor('admin/groups/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>', ['title' => 'Edit Role']) . ' ' . anchor('admin/groups/delete/$1', '<i class="fa fa-lg fa-times-circle-o"></i>', ['title' => 'Delete Role', 'class' => 'confirm', 'id' => '$1']), 'id');
        echo $this->datatables->generate();
    }

    // create a new group
    public function add()
    {
        $this->data['title'] = $this->lang->line('create_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth');
        }

        // validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

        if ($this->form_validation->run() == TRUE)
        {
            $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
            if($new_group_id)
            {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth");
            }
        }
        else
        {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['group_name'] = array(
                'name'  => 'group_name',
                'id'    => 'group_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('group_name'),
            );
            $this->data['description'] = array(
                'name'  => 'description',
                'id'    => 'description',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('description'),
            );

            $this->_render_page('auth/create_group', $this->data);
        }
    }

    // edit a group
    public function edit()
    {
        // bail if no group id given
        if(!$id || empty($id))
        {
            redirect('auth');
        }

        $this->data['title'] = $this->lang->line('edit_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth');
        }

        $group = $this->ion_auth->group($id)->row();

        // validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

        if (isset($_POST) && !empty($_POST))
        {
            if ($this->form_validation->run() === TRUE)
            {
                $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

                if($group_update)
                {
                    $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
                }
                else
                {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("auth");
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['group'] = $group;

        $readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

        $this->data['group_name'] = array(
            'name'    => 'group_name',
            'id'      => 'group_name',
            'type'    => 'text',
            'value'   => $this->form_validation->set_value('group_name', $group->name),
            $readonly => $readonly,
        );
        $this->data['group_description'] = array(
            'name'  => 'group_description',
            'id'    => 'group_description',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('group_description', $group->description),
        );

        $this->_render_page('auth/edit_group', $this->data);
    }

    public function delete()
    {
    }

}

/* End of file Groups.php */
/* Location: ./application/controllers/admin/Groups.php */