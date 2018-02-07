<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends Account_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['project_model','project_time_model','project_requirement_model']);
        $this->load->language('project');
		if ( ! in_array($this->user_group['id'], [2,3]) ){
            $this->flasher->set_warning_extra(lang('project_access_denied'), 'dashboard', TRUE);
        }
		//public $interval_type= array('1'=>'Day(s)','2'=>'Month(s)');
		$this->breadcrumbs->push('Home', 'dashboard');
        $this->breadcrumbs->push('Project', 'project');
		// Static Dropdown 
		$this->interval_type_dropdown= array('1'=>'Day(s)','2'=>'Month(s)');
		$this->project_type_dropdown= array('1'=>'Demand Generation','2'=>'Database Validation');
		$this->rc_type_dropdown= array('1'=>'Text Box','2'=>'Check Box','3'=>'Radio Button','4'=>'Select Box','5'=>'Text Area');
		// Get the total user for this company
		$this->total_users = $this->db->from("users")->where(['company_id'=>$this->user->company_id,'active'=>1,'is_company_admin'=>0])->count_all_results();
		
   
    }

    public function index()
    {
        $this->template->title(lang('project_heading_index'))
                       ->set_css(['datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min'])
                       ->set_js(['datatables.jquery.min', 'datatables.buttons.min', 'datatables.responsive.min', 'datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min', 'datatables.buttons.print.min', 'datatables.buttons.flash.min', 'datatables.buttons.html5.min', 'bootbox-4.4.0.min', 'moment', 'jszip.min', 'pdfmake.min', 'vfs_fonts'])
                       ->set_partial('custom_js', 'project/datatables_js', ['url' => site_url('project/project/datatables')])
                       ->build('project/index', $this->data);
    }

    public function datatables()
    {
        $this->load->library('datatables');

        $this->datatables->select("P.id, P.name, P.user_allocated, P.start_date, P.status");
        $this->datatables->from("data_project P");
        $this->datatables->where(['P.companies_id'=> $this->user->company_id]);
		$this->datatables->add_column('edit', anchor('project/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>', ['id' => '$1', 'title'=>'Edit']). ' ' . anchor('callingstatus/delete/$1', '<i class="fa fa-lg fa-times-circle-o"></i>', ['class' => 'confirm', 'id' => '$1', 'title'=>'Delete']), 'id');
		//print '<pre>';print_r($this->datatables); print '</pre>';
		//echo $this->datatables->last_query();die;
        echo $this->datatables->generate();
    }

    public function add()
    {
		
		// Check Form Validation
        $this->__form_validation();
        if ($this->form_validation->run() == TRUE)
        {
			$project_name = $this->input->post('project_name', TRUE);
            $user_allocated = $this->input->post('user_allocated', TRUE);
            $productivity_required = $this->input->post('productivity_required', TRUE);
            $per_day_required = $this->input->post('per_day_required', TRUE);
            $start_date = $this->input->post('start_date', TRUE);
            $end_date = $this->input->post('end_date', TRUE);
            $project_type = $this->input->post('project_type', TRUE);
			$status = $this->input->post('active', TRUE);
            $data = [
                'companies_id' => $this->user->company_id,
                'created_by'=> $this->user->user_id,
                'project_type'=> $project_type,
                'name'   	=> $project_name,
				'user_allocated' => $user_allocated,
                'productivity_required'	=> $productivity_required,
                'per_day_required' => $per_day_required,
                'start_date' => $start_date,
				'end_date' => $end_date,
                'status'    => $status,
                'created_at'=> date('Y-m-d H:i:s')                
            ];
			
            $id = $this->project_model->insert($data);
            if ($id){
				$this->__delete_project_time_requirement($id);
				if($project_type=='1'){	//Only demand generation
					$this->__project_time($id);
					$this->__project_requirement($id);
				}
				
				$this->flasher->set_success(lang('project_insert_successful'), 'project/', false);
            }else{
                $this->flasher->set_warning_extra(lang('project_insert_failed'), 'project/add', TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push('Add Calling Status', 'project/add');

			$this->template->title(lang('project_heading_add'))
                           ->set_css(['formvalidation.min','bootstrap-datepicker.min', 'bootstrap-checkbox-radio.min','bootstrap-select.min'])
                           ->set_js(['formvalidation.min','formvalidation-bootstrap.min', 'bootstrap-datepicker.min','bootstrap-select.min'])
                          ->set_partial('custom_js', 'project/custom_js', ['form_name' => 'project-form'])
						  ->set('interval_type_dropdown', ['' => '--Select--'] + $this->interval_type_dropdown)
						  ->set('rc_type_dropdown', $this->rc_type_dropdown)
						  ->set('total_users', $this->total_users)
						  ->set('project_type_dropdown',$this->project_type_dropdown)
                          ->build('project/add', $this->data);
        }
    }
	private function __delete_project_time_requirement($id){
		if($id>0){
			$this->project_time_model->where(['companies_id'=> $this->user->company_id,'data_project_id'=> $id])->delete();
			$this->project_requirement_model->where(['companies_id'=> $this->user->company_id,'data_project_id'=> $id])->delete();
		}
	}
	private function __project_time($data_project_id){
		
		$array_interval_from = $this->input->post('interval_from', TRUE);
		$array_interval_to = $this->input->post('interval_to', TRUE);
		$array_interval_type = $this->input->post('interval_type', TRUE);
		//echo count($array_interval_from); die;

		if(count($array_interval_from)>0 && $data_project_id>0){
			$arr_data=array();
			for($i=0;$i<count($array_interval_from);$i++){
				if($array_interval_from[$i]!='' && $array_interval_to[$i]!='' && $array_interval_type[$i]!=''){
					//&& $array_interval_to[$i]!='' && $array_interval_type[$i]!=''
					$arr_data[] = [
									  'companies_id' =>$this->user->company_id,
									  'data_project_id' => $data_project_id,
									  'interval_from' => $array_interval_from[$i] ,
									  'interval_to' => $array_interval_to[$i],
									  'interval_type' => $array_interval_type[$i],
									  'status' => '1'
								 ];
				}	
			}
			$this->db->insert_batch('data_project_time',$arr_data);
			//echo $this->db->last_query();
			//print'<pre>'; print_r($arr_data);print'</pre>';die;
		}
		
	}
	private function __project_requirement($data_project_id){
		
		$array_input_type = $this->input->post('input_type', TRUE);
		$array_input_label = $this->input->post('input_label', TRUE);
		$array_input_value = $this->input->post('input_value', TRUE);
		//echo count($array_input_type); die;
		if(count($array_input_type)>0 && $data_project_id>0){
			$arr_data_requirement=array();
			for($i=0;$i<count($array_input_type);$i++){
				if($array_input_type[$i]!='' && $array_input_label[$i]!='' && $array_input_value[$i]!=''){
					//&& $array_interval_to[$i]!='' && $array_input_value[$i]!=''
					$arr_data_requirement[] = [
									  'companies_id' =>$this->user->company_id,
									  'data_project_id' => $data_project_id,
									  'input_type' => $array_input_type[$i] ,
									  'input_label' => $array_input_label[$i],
									  'input_value' => $array_input_value[$i],
									  'status' => '1'
								 ];
				}	
			}
			
			$this->db->insert_batch('data_project_requirement',$arr_data_requirement);
			//echo $this->db->last_query();
			//print'<pre>'; print_r($arr_data_requirement);print'</pre>';die;
		}
		
	}
	public function name_check($name)
    {	
		//$parent_id = $this->input->post('parent_id', TRUE);
		$name = trim($name);
		$id = $this->uri->segment(3);
		if(isset($id) && $id>0){
			$query = $this->project_model->fields('id')
					->where('name', $name)
					->where('id!=', $id)
					->where('companies_id', $this->user->company_id)
					->get();
		}else{
			 $query = $this->project_model->fields('id')
					->where('name', $name)
					->where('companies_id', $this->user->company_id)
					->get();
		}
		if ($query)
        {
            $this->form_validation->set_message('name_check', $this->lang->line('project_name_exists'));

            return FALSE;
        }

        return TRUE;

        unset($query);
    }
	private function __project_dropdown(){
		$project_dropdown=array();
		$project = $this->project_model->fields('id, name')
					->where('status =', 1)
					->where('companies_id =', $this->user->company_id)
					->order_by('name', 'desc')
					->get_all();
		if($project){
			foreach ($project as $calling){
				$project_dropdown[$calling['id']] = $calling['name'];
			}
		}
		return $project_dropdown;
		
	}
	
	/// Delete the Project, Time & Requirements V20180108
	public function delete()
    {
		if ( ! $this->ion_auth->in_group([2,3]) )
        {
            $this->flasher->set_warning_extra(lang('project_access_denied'), 'dashboard', TRUE);
        }

        $id = $this->uri->segment(4);

        if ( !$id )
        {
            $this->flasher->set_info(lang('project_invalid_id'), 'project', TRUE);
        }
		if (trim($id)==1)
        {
            $this->flasher->set_warning_extra(lang('project_access_denied'), 'project', TRUE);
        }
		
		$project = $this->project_model->fields('companies_id')->get($id);
		
		/*$ra = $this->project_model->fields('id')->where('id', $id)->get();
		//print '<pre>';print_r($ra);die;
		if ($ra['id']>0)
        {
           $this->flasher->set_danger(lang('project_delete_failed'), 'project', TRUE);
        }*/
		
		if ($project['companies_id'] !== $this->user->company_id)
        {
            $this->flasher->set_warning_extra(lang('project_invalid_company_to_delete'), 'project', TRUE);
        }
		
        if ($this->project_model->delete($id))
        {
			// Delete  data_project_time & data_project_requirement
			$this->__delete_project_time_requirement($id);
			//$this->project_model->where('parent_id', $id)->delete();
			$this->flasher->set_success(lang('project_delete_successful'), 'project', TRUE);
        }
        else
        {
            $this->flasher->set_danger(lang('project_delete_failed'), 'project', TRUE);
        }
    }
	/***
	*** For Edit The Project V20180125
	***/
    public function edit()
    {
		// For dropdown
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('project_invalid_id'), 'project', TRUE);
        }

        $project = $this->project_model->where(['companies_id'=> $this->user->company_id])->get($id);
		
		if ( ! $project)
        {
            $this->flasher->set_warning_extra(lang('project_not_found'), 'project', TRUE);
        }
		$project = $this->project_model->where(['companies_id'=> $this->user->company_id])->get($id);
		//'project_time_model','project_requirement_model'
		// Get Project Time Data
		$project_pt = $this->project_time_model->where(['companies_id'=> $this->user->company_id,'data_project_id'=>$id])->get_all();
		
		$project_rc = $this->project_requirement_model->where(['companies_id'=> $this->user->company_id,'data_project_id'=>$id])->get_all();
		//print '<pre>';print_r($project_rc);print '</pre>';die;
		// Check Form Validation
        $this->__form_validation();
		 
        if ($this->form_validation->run() == TRUE)
        {
            $project_name = $this->input->post('project_name', TRUE);
            $user_allocated = $this->input->post('user_allocated', TRUE);
            $productivity_required = $this->input->post('productivity_required', TRUE);
            $per_day_required = $this->input->post('per_day_required', TRUE);
            $start_date = $this->input->post('start_date', TRUE);
            $end_date = $this->input->post('end_date', TRUE);
            $project_type = $this->input->post('project_type', TRUE);
			$status = $this->input->post('active', TRUE);
            $project_data = [
                'companies_id' => $this->user->company_id,
                'updated_by'=> $this->user->user_id,
                'project_type'=> $project_type,
                'name'   	=> $project_name,
				'user_allocated' => $user_allocated,
                'productivity_required'	=> $productivity_required,
                'per_day_required' => $per_day_required,
                'start_date' => $start_date,
				'end_date' => $end_date,
                'status'    => $status            
            ];
			
            if ($this->project_model->update($project_data, $id))
            {
				$this->__delete_project_time_requirement($id);
				if($project_type=='1'){	//Only demand generation
					$this->__project_time($id);
					$this->__project_requirement($id);
				}
                $this->flasher->set_success(lang('project_update_successful'), 'project', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('project_update_failed'), 'project/edit/' . $id, TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push(lang('project_heading_edit'), 'project/edit/');

            $this->template->title(lang('project_heading_edit'))
                           ->set_css(['formvalidation.min','bootstrap-datepicker.min', 'bootstrap-checkbox-radio.min','bootstrap-select.min'])
                           ->set_js(['formvalidation.min','formvalidation-bootstrap.min', 'bootstrap-datepicker.min','bootstrap-select.min'])
                          ->set_partial('custom_js', 'project/custom_js', ['form_name' => 'project-form'])
						  ->set('interval_type_dropdown', ['' => '--Select--'] + $this->interval_type_dropdown)
						  ->set('rc_type_dropdown', $this->rc_type_dropdown)
						  ->set('total_users', $this->total_users)
						   ->set('project', $project)
						   ->set('project_rc', $project_rc)
						   ->set('project_pt', $project_pt)
						 ->set('project_type_dropdown',$this->project_type_dropdown)
						   ->build('project/edit', $this->data);
        }
    }
	private function __form_validation(){
		$this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('project_name', 'Name', 'trim|required|callback_name_check');
		$this->form_validation->set_rules('start_date', 'Start date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End date', 'trim|required|callback_compareDate');
		
		$this->form_validation->set_rules('user_allocated', 'No. of Users Allocated ', 'trim|required|less_than['.($this->total_users+1).']');
		$this->form_validation->set_rules('per_day_required', 'Per Day Productivity Required', 'trim|required');
		$this->form_validation->set_rules('productivity_required', 'Productivity Required', 'trim|required');
	}
	function compareDate() {
		
		$startDate = strtotime($this->input->post('start_date'));
		$endDate =  strtotime($this->input->post('end_date'));
		$current_date=time();
		if ($endDate >= $startDate){
			return True;
		}elseif($current_date<$startDate){
			$this->form_validation->set_message('compareDate', 'Start Date should be less than Today Date.');
			return False;
		}elseif($current_date>$endDate){
			$this->form_validation->set_message('compareDate', 'End Date should be greater than Today Date.');
			return False;
		}else {
			$this->form_validation->set_message('compareDate', '%s should be greater than Start Date.');
			return False;
		}
	}
	
	/// Delete the Project Time V20180108
	public function delete_project_time()
    {
		$message = '';
		$id = $this->input->post('id', TRUE);
		if ( ! $this->ion_auth->in_group([2,3]) )
        {
            $message = $this->flasher->set_warning_extra(lang('project_access_denied'), NULL, TRUE);
        }

        if ( !$id )
        {
            $message = $this->flasher->set_info(lang('project_invalid_id'), NULL, TRUE);
        }
		
		$project_time = $this->project_time_model->fields('companies_id')->get($id);
		
		if ($project_time['companies_id'] !== $this->user->company_id)
        {
            $message = lang('project_invalid_company_to_delete');
        }

        if ($this->project_time_model->where(['companies_id'=> $this->user->company_id,'id'=> $id])->delete())
		{
			
			//$message = lang('project_delete_successful');
			$message = 'deleted';
        }
        else
        {
            $message = lang('project_delete_failed');
        }
		echo $message;
    }
	
	/// Delete the Project Time V20180108
	public function delete_requirement_criteria()
    {
		$message = '';
		$id = $this->input->post('id', TRUE);
		if ( ! $this->ion_auth->in_group([2,3]) )
        {
            $message = $this->flasher->set_warning_extra(lang('project_access_denied'), NULL, TRUE);
        }

        if ( !$id )
        {
            $message = $this->flasher->set_info(lang('project_invalid_id'), NULL, TRUE);
        }
		
		$project_time = $this->project_requirement_model->fields('companies_id')->get($id);
		
		if ($project_time['companies_id'] !== $this->user->company_id)
        {
            $message = lang('project_invalid_company_to_delete');
        }

        if ($this->project_requirement_model->where(['companies_id'=> $this->user->company_id,'id'=> $id])->delete())
		{
			
			//$message = lang('project_delete_successful');
			$message = 'deleted';
        }
        else
        {
            $message = lang('project_delete_failed');
        }
		echo $message;
    }

}

/* End of file Project.php */
/* Location: ./application/controllers/project/project.php */