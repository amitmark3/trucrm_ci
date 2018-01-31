<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Account_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('profile');
		if($this->session->userdata['is_company_admin']==0 && $this->session->userdata['is_dep_manager']==0){
			$this->breadcrumbs->push('Admin', 'admin');
		}else{
			$this->breadcrumbs->push('Home', 'admin');
		}
        $this->breadcrumbs->push('Your Profile', 'profile');
		
		// For UPload the file
		$this->load->helper('MY_file');
    }

    public function index()
    {
        $user = $this->user_model->fields('id, email, created_at, notify_by')->with_profile()->with_department('fields: name')->get($this->user->id);

        if ($user['profile']['avatar'])
        {
            if ( ! $this->ion_auth->is_admin())
            {
                $avatar = site_url('uploads/' . $this->company['uploads_folder'] . '/avatars/' . $user['profile']['avatar']);
            }
            else
            {
                $avatar = site_url('uploads/' . $user['profile']['avatar']);
            }
        }
        else
        {
            $avatar = site_url('assets/img/icons/user.png');
        }

        $this->template->title(lang('profile_heading_index'))
                       ->set_css('fileinput.min')
                       ->set_js('fileinput.min')
                       ->set('user', $user)
                       ->set_partial('custom_js', 'profile/upload_js', ['avatar' => $avatar, 'user_id' => $user['id']])
                       ->build('profile/view', $this->data);
    }

    public function update()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('first_name', 'first name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'last name', 'trim|required');
        $this->form_validation->set_rules('email_address', 'email address', 'trim|required|valid_email');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        if ($this->form_validation->run() == TRUE)
        {
            $user_data = [
                'email' => $this->input->post('email_address', TRUE),
            ];

            $profile_data = [
                'first_name' => $this->input->post('first_name', TRUE),
                'last_name' => $this->input->post('last_name', TRUE),
                'job_title' => $this->input->post('job_title', TRUE),
                'employee_number' => $this->input->post('employee_number', TRUE),
            ];

            $update_user = $this->user_model->update($user_data, $this->user->id);

            $update_profile = $this->profile_model->where('user_id', $this->user->id)->update($profile_data);

            if ($update_user && $update_profile)
            {
                $this->flasher->set_success(lang('profile_update_successful'), 'profile', TRUE);
            }

            $this->flasher->set_danger(lang('profile_update_failed'), 'profile', TRUE);
        }
        else
        {
            $user = $this->user_model->fields('id, email')->with_profile()->get($this->user->id);

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

            $this->template->title(lang('profile_heading_index'))
                           ->set_css('formvalidation.min')
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set('user', $user)
                           ->set_partial('custom_js', 'profile/validation_js', ['form_name' => 'update-profile-form'])
                           ->build('profile/update', $this->data);
        }
    }

    public function change_password()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('old_password', 'old password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'new password', 'trim|required|matches[new_password_confirm]');
        $this->form_validation->set_rules('new_password_confirm', 'confirm new password', 'trim|required');

        $user = $this->user_model->fields('email')->with_profile()->get($this->user->id);

        if ($this->form_validation->run() == FALSE)
        {
            $this->breadcrumbs->push('Change Password', 'profile/change_password');

            $this->template->title(lang('profile_heading_change_password'))
                           ->set_css('formvalidation.min')
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'profile/change_password_js', ['form_name' => 'change-password-form'])
                           ->build('profile/change_password', $this->data);
        }
        else
        {
            if ($this->ion_auth->change_password($user['email'], $this->input->post('old_password', TRUE), $this->input->post('new_password', TRUE)))
            {
                $email_data = [
                    'first_name' => $user['profile']['first_name'],
                    'password' => $this->input->post('new_password')
                ];

                $this->email_lib->send_email($user['email'], 'Password Changed on Trucrm', 'profile/email/new_password', $email_data);

                $this->flasher->set_success($this->ion_auth->messages(), 'profile', TRUE);
            }

            $this->flasher->set_danger($this->ion_auth->errors(), 'profile/change_password', TRUE);
        }
    }

    public function avatar()
    {
		
		$upload_path = $this->company_lib->get_uploads_folder($this->company['id'], TRUE);
        $admin_upload_path = FILE_PATH;//FCPATH . DIRECTORY_SEPARATOR . 'uploads';
		$file_path = $this->ion_auth->is_admin() ? $admin_upload_path : $upload_path;
		
        $user_id = $this->input->post('user_id', TRUE);

        if ($user_id)
        {
            // get avatar file_name from profiles table
            $profile = $this->profile_model->fields('avatar')->where('user_id', $user_id)->get();
			if ( ! is_null($profile['avatar'])){
				
				$avatar = $file_path . DIRECTORY_SEPARATOR  . $profile['avatar'];
				//Delete the image
				$file_name = $profile['avatar'];
				delete_image($file_name, $file_path);
            }
        }
		//*******Start File Upload Using Helper V20180123*******//
		//print '<pre>';print_r($_FILES);die;
		if (isset($_FILES['avatar'])){
			//FILE_PATH	
			$img_response = array();			
			$image_config=array();
			$image_config['field']='avatar';
			$image_config['cur_time'] = time();
			$image_config['directory'] = $upload_path;;
			$image_config['file_type'] = 'image';
			$image_config['create_thumb'] = TRUE;
			$image_config['width'] = 25;
			$image_config['height'] = 25;	
			$image_config['max_size'] = $this->config->item('max_file_size');			
			$img_response = uploadfile_image($image_config);
			
			if($img_response['error']==''){
				if ($this->profile_model->where('user_id', $this->user->id)->update(['avatar' => $img_response['file_name']]))
				{
					$response = [];
				}
			}else{
				$response =["error" => $img_response['error']];
			}
        }else{
			$response = ["error" => "No file selected."];
		}
		//*******End File Upload Using Helper V20180123*******//
		
		echo json_encode($response);
    }

    public function preferences()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        if ( ! empty($_POST) )
        {
            $email = $this->input->post('email');

            $website = $this->input->post('website');

            if ($email && $website)
            {
                $notify_by = 'both';
            }
            elseif ($website)
            {
                $notify_by = 'website';
            }
            elseif ($email)
            {
                $notify_by = 'email';
            }
            else
            {
                $notify_by = 'neither';
            }

            if ($this->user_model->update(['notify_by' => $notify_by], $this->user->id))
            {
                $this->flasher->set_success(lang('profile_change_preferences_successful'), 'profile/preferences', TRUE);
            }
        }
        else
        {
            $this->data['user'] = $this->user_model->fields('notify_by')->get($this->user->id);

            $this->breadcrumbs->push(lang('profile_heading_change_preferences'), 'profile/preferences');

            $this->template->title(lang('profile_heading_change_preferences'))
                           ->set_css('bootstrap-checkbox-radio.min')
                           ->build('profile/preferences', $this->data);
        }
    }
}

/* End of file Profile.php */
/* Location: ./application/controllers/Profile.php */