<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Account_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->language('users');

        if ( ! in_array($this->user_group['id'], [2,3]) )
        {
            $this->flasher->set_warning_extra(lang('users_access_denied'), 'dashboard', TRUE);
        }

        $this->breadcrumbs->push('Home', 'dashboard');
        $this->breadcrumbs->push('Users', 'users');
    }

    public function index()
    {
        switch ($this->user_group['id'])
        {
            case 3:
                $view = 'department';
                $this->load->model('department_model');
                $department = $this->department_model->fields('name')->get($this->user->department_id);
                $last_column = 6;
                $language_line = sprintf(lang('users_heading_department'), $department['name']);
                break;
            default:
                $view = 'index';
                $last_column = 7;
                $language_line = lang('users_heading_index');
                break;
        }

        $this->breadcrumbs->push('Users', 'users');

        $this->template->title($language_line)
                       ->set_css(['datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min'])
                       ->set_js(['datatables.jquery.min', 'datatables.buttons.min', 'datatables.responsive.min', 'datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min', 'datatables.buttons.print.min', 'datatables.buttons.flash.min', 'datatables.buttons.html5.min', 'bootbox-4.4.0.min', 'moment', 'jszip.min', 'pdfmake.min', 'vfs_fonts'])
                       ->set_partial('custom_js', 'users/datatables_js', ['url' => site_url('users/datatables'), 'last_column' => $last_column])
                       ->build('users/'.$view, $this->data);
    }

    public function datatables()
    {
        $this->load->library('datatables');
        $this->datatables->from("users");
        $this->datatables->join("profiles", "users.id = profiles.user_id", "left");
        $this->datatables->join("departments", "users.department_id = departments.id", "left");
        $this->datatables->join("users_groups", "users.id = users_groups.user_id", "left");
        $this->datatables->join("groups", "groups.id = users_groups.group_id", "left");

        switch ($this->user_group['id'])
        {
            case 3:
                $this->datatables->select("users.id, profiles.first_name, profiles.last_name, users.email, groups.description, users.active");
                $this->datatables->where('users.department_id', $this->user->department_id);
                $this->datatables->add_column('edit', anchor('users/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>'), 'id');
                break;
            default:
                $this->datatables->select("users.id, profiles.first_name, profiles.last_name, users.email, departments.name, groups.description, users.active");
                $this->datatables->where('users.company_id', $this->user->company_id);
                $this->datatables->add_column('edit', anchor('users/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>'), 'id');
                break;
        }

        echo $this->datatables->generate();
    }

    public function view()
    {
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_warning_extra(lang('users_invalid_id'), 'users', TRUE);
        }

        $user = $this->user_model->with_profile()->get($id);

        $role = $this->db->select('groups.id, groups.description, users_groups.group_id')
                         ->from('users_groups')
                         ->where('user_id', $user['id'])
                         ->join('groups', 'groups.id = users_groups.group_id')
                         ->get()
                         ->row_array();

        $this->load->model('department_model');
        $this->load->helper('text');

        if ( $user['department_id'] !== NULL )
        {
            $department = $this->department_model->fields('name')->get($user['department_id']);
            $this->data['department'] = $department['name'];
        }

        $page_title = ($user['id'] == $this->user->id) ? 'Your Profile' : $user['profile']['first_name'] . ' ' . $user['profile']['last_name'];

        $this->breadcrumbs->push($user['profile']['first_name'] . ' ' . $user['profile']['last_name'], 'users/view/'.$id);

        $this->template->title($page_title)
                       ->set_css('bootstrap-toggle.min')
                       ->set_js(['bootstrap-toggle.min', 'bootbox-4.4.0.min'])
                       ->set_partial('custom_js', 'users/custom_js')
                       ->set('user', $user)
                       ->set('user_role', $role['description'])
                       ->build('users/view', $this->data);
    }

    // TODO: Add option to reassign dep to new user (maybe this should be done in the department controller?)
    // TODO: Check if department already has a manager.
    // TODO: Change form if department manager.
    // TODO: Add default '-- select role --' to form.
    public function add()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('first_name', 'first name', 'trim|required|callback_name_check');
        $this->form_validation->set_rules('last_name', 'last name', 'trim|required');
        $this->form_validation->set_rules('email_address', 'email address', 'trim|required|valid_email|callback_email_check');
        // $this->form_validation->set_rules('department_id', 'department', 'trim|callback_department_manager_check');
        $this->form_validation->set_rules('job_title', 'job title', 'trim');
        $this->form_validation->set_rules('employee_number', 'employee number', 'trim');

        if ($this->form_validation->run() == TRUE)
        {
            $this->load->helper('string');

            $first_name = $this->input->post('first_name', TRUE);
            $last_name = $this->input->post('last_name', TRUE);
            $email_address = $this->input->post('email_address', TRUE);
            $job_title = $this->input->post('job_title', TRUE);
            $employee_number = $this->input->post('employee_number', TRUE);
            $department_id = $this->input->post('department_id', TRUE);
            $group_id = $this->input->post('group_id', TRUE);

            $salt = $this->config->item('store_salt', 'ion_auth') ? $this->ion_auth->salt() : FALSE;

            $password = random_string('alnum', 8);

            $hashed_password = $this->ion_auth->hash_password($password, $salt);

            $user_data = [
                'company_id' => $this->session->company_id,
                'email' => $email_address,
                'password' => $hashed_password,
                'active' => 1,
                'is_company_admin' => ($group_id == 2 ? 1 : 0),
                'is_dep_manager' => ($group_id == 3 ? 1 : 0),
                'department_id' => ($group_id >= 3 ? $department_id : 0)
            ];

            $user_id = $this->user_model->insert($user_data);

            // if role provided was 'department manager' assign the user to the department selected
            if ($group_id == 3)
            {
                $this->load->model('department_model');
                $assigned_data = ['assigned_user_id' => $user_id];
                $this->department_model->update($assigned_data, $department_id);
            }

            // Add to users_groups
            $this->ion_auth->add_to_group($group_id, $user_id);

            // add users info to profile table
            $profile_data = [
                'user_id' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'job_title' => $job_title,
                'employee_number' => $employee_number,
            ];

            $this->profile_model->insert($profile_data);

            $email_data = [
                'email' => $email_address,
                'password' => $password,
            ];

            $this->email_lib->send_email($email_address, lang('users_email_welcome_subject'), 'users/email/welcome', $email_data);

			$this->flasher->set_success(lang('users_insert_successful'), 'users', TRUE);
        }
        else
        {
            $form_fields = ['first_name', 'last_name', 'email_address', 'job_title', 'employee_number'];

            foreach ($form_fields as $field) {
                $this->data[$field] = [
                    'name'  => $field,
                    'id'    => $field,
                    'value' => $this->form_validation->set_value($field),
                ];
            }

            $departments = $this->company_lib->get_company_departments();

            if ($departments == 0)
            {
                $this->flasher->set_warning_extra(lang('users_insert_failed_no_departments'), 'departments/add', TRUE);
            }

            $groups = $this->db->select('id, description')->where('id >', 1)->order_by('id', 'desc')->get('groups')->result_array();

            foreach ($groups as $group)
            {
                $groups_dropdown[$group['id']] = $group['description'];
            }

            $this->breadcrumbs->push('Add', 'users/add');

            $this->template->title(lang('users_heading_add'))
                           ->set_css('formvalidation.min')
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'users/validation_js', ['form_name' => 'add-user-form'])
                           ->set('departments', ['' => 'Please select a department'] + $departments)
                           ->set('groups', ['' => 'Please select a role'] + $groups_dropdown)
                           ->build('users/add', $this->data);
        }
    }

    public function name_check($first_name, $last_name)
    {
        $first_name = $this->input->post('first_name');

        $last_name = $this->input->post('last_name');

        $this->db->select('user_id');
        $this->db->where('first_name', $first_name);
        $this->db->where('last_name', $last_name);

        $result = $this->db->get('profiles');

        if ($result->num_rows() > 0)
        {
            $this->form_validation->set_message('name_check', sprintf(lang('users_insert_failed_name_exists'), $first_name . ' ' . $last_name));

            return false;
        }
        else
        {
            return true;
        }
    }

    public function email_check($email)
    {
        $query = $this->user_model->fields('email')->where('email', $email)->get();

        if ($query)
        {
            $this->form_validation->set_message('email_check', $this->lang->line('users_insert_failed_email_exists'));

            return FALSE;
        }

        return TRUE;

        unset($query);
    }

    public function department_manager_check($department_id)
    {
        $query = $this->user_model->fields('id')->where(['department_id' => $department_id, 'is_dep_manager' => 1])->get();

        if ($query)
        {
            $this->form_validation->set_message('department_manager_check', $this->lang->line('users_insert_failed_department_manager_exists'));

            return FALSE;
        }

        return TRUE;

        unset($query);
    }

    // TODO: Change form if department manager.
    public function edit()
    {
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('users_invalid_id'), 'users', TRUE);
        }

        $user = $this->user_model->with_profile()->get($id);

        if ($user['company_id'] != $this->session->company_id)
        {
            $this->flasher->set_warning_extra(lang('users_invalid_company_to_edit'), 'users', TRUE);
        }

        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('first_name', 'first name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'last name', 'trim|required');
        $this->form_validation->set_rules('job_title', 'job title', 'trim');
        $this->form_validation->set_rules('employee_number', 'employee number', 'trim');

        if ($this->form_validation->run() == TRUE)
        {
            $first_name = $this->input->post('first_name', TRUE);
            $last_name = $this->input->post('last_name', TRUE);
            $email_address = $this->input->post('email_address', TRUE);
            $job_title = $this->input->post('job_title', TRUE);
            $employee_number = $this->input->post('employee_number', TRUE);
            $department_id = $this->input->post('department_id', TRUE);
            $group_id = $this->input->post('group_id', TRUE);

            $update_data = [
                'email' => $email_address,
                'department_id' => $department_id
            ];

            $this->user_model->update($update_data, $user['id']);

            // if role provided was 'department manager' assign the user to the department selected
            // and set the user as the department manager
            if ($group_id == 3)
            {
                $this->load->model('department_model');
                $this->department_model->update(['assigned_user_id' => $user['id']], $department_id);
                $this->user_model->update(['is_dep_manager' => 1], $user['id']);
            }

            // First remove from ALL groups
            $this->ion_auth->remove_from_group(NULL, $user['id']);

            // Add to users_groups
            $this->ion_auth->add_to_group($group_id, $user['id']);

            // update users info in profile table
            $profile_data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'job_title' => $job_title,
                'employee_number' => $employee_number,
            ];

            $this->profile_model->where('user_id', $user['id'])->update($profile_data);

            $this->flasher->set_success(lang('users_update_successful'), 'users', TRUE);
        }
        else
        {
            $form_fields = ['first_name', 'last_name', 'job_title', 'employee_number'];

            foreach ($form_fields as $field) {
                $this->data[$field] = [
                    'name'  => $field,
                    'id'    => $field,
                    'value' => $this->form_validation->set_value($field, $user['profile'][$field]),
                ];
            }

            $this->data['email_address'] = [
                'name'  => 'email_address',
                'id'    => 'email_address',
                'value' => $this->form_validation->set_value('email_address', $user['email']),
            ];

            $departments = $this->company_lib->get_company_departments();

            $departments = ['' => ''] + $departments;

            $groups = $this->db->select('id, description')->where('id >', 1)->get('groups')->result_array();

            foreach ($groups as $group)
            {
                $groups_dropdown[$group['id']] = $group['description'];
            }

            $group = $this->db->select('group_id')->where('user_id', $user['id'])->get('users_groups')->row_array();

            $this->data['user'] = $user;
            $this->data['group_id'] = $group['group_id'];
            $this->data['departments'] = $departments;
            $this->data['groups'] = $groups_dropdown;

            $this->breadcrumbs->push('Edit', 'users/edit');

            $this->template->title(lang('users_heading_edit'))
                           ->set_css('formvalidation.min')
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'users/validation_js', ['form_name' => 'edit-user-form'])
                           ->build('users/edit', $this->data);
        }
    }

    /*
    public function delete()
    {
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('users_invalid_id'), 'users', TRUE);
        }

        $user = $this->user_model->fields('company_id')->get($id);

        if ($user['company_id'] != $this->session->company_id)
        {
            $this->flasher->set_warning_extra(lang('users_invalid_company_to_delete'), 'users', TRUE);
        }

        $delete_user = $this->user_model->delete($id);

        $delete_profile = $this->profile_model->where('user_id', $id)->delete();

        $delete_group = $this->ion_auth->remove_from_group(false, $id);

        if ($delete_user && $delete_profile && $delete_group)
        {
            $this->flasher->set_success(lang('users_delete_successful'), 'users', TRUE);
        }

        $this->flasher->set_danger(lang('users_delete_failed'), 'users', TRUE);
    }
    */

    public function reset_password()
    {
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_warning_extra(lang('users_invalid_id'), 'users', TRUE);
        }

        $user = $this->user_model->get($id);

        if ($user['company_id'] != $this->user->company_id)
        {
            $this->flasher->set_warning_extra(lang('users_access_denied'), 'users', TRUE);
        }

        $this->load->helper('string');

        $salt = $this->config->item('store_salt', 'ion_auth') ? $this->ion_auth->salt() : FALSE;

        $password = random_string('alnum', 8);

        $hashed_password = $this->ion_auth->hash_password($password, $salt);

        // update users password in database
        if ($this->user_model->update(['password' => $hashed_password], $id))
        {
            $this->email_lib->send_email($user['email'], lang('users_reset_password_email_subject'), 'users/email/reset_password', ['password' => $password]);

            $this->flasher->set_success(lang('users_reset_password_successful'), 'users', TRUE);
        }
        else
        {
            $this->flasher->set_danger(lang('users_reset_password_failed'), 'users/view/'.$id, TRUE);
        }
    }

    // TODO: Is security needed?
    public function set_active_status()
    {
        $active = $this->input->post('active', TRUE);

        $user_id = $this->input->post('user_id', TRUE);

        $user = $this->user_model->fields('id')->get($user_id);

        if ( ! $user )
        {
            echo "User Not Found";
        }
        else
        {
            $result = $this->user_model->update(['active' => $active], $user_id);

            if ($result)
            {
                echo "Updated";
            }
            else
            {
                echo "Failed";
            }
        }
    }
}

/* End of file Users.php */
/* Location: ./application/controllers/Users.php */