<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Masterdata extends Account_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['data_master_model','data_master_contact_model','datasource_model','industrytype_model','districts_model','states_model','countries_model','callingstatus_model']);
        $this->load->language('masterdata');
		if ( ! in_array($this->user_group['id'], [2,3]) ){
            $this->flasher->set_warning_extra(lang('masterdata_access_denied'), 'dashboard', TRUE);
        }
        $this->breadcrumbs->push('Home', 'dashboard');
        $this->breadcrumbs->push('Master Data', 'masterdata/');
		
		$this->job_function_dropdown = array('1'=>'Decision Maker','2'=>'Recommender','3'=>'Influencer');
    }

    public function index()
    {
        $this->template->title(lang('masterdata_heading_index'))
                       ->set_css(['datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min'])
                       ->set_js(['datatables.jquery.min', 'datatables.buttons.min', 'datatables.responsive.min', 'datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min', 'datatables.buttons.print.min', 'datatables.buttons.flash.min', 'datatables.buttons.html5.min', 'bootbox-4.4.0.min', 'moment', 'jszip.min', 'pdfmake.min', 'vfs_fonts'])
                       ->set_partial('custom_js', 'masterdata/datatables_js', ['url' => site_url('masterdata/masterdata/datatables')])
                       ->build('masterdata/index', $this->data);
    }

    public function datatables()
    {
		$companies_id = $this->user->company_id;
		$table_data_master = trim('data_master_'.$companies_id);
        $this->load->library('datatables');
		//(select name from callingstatus where id=dm.parent_id limit 1) as sub_name,
        $this->datatables->select("dm.id, dm.unique_id, dm.head_title, dm.updated_at, dm.status
		");
        $this->datatables->from($table_data_master." dm");
        $this->datatables->where('dm.companies_id', $this->user->company_id);
		$this->datatables->add_column('edit', anchor('masterdata/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>', ['id' => '$1', 'title'=>'Edit']). ' ' . anchor('masterdata/delete/$1', '<i class="fa fa-lg fa-times-circle-o"></i>', ['class' => 'confirm', 'id' => '$1', 'title'=>'Delete']), 'id');
		//echo $this->db->last_query();die;
        echo $this->datatables->generate();
    }
	
	public function view()
    {
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('masterdata_invalid_id'), 'masterdata', TRUE);
        }
		//districts_id,states_id,countries_id,pincode
		$fields = 'id,unique_id, head_title, address, pincode, website, no_of_employee, no_of_pc,companies_id,
		(select name from countries where id=DM.countries_id limit 1) as countries,
		(select name from districts where id=DM.districts_id limit 1) as districts,
		(select name from states where id=DM.states_id limit 1) as states,
		(select name from industrytype where id=DM.industrytype_id limit 1) as industrytype,
		(select name from industrytype where id=DM.sub_industrytype_id limit 1) as sub_industrytype,
		(select name from datasource where id=DM.datasource_id limit 1) as datasource		
		';
		$dm_param['where'] = ' DM.id='.$id;
		$dm_param['fields'] = $fields;
		$data_master = $this->data_master_model->fetchOneDataMaster($dm_param);
		//print '<pre>';print_r($data_master);print '</pre>';die;
		$datamaster_contact = $this->__masterDataContact($id);
		//print '<pre>';print_r($datamaster_contact);print '</pre>';die;
        if ($data_master['companies_id'] != $this->user->company_id)
        {
            $this->flasher->set_warning_extra(lang('masterdata_invalid_to_view'), 'masterdata', TRUE);
        }
		

        $this->data['data_master'] = $data_master;
        $this->data['datamaster_contact'] = $datamaster_contact;
		$this->data['job_function'] = $this->job_function_dropdown;
        
       
        $this->breadcrumbs->push('Details', 'masterdata/view/' . $id);

        $this->template->title(lang('masterdata_heading_view'))
                       ->build('masterdata/view', $this->data);
    }
	
	private function __masterDataContact($data_master_id){
		$fields = 'DMC.id,DMC.salutation,DMC.first_name,DMC.last_name,DMC.job_title,DMC.job_function,DMC.phone_country_code,DMC.phone_std_code,DMC.email_office,DMC.email_personal,DMC.phone_office,DMC.phone_personal,DMC.department,DMC.updated_at, 
		(select name from callingstatus where id=DMC.callingstatus_id limit 1) as callingstatus,
		(select CONCAT(`first_name`," ",`last_name`) from profiles where user_id=DMC.updated_by limit 1) as updated_by';
		$dmc_param['where'] = ' DMC.data_master_id='.$data_master_id;
		$dmc_param['total_records'] = '';
		//$dm_param['group_by']= 'hr,channelId';
		$dmc_param['fields'] = $fields;
		$dmc_param['orderby']= ' updated_at ';
		$dmc_param['sortby']= ' asc ';
		//$dmc_param['recPerPage']=1;
		//$dmc_param['cPage']=1;		
        $data_master_array = $this->data_master_contact_model->fetchAllDataMasterContact($dmc_param);
		return $data_master_contact = $data_master_array['data'];
	}
	
	/***
	*Delete the Master Data & Master Data Cotact V20180202
	***/
	public function delete(){
		
		if ( ! $this->ion_auth->in_group([2,3]) )
        {
            $this->flasher->set_warning_extra(lang('masterdata_access_denied'), 'dashboard', TRUE);
        }
		$id = $this->uri->segment(3);

        if ( !$id )
        {
            $this->flasher->set_info(lang('masterdata_invalid_id'), 'masterdata/', TRUE);
        }
		$fields='DM.companies_id';
		$dm_param['where'] = ' DM.id='.$id;
		$dm_param['fields'] = $fields;
		$data_master = $this->data_master_model->fetchOneDataMaster($dm_param);	
        if ($data_master['companies_id'] !== $this->user->company_id)
        {
            $this->flasher->set_warning_extra(lang('masterdata_invalid_company_to_delete'), 'masterdata', TRUE);
        }
		
        if ($this->data_master_model->delete_masterdata($id))
        {
			$this->flasher->set_success(lang('masterdata_delete_successful'), 'masterdata', TRUE);
        }
        else
        {
            $this->flasher->set_danger(lang('masterdata_delete_failed'), 'masterdata', TRUE);
        }
    }
	/***
	*Delete the Master Data Cotact V20180202
	***/
	public function mdcdelete(){
		
		if ( ! $this->ion_auth->in_group([2,3]) )
        {
            $this->flasher->set_warning_extra(lang('masterdata_access_denied'), 'dashboard', TRUE);
        }
		$id = $this->uri->segment(3);
		$mdc_id = $this->uri->segment(4);

        if ( !$id && !$mdc_id)
        {
            $this->flasher->set_info(lang('masterdata_invalid_id'), 'masterdata/view/'.$id, TRUE);
        }
		$fields='DM.companies_id';
		$dm_param['where'] = ' DM.id='.$id;
		$dm_param['fields'] = $fields;
		$data_master = $this->data_master_model->fetchOneDataMaster($dm_param);		
        if ($data_master['companies_id'] !== $this->user->company_id)
        {
            $this->flasher->set_warning_extra(lang('masterdata_invalid_company_to_delete'), 'masterdata', TRUE);
        }
		
        if ($this->data_master_contact_model->delete_masterdata_contact($mdc_id))
        {
			$this->flasher->set_success(lang('md_contact_delete_successful'), 'masterdata/view/'.$id, TRUE);
        }
        else
        {
            $this->flasher->set_danger(lang('md_contact_delete_failed'), 'masterdata/view/'.$id, TRUE);
        }
    }
	
	/***
	*** For Edit The Master Data V20180202
	***/
    public function edit()
    {
		$id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('masterdata_invalid_id'), 'masterdata', TRUE);
        }
		$fields='DM.id,DM.unique_id, DM.head_title, DM.address,DM.landmark, DM.pincode, DM.website, DM.no_of_employee, DM.no_of_pc, DM.companies_id, DM.countries_id, DM.districts_id, DM.states_id, DM.industrytype_id, DM.sub_industrytype_id, DM.datasource_id, DM.status';
		$dm_param['where'] = ' DM.id='.$id;
		$dm_param['fields'] = $fields;
		$data_master = $this->data_master_model->fetchOneDataMaster($dm_param);
		//print '<pre>';print_r($data_master);print '</pre>';die;
        if ( ! $data_master)
        {
            $this->flasher->set_warning_extra(lang('masterdata_not_found'), 'masterdata', TRUE);
        }
		// For dropdown
		$datasource_dropdown = $this->datasource_model->where('companies_id', $this->user->company_id)->as_dropdown('name')->order_by('name', 'asc')->get_all();
		
		$industrytype_dropdown = $this->industrytype_model->where(['companies_id' => $this->user->company_id,'parent_id'=>0])->as_dropdown('name')->order_by('name', 'asc')->get_all();
		
		$sub_industrytype_dropdown = $this->industrytype_model->where(['companies_id' => $this->user->company_id,'parent_id'=>$data_master['industrytype_id']])->as_dropdown('name')->order_by('name', 'asc')->get_all();

		$countries_dropdown = $this->countries_model->where(['companies_id' => $this->user->company_id])->as_dropdown('name')->order_by('name', 'asc')->get_all();
		
		$states_dropdown = $this->states_model->where(['companies_id' => $this->user->company_id,'country_id'=>$data_master['countries_id']])->as_dropdown('name')->order_by('name', 'asc')->get_all();
		
		$districts_dropdown = $this->districts_model->where(['companies_id' => $this->user->company_id,'country_id'=>$data_master['countries_id'],'state_id'=>$data_master['states_id']])->as_dropdown('name')->order_by('name', 'asc')->get_all();
		
        $this->load->library('form_validation');
        $this->load->helper('form');

         $this->form_validation->set_rules('head_title', 'Name', 'trim|required|callback_name_check');
		 
        if ($this->form_validation->run() == TRUE)
        {
			//print '<pre>';print_r($_POST);print '</pre>';DIE;
			$head_title = $this->input->post('head_title', TRUE);
            $address = $this->input->post('address', TRUE);
            $landmark = $this->input->post('landmark', TRUE);
            $pincode = $this->input->post('pincode', TRUE);
            $website = $this->input->post('website', TRUE);
            $no_of_employee = $this->input->post('no_of_employee', TRUE);
            $no_of_pc = $this->input->post('no_of_pc', TRUE);
            $countries_id = $this->input->post('countries_id', TRUE);
            $states_id = $this->input->post('states_id', TRUE);
            $districts_id = $this->input->post('districts_id', TRUE);
            $industrytype_id = $this->input->post('industrytype_id', TRUE);
            $sub_industrytype_id = $this->input->post('sub_industrytype_id', TRUE);
            $datasource_id = $this->input->post('datasource_id', TRUE);
			$status = $this->input->post('active', TRUE);
            
			$dm_data = [                
                'head_title' => $head_title,
                'address' => $address,
                'landmark' => $landmark,
                'pincode' => $pincode,
                'website' => $website,
                'no_of_employee' => $no_of_employee,
                'no_of_pc' => $no_of_pc,
                'countries_id' => $countries_id,
                'states_id' => $states_id,
                'districts_id' => $districts_id,
                'industrytype_id' => $industrytype_id,
                'sub_industrytype_id' => $sub_industrytype_id,
                'datasource_id' => $datasource_id,
				'updated_by'=> $this->user->user_id,
				'updated_at'=> date('Y-m-d H:i:s'),
                'status'    => $status
            ];
			
            if ($this->data_master_model->update_data_master($dm_data, $id))
            {
                $this->flasher->set_success(lang('masterdata_update_successful'), 'masterdata', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('masterdata_update_failed'), 'masterdata/edit/' . $id, TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push(lang('masterdata_heading_edit'), 'masterdata/edit/');

            $this->template->title(lang('masterdata_heading_edit'))
                           ->set_css(['formvalidation.min','bootstrap-checkbox-radio.min', 'bootstrap-select.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min', 'bootstrap-select.min'])
						   ->set('datasource_dropdown', ['' => '--Select--'] + $datasource_dropdown)
						   ->set('industrytype_dropdown', ['' => '--Select--'] + $industrytype_dropdown)
						   ->set('sub_industrytype_dropdown', ['' => '--Select--'] + $sub_industrytype_dropdown)
						   ->set('countries_dropdown', ['' => '--Select--'] + $countries_dropdown)
						   ->set('states_dropdown', ['' => '--Select--'] + $states_dropdown)
						   ->set('districts_dropdown', ['' => '--Select--'] + $districts_dropdown)
                           ->set('data_master', $data_master)
						   ->build('masterdata/edit', $this->data);
        }
    }
	/***
	** Check Master Data Head Title (Name) duplicate V20180202
	**/
	public function name_check($name)
    {	
		$name = trim($name);
		$id = $this->uri->segment(3);
		
		if(isset($id) && $id>0){			
			$dm_param['where'] = ' DM.id!='.$id.' AND LOWER(DM.head_title)="'.strtolower($name).'" AND DM.companies_id = '. $this->user->company_id;
		}else{
			$dm_param['where'] = ' LOWER(DM.head_title)="'.strtolower($name).'" AND DM.companies_id = '. $this->user->company_id;
		}
		$fields='DM.id';
		//echo $dm_param['where'] ; die;
		$dm_param['fields'] = $fields;
		$data_master = $this->data_master_model->fetchOneDataMaster($dm_param);
		//$last_query = $this->db->last_query();
		//print '<pre>';print($last_query);print '</pre>';die;
		if (isset($data_master['id']) && $data_master['id']>0)
        {
            $this->form_validation->set_message('name_check', $this->lang->line('masterdata_name_exists'));
            return FALSE;
        }

        return TRUE;
        unset($data_master);
        unset($dm_param);
    }
	/***
	*** For Edit The Master Data Contact Edit V20180202
	***/
    public function mdcedit(){
		unset($dm_param);
		$md_id = $this->uri->segment(3);
		$md_contact_id = $this->uri->segment(4);
		if ( ! $md_id )
        {
            $this->flasher->set_info(lang('masterdata_invalid_id'), 'masterdata', TRUE);
        }
        if ( ! $md_contact_id )
        {
            $this->flasher->set_info(lang('md_contact_invalid_id'), 'masterdata/view/'.$md_id, TRUE);
        }

		$fields='DMC.id, DMC.data_master_id, DMC.salutation, DMC.first_name, DMC.last_name, DMC.job_title, DMC.phone_office, DMC.phone_personal, DMC.email_office, DMC.email_personal, DMC.department, DMC.emp_no, DMC.callingstatus_id,DMC.sub_callingstatus_id, DMC.job_function, DMC.status';
		$dm_param['where'] = ' DMC.id='.$md_contact_id.' AND DMC.data_master_id='.$md_id;
		$dm_param['fields'] = $fields;
		$md_contact = $this->data_master_contact_model->fetchOneDataMasterContact($dm_param);
		//print '<pre>';print_r($md_contact);print '</pre>';die;
        if ( ! $md_contact)
        {
            $this->flasher->set_warning_extra(lang('md_contact_not_found'), 'masterdata/view/'.$md_id, TRUE);
        }
		// For dropdown
		$callingstatus_dropdown = $this->callingstatus_model->where(['companies_id'=> $this->user->company_id,'parent_id'=>0])->as_dropdown('name')->order_by('name', 'asc')->get_all();
		
		$sub_callingstatus_dropdown = $this->callingstatus_model->where(['companies_id'=> $this->user->company_id,'parent_id!='=>0])->as_dropdown('name')->order_by('name', 'asc')->get_all();
		
		$job_function_dropdown = $this->job_function_dropdown;
		//print '<pre>';print_r($sub_callingstatus_dropdown);print '</pre>';die;
		
		$this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		
        if ($this->form_validation->run() == TRUE)
        {
			//print '<pre>';print_r($_POST);print '</pre>';DIE;
			$salutation = $this->input->post('salutation', TRUE);
            $first_name = $this->input->post('first_name', TRUE);
            $last_name = $this->input->post('last_name', TRUE);
            $job_title = $this->input->post('job_title', TRUE);
            $phone_office = $this->input->post('phone_office', TRUE);
            $phone_personal = $this->input->post('phone_personal', TRUE);
            $email_office = $this->input->post('email_office', TRUE);
            $email_personal = $this->input->post('email_personal', TRUE);
            $department = $this->input->post('department', TRUE);
            $emp_no = $this->input->post('emp_no', TRUE);
            $callingstatus_id = $this->input->post('callingstatus_id', TRUE);
			$sub_callingstatus_id = $this->input->post('sub_callingstatus_id', TRUE);
            $job_function = $this->input->post('job_function', TRUE);
			$status = $this->input->post('active', TRUE);
            
			$dmc_data = [  
				'job_title' => $job_title,
                'salutation' => $salutation,
                'first_name' => $first_name,
                'last_name' => $last_name,                
                'phone_office' => $phone_office,
                'phone_personal' => $phone_personal,
                'email_office' => $email_office,
                'email_personal' => $email_personal,
                'department' => $department,
                'emp_no' => $emp_no,
                'callingstatus_id' => $callingstatus_id,
                'sub_callingstatus_id' => $sub_callingstatus_id,
                'job_function' => $job_function,
                'updated_by'=> $this->user->user_id,
				'updated_at'=> date('Y-m-d H:i:s'),
                'status'    => $status
            ];
			
            if ($this->data_master_contact_model->update_data_master_contact($dmc_data, $md_contact_id))
            {
                $this->flasher->set_success(lang('md_contact_update_successful'), 'masterdata/view/'.$md_id, TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('md_contact_update_failed'), 'masterdata/mdcedit/'.$md_contact_id, TRUE);
            }
        }
        else
        {
			$this->breadcrumbs->push(lang('masterdata_heading_view'), 'masterdata/view/'.$md_id);
            $this->breadcrumbs->push(lang('md_contact_heading_edit'), 'masterdata/mdcedit/'.$md_id.'/'.$md_contact_id);

            $this->template->title(lang('md_contact_heading_edit'))
                           ->set_css(['formvalidation.min','bootstrap-checkbox-radio.min', 'bootstrap-select.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min', 'bootstrap-select.min'])
						   ->set('callingstatus_dropdown', ['' => '--Select--'] + $callingstatus_dropdown)
						   ->set('sub_callingstatus_dropdown', ['' => '--Select--'] + $sub_callingstatus_dropdown)
						    ->set('job_function_dropdown', ['' => '--Select--'] + $job_function_dropdown)
                           ->set('md_contact', $md_contact)
						   ->build('masterdata/edit_contact', $this->data);
						   
						   //->set('sub_callingstatus_dropdown', ['' => '--Select--'] + $sub_callingstatus_dropdown)
        }
    }
	

}

/* End of file Masterdata.php */
/* Location: ./application/controllers/masterdata/Masterdata.php */