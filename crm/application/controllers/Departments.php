<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Departments extends Account_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ( ! in_array($this->user_group['id'], [2]) )
        {
            $this->flasher->set_warning(lang('departments_access_denied'), 'dashboard', TRUE);
        }
        $this->load->model('department_model');
        $this->load->language('departments');
        $this->breadcrumbs->push('Home', 'dashboard');
        $this->breadcrumbs->push('Departments', 'departments');
    }

    public function index()
    {
        $this->template->title(lang('departments_heading_index'))
                       ->set_css(['datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min'])
                       ->set_js(['datatables.jquery.min', 'datatables.buttons.min', 'datatables.responsive.min', 'datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min', 'datatables.buttons.print.min', 'datatables.buttons.flash.min', 'datatables.buttons.html5.min', 'bootbox-4.4.0.min', 'moment', 'jszip.min', 'pdfmake.min', 'vfs_fonts'])
                       ->set_partial('custom_js', 'departments/custom_js', ['url' => site_url('departments/datatables')])
                       ->build('departments/index', $this->data);
    }

    public function datatables()
    {
        $this->load->library('datatables');
        $this->datatables->select("departments.id,
                                   departments.name,
                                   departments.description,
                                   CONCAT(profiles.first_name, ' ' , profiles.last_name) as assignee_name");
        $this->datatables->from("departments");
        $this->datatables->join("profiles", "departments.assigned_user_id = profiles.user_id", "left");
        $this->datatables->where('company_id', $this->user->company_id);
        $this->datatables->add_column('edit', anchor('departments/edit/$1', '<i class="fa fa-lg fa-edit"></i>') . ' ' . anchor('departments/delete/$1', '<i class="fa fa-lg fa-times-circle-o"></i>', ['class' => 'confirm', 'id' => '$1']), 'id');
        echo $this->datatables->generate();
    }

    public function view()
    {
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('departments_invalid_id'), 'departments', TRUE);
        }

        $department = $this->department_model->get($id);

        if ($department['company_id'] != $this->user->company_id)
        {
            $this->flasher->set_warning_extra(lang('departments_invalid_company_to_view'), 'departments', TRUE);
        }

        $this->data['department'] = $department;
        $this->data['department_manager'] = $this->user_model->fields('id')->with_profile('fields:first_name, last_name')->get($department['assigned_user_id']);
        $this->data['department_users'] = $this->user_model->fields('id')->where('department_id', $id)->with_profile('fields:first_name,last_name')->get_all();
       

        $this->breadcrumbs->push($department['name'], 'departments/view/' . $id);

        $this->template->title(lang('departments_heading_view'))
                       ->build('departments/view', $this->data);
    }

    public function add()
    {
        $this->breadcrumbs->push('Add', 'departments/add');
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('name', 'name', 'trim|required');

        if ($this->form_validation->run() == TRUE)
        {
            // check that the user chosen has correct level
            $assigned_user_id = $this->input->post('user_id', TRUE);

            $user = $this->user_model->get($assigned_user_id);

            if ($user['is_dep_manager'] === NULL)
            {
                $this->flasher->set_warning_extra(lang('departments_invalid_dep_manager'), 'departments/add', TRUE);
            }

            $insert_data = [
                'company_id'        => $this->user->company_id,
                'assigned_user_id'  => $assigned_user_id,
                'name'              => $this->input->post('name', TRUE),
                'description'       => $this->input->post('description', TRUE)
            ];

            $department_id = $this->department_model->insert($insert_data);

            if ($department_id)
            {
                // update the user with the department id
                $this->user_model->update(['department_id' => $department_id], $assigned_user_id);

                $this->flasher->set_success(lang('departments_insert_successful'), 'departments', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('departments_insert_failed'), 'departments/add', TRUE);
            }
        }
        else
        {
            $this->template->title(lang('departments_heading_add'))
                           ->set_css(['formvalidation.min', 'bootstrap-select.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min', 'bootstrap-select.min'])
                           ->set_partial('custom_js', 'departments/validation_js', ['form_name' => 'add-department-form'])
                           ->set('users', $this->company_lib->get_company_users($active = 1))
                           ->build('departments/add', $this->data);
        }
    }

    public function edit()
    {
        $id = $this->uri->segment(3);

        if ( !$id )
        {
            $this->flasher->set_info(lang('departments_invalid_id'), 'departments', TRUE);
        }
        else
        {
            $department = $this->department_model->get($id);

            if ( $department['company_id'] != $this->user->company_id)
            {
                $this->flasher->set_warning_extra(lang('departments_invalid_company_to_edit'), 'departments', TRUE);
            }
            else
            {
                $this->load->library('form_validation');
                $this->load->helper('form');

                $this->form_validation->set_rules('name', 'name', 'trim|required');

                if ($this->form_validation->run() == TRUE)
                {
                    $update_data = [
                        'name' => $this->input->post('name', TRUE),
                        'description' => $this->input->post('description', TRUE),
                        'assigned_user_id' => $this->input->post('user_id', TRUE),
                    ];

                    $query = $this->department_model->update($update_data, $id);

                    if ($query)
                    {
                        // update the users table with department_id
                        $this->user_model->update(['department_id' => $department['id']], $update_data['assigned_user_id']);

                        $this->flasher->set_success(lang('departments_update_successful'), 'departments', TRUE);
                    }
                    else
                    {
                        $this->flasher->set_danger(lang('departments_update_failed'), 'departments/edit/' . $id, TRUE);
                    }
                }
                else
                {
                    $this->breadcrumbs->push($department['name'], 'departments/edit/' . $id);

                    $this->template->title(lang('departments_heading_edit'))
                                   ->set_css(['formvalidation.min', 'bootstrap-select.min'])
                                   ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min', 'bootstrap-select.min'])
                                   ->set_partial('custom_js', 'departments/validation_js', ['form_name' => 'edit-department-form'])
                                   ->set('department', $department)
                                   ->set('users', $this->company_lib->get_company_users($active = 1))
                                   ->build('departments/edit', $this->data);
                }
            }
        }
    }

    public function delete()
    {
        $id = $this->uri->segment(3);

        if ( !$id )
        {
            $this->flasher->set_info(lang('departments_invalid_id'), 'departments', TRUE);
        }
        else
        {
            $department = $this->department_model->get($id);

            if ($department['company_id'] != $this->user->company_id)
            {
                $this->flasher->set_warning_extra(lang('departments_invalid_company_to_delete'), 'departments', TRUE);
            }

            if ($this->department_model->delete($department['id']))
            {
                // get all users in the department
                $users = $this->user_model->fields('id')->where('department_id', $id)->get_all();

                foreach ($users as $user)
                {
                    // reset their department info
                    $this->user_model->update(['department_id' => NULL, 'is_dep_manager' => 0], $user['id']);
                }

                $this->flasher->set_success(lang('departments_delete_successful'), 'departments', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('departments_delete_failed'), 'departments', TRUE);
            }
        }
    }
}

/* End of file Departments.php */
/* Location: ./application/controllers/Departments.php */