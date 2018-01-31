<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->language('users');

        $this->breadcrumbs->push('Admin', 'admin');
        $this->breadcrumbs->push('Users', 'admin/users');
    }

    public function index()
    {
        $this->template->title(lang('users_heading_index'))
                       ->set_css('datatables.bootstrap.min')
                       ->set_js(['datatables.jquery.min', 'datatables.bootstrap.min', 'bootbox-4.4.0.min'])
                       ->set_partial('custom_js', 'admin/users/datatables_js', ['url' => site_url('admin/users/datatables')])
                       ->build('admin/users/index', $this->data);
    }

    public function datatables()
    {
        $this->load->library('datatables');

        $this->datatables->select("users.id, profiles.first_name, profiles.last_name, users.email, companies.name, groups.description");
        $this->datatables->from("users");
        $this->datatables->join("companies", "users.company_id = companies.id", "left");
        $this->datatables->join("profiles", "users.id = profiles.user_id", "left");
        $this->datatables->join("users_groups", "users.id = users_groups.user_id", "left");
        $this->datatables->join("groups", "groups.id = users_groups.group_id", "left");

        echo $this->datatables->generate();
    }

    public function view()
    {
        $id = $this->uri->segment(4);

        if ( ! $id )
        {
            $this->flasher->set_warning_extra(lang('users_invalid_id'), 'admin/users', TRUE);
        }

        $user = $this->user_model
                     ->with_profile()
                     ->with_department('fields: name')
                     ->with_company('fields: name')
                     ->get($id);

        $this->breadcrumbs->push($user['profile']['first_name'] . ' ' . $user['profile']['last_name'], 'admin/users/view');

        $this->template->title(lang('users_heading_view'))
                       ->set_css('bootstrap-toggle.min')
                       ->set_js(['bootstrap-toggle.min', 'bootbox-4.4.0.min'])
                       ->set_partial('custom_js', 'admin/users/view_js')
                       ->Set('user', $user)
                       ->build('admin/users/view', $this->data);
    }

    public function add()
    {
        $this->load->library(['form_validation', 'admin/admin_lib']);
        $this->load->helper('form');

        $this->form_validation->set_rules('first_name', 'first name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'last name', 'trim|required');
        $this->form_validation->set_rules('email_address', 'email address', 'trim|required|valid_email|callback_email_check');

        if ($this->form_validation->run() == TRUE)
        {
            $first_name = $this->input->post('first_name', TRUE);
            $last_name = $this->input->post('last_name', TRUE);
            $email_address = $this->input->post('email_address', TRUE);
            $phone_number = $this->input->post('phone_number', TRUE);
            $job_title = $this->input->post('job_title', TRUE);
            $employee_number = $this->input->post('employee_number', TRUE);
            $department_id = $this->input->post('department_id', TRUE);
            $group_id = $this->input->post('group_id', TRUE);
            $company_id = $this->input->post('company_id', TRUE);

            $this->load->helper('string');
            $salt = $this->config->item('store_salt', 'ion_auth') ? $this->ion_auth->salt() : FALSE;
            $password = random_string('alnum', 8);
            $hashed_password = $this->ion_auth->hash_password($password, $salt);

            $user_data = [
                'company_id' => $company_id,
                'email' => $email_address,
                'password' => $hashed_password,
                'active' => 1,
                'is_company_admin' => ($group_id == 2 ? 1 : 0),
                'is_dep_manager' => ($group_id == 3 ? 1 : 0),
                'department_id' => ($group_id == 4 ? $department_id : 0)
            ];
            $user_id = $this->user_model->insert($user_data);
            // TODO: Check if there is already a dep manager assigned to the selected dep
            $this->load->model('department_model');
            if ($group_id == 3)
            {
                $assigned_data = ['assigned_user_id' => $user_id];
                $this->department_model->where('id', $department_id)->update($assigned_data);
            }

            // Add to users_groups
            $this->ion_auth->add_to_group($group_id, $user_id);

            // Insert info into profile table
            $profile_data = [
                'user_id' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone_number' => $phone_number,
                'job_title' => $job_title,
                'employee_number' => $employee_number,
            ];
            $this->profile_model->insert($profile_data);

            // Send welcome email to new user
            $email_data = [
                'first_name' => $first_name,
                'email' => $email_address,
                'password' => $password,
            ];
            $this->email_lib->send_email($email_address, lang('users_email_welcome_subject'), 'admin/users/email/welcome', $email_data);

            // Get all course ids for the chosen department
            $course_ids = $this->db->select('course_id')
                                   ->from('courses_departments')
                                   ->where(['department_id' => $department_id, 'company_id' => $company_id])
                                   ->get()
                                   ->result_array();
            // Convert multi-dimentional array to single array
            $courses_array = array_column($course_ids, 'course_id');

            if ($courses_array)
            {
                // Create batch insert array from courses_array
                $batch_data = [];
                $i = 0;
                foreach ($courses_array as $course => $value)
                {
                    $batch_data[$i]['company_id'] = $company_id;
                    $batch_data[$i]['course_id'] = $value;
                    $batch_data[$i]['user_id'] = $user_id;
                    $batch_data[$i]['department_id'] = $department_id;
                    $course = $this->course_model->fields('days_to_retrain')->where('id', $value)->get();
                    $batch_data[$i]['date'] = date('Y-m-d', strtotime(date('Y-m-d') . ' + ' . $course['days_to_retrain'] . ' days'));
                    $i++;
                }
                $query = $this->db->insert_batch('training_required', $batch_data);
            }

            $this->flasher->set_success(lang('users_insert_successful'), 'admin/users', TRUE);
        }
        else
        {
            $form_fields = ['first_name', 'last_name', 'email_address', 'phone_number', 'job_title', 'employee_number'];

            foreach ($form_fields as $field)
            {
                $this->data[$field] = [
                    'name'  => $field,
                    'id'    => $field,
                ];
            }

            $this->load->model(['department_model', 'company_model']);

            $companies = $this->admin_lib->get_companies($active = 1);

            if ( ! $companies )
            {
                $this->flasher->set_warning(sprintf('No companies have been added yet. %s', anchor('admin/companies/add', 'Add A Company')), 'admin/users', TRUE);
            }

            $companies = ['' => 'Please choose a company'] + $companies;

            $query = $this->db->select('id, description')->where('id != 2')->order_by('id', 'desc')->get('groups');

            $groups = $query->result_array();

            foreach ($groups as $group)
            {
                $group_id = $group['id'];
                $group_name = $group['description'];
                $groups_dropdown[$group_id] = $group_name;
            }

            $this->data['groups'] = ['' => 'Please choose a role'] + $groups_dropdown;

            $this->breadcrumbs->push(lang('users_heading_add'), 'admin/users/add');

            $this->template->title(lang('users_heading_add'))
                           ->set_css(['formvalidation.min', 'bootstrap-checkbox-radio.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'admin/users/add_js')
                           ->set('companies', $companies)
                           ->build('admin/users/add', $this->data);
        }
    }

    public function email_check($email)
    {
        $query = $this->user_model->fields('email')->where('email', $email)->as_array()->get();
        if ($query)
        {
            $this->form_validation->set_message('email_check', $this->lang->line('users_insert_failed_email_exists'));
            return FALSE;
        }
        return TRUE;
        unset($query);
    }

    public function edit()
    {
        $id = $this->uri->segment(4);
        if ( !$id )
        {
            $this->flasher->set_info(lang('users_invalid_id'), 'admin/users', TRUE);
        }
        $user = $this->user_model->where('id', $id)->with_profile()->get();
        $this->breadcrumbs->push('Edit User', 'admin/users/edit');
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('first_name', 'first name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'last name', 'trim|required');
        $this->form_validation->set_rules('email_address', 'email address', 'trim|required|valid_email');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        if ($this->form_validation->run() == TRUE)
        {
            // update user table
            $user_data = ['email' => $this->input->post('email_address', TRUE)];
            $user_id = $this->user_model->where('id', $user['id'])->update($user_data);

            // update info in profile table
            $this->load->model('profile_model');
            $profile_data = [
                'first_name' => $this->input->post('first_name', TRUE),
                'last_name' => $this->input->post('last_name', TRUE),
                'job_title' => $this->input->post('job_title', TRUE),
                'employee_number' => $this->input->post('employee_number', TRUE),
            ];
            $this->profile_model->where('user_id', $user['id'])->update($profile_data);

            $this->flasher->set_success(lang('users_update_successful'), 'admin/users', TRUE);
        }
        else
        {
            $form_fields = ['first_name', 'last_name', 'email_address', 'job_title', 'employee_number'];
            foreach ($form_fields as $field) {
                $this->data[$field] = [
                    'name'  => $field,
                    'id'    => $field,
                    // 'value' => $this->form_validation->set_value($field),
                ];
            }

            $this->data['user'] = $user;
            $this->template->title(lang('users_heading_edit'))
                           ->set_css(['formvalidation.min', 'bootstrap-checkbox-radio.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'admin/users/edit_js')
                           ->build('admin/users/edit', $this->data);
        }
    }

    /*
    public function delete()
    {
        $id = $this->uri->segment(4);
        if ( !$id )
        {
            $this->flasher->set_info(lang('users_invalid_id'), 'admin/users', TRUE);
        }
        else
        {
            if ( $this->user_model->delete($id) )
            {
                $this->flasher->set_success(lang('users_delete_successful'));
            }
            else
            {
                $this->flasher->set_danger(lang('users_delete_failed'));
            }
            redirect('admin/users');
        }
    }
    */

    public function get_company_departments()
    {
        $company_id = $this->input->post('company_id', TRUE);
        $this->load->model('department_model');
        $departments = $this->department_model->fields('id, name')->where('company_id', $company_id)->as_dropdown('name')->order_by('name')->get_all();
        if ($departments)
        {
            foreach ($departments as $key => $value)
            {
                echo "<option value='".$key."'>".$value."</option>";
            }
        }
        else
        {
            echo "<option value=''>No departments exist!</option>";
        }
    }

    public function change_password()
    {
        $id = $this->uri->segment(4);
        if ( !$id )
        {
            $this->flasher->set_info(lang('users_invalid_id'), 'admin/users', TRUE);
        }
        $user = $this->user_model->fields('id, email, salt')->where('id', $id)->with_profile('fields:first_name')->get();

        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('new_password', 'new password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
        $this->form_validation->set_rules('confirm_new_password', 'confirm new password', 'required|matches[new_password]');

        if ( $this->form_validation->run() == TRUE )
        {
            $password = $this->input->post('new_password', TRUE);
            // hash the new password
            $hashed_new_password = $this->ion_auth->hash_password($password, $user['salt']);
            // reset the remember code so all remembered instances have to re-login
            $update_data = [
                'password' => $hashed_new_password,
                'remember_code' => NULL
            ];
            $change = $this->user_model->where('id', $user['id'])->update($update_data);
            if ($change)
            {
                // Send an email to the user
                $this->load->library('email');
                $this->email->clear();
                $this->email->from($this->config->item('website_email'), $this->config->item('website_title'));
                $this->email->to($user['email']);
                $this->email->subject(lang('users_change_password_by_admin_email_subject'));
                $email_data = [
                    'first_name'    => $user['profile']['first_name'],
                    'password'      => $password
                ];
                $this->email->message($this->load->view('admin/users/email/new_password', $email_data, TRUE));
                $this->email->send();
                $this->flasher->set_success(lang('users_change_password_successful'), 'admin/users/view/'.$id, TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('users_change_password_failed'), 'admin/change_password/view/'.$id, TRUE);
            }
        }
        else
        {
            $this->data['new_password'] = [
                'name'    => 'new_password',
                'id'      => 'new_password',
            ];
            $this->data['confirm_new_password'] = [
                'name'    => 'confirm_new_password',
                'id'      => 'confirm_new_password',
            ];

            $this->data['user'] = $user;
            $this->breadcrumbs->push(lang('users_heading_change_password'), 'admin/users/change_password');
            $this->template->title(lang('users_heading_change_password'))
                           ->set_css('formvalidation.min')
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'admin/users/password_js')
                           ->build('admin/users/change_password', $this->data);
        }
    }

    public function set_active_status()
    {
        $user_id = $this->input->post('user_id', TRUE);
        $active = $this->input->post('active', TRUE);
        if ($this->user_model->where('id', $user_id)->update(['active' => $active]))
        {
            echo 'TRUE';
        }
        else
        {
            echo 'FALSE';
        }
    }

}

/* End of file Users.php */
/* Location: ./application/controllers/admin/Users.php */