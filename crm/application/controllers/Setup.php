<?php defined('BASEPATH') OR exit('No direct script access allowed');

// use \PHPExcel;
// use \PHPExcel_IOFactory;

class Setup extends Setup_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ( ! in_array($this->user_group['id'], [2]) )
        {
            die('<h1>Setup Incomplete</h1><p>Your company manager has not completed the setup process. Please contact them to remind them.</p>');
        }

        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->language('setup');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    public function billing()
    {
        $this->form_validation->set_rules('setup_step', 'setup step', 'trim');

        if ($this->form_validation->run() == TRUE)
        {
            $this->update_setup_step(1);
            redirect('setup/price_plan');
        }
        else
        {
            $this->template->title(lang('setup_heading'))
                           ->build('setup/billing', $this->data);
        }
    }

    public function price_plan()
    {
        $this->load->model('price_plan_model');

        if (isset($_POST['price_plan_id']))
        {
            $update_data = ['price_plan_id' => $this->input->post('price_plan_id', TRUE)];

            if ($this->company_model->update($update_data, $this->company['id']))
            {
                $this->update_setup_step(2);
                $this->flasher->set_success('Price plan has been set.', 'setup/checkout', TRUE);
            }
            else
            {
                $this->flasher->set_danger('There was a problem setting your price plan.', 'setup/price_plan', TRUE);
            }
        }
        else
        {
            $this->template->title('Choose a Price Plan')
                           ->set('price_plans', $this->price_plan_model->order_by('price', 'asc')->get_all())
                           ->build('setup/price_plan', $this->data);
        }
    }

    public function checkout()
    {
        $this->load->model('price_plan_model');
        $this->load->config('stripe');

        $this->data['price_plan'] = $this->price_plan_model->get($this->company['price_plan_id']);
        $this->data['company_admin'] = $this->company_lib->get_company_admin();

        $this->template->title('Pay with Stripe')
                       ->build('setup/checkout', $this->data);
    }

    // TODO: Move to company_lib and refactor
    public function stripe_charge()
    {
        $token = $this->input->post('stripeToken', TRUE);

        if ( ! $token )
        {
            $this->flasher->set_danger('There was a problem sending the request to Stripe. Please try again.', 'setup/checkout');
        }

        $this->load->model(['price_plan_model', 'payment_model']);
        $this->load->config('stripe');

        $price_plan = $this->price_plan_model->fields('name, price')->get($this->company['price_plan_id']);

        \Stripe\Stripe::setApiKey($this->config->item('apiKey'));

        try
        {
            // TODO: Look into Stripe subscriptions
            $customer = \Stripe\Customer::create(array(
                'email' => $this->user->email,
                'card'  => $token
            ));

            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $price_plan['price'] * 100, // Stripe charges in cents so multiply by 100 to convert to euros
                'currency' => 'inr'
            ));

            if ($charge->paid == TRUE)
            {
                $this->company_model->update(['setup_step' => 3, 'active' => 1], $this->company['id']);

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

                if ($this->config->item('send_emails') == TRUE)
                {
                    // Send the admins an email with payment details
                    $email_data = [
                        'company_name'          => $this->company['name'],
                        'stripe_charge_id'      => $charge->id,
                        'stripe_customer_id'    => $customer->id,
                        'amount'                => $charge->amount / 100,
                        'description'           => $price_plan['name'],
                    ];

                    // TODO: Replace with code to get all admins from the database
                    $this->email_lib->send_email(['admin@mark3.in', 'amit@mark3.in', 'amitmalik750@gmail.com'], 'Stripe Payment Successfully Charged on Trucrm', 'setup/emails/payment_details', $email_data);
                }

                $this->update_setup_step(3);

                $this->flasher->set_success(lang('setup_payment_successful'), 'setup/confirmation');
            }
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->flasher->set_danger(lang('setup_payment_network_error'), NULL, TRUE);
        } catch (\Stripe\Error\InvalidRequest $e) {
            // debug mode time!
            $e_json = $e->getJsonBody();
            $error = $e_json['error'];
            log_message('error', 'There was an invalid request when trying to charge to Stripe. The error returned from Stripe was
                : ' . $error['message']);
            $this->load->library('email');
            $this->email->from($this->config->item('website_email'), $this->config->item('website_title'));
            // TODO: Get all admin emails for to field
            $this->email->to($this->config->item('dev_email'));
            $this->email->subject('Stripe Charged Failed');
            $this->email->message('A Stripe charge failed. See log files for more info.');
            $this->email->send();
            $this->flasher->set_danger(lang('setup_payment_we_screwed_up'), NULL, TRUE);
        } catch (\Stripe\Error\Api $e) {
            $this->flasher->set_danger(lang('setup_payment_api_error'), NULL, TRUE);
        } catch (\Stripe\Error\Card $e) {
            $e_json = $e->getJsonBody();
            $error = $e_json['error'];
            $this->flasher->set_danger($error['message'], NULL, TRUE);
        }
        redirect('setup/checkout');
    }

    // TODO: Provide a reference number for the client?
    public function confirmation()
    {
        $this->form_validation->set_rules('setup_step', 'setup step', 'trim');

        if ($this->form_validation->run() == TRUE)
        {
            $this->update_setup_step(4);
            redirect('setup/company');
        }
        else
        {
            $this->template->title(lang('setup_confirmation_heading'))
                           ->build('setup/confirmation', $this->data);
        }
    }

    public function company()
    {
        $this->form_validation->set_rules('setup_step', 'setup step', 'trim');

        if ($this->form_validation->run() == TRUE)
        {
            $this->update_setup_step(5);
            redirect('setup/import');
        }
        else
        {
            $this->template->title(lang('setup_heading'))
                           ->build('setup/company', $this->data);
        }
    }

    // TODO: Check if department already has an owner. (V2)
    // TODO: Look into improving speed of import (search for MySQL Local Load or read about CI's transactions) (V2)
    // TODO: https://github.com/parsecsv/parsecsv-for-php
    public function import()
    {
        $this->load->helper('form');

        // die(var_dump($_FILES));

        if ( ! empty($_FILES) )
        {
            $config['upload_path'] = $this->company_lib->get_uploads_folder($this->user->company_id);
            $config['allowed_types'] = 'csv'; // TODO: If Excel option is provided in the future add 'xls' and 'xlsx' here.
            $config['max_size'] = $this->config->item('max_file_size');
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file') )
            {
                $this->flasher->set_danger($this->upload->display_errors('', ''), 'setup/import', TRUE);
            }
            else
            {
                $data = $this->upload->data();

                $this->load->library('csvreader');
                $this->load->helper('string');

                // determine what to do depending on the mime type of the file
                if ($data['file_ext'] == '.csv')
                {
                    $fields = $this->csvreader->parse_file($data['full_path']);

                    if ( ! empty($fields) )
                    {
                        // $first_names = $last_names = $emails = $departments = []; $i = 0;

                        foreach ($fields as $field)
                        {
                            // $emails[$i] = $field['EmailAddress'];

                            // $i++;

                            $email = $field['EmailAddress'];
                            $first_name = $field['FirstName'];
                            $last_name = $field['LastName'];
                            $department_name = $field['Department'];
                            $role = $field['Role'] + 2;

                            // make sure they cannot add a user with a role of 1 (admin) or 2 (safety manager)
                            if ($role >= 3)
                            {
                                if ( ! $this->ion_auth->email_check($email) )
                                {
                                    $salt = $this->config->item('store_salt', 'ion_auth') ? $this->ion_auth->salt() : FALSE;
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

                                    $user_id = $this->user_model->insert($user_data); // insert new user into users table

                                    unset($user_data);

                                    $this->ion_auth_model->add_to_group($role, $user_id); // add user to users_groups table

                                    // send welcome email to user
                                    $email_data = ['email' => $email, 'password' => $password];
                                    $this->email_lib->send_email($email, lang('setup_welcome_email_subject'), 'setup/emails/welcome', $email_data);

                                    // once user is inserted, create their profile
                                    $profile_data = [
                                        'user_id'           => $user_id,
                                        'first_name'        => $first_name,
                                        'last_name'         => $last_name,
                                    ];
                                    $this->profile_model->insert($profile_data);

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
                                        // a department already exists, so set the users department_id to the existing department
                                        $this->user_model->update(['department_id' => $department['id']], $user_id);
                                    }

                                    $this->flasher->set_success(lang('setup_import_successful'), NULL, TRUE);
                                }
                                else
                                {
                                    $this->flasher->set_danger(sprintf(lang('setup_import_failed_email'), $email), NULL, TRUE);
                                }
                            }
                            else
                            {
                                $this->flasher->set_danger(lang('setup_import_failed_role'), NULL, TRUE);
                            }
                        } 
                    }
                    else
                    {
                        $this->flasher->set_danger(lang('setup_import_failed_file_is_empty'), 'setup/import', TRUE);
                    }

                    unlink($data['full_path']); // delete the uploaded csv file

                    $this->update_setup_step(6);

                    redirect('setup/finish');
                }
            }
        }
        else
        {
            $step = $this->input->post('step');
            if ($step)
            {
                $this->update_setup_step(6);
                redirect('setup/finish', 'refresh');
            }
            else
            {
                $this->template->title(lang('setup_import_heading'))
                               ->set_js('bootstrap-filestyle.min')
                               ->set_partial('custom_js', 'setup/partials/import_js')
                               ->build('setup/import', $this->data);
            }
        }
    }

    public function finish()
    {
        $this->form_validation->set_rules('setup_step', 'setup step', 'trim');

        if ($this->form_validation->run() == TRUE)
        {
            $this->update_setup_step(7);
            redirect('dashboard');
        }
        else
        {
            $this->template->title(lang('setup_finish_heading'))
                           ->build('setup/finish', $this->data);
        }
    }

    public function update_setup_step($step)
    {
        $this->company_model->update(['setup_step' => $step], $this->company['id']);
    }

}

/* End of file Setup.php */
/* Location: ./application/controllers/Setup.php */