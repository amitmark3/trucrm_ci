<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends Account_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->language('company');

        if ( ! in_array($this->user_group['id'], [2]) )
        {
            $this->flasher->set_warning_extra(lang('company_access_denied'), 'dashboard', TRUE);
        }

        $this->breadcrumbs->push('Home', 'dashboard');
        $this->breadcrumbs->push('Company', 'company');
		// For UPload the file
		$this->load->helper('MY_file');
    }

    public function index()
    {
        $this->load->model(['price_plan_model', 'payment_model']);

        if ($this->company['logo'] !== NULL)
        {
            $logo = site_url('uploads/' . $this->company['uploads_folder'] . '/avatars/' . $this->company['logo']);
        }
        else
        {
            $logo = site_url('assets/img/icons/user.png');
        }

        $this->template->title(lang('company_heading_index'))
                       ->set_css('fileinput.min')
                       ->set_js('fileinput.min')
                       ->set_partial('custom_js', 'company/upload_js', ['logo' => $logo, 'company_id' => $this->company['id']])
                       ->set('percent_used', $this->company_lib->percentage_used($this->company['id']))
                       ->set('price_plan', $this->price_plan_model->get($this->company['price_plan_id']))
                       ->set('payment', $this->payment_model->where('company_id', $this->company['id'])->order_by('created_at', 'desc')->limit(1)->get())
                       ->set('company', $this->company)
                       ->build('company/index', $this->data);
    }

    public function logo()
    {
        $company_id = $this->input->post('company_id', TRUE);

        if ($company_id)
        {
            $upload_path = $this->company_lib->get_uploads_folder($this->company['id'], TRUE);

            // get logo file_name from company table
            $company = $this->company_model->fields('logo')->get($company_id);

            if ( ! is_null($company['logo']) )
            {
                //Delete the image
				$file_name = $company['logo'];
				delete_image($file_name, $upload_path);
            }
        }
		//*******Start File Upload Using Helper V20180123*******//
		//print '<pre>';print_r($_FILES);die;
		if (isset($_FILES['logo'])){ 
			//FILE_PATH	
			$img_response = array();			
			$image_config=array();
			$image_config['field']='logo';
			$image_config['cur_time'] = time();
			$image_config['directory'] = $upload_path;;
			$image_config['file_type'] = 'image';
			$image_config['create_thumb'] = TRUE;
			$image_config['width'] = 160;
			$image_config['height'] = 120;	
			$image_config['max_size'] = $this->config->item('max_file_size');			
			$img_response = uploadfile_image($image_config);
			
			if($img_response['error']==''){
				if ($this->company_model->where('id', $this->user->company_id)->update(['logo' => $img_response['file_name']]))
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

    public function edit()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim');
        $this->form_validation->set_rules('address', 'address', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'phone number', 'trim');
        $this->form_validation->set_rules('website_url', 'website address', 'trim');

        if ($this->form_validation->run() == TRUE)
        {
            $update_data = [
                'name'          => $this->input->post('name', TRUE),
                'description'   => $this->input->post('description', TRUE),
                'address'       => $this->input->post('address', TRUE),
                'phone_number'  => $this->input->post('phone_number', TRUE),
                'website_url'   => prep_url($this->input->post('website_url', TRUE)),
            ];

            if ($this->company_model->update($update_data, $this->company['id']))
            {
                $this->flasher->set_success(lang('company_edit_details_successful'), 'company/edit', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('company_edit_details_failed'), 'company', TRUE);
            }
        }
        else
        {
            $fields = ['name', 'description', 'address', 'phone_number', 'website_url'];

            foreach ($fields as $field)
            {
                $this->data[$field] = [
                    'name'  => $field,
                    'id'  => $field,
                    'placeholder' => lang('company_'.$field.'_placeholder'),
                    'value' => $this->form_validation->set_value($field, $this->company[$field]),
                ];
            }

            $this->template->title(lang('company_heading_edit'))
                           ->set_css('formvalidation.min')
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'company/edit_js')
                           ->set('company', $this->company)
                           ->build('company/edit', $this->data);
        }
    }

    public function change_price_plan()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('price_plan_model');
        $this->load->config('stripe');

        $this->form_validation->set_rules('price_plan_id', 'price plan', 'trim|required');

        if ($this->form_validation->run() == TRUE)
        {
            $price_plan_id = $this->input->post('price_plan_id', TRUE);

            $price_plan = $this->price_plan_model->fields('name, price')->get($price_plan_id);

            $this->session->set_userdata('proposed_price_plan_id', $price_plan_id);

            $this->breadcrumbs->push('Checkout', 'company/checkout');

            $this->template->title('Checkout')
                           ->set('price_plan', $price_plan)
                           ->set('company_admin', $this->company_lib->get_company_admin())
                           ->build('company/checkout', $this->data);
        }
        else
        {
            $old_plan = $this->price_plan_model->fields('price')->get($this->company['price_plan_id']);

            $this->breadcrumbs->push('Change Price Plan', 'company/change_price_plan');

            $this->template->title('Change Price Plan')
                           ->set('old_plan', $old_plan)
                           ->set('price_plans', $this->price_plan_model->order_by('price', 'asc')->get_all())
                           ->build('company/change_price_plan', $this->data);
        }
    }

    // TODO: Finish
    public function renew_price_plan()
    {
        $this->load->model('price_plan');

        // get price plan
        $price_plan = $this->price_plan_model->get();
    }

    // TODO: Add image to Stripe form
    // TODO: Move to company_lib and refactor
    public function stripe_charge()
    {
        $token = $this->input->post('stripeToken', TRUE);

        if ( ! $token )
        {
            $this->flasher->set_danger('There was a problem sending the request to Stripe. Please try again in a few minutes.', 'company/change_price_plan');
        }

        $this->load->model(['price_plan_model', 'payment_model']);
        $this->load->config('stripe');

        \Stripe\Stripe::setApiKey($this->config->item('apiKey'));

        try
        {
            // get price plan id from session
            $price_plan = $this->price_plan_model->get($this->session->userdata('proposed_price_plan_id'));

            if ($this->company['stripe_customer_id'] !== NULL)
            {
                $customer = \Stripe\Customer::retrieve($this->company['stripe_customer_id']);
            }
            else
            {
                $customer = \Stripe\Customer::create(array(
                    'email' => $this->user->email,
                    'card'  => $token,
                    'metadata' => ['company_name' => $this->company['name']]
                ));
            }

            // TODO: Look into Stripe subscriptions (https://stripe.com/docs/subscriptions/guide)
            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $price_plan['price'] * 100, // Stripe charges in cents so multiply by 100 to convert to euros
                'currency' => 'inr'
            ));

            if ($charge->paid == TRUE)
            {
                $payment_data = [
                    'company_id'            => $this->company['id'],
                    'user_id'               => $this->user->id,
                    'stripe_charge_id'      => $charge->id,
                    'stripe_customer_id'    => $customer->id,
                    'amount'                => $charge->amount / 100,
                    'description'           => $price_plan['name'] . ' Plan [' .$this->company['name']. ']',
                    'created_at'            => date('Y-m-d H:i:s', $charge->created),
                    'renewal_date'          => date('Y-m-d H:i:s', strtotime('+1 year', $charge->created)),
                ];
                // record payment details in the database
                $payment = $this->payment_model->insert($payment_data);

                // update the companys price plan
                $this->company_model->update(['price_plan_id' => $price_plan['id'], 'stripe_customer_id' => $customer->id], $this->company['id']);

                // destroy the proposed_price_plan_id session variable
                unset($_SESSION['proposed_price_plan_id']);

                // Send the admins an email with payment details
                $email_data = [
                    'company_name'          => $this->company['name'],
                    'stripe_charge_id'      => $charge->id,
                    'stripe_customer_id'    => $customer->id,
                    'amount'                => $charge->amount / 100,
                    'description'           => $price_plan['name'],
                ];

                // TODO: Replace with code to get all admins from the database (V2)
                $this->email_lib->send_email(['admin@mark3.in', 'amit@mark3.in', 'amitmalik750@gmail.com'], 'Stripe Payment Successfully Charged on Trucrm', 'setup/emails/payment_details', $email_data);

                $this->flasher->set_success(lang('company_payment_successful'), 'company/confirmation');
            }
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->flasher->set_danger(lang('company_payment_network_error'), NULL, TRUE);
        } catch (\Stripe\Error\InvalidRequest $e) {
            // debug mode time!
            $e_json = $e->getJsonBody();
            $error = $e_json['error'];
            log_message('error', 'There was an invalid request when trying to charge to Stripe. The error returned from Stripe was
                : ' . $error['message']);

            $this->load->library('email');

            $this->email->from($this->config->item('website_email'), $this->config->item('website_title'));
            $this->email->to($this->config->item('dev_email'));
            $this->email->subject('Stripe Charged Failed');
            $this->email->message('A Stripe charge failed. See log files for more info.');
            $this->email->send();

            $this->flasher->set_danger(lang('company_payment_we_screwed_up'), NULL, TRUE);
        } catch (\Stripe\Error\Api $e) {
            $this->flasher->set_danger(lang('company_payment_api_error'), NULL, TRUE);
        } catch (\Stripe\Error\Card $e) {
            $e_json = $e->getJsonBody();
            $error = $e_json['error'];
            $this->flasher->set_danger($error['message'], NULL, TRUE);
        }
        redirect('company/change_price_plan');
    }

    // TODO: Provide a reference number for the client?
    public function confirmation()
    {
        $this->breadcrumbs->push(lang('company_heading_confirmation'), 'company/confirmation');
        $this->template->title(lang('company_heading_confirmation'))
                       ->build('company/confirmation', $this->data);
    }

    // TODO: Check if department already has an owner. (V2)
    // TODO: Look into improving speed of import (search for MySQL Local Load) (V2)
    // TODO: Check all users get imported correctly
    public function import()
    {
        $this->load->helper('form');

        if ( ! empty($_FILES) )
        {
            $config['upload_path'] = $this->company_lib->get_uploads_folder($this->company['id']);
            $config['allowed_types'] = 'csv'; // TODO: If Excel option is provided in the future add 'xls' and 'xlsx' here.
            $config['max_size'] = '10240'; // 10MB

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file') )
            {
                $this->flasher->set_warning_extra($this->upload->display_errors('', ''), 'company/import', TRUE);
            }
            else
            {
                $data = $this->upload->data();

                // determine what to do depending on the mime type of the file
                if ($data['file_ext'] == '.csv')
                {
                    $this->load->library('csvreader');

                    $fields = $this->csvreader->parse_file($data['full_path']);

                    // die(var_dump($fields));

                    if ( ! empty($fields) )
                    {
                        foreach ($fields as $field)
                        {
                            $first_name = $field['FirstName'];
                            $last_name = $field['LastName'];
                            $email = $field['EmailAddress'];
                            $department_name = $field['Department'];
                            $role = $field['Role'] + 2;

                            // make sure they cannot add a user with a role of 1 (admin) or 2 (safety manager)
                            if ($role >= 3)
                            {
                                if ($this->ion_auth->email_check($email) == FALSE)
                                {
                                    $salt = $this->config->item('store_salt', 'ion_auth') ? $this->ion_auth->salt() : FALSE;

                                    $this->load->helper('string');

                                    $password = random_string('alnum', 8);

                                    $hashed_password = $this->ion_auth->hash_password($password, $salt);

                                    // User must be created first, in order to have the id
                                    $user_data = [
                                        'company_id' => $this->user->company_id,
                                        'password' => $hashed_password,
                                        'email' => $email,
                                        'active' => 1,
                                        'is_dep_manager' => $role == 3 ? 1 : NULL
                                    ];

                                    $user_id = $this->user_model->insert($user_data);

                                    if ( ! $user_id )
                                    {
                                        $this->flasher->set_warning_extra('There was a problem adding a user.', 'company/import', TRUE);
                                    }

                                    // send welcome email to user
                                    $email_data = ['email' => $email, 'password' => $password];
                                    $this->email_lib->send_email($email, lang('company_welcome_email_subject'), 'setup/emails/welcome', $email_data);

                                    // add user to users_groups table
                                    // TODO: Add check for incorrect numbers in the csv file.
                                    $this->ion_auth_model->add_to_group($role, $user_id);

                                    // once user is inserted, create their profile
                                    $profile_data = [
                                        'user_id'           => $user_id,
                                        'first_name'        => $first_name,
                                        'last_name'         => $last_name,
                                    ];
                                    $this->profile_model->insert($profile_data);

                                    // check if department exists in the database
                                    $this->load->model('department_model');

                                    // check if department for the company exists in the database
                                    $department = $this->department_model
                                                       ->fields('id')
                                                       ->where(['name' => $department_name, 'company_id' => $this->user->company_id])
                                                       ->get();

                                    // if department does NOT exist, create one
                                    if ( ! $department )
                                    {
                                        $department_data = [];

                                        $department_data['name'] = $department_name;
                                        $department_data['company_id'] = $this->user->company_id;

                                        if ($role == 3) // User is a department manager, so set them as the assigned user for the department
                                        {
                                            $department_data['assigned_user_id'] = $user_id;
                                        }

                                        $department_id = $this->department_model->insert($department_data);

                                        // update the user with the new department id
                                        $this->user_model->update(['department_id' => $department_id], $user_id);
                                    }
                                    else
                                    {
                                        $this->user_model->update(['department_id' => $department['id']], $user_id);
                                    }

                                    $this->flasher->set_success(lang('company_import_successful'), NULL, TRUE);
                                }
                                else
                                {
                                    $this->flasher->set_danger(sprintf(lang('company_import_failed_email'), $email), 'company/import', TRUE);
                                }
                            }
                            else
                            {
                                $this->flasher->set_danger(lang('company_import_failed_role'), 'company/import', TRUE);
                            }
                        } 
                    }
                    else
                    {
                        $this->flasher->set_danger(lang('company_import_failed_file_is_empty'), 'company/import', TRUE);
                    }

                    unlink($data['full_path']); // delete the uploaded csv file

                    redirect('company');
                }
                elseif ($data['file_ext'] == '.xls')
                {
                    // TODO: Will this be used at any point?
                }
            }
        }
        else
        {
            $this->breadcrumbs->push(lang('company_import_heading'), 'company/import');
            $this->template->title(lang('company_import_heading'))
                           ->set_js('bootstrap-filestyle.min')
                           ->set_partial('custom_js', 'company/import_js')
                           ->build('company/import', $this->data);
        }
    }
}

/* End of file Company.php */
/* Location: ./application/controllers/Company.php */