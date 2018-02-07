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
			$xls_config['max_size'] = '100000000000000';//$this->config->item('max_file_size');			
			$xls_response = uploadfile_image($xls_config);
			//print'<pre>';print_r($xls_response);print'</pre>';die;
			if($xls_response['error']==''){
				//print'<pre>';print_r($xls_response);print'</pre>';die;
				 $file_name = trim($xls_response['file_name']);
				 $file_type_array = trim($xls_response['file_ext']);
				 //print '<pre>';print_r($file_type_array);print '</pre>';die;
				 if(strtolower($file_type_array)=='.csv'){
					 // TODO: Read Only CSV Files
					 $this->__read_upload_csv_file($xls_response);
				 }elseif(strtolower($file_type_array)=='.xls' || strtolower($file_type_array)=='.xlsx'){
					 //TODO:  Read Upload Excel File
					$this->__read_upload_excel_file($xls_response);
				 }
			}else{
				//$xls_response['error']==''?lang('masterdata_import_failed_empty_fields')
				if(trim($xls_response['error'])=='Invalid file type'){
					$this->flasher->set_danger(lang('masterdata_import_invalid_file_type'), 'masterdata/uploadexcel', TRUE);
				}elseif(trim($xls_response['error'])=='Invalid file size'){
					$this->flasher->set_danger(lang('masterdata_import_invalid_file_size'), 'masterdata/uploadexcel', TRUE);
				}else{
					$this->flasher->set_warning_extra($xls_response['error'], 'masterdata/uploadexcel', TRUE);
				//$response =["error" => $xls_response['error']];
				}
			}
		//*******End File Upload Using Helper V20180123*******//
	}
	// Read Upload Excel File
	private function __read_upload_excel_file($files_data){
		//print '<pre>';print_r($files_data);print '</pre>';die;
		$file_name = $files_data['file_name'];
		$full_path = $files_data['full_path'];
		$this->load->library('excel');
		$obj_excel =$this->excel;
		
		// Load the File
		$obj_excel->load($full_path);
		// Set the Object of worksheet
		$obj_worksheet=$obj_excel->setActiveSheetIndex(0);
		
		//Get Total Rows
		$totalrows=$obj_worksheet->getHighestRow();   //Count Number of rows avalable in excel  
		//loop from first data untill last data
		if($totalrows>0){
			$fields_arr=array("unique_id" => "Unique Data ID", "callingstatus" => "Calling Status","datasource" => "Data Source", "head_title" => "Account Name", "address" => "Full Addresss", "districts" => "City", "pincode" => "Postal Code", "states" => "State", "countries" => "Country", "phone_country_code" => "Phone Country Code", "phone_std_code" => "Phone Area Code", "phone_office" => "Phone No  Landline", "phone_personal" => "Mobile No", "website" => "Website", "no_of_employee" => "No of employees", "no_of_pc" => "Number Of Desktops/ Pcs", "industrytype" => "Industry", "sub_industrytype" => "Sub Industry", "email_office" => "Email Address", "salutation" => "Salute", "first_name" => "First name", "last_name" => "Last Name", "job_title" => "Key Designation / Role");
			
			for($row=1;$row<=$totalrows;$row++){
				if($row==1){
					$col=0;
					foreach($fields_arr as $field_name){
						$col_field= $obj_worksheet->getCellByColumnAndRow($col,$row)->getValue();
						
						if(trim(strtolower($col_field))!=trim(strtolower($field_name))){
							unlink($data['full_path']);
							$this->flasher->set_danger(lang('masterdata_import_failed_empty_fields'), 'masterdata/uploadexcel', TRUE);
							break;
						}
						$col++;
					}	
					
				}elseif($row>1){
					$col=0;
					foreach($fields_arr as $field_name=>$field){
						$data[$field]= $obj_worksheet->getCellByColumnAndRow($col,$row)->getValue();//Excel Column 1					
						$col++;
					}
					$excel_data_arr[]=$data;
				} 
				
			}
			if(count($excel_data_arr)>0){
				$exdata = $this-> __manage_excel_data($excel_data_arr,$files_data);
			}
			
			//print'<pre>'; print_r($excel_data_arr);print '</pre>';die;
		}	
			
	}
	
	/**
	*** TODO: Read CSV File
	*/		
	private function __read_upload_csv_file($data=''){
	//public function upload_csv_file($data=''){	//$data['full_path']='E:/xampp/htdocs/trucrm_ci/uploads/company2_1516709167/1517395335_template_import_master_data.csv';
		$this->load->library('csvreader');
		$fields = $this->csvreader->parse_file($data['full_path']);
		//print '<pre>';print_r($fields[1]['FirstName']);print '</pre>';die;
		// die(var_dump($fields));
		$exdata = $this-> __manage_excel_data($fields,$data);
	}
	/**
	** TODO: XLS, XLSX CSV File DATA MANAGE
	**/
	private function __manage_excel_data($fields,$data){
		//print '<pre>';print_r($data);print '</pre>';die;
		$user_id = $this->user->user_id;
		$companies_id = $this->user->company_id;
		$current_date = date('Y-m-d H:i:s');
		if ( ! empty($fields)){
		  if($fields[1]['Account Name'] || $fields[0]['Account Name']){
			//print '<pre>';print_r($this->user);die;
			$exdata=array();
			$exdata['insert']['data_master']='';
			$exdata['insert']['data_master_contact']='';
			$exdata['exists']='';
			$exdata['invalid']='';
			$last_id ='';
			foreach ($fields as $field){
				//print '<pre>';print_r($field);die;
				
				$excel_data['head_title'] = trim($field['Account Name']);
				$excel_data['unique_id'] = trim($field['Unique Data ID']);
				$excel_data['datasource']  = trim($field['Data Source']);
				$excel_data['address'] = trim($field['Full Addresss']);
				$excel_data['pincode'] = trim($field['Postal Code']);
				$excel_data['website'] = trim($field['Website']);
				$excel_data['no_of_employee'] = trim($field['No of employees']);
				$excel_data['no_of_pc'] = trim($field['Number Of Desktops/ Pcs']);
				$excel_data['districts'] = trim($field['City']);
				$excel_data['states'] = trim($field['State']);
				$excel_data['countries'] = trim($field['Country']);
				$excel_data['industrytype'] = trim($field['Industry']);
				$excel_data['sub_industrytype'] = trim($field['Sub Industry']);
				
				$excel_data['companies_id'] = trim($companies_id);
				$excel_data['users_id'] = trim($user_id);
				$excel_data['created_by'] = trim($user_id);
				$excel_data['created_at'] = $current_date;
				$excel_data['updated_at'] = $current_date;
				
				// Data for personal details 
				$pexcel_data['callingstatus'] = trim($field['Calling Status']);
				$pexcel_data['phone_country_code'] = trim($field['Phone Country Code']);
				$pexcel_data['phone_std_code'] = trim($field['Phone Area Code']);
				$pexcel_data['phone_office'] = trim($field['Phone No  Landline']);
				$pexcel_data['phone_personal'] = trim($field['Mobile No']);
				$pexcel_data['email_office'] = trim($field['Email Address']);
				$pexcel_data['salutation'] = trim($field['Salute']);
				$pexcel_data['first_name'] = trim($field['First name']);
				$pexcel_data['last_name'] = trim($field['Last Name']);
				$pexcel_data['job_title'] = trim($field['Key Designation / Role']);
				$pexcel_data['companies_id'] = trim($companies_id);
				$pexcel_data['created_by'] = trim($user_id);
				$pexcel_data['created_at'] = $current_date;
				$pexcel_data['updated_at'] = $current_date;
				// get the already exis table id
				$head_title = trim($excel_data['head_title']);
				if($head_title!=''){
					$data_master_id = $this->__get_datamaster($head_title);
					if($data_master_id==''){
						
						$datasource = $excel_data['datasource'];
						$excel_data['datasource_id']=$this->__get_datasource_id($datasource);
						//get the countries states city ids
						
						$arrcsc['districts'] = $excel_data['districts'];
						$arrcsc['states'] = $excel_data['states'];
						$arrcsc['pincode'] = $excel_data['pincode'];
						$arrcsc['countries'] = $excel_data['countries'];					
						$array_csc = $this->__get_country_city_state_id($arrcsc);
						//print_r($array_csc);die;
						$excel_data['districts_id'] = trim($array_csc['districts']);
						$excel_data['states_id'] = trim($array_csc['states']);
						$excel_data['countries_id'] = trim($array_csc['countries']);
						// Get Industry Type id
						$arr_industrytype['industrytype'] = trim($excel_data['industrytype']);
						$arr_industrytype['sub_industrytype'] = trim($excel_data['sub_industrytype']);
						$array_industrytype = $this->__get_industrytype($arr_industrytype);
						$excel_data['industrytype_id'] = trim($array_industrytype['industrytype']);
						$excel_data['sub_industrytype_id'] = trim($array_industrytype['sub_industrytype']);
						
						// Get Calling Status //callingstatus_id
						$callingstatus = trim($pexcel_data['callingstatus']);
						$pexcel_data['callingstatus_id']=$this->__get_callingstatus_id($callingstatus);
						
						
						unset($excel_data['datasource']);
						unset($excel_data['districts']);
						unset($excel_data['states']);
						unset($excel_data['countries']);
						unset($excel_data['industrytype']);
						unset($excel_data['sub_industrytype']);
						unset($pexcel_data['callingstatus']);
						
						$exdata['insert']['data_master'][]=$excel_data;
						$exdata['insert']['data_master_contact'][]=$pexcel_data;
						//print '<pre>';print_r($pexcel_data);die;
						//Save one by one data in tables
						$last_id = $this->data_master_model->save_data_master($excel_data,$pexcel_data);
						
					}else if($data_master_id!=''){
						$exdata['exists'][]=$excel_data;
						
					}else{
						$exdata['invalid'][]=$excel_data;
					}
				}else{
					$exdata['invalid'][]=$excel_data;
					
				}
			}
			
			$this->__save_data_master_batch($exdata,$data);
		  }else{
			unlink($data['full_path']);
			$this->flasher->set_danger(lang('masterdata_import_failed_empty_fields'), 'masterdata/uploadexcel', TRUE);
		  }	
		}else{
			unlink($data['full_path']);
			$this->flasher->set_danger(lang('masterdata_import_failed_file_is_empty'), 'masterdata/uploadexcel', TRUE);
		}
	}
	
	private function __save_data_master_batch($exdata='',$data=''){
		
		unlink($data['full_path']);
		$arr_insert=$exdata['insert'];// For Inserted Records
		$arr_exists=$exdata['exists'];// For Already Exists Records
		$arr_invalid=$exdata['invalid'];// For Invalid Records
		
		// // delete the uploaded csv file
		//redirect('company');
		$array_insert=$arr_insert['data_master'];
		//echo count($array_insert);die;
		if(count($array_insert)>0 && $array_insert!=''){
			$ardata_master=$array_insert;	
			//$array_insert['data_master_contact'];				//$affected_rows=$this->data_master_model->save_data_master_batch($ardata_master);
			$affected_rows = count($array_insert);
			if($affected_rows>0){
				$array_insert=$arr_insert['data_master'];
				$msg = 'You have successfully inserted the '.$affected_rows.' record(s).';
				$this->flasher->set_success($msg, 'masterdata/uploadexcel', true);
			}
		}
		// Data From Already Exists 
		$exis_data='';		
		if(count($arr_exists)>0){
			$exis_data.='<table width="100%" cellpadding="2" cellpadding="2" cellspacing="2" > <tr><td><strong>Unique Id</strong></td><td><strong>Title</strong></td></tr>';

			foreach($arr_exists as $ex_data){
				$exis_data.='<tr><td>'.$ex_data["unique_id"].'</td><td>'.$ex_data["head_title"].'</td></tr>';
			}
	
			$exis_data.='</table>';
			$msg_error =  '<strong>'.count($arr_exists).' record(s) already exists.<br/></strong>'.$exis_data;
			$this->flasher->set_warning_extra($msg_error, 'masterdata/uploadexcel', FALSE);
		}
		// Data From Invalid Channel
		$inv_data='';
		if(count($arr_invalid)>0){
			
			$inv_data.='<table width="100%" cellpadding="2" cellpadding="2" cellspacing="2" > <tr><td><strong>Unique Id</strong></td><td><strong>Title</strong></td></tr>';
			foreach($arr_invalid as $invalid_data){
				
				$inv_data.='<tr><td>'.$invalid_data["unique_id"].'</td><td>'.$invalid_data["head_title"].'</td></tr>';
			}
			$inv_data.='</table>';
			$msg_invalid =  '<br/><strong>'.count($arr_invalid).' record(s) from invalid channel.</strong><br/>'.$inv_data.'';
			$this->flasher->set_warning_extra($msg_invalid, 'masterdata/uploadexcel', FALSE);
		}
		
	}
	private function __get_country_city_state_id($field){
		$districts_id='';
		$states_id='';
		$countries_id='';
		//print '<pre>';print_r($field);print '</pre>';die;
		$districts = trim($field['districts']);
		$states = trim($field['states']);
		$countries = trim($field['countries']);
		$pincode = trim($field['pincode']);
		
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
		if($countries!=''){
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
		}else{
			$countries_id ='';
		}
		//echo $countries_id; die;
		return $countries_id;
	}
	private function __get_states_id($states,$country_id){
		$states_id='';
		$user_id = $this->user->user_id;
		$companies_id = $this->user->company_id;
		if($states!='' && $country_id!=''){
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
		}else{
			$states_id ='';
		}
		return $states_id;
	}
	private function __get_districts_id($districts,$state_id,$country_id,$pincode=''){
		$districts_id='';
		$user_id = $this->user->user_id;
		$companies_id = $this->user->company_id;
		if($districts!='' && $state_id!='' && $country_id!=''){
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
		}else{
			$districts_id='';
		}
		return $districts_id;
	}
	// Get the industry type
	private function __get_industrytype($field){
		$industrytype = trim($field['industrytype']);
		$sub_industrytype = trim($field['sub_industrytype']);
		if($industrytype!=''){
			$sub_industrytype_id ='';
			$industrytype_id = $this->__get_industrytype_id($industrytype,'0');
			if($industrytype_id>0){
				if($sub_industrytype!=''){
					$sub_industrytype_id = $this->__get_industrytype_id($sub_industrytype,$industrytype_id);
				}else{
					$sub_industrytype_id ='';
				}	
			}	
			$array_industrytype=array();
			$array_industrytype['industrytype'] = $industrytype_id;
			$array_industrytype['sub_industrytype'] = $sub_industrytype_id;
			//print_r($array_industrytype);die;
		}else{
			$array_industrytype['industrytype'] ='';
			$array_industrytype['sub_industrytype']='';
		}
		return $array_industrytype;
	}
	private function __get_industrytype_id($industrytype,$parent_id){
		$user_id = $this->user->user_id;
		$companies_id = $this->user->company_id;
		$this->load->model(['industrytype_model']);
		$arra_industrytype = $this->industrytype_model->fields('id')
						->where(['LOWER(name)' => strtolower($industrytype), 'parent_id' => $parent_id,'companies_id' => $this->user->company_id])
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
	// For Data Source
	private function __get_datasource_id($datasource){
		if($datasource!=''){
			$user_id = $this->user->user_id;
			$companies_id = $this->user->company_id;
			$this->load->model(['datasource_model']);
			$arra_datasource = $this->datasource_model->fields('id')
							->where(['LOWER(name)' => strtolower($datasource),'companies_id' => $this->user->company_id])
							->get();
			if(!$arra_datasource){
				$datasource_data = [];
				$datasource_data['companies_id'] = $companies_id;
				$datasource_data['created_by'] = $user_id;
				$datasource_data['name'] = ucfirst(strtolower($datasource));
				$datasource_id = $this->datasource_model->insert($datasource_data);
				//echo $this->db->last_query();die;
			}else{
				$datasource_id =$arra_datasource['id'];
			}
		}else{
			$datasource_id ='';
		}
		return $datasource_id;				
		
	}
	// For Data Source
	private function __get_callingstatus_id($callingstatus){
		if($callingstatus!=''){
			$user_id = $this->user->user_id;
			$companies_id = $this->user->company_id;
			$this->load->model(['callingstatus_model']);
			$arra_cs = $this->callingstatus_model->fields('id')
							->where(['LOWER(name)' => strtolower($callingstatus),'companies_id' => $this->user->company_id])
							->get();
			if(!$arra_cs){
				$cs_data = [];
				$cs_data['companies_id'] = $companies_id;
				$cs_data['created_by'] = $user_id;
				$cs_data['name'] = ucfirst(strtolower($callingstatus));
				$cs_data['parent_id'] = 0;
				$callingstatus_id = $this->callingstatus_model->insert($cs_data);
				//echo $this->db->last_query();die;
			}else{
				$callingstatus_id =$arra_cs['id'];
			}
			
		}else{
			$callingstatus_id='';
		}
		return $callingstatus_id;
	}
	// For Data Master
	private function __get_datamaster($head_title){
		return $this->data_master_model->fetchOneDataMasterTitle($head_title);		
	}
}

/* End of file Uploadexcel.php */
/* Location: ./application/controllers/masterdata/Uploadexcel.php */