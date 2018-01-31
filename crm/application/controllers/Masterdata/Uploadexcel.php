<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadexcel extends Account_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['data_master_model','data_master_contact_model']);
        $this->load->language('masterdata');
		if ( ! in_array($this->user_group['id'], [2,3]) ){
            $this->flasher->set_warning_extra(lang('masterdata_access_denied'), 'dashboard', TRUE);
        }
		$this->load->library('excel');
		// Load form helper library
		$this->load->helper('form');
		// Form Security like xss_clean|valid_email etc
		$this->load->helper('security');
		// Load form validation library
		$this->load->library('form_validation');
		// For UPload the file
		$this->load->helper('MY_file');
		
        $this->breadcrumbs->push('Home', 'dashboard');
		$this->breadcrumbs->push('Master Data', 'masterdata/masterdata');
        $this->breadcrumbs->push('Uploads', 'masterdata/uploadexcel');
    }
	
    public function index()
    {
		//echo json_encode($response);die;
		if ( ! empty($_FILES) ){
			$this->__upload_excel_file();
			//print'<pre>'; print_r($_POST);print'</pre>';
			//print'<pre>'; print_r($_FILES);print'</pre>';
			//DIE;
            
        }else{
            //$this->breadcrumbs->push(lang('company_import_heading'), 'company/import');
            $this->template->title(lang('masterdata_uploads_heading_index'))
                           ->set_js('bootstrap-filestyle.min')
                           ->set_partial('custom_js', 'masterdata/uploadexcel/import_js')
                           ->build('masterdata/uploadexcel/import', $this->data);
        }
    }
	
	private function __upload_excel_file(){
		
		//*******Start File Upload Using Helper V20180123*******//
		//print '<pre>';print_r($_FILES);die;
		//
		//create_directory($directory = '',$directory_name='')
			//FILE_PATH	
			//$path = create_directory($directory='',$directory_name='');
			$upload_path = $this->company_lib->get_uploads_folder($this->company['id'], FALSE);// Upload file in without avatar folder
			$xls_response = array();			
			$xls_config=array();
			$xls_config['field']='excel_file';
			$xls_config['cur_time'] = time();
			$xls_config['directory'] = $upload_path;
			$xls_config['file_type'] = 'excel';
			$xls_config['max_size'] = $this->config->item('max_file_size');			
			$xls_response = uploadfile_image($xls_config);
			if($xls_response['error']==''){
				 $file_name = trim($xls_response['file_name']);
				 $file_type_array = explode(".",$file_name);
				 if(strtolower($file_type_array[1])=='csv'){
					 // TODO: Read Only CSV Files
					 //print'<pre>';print_r($this);print'</pre>';die;
					 $this->__read_upload_csv_file($xls_response);
				 }elseif(strtolower($file_type_array[1])=='xls' || strtolower($file_type_array[1])=='xlsx'){
					 //TODO:  Read Upload Excel File
					$this->__read_upload_excel_file($xls_response);
				 }
			}else{
				if(trim($xls_response['error'])=='Invalid file type'){
					$this->flasher->set_danger(lang('masterdata_import_invalid_file_type'), 'masterdata/uploadexcel', TRUE);
				}elseif(trim($xls_response['error'])=='Invalid file size'){
					$this->flasher->set_danger(lang('company_import_failed_file_is_empty'), 'masterdata/uploadexcel', TRUE);
				}else{
					$this->flasher->set_warning_extra($xls_response['error'], 'masterdata/uploadexcel', TRUE);
				//$response =["error" => $xls_response['error']];
				}
			}
		//*******End File Upload Using Helper V20180123*******//
	}
	// Read Upload Excel File
	private function __read_upload_excel_file($files_data){
		
			
	}
		/**
		*** TODO: Read CSV File
		*/
		
	//private function __read_upload_csv_file($data=''){
	public function upload_csv_file($data=''){
		$data['full_path']='E:/xampp/htdocs/trucrm_ci/uploads/company2_1516709167/1517306055_template_import_master_data.csv';
		
		$this->load->library('csvreader');
		$fields = $this->csvreader->parse_file($data['full_path']);
		//print '<pre>';print_r($fields);print '</pre>';
		// die(var_dump($fields));

		if ( ! empty($fields) )
		{
			//print '<pre>';print_r($this->user);die;
			$user_id = $this->user->user_id;
			$companies_id = $this->user->company_id;
			
			foreach ($fields as $field)
			{
				//print '<pre>';print_r($field);die;
				$head_title = trim($field['Account Name']);
				
				
				
				
				$unique_id = trim($field['Unique Data ID']);
				$datasource  = trim($field['Data Source']);
				$address = trim($field['Full Addresss']);
				$pincode = trim($field['Postal Code']);
				$website = trim($field['Website']);
				$no_of_employee = trim($field['No of employees']);
				$no_of_pc = trim($field['Number Of Desktops/ Pcs']);
				//get the countries states city ids
				$array_csc = $this->__get_country_city_state_id($field);
				//print_r($array_csc);die;
				$districts_id = trim($array_csc['districts']);
				$states_id = trim($array_csc['states']);
				$countries_id = trim($array_csc['countries']);
				// Get Industry Type id
				$array_industrytype = $this->__get_industrytype($field);
				$industrytype_id = trim($array_industrytype['industrytype']);
				$sub_industrytype_id = trim($array_industrytype['sub_industrytype']);
				
				
				// Data for personal details
				$phone_country_code = trim($field['Phone Country Code']);
				$phone_std_code = trim($field['Phone Area Code']);
				$phone_office = trim($field['Phone No  Landline']);
				$phone_personal = trim($field['Mobile No']);
				$email_office = trim($field['Email Address']);
				$salutation = trim($field['Salute']);
				$first_name = trim($field['First name']);
				$last_name = trim($field['Last Name']);
				$job_title = trim($field['Key Designation / Role']);
				
				
				
				$role = $field['Role'] + 2;

	
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

		//unlink($data['full_path']); // delete the uploaded csv file
		//redirect('company');
		
		
	}
	
	private function __get_country_city_state_id($field){
		//print '<pre>';print_r($field);print '</pre>';die;
		$districts = trim($field['City']);
		$states = trim($field['State']);
		$countries = trim($field['Country']);
		$pincode = trim($field['Postal Code']);
		//$this->load->model(['countries_model','states_model','districts_model']);
		$countries_id = $this->__get_countries_id($countries);
		if($countries_id>0){
			$states_id= $this->__get_states_id($states,$countries_id);
			if($states_id>0){
				$districts_id= $this->__get_districts_id($districts,$states_id,$countries_id,$pincode='');
			}
			
		}
		$array_csc=array();
		$array_csc['districts']= $districts_id;
		$array_csc['states'] = $states_id;
		$array_csc['countries']=$countries_id;
		return $array_csc;
				
		
	}
	private function __get_countries_id($countries){
		$countries_id='';
		$user_id = $this->user->user_id;
		$companies_id = $this->user->company_id;
		$this->load->model(['countries_model']);
		$arra_countries = $this->countries_model->fields('id')
						->where(['LOWER(name)' => strtolower($countries), 'companies_id' => $this->user->company_id])
						->get();
		if(!$arra_countries){
			$countries_data = array();
			$countries_data['companies_id'] = $companies_id;
			$countries_data['name'] = ucfirst(strtolower($countries));
			$countries_id = $this->countries_model->insert($countries_data);
			//echo $this->db->last_query();die;
		}else{
			$countries_id =$arra_countries['id'];
		}
		//echo $countries_id; die;
		return $countries_id;
	}
	private function __get_states_id($states,$country_id){
		$states_id='';
		$user_id = $this->user->user_id;
		$companies_id = $this->user->company_id;
		$this->load->model(['states_model']);
		$arra_states = $this->states_model->fields('id')
						->where(['LOWER(name)' => strtolower($states), 'companies_id' => $this->user->company_id, 'country_id' => $country_id])
						->get();
		if(!$arra_states){
			$states_data = [];
			$states_data['country_id'] = $country_id;
			$states_data['companies_id'] = $companies_id;
			$states_data['name'] = ucfirst(strtolower($states));
			$states_data['created_by_id'] = $user_id;
			$states_id = $this->states_model->insert($states_data);
			//echo $this->db->last_query();die;
		}else{
			$states_id =$arra_states['id'];
		}
		return $states_id;
	}
	private function __get_districts_id($districts,$state_id,$country_id,$pincode=''){
		$districts_id='';
		$user_id = $this->user->user_id;
		$companies_id = $this->user->company_id;
		$this->load->model(['districts_model']);
		$arra_districts = $this->districts_model->fields('id')
						->where(['LOWER(name)' => strtolower($districts), 'companies_id' => $this->user->company_id, 'country_id' => $country_id, 'state_id' => $state_id])
						->get();
		if(!$arra_districts){
			$districts_data = [];
			$districts_data['companies_id'] = $companies_id;
			$districts_data['name'] = ucfirst(strtolower($districts));
			$districts_data['country_id'] = $country_id;
			$districts_data['pincode'] = $pincode;
			$districts_data['state_id'] = $state_id;
			$districts_data['updated'] = date('Y-m-d H:i:s');
			$districts_data['created_by_id'] = $user_id;
			$districts_id = $this->districts_model->insert($districts_data);
			//echo $this->db->last_query();die;
		}else{
			$districts_id =$arra_districts['id'];
		}
		return $districts_id;
	}
	// Get the industry type
	private function __get_industrytype($field){
		$industrytype = trim($field['Industry']);
		
		$sub_industrytype_id ='';
		$industrytype_id = $this->__get_industrytype_id($industrytype,'0');
		if($industrytype_id>0){
			$sub_industrytype = trim($field['Sub Industry']);
			$sub_industrytype_id = $this->__get_industrytype_id($sub_industrytype,$industrytype_id);
		}	
		$array_industrytype=array();
		$array_industrytype['industrytype'] = $industrytype_id;
		$array_industrytype['sub_industrytype'] = $sub_industrytype_id;
		//print_r($array_industrytype);die;
		return $array_industrytype;
	}
	private function __get_industrytype_id($industrytype,$parent_id){
		$user_id = $this->user->user_id;
		$companies_id = $this->user->company_id;
		$this->load->model(['industrytype_model']);
		$arra_industrytype = $this->industrytype_model->fields('id')
						->where(['LOWER(name)' => strtolower($industrytype), 'parent_id' => $parent_id])
						->get();
		if(!$arra_industrytype){
			$industrytype_data = [];
			$industrytype_data['companies_id'] = $companies_id;
			$industrytype_data['parent_id'] = $parent_id;
			$industrytype_data['created_by'] = $user_id;
			$industrytype_data['name'] = ucfirst(strtolower($industrytype));
			$industrytype_id = $this->industrytype_model->insert($industrytype_data);
			//echo $this->db->last_query();die;
		}else{
			$industrytype_id =$arra_industrytype['id'];
		}
		return $industrytype_id;				
		
	}	
	private function __get_datamaster_id($field){
		$industrytype = trim($field['Industry']);
		$user_id = $this->user->user_id;
		$companies_id = $this->user->company_id;
		$this->load->model(['industrytype_model']);
		$arra_industrytype = $this->industrytype_model->fields('id')
						->where(['LOWER(name)' => strtolower($industrytype), 'parent_id' => $parent_id])
						->get();
		if(!$arra_industrytype){
			$industrytype_data = [];
			$industrytype_data['companies_id'] = $companies_id;
			$industrytype_data['parent_id'] = $parent_id;
			$industrytype_data['created_by'] = $user_id;
			$industrytype_data['name'] = ucfirst(strtolower($industrytype));
			$industrytype_id = $this->industrytype_model->insert($industrytype_data);
			//echo $this->db->last_query();die;
		}else{
			$industrytype_id =$arra_industrytype['id'];
		}
		return $industrytype_id;				
		
	}
}

/* End of file Uploadexcel.php */
/* Location: ./application/controllers/masterdata/Uploadexcel.php */