<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template->set_css(array('bootstrap-3.3.6.min', 'AdminLTE.min', 'skin-red.min', 'style', 'print'));
        $this->template->set_js(array('jquery-2.1.4.min', 'bootstrap-3.3.6.min', 'app.min'));

        $this->load->library('form_validation');
        $this->load->helper('language');
        $this->load->language('auth');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    public function index()
    {
        if ( ! $this->ion_auth->logged_in() )
        {
            $this->login();
        }
        elseif ( $this->ion_auth->is_admin() )
        {
            redirect('admin');
        }
        else
        {
            redirect('dashboard');
        }
    }

    public function login()
    {
        if  ( ! $this->ion_auth->logged_in() )
        {
            $this->form_validation->set_rules('email', 'email', 'required');
            $this->form_validation->set_rules('password', 'password', 'required');
			
            if ( $this->form_validation->run() == TRUE )
            {
                $remember = (bool) $this->input->post('remember', TRUE);
				
                if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
                {	
                    $this->flasher->set_success($this->ion_auth->messages(), NULL, TRUE);
				
                    if ( $this->ion_auth->is_admin() )
                    {
                        redirect('admin');
                    }
                    else
                    {
                        redirect('dashboard');
                    }
                }
                else
                {
                    $this->flasher->set_danger($this->ion_auth->errors(), 'login', FALSE);
                }
            }
            else
            {
                // $this->data['message'] = (validation_errors()) ? validation_errors() : $this->flasher->get_all();
                $this->data['email'] = array('name' => 'email',
                    'id'            => 'email',
                    'type'          => 'text',
                    'placeholder'   => lang('login_email_label'),
                    'value'         => $this->form_validation->set_value('email'),
                );

                $this->data['password'] = array('name' => 'password',
                    'id'            => 'password',
                    'type'          => 'password',
                    'placeholder'   => lang('login_password_label'),
                );

                $this->template->title(lang('login_heading'))
                               ->set_css('formvalidation.min')
                               ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                               ->build('auth/login', $this->data);
            }
        }
        else
        {
            $this->flasher->set_info(lang('login_logged_in'), 'dashboard', TRUE);
        }
    }

    public function logout()
    {
        $logout = $this->ion_auth->logout();

        $this->flasher->set_success($this->ion_auth->messages(), 'login');
    }

    public function register()
    {
        $this->form_validation->set_rules('name', 'Company Name', 'trim|required|callback_name_check');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|callback_email_check');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|matches[password]');

        if ( $this->form_validation->run() == TRUE )
        {
            $name = $this->input->post('name', TRUE);
            $email = $this->input->post('email', TRUE);
            $password = $this->input->post('password', TRUE);

            $this->load->helper('string');

            $random_string = random_string('md5');

            // create a new folder on the server for any uploads this company does.
            $uploads_folder = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $random_string;

            if ( ! file_exists($uploads_folder) ) // create the folder if it doesn't already exist
            {
                $folder_created = mkdir($uploads_folder, 0755, TRUE);

                if ($folder_created === FALSE)
                {
                    $this->load->library('email_lib');
                    $this->email_lib->send_email($this->config->item('dev_email'), 'Company Registration Error', 'auth/email/error', ['name' => $name, 'string' => $random_string]);
                }

                $file_to_copy = APPPATH . 'index.html';

                // copy index.html file to uploads folder to deny direct directory access.
                copy($file_to_copy, $uploads_folder . DIRECTORY_SEPARATOR . 'index.html');

                // create an avatars folder for profile avatars
                $avatars_folder = mkdir($uploads_folder . DIRECTORY_SEPARATOR . 'avatars', 0755, TRUE);

                // copy index.html file to avatars folder to deny direct directory access.
                copy($file_to_copy, $uploads_folder . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . 'index.html');

                $company_data = [
                    'name'              => $name,
                    'active'            => 1,
                    'uploads_folder'    => $random_string,
                ];

                if ($this->company_model->insert($company_data))
                {
                    $company_id = $this->db->insert_id();

                    $user_id = $this->ion_auth->register($email, $password, $company_id, ['2']);

                    if ($user_id)
                    {
                        $profile_data = [
                            'user_id'   => $user_id,
                            'first_name' => 'Safety',
                            'last_name' => 'Manager',
                        ];

                        $this->profile_model->insert($profile_data);

                        $email_admin_data = [
                            'name'  => $name,
                            'email' => $email,
                        ];

                        $this->email_lib->send_email(['admin@mark3.in', 'amit@mark3.in', 'amitmalik750@gmail.com'], 'New Company Registered on Trucrm', 'company/email/admin/new_company', $email_admin_data);

                        $email_data = [
                            'email'     => $email,
                            'password'  => $password,
                        ];

                        $this->email_lib->send_email($email, 'Welcome to Trucrm by Mark3!', 'company/email/welcome', $email_data);

                        $this->flasher->set_success($this->ion_auth->messages(), 'login', TRUE);
                    }
                }
                
                $this->flasher->set_warning_extra($this->ion_auth->errors(), 'signup', TRUE);
            }
        }
        else
        {
            $fields = ['name', 'email', 'password', 'password_confirm'];

            foreach ($fields as $field)
            {
                $this->data[$field] = [
                    'name'  => $field,
                    'id'    => $field,
                    'placeholder' => lang('signup_'.$field.'_label'),
                    'value' => $this->form_validation->set_value($field),
                ];
            }

            $this->template->title(lang('signup_heading'))
                           ->set_css('formvalidation.min')
                           ->set_js(array('formvalidation.min', 'formvalidation-bootstrap.min'))
                           ->build('auth/signup', $this->data);
        }
    }

    public function name_check($name)
    {
        $query = $this->company_model->fields('name')->where('name', $name)->get();

        if ($query)
        {
            $this->form_validation->set_message('name_check', $this->lang->line('signup_failed_name_exists'));

            return FALSE;
        }

        return TRUE;

        unset($query);
    }

    public function email_check($email)
    {
        $query = $this->user_model->fields('email')->where('email', $email)->get();

        if ($query)
        {
            $this->form_validation->set_message('email_check', $this->lang->line('signup_failed_email_exists'));

            return FALSE;
        }

        return TRUE;

        unset($query);
    }

    public function forgot_password()
    {
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        if ( $this->form_validation->run() == FALSE )
        {
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'placeholder' => lang('login_email_label'),
                'required'  => 'required',
            );

            // $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->template->title(lang('forgot_password_heading'))
                           ->set_css('formvalidation.min')
                           ->set_js(array('formvalidation.min', 'formvalidation-bootstrap.min'))
                           ->build('auth/forgot_password', $this->data);
        }
        else
        {
            $user = $this->ion_auth->where('email', $this->input->post('email'))->users()->row();

            if ( ! $user->email )
            {
                $this->ion_auth->set_error('forgot_password_email_not_found');
                $this->flasher->set_danger($this->ion_auth->errors(), 'forgot_password');
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($user->email);

            if ($forgotten)
            {
                $this->flasher->set_success($this->ion_auth->messages(), 'login'); // should display a confirmation page here instead of login page
            }
            else
            {
                $this->flasher->set_danger($this->ion_auth->errors(), 'forgot_password');
            }
        }
    }

    // reset password - final step for forgotten password
    public function reset_password($code = NULL)
    {
        if ( ! $code )
        {
            $this->flasher->set_danger(lang('reset_password_invalid_code'), 'forgot_password');
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user)
        {
            // if the code is valid then display the password reset form
            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id'   => 'new',
                    'type' => 'password',
                    'placeholder' => sprintf(lang('reset_password_new_password_label'), $this->config->item('min_password_length', 'ion_auth')),
                    'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name'    => 'new_confirm',
                    'id'      => 'new_confirm',
                    'type'    => 'password',
                    'placeholder' => lang('reset_password_new_password_confirm_label'),
                    'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
                );
                $this->data['user_id'] = array(
                    'name'  => 'user_id',
                    'id'    => 'user_id',
                    'type'  => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;

                $this->template->title(lang('reset_password_heading'))
                               ->set_css('formvalidation.min')
                               ->set_js(array('formvalidation.min', 'formvalidation-bootstrap.min'))
                               ->build('auth/reset_password', $this->data);
            }
            else
            {
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
                {
                    $this->ion_auth->clear_forgotten_password_code($code);
                    $this->flasher->set_danger(lang('error_csrf'), 'reset_password/' . $code);
                }
                else
                {
                    $change = $this->ion_auth->reset_password($user->email, $this->input->post('new'));

                    if ($change)
                    {
                        $this->flasher->set_success($this->ion_auth->messages(), 'login', TRUE);
                    }
                    else
                    {
                        $this->flasher->set_danger($this->ion_auth->errors(), 'reset_password/' . $code);
                    }
                }
            }
        }
        else
        {
            $this->flasher->set_danger($this->ion_auth->errors(), 'forgot_password');
        }
    }

    public function activate($id, $code = FALSE)
    {
        if ($code !== FALSE)
        {
            $activation = $this->ion_auth->activate($id, $code);
        }
        elseif ($this->ion_auth->is_admin())
        {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation)
        {
            $this->flasher->set_success($this->ion_auth->messages(), 'login', TRUE);
        }
        else
        {
            $this->flasher->set_danger($this->ion_auth->errors(), 'forgot_password', TRUE);
        }
    }

    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}
