<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Companies extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/companies');
        $this->breadcrumbs->push('Admin', 'admin');
        $this->breadcrumbs->push('Companies', 'admin/companies');
    }

    public function index()
    {
        $this->template->title(lang('companies_heading_index'))
                       ->set_css('datatables.bootstrap.min')
                       ->set_js(['datatables.jquery.min', 'datatables.bootstrap.min', 'bootbox-4.4.0.min'])
                       ->set_partial('custom_js', 'admin/companies/datatables_js', ['url' => site_url('admin/companies/datatables')])
                       ->build('admin/companies/index', $this->data);
    }

    public function datatables()
    {
        $this->load->library("datatables");
        $this->datatables->select("companies.id, companies.name, price_plans.name as price_plan_name, companies.active, companies.setup_step");
        $this->datatables->from("companies");
        $this->datatables->join("price_plans", "companies.price_plan_id = price_plans.id", "left");
        $this->datatables->edit_column('id', '<a href="companies/view/$1" title="View Company">$1</a>', 'id');
        $this->datatables->add_column('actions', anchor('admin/companies/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>', ['title' => 'Edit Company']), 'id');
        echo $this->datatables->generate();
    }

    public function view()
    {
        $id = $this->uri->segment(4);

        if ( ! $id )
        {
            $this->flasher->set_warning_extra(lang('companies_invalid_id'), 'admin/companies');
        }
        else
        {
            $this->load->helper('form');
            $this->load->model('price_plan_model');

            $company = $this->company_model
                            ->with_price_plan('fields:name')
                            ->with_users('fields:*count*')
                            ->with_departments('fields:*count*')
                            ->with_payments('order_inside:renewal_date desc')
                            ->get($id);

            $price_plan = $this->price_plan_model->fields('space_allotted, space_unit')->get($company['price_plan_id']);

            if ($price_plan)
            {
                $this->data['percent_used'] = $this->company_lib->percentage_used($company['id']);
            }

            $this->breadcrumbs->push($company['name'], 'admin/companies/view');

            $this->template->title(lang('companies_heading_view'))
                           ->set_css('bootstrap-toggle.min')
                           ->set_js(['bootstrap-toggle.min', 'bootbox-4.4.0.min'])
                           ->set_partial('custom_js', 'admin/companies/view_js')
                           ->set('company', $company)
                           ->set('company_folder', $this->company_lib->get_uploads_folder($id))
                           ->build('admin/companies/view', $this->data);
        }
    }

    protected function get_dir_size($directory)
    {
        $size = 0;

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file)
        {
            $size += $file->getSize();
        }
        return $size;
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->load->helper(['form', 'string']);

        $this->form_validation->set_rules('name', 'name', 'trim|required|callback_name_check');
        $this->form_validation->set_rules('email', 'email', 'trim|required|callback_email_check');

        if ($this->form_validation->run() == TRUE)
        {
            $name = $this->input->post('name', TRUE);
            $email = $this->input->post('email', TRUE);
            $price_plan_id = $this->input->post('price_plan_id', TRUE);
            $active = $this->input->post('active', TRUE);
            $setup = $this->input->post('setup', TRUE);
			$fld_name = str_replace(" ","",str_replace(" ","",strtolower($name)));
            $random_string = $fld_name.'_'.time();//random_string('md5');

            // create a new folder on the server for any uploads this company does.
            $uploads_folder = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $random_string;

            if ( ! file_exists($uploads_folder) ) // if the folder does not already exist
            {
                $folder_created = mkdir($uploads_folder, 0755, TRUE); // create it

                if ($folder_created === FALSE)
                {
                    $this->flasher->set_warning_extra("There was a problem creating the uploads folder ({$random_string}) for the company. Try again.", 'admin/companies/add', TRUE);
                }
                else
                {
                    $file_to_copy = APPPATH . 'index.html';

                    // copy index.html file to uploads folder to deny direct directory access.
                    copy($file_to_copy, $uploads_folder . DIRECTORY_SEPARATOR . 'index.html');

                    // create an avatars folder for profile avatars
                    $avatars_folder = mkdir($uploads_folder . DIRECTORY_SEPARATOR . 'avatars', 0755, TRUE);

                    // copy index.html file to avatars folder to deny direct directory access.
                    copy($file_to_copy, $uploads_folder . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . 'index.html');

                    $company_data = [
                        'name'              => $name,
                        'price_plan_id'     => $price_plan_id,
                        'active'            => $active,
                        'setup_step'        => $setup,
                        'uploads_folder'    => $random_string,
                    ];

                    $company_id = $this->company_model->insert($company_data);
					// Create table for data V20180129
					if(isset($company_id) && $company_id>0){
						$this->__create_company_tables($company_id);
					}
                    $salt = $this->config->item('store_salt', 'ion_auth') ? $this->ion_auth->salt() : FALSE;

                    $password = random_string('alnum', 8);

                    $hashed_password = $this->ion_auth->hash_password($password, $salt);

                    $user_data = [
                        'company_id'        => $company_id,
                        'is_company_admin' => 1,
                        'is_dep_manager'    => 0,
                        'password'          => $hashed_password,
                        'email'             => $email,
                        'active'            => $active,
                    ];

                    $user_id = $this->user_model->insert($user_data);

                    $profile_data = [
                        'user_id'   => $user_id,
                        'first_name' => 'Company',
                        'last_name' => 'Admin',
                    ];

                    $this->profile_model->insert($profile_data);

                    $pivot_data = [
                        'user_id'   => $user_id,
                        'group_id'  => 2, // 2 refers to the role/group
                    ];

                    $this->db->insert('users_groups', $pivot_data);

                    $this->load->model('price_plan_model', 'price_plans');

                    $price_plan = $this->price_plans->fields('name, price')->get($price_plan_id);

                    $today = new DateTime('today');

                    $renewal_date = $today->add(new DateInterval('P1Y'))->format('Y-m-d');

                    $payment_data = [
                        'company_id'    => $company_id,
                        'user_id'       => $user_id,
                        'amount'        => $price_plan['price'],
                        'description'   => $price_plan['name'] . ' [' . $name . ']',
                        'renewal_date'  => $renewal_date
                    ];

                    $this->load->model('payment_model', 'payments');

                    $payment_id = $this->payments->insert($payment_data);

                    if ($company_id === FALSE && $user_id === FALSE)
                    {
                        $this->flasher->set_warning_extra(lang('companies_insert_failed'), 'admin/companies/add', TRUE);
                    }
                    else
                    {
                        $email_data = [
                            'email'     => $email,
                            'password'  => $password,
                        ];

                        $this->email_lib->send_email($email, 'Welcome to Trucrm by Mark3!', 'admin/companies/email/welcome', $email_data);

                        $this->flasher->set_success(lang('companies_insert_successful'), 'admin/companies', TRUE);
                    }
                }
            }
        }
        else
        {
            $this->load->model('price_plan_model');

            $this->data['price_plans'] = $this->price_plan_model->as_dropdown('name')->get_all();

            $this->data['name'] = array(
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'placeholder' => lang('companies_name_placeholder'),
                'value' => $this->form_validation->set_value('name'),
            );

            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'placeholder' => lang('companies_email_placeholder'),
                'value' => $this->form_validation->set_value('email'),
            );

            $this->breadcrumbs->push('Add', 'admin/companies/add');

            $this->template->title(lang('companies_heading_add'))
                           ->set_css(['formvalidation.min', 'bootstrap-checkbox-radio.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'admin/companies/add_js')
                           ->build('admin/companies/add', $this->data);
        }
    }

    public function edit()
    {
        $id = $this->uri->segment(4);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('companies_invalid_id'), 'admin/companies', TRUE);
        }
        else
        {
            $company = $this->company_model->get($id);

            if ( ! $company )
            {
                $this->flasher->set_info(lang('companies_not_found'), 'admin/companies');
            }

            $this->load->model('price_plan_model');
            $this->load->library('form_validation');
            $this->load->helper('form');

            $this->form_validation->set_rules('name', 'name', 'trim|required');
            $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

            if ($this->form_validation->run() == TRUE)
            {
                $name = $this->input->post('name', TRUE);
                $price_plan_id = $this->input->post('price_plan_id', TRUE);
                $active = $this->input->post('active', TRUE);
                $setup = $this->input->post('setup', TRUE);
				
				$company_data = [
                    'name'          => $name,
                    'price_plan_id' => $price_plan_id,
                    'active'        => $active,
                    'setup_step'    => $setup,
                ];
				
                $company_update = $this->company_model->update($company_data, $id);

                if ($company_update == FALSE)
                {
                    $this->flasher->set_danger(lang('companies_update_failed'), 'admin/companies/edit/'.$id, TRUE);
                }
                else
                {
                    // Email safety manager if price plan is changed
                    if ($company['price_plan_id'] != $price_plan_id && $this->config->item('send_emails') == TRUE)
                    {
                        // get name of old price plan
                        $old_price_plan = $this->price_plan_model->fields('name')->get($company['price_plan_id']);

                        // get name of new price plan
                        $new_price_plan = $this->price_plan_model->fields('name')->get($price_plan_id);

                        // get email address of safety manager
                        $company_admin = $this->user_model->fields('email')->where(['company_id' => $id, 'is_company_admin' => 1])->get();

                        $email_data = [
                            'old_price_plan'    => $old_price_plan['name'],
                            'new_price_plan'    => $new_price_plan['name'],
                        ];

                        $this->email_lib->send_email($company_admin['email'], 'Company Price Plan Changed on Trucrm', 'admin/companies/email/price_plan_changed', $email_data);
                    }

                    $this->flasher->set_success(lang('companies_update_successful'), 'admin/companies', TRUE);
                }
            }
            else
            {
                $this->data['price_plans'] = $this->price_plan_model->as_dropdown('name')->get_all();

                $this->data['name'] = array(
                    'name'  => 'name',
                    'id'    => 'name',
                    'type'  => 'text',
                    'placeholder' => lang('companies_name_placeholder'),
                    'value' => $this->form_validation->set_value('name', $company['name']),
                );

                $this->data['company'] = $company;

                $this->breadcrumbs->push('Edit', 'admin/companies/edit');
                $this->template->title(lang('companies_heading_edit'))
                               ->set_css(['formvalidation.min', 'bootstrap-checkbox-radio.min'])
                               ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                               ->set_partial('custom_js', 'admin/companies/add_js')
                               ->build('admin/companies/edit', $this->data);
            }
        }
    }

    public function confirm_delete()
    {
        $id = $this->uri->segment(4);

        $this->load->library('form_validation');
        $this->load->helper(['form', 'string']);

        $this->form_validation->set_rules('confirm', 'confirmation', 'trim|required');

        if ($this->form_validation->run() == TRUE)
        {
            $confirm = $this->input->post('confirm', TRUE);

            if ($confirm === "DELETE")
            {
                if ($this->company_model->delete($id))
                {
                    $company_folder = $this->company_lib->get_uploads_folder($id);

                    $this->load->helper('file');

                    // delete all uploaded files for the company
                    $files_deleted = delete_files($company_folder, TRUE);

                    if ($files_deleted)
                    {
                        $this->flasher->set_success(lang('companies_delete_successful'), 'admin/companies', TRUE);
                    }
                    else
                    {
                        $this->flasher->set_warning_extra(lang('companies_delete_files_failed'), "admin/companies", TRUE);
                    }
                }
            }
            else
            {
                $this->flasher->set_warning_extra(lang('companies_delete_incorrect'), "admin/companies/delete/{$id}/confirmation", TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push('Confirm Delete', "admin/companies/delete/{$id}/confirmation");

            $this->template->title(lang('companies_heading_delete'))
                           ->set_css('formvalidation.min')
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set('id', $id)
                           ->set('company', $this->company_model->fields('name')->get($id))
                           ->build('admin/companies/confirm', $this->data);
        }
    }

    public function set_active_status()
    {
        $company_id = $this->input->post('company_id', TRUE);

        $active = $this->input->post('active', TRUE);

        if ($this->company_model->update(['active' => $active], $company_id))
        {
            // set all users for the company to same active status as the company
            $query = $this->user_model->where(['company_id' => $company_id])->update(['active' => $active]);

            echo 'TRUE';
        }
        else
        {
            echo 'FALSE';
        }
    }

    public function set_setup_status()
    {
        $company_id = $this->input->post('company_id', TRUE);

        $setup_step = $this->input->post('setup', TRUE);

        if ($this->company_model->update(['setup_step' => $setup_step], $company_id))
        {
            echo 'TRUE';
        }
        else
        {
            echo 'FALSE';
        }
    }

    public function name_check($name)
    {
        $query = $this->company_model->fields('name')->where('name', $name)->get();

        if ($query)
        {
            $this->form_validation->set_message('name_check', $this->lang->line('companies_insert_failed_duplicate_entry'));

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
            $this->form_validation->set_message('email_check', $this->lang->line('companies_insert_failed_email_exists'));

            return FALSE;
        }

        return TRUE;

        unset($query);
    }
	/**
	**	Create Tables for new Companies Created V20180129
	** Heavy data import using excel so created the company id wise tables
	*/
	private function __create_company_tables($company_id){
		$this->__create_table_datamaster($company_id);
		$this->__create_table_datamaster_contact($company_id);
	}
	
	private function __create_table_datamaster($company_id){
		try{
			$this->load->dbforge();
			//$this->dbforge->drop_table('ipn_log');
			$table_data_master = 'data_master_'.$company_id;
			$fields_data_master=array();
			$fields_data_master = array(
							'id' => 		array(
												 'type' => 'INT',
												 'constraint' => 11,
												 'unsigned' => TRUE,
												 'auto_increment' => TRUE
												),
							'companies_id' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'default' =>  '0',
													 'null' => TRUE,
													 'COMMENT' => 'companies table id'
											  ),
							'users_id' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'default' =>  '0',
													 'null' => TRUE,
													 'COMMENT' => 'login users table id'
											  ),				  
							'unique_id' => array(
												 'type' =>'VARCHAR',
												 'constraint' => '255',
												 'default' => 'NULL',
												 'null' => TRUE,
												 'COMMENT' => 'special id by given any person'
											  ),
							'head_title' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '255',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'address' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '255',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'landmark' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '255',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'districts_id' => array(
													 'type' => 'INT',
													 'constraint' => '10',
													 'null' => TRUE,
													 'COMMENT' => 'districts table id'
											  ),
							'states_id' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'null' => TRUE,
													 'COMMENT' => 'states table id'
											  ),
							'countries_id' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'null' => TRUE,
													 'COMMENT' => 'countries table id'
											  ),
							'industrytype_id' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'null' => TRUE,
													 'COMMENT' => 'industrytype table id'
											  ),
							'sub_industrytype_id' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'null' => TRUE,
													 'COMMENT' => 'industrytype table id'
											  ),
							'datasource_id' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'null' => TRUE,
													 'COMMENT' => 'datasource table id'
											  ),
							'pincode' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'null' => TRUE,
													 'COMMENT' => 'postal code zipcode'
											  ),
							'no_of_employee' => array(
													 'type' => 'INT',
													 'constraint' => '5',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'no_of_employee' => array(
													 'type' => 'INT',
													 'constraint' => '5',
													 'null' => TRUE,
													 'COMMENT' => 'Number of employee'
											  ),
							'no_of_pc' => array(
													 'type' => 'INT',
													 'constraint' => '5',
													 'null' => TRUE,
													 'COMMENT' => 'Number of computer'
											  ),
							'website' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '100',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => 'website link'
											  ),
							'updated_at' => array(
													'type' =>'timestamp',
													'default' => '0000-00-00 00:00:00',
													'null' => TRUE,
													'COMMENT' => ''
												),
							'created_at' => array(
													'type' =>'timestamp',
													'default' => '0000-00-00 00:00:00',
													'null' => TRUE,
													'COMMENT' => ''
												),
							
							
							'created_by' => array(
													 'type' => 'INT',
													 'constraint' => '5',
													 'null' => TRUE,
													 'COMMENT' => 'login users table id'
											  ),
							'updated_by' => array(
													 'type' => 'INT',
													 'constraint' => '5',
													 'null' => TRUE,
													 'COMMENT' => 'login users table id'
											  ),
							'status' => array(
													 'type' => 'tinyint',
													 'constraint' => '1',
													 'default' =>  '1',
													 'COMMENT' => '1=Active,0=Inactive'
											  )	  
						
					);
			$this->dbforge->add_field($fields_data_master);
			$this->dbforge->add_key('id', TRUE);
			$attributes = array('ENGINE' => 'MyIsam');
			$this->dbforge->create_table($table_data_master, TRUE, $attributes);
		
		}catch(Exception $e){
			$e->getMessage();
		}
	}
	private function __create_table_datamaster_contact($company_id){
		try{
			$this->load->dbforge();
			$table_datamaster_contact = 'data_master_contact_'.$company_id;
			$fields_datamaster_contact=array();
			$fields_datamaster_contact = array(
							'id' => 		array(
												 'type' => 'INT',
												 'constraint' => 11,
												 'unsigned' => TRUE,
												 'auto_increment' => TRUE
												),
							'companies_id' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'default' =>  '0',
													 'null' => TRUE,
													 'COMMENT' => 'companies table id'
											  ),
							'data_master_id' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'default' =>  '0',
													 'null' => TRUE,
													 'COMMENT' => 'data_master table id'
											  ),
							'salutation' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '50',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => 'Mr. Mrs. Dr. etc'
											  ),
							'first_name' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '255',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'last_name' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '255',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'job_title' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '255',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'job_function' => array(
													 'type' => 'TINYINT',
													 'constraint' => '1',
													 'null' => TRUE,
													 'COMMENT' => '1=Decision Maker, 2=Recommender, 3=Influencer'
											  ),
							'email_office' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '100',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'email_personal' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '100',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'phone_country_code' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '25',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => 'Country Code'
											  ),
							'phone_std_code' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '25',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => 'STD Code'
											  ),
							'phone_office' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '100',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'phone_personal' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '100',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'department' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '150',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'emp_no' => array(
													 'type' =>'VARCHAR',
													 'constraint' => '150',
													 'default' => 'NULL',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'callingstatus_id' => array(
													 'type' => 'INT',
													 'constraint' => '11',
													 'default' =>  '0',
													 'null' => TRUE,
													 'COMMENT' => 'callingstatus table id'
											  ),
							'updated_at' => array(
													 'type' =>'timestamp',
													 'default' => '0000-00-00 00:00:00',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'created_at' => array(
													 'type' =>'timestamp',
													 'default' => '0000-00-00 00:00:00',
													 'null' => TRUE,
													 'COMMENT' => ''
											  ),
							'created_by' => array(
													 'type' => 'INT',
													 'constraint' => '5',
													 'null' => TRUE,
													 'COMMENT' => 'login users table id'
											  ),
							'updated_by' => array(
													 'type' => 'INT',
													 'constraint' => '5',
													 'null' => TRUE,
													 'COMMENT' => 'login users table id'
											  ),
							'status' => array(
													 'type' => 'tinyint',
													 'constraint' => '1',
													 'default' =>  '1',
													 'null' => TRUE,
													 'COMMENT' => '1=Active,0=Inactive'
											  )			  
						
					);
			$this->dbforge->add_field($fields_datamaster_contact);
			$this->dbforge->add_key('id', TRUE);
			$attributes_dc = array('ENGINE' => 'MyIsam');
			$this->dbforge->create_table($table_datamaster_contact, TRUE, $attributes_dc);

		}catch(Exception $e){
			$e->getMessage();
		}
	}
}

/* End of file Companies.php */
/* Location: ./application/controllers/Companies.php */