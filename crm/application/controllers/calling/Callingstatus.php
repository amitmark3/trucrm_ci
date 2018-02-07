<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Callingstatus extends Account_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['callingstatus_model']);
        $this->load->language('calling');
		if ( ! in_array($this->user_group['id'], [2,3]) ){
            $this->flasher->set_warning_extra(lang('calling_status_access_denied'), 'dashboard', TRUE);
        }
        $this->breadcrumbs->push('Home', 'dashboard');
        $this->breadcrumbs->push('Calling Status', 'calling/status');
    }

    public function index()
    {
        $this->template->title(lang('calling_status_heading_index'))
                       ->set_css(['datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min'])
                       ->set_js(['datatables.jquery.min', 'datatables.buttons.min', 'datatables.responsive.min', 'datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min', 'datatables.buttons.print.min', 'datatables.buttons.flash.min', 'datatables.buttons.html5.min', 'bootbox-4.4.0.min', 'moment', 'jszip.min', 'pdfmake.min', 'vfs_fonts'])
                       ->set_partial('custom_js', 'callingstatus/datatables_js', ['url' => site_url('calling/callingstatus/datatables')])
                       ->build('callingstatus/index', $this->data);
    }

    public function datatables()
    {
        $this->load->library('datatables');

        $this->datatables->select("ca.id,
		(select name from callingstatus where id=ca.parent_id limit 1) as sub_name,
		ca.name,
		ca.updated_at, ca.status
		");
        $this->datatables->from("callingstatus ca");
        $this->datatables->where(['ca.companies_id'=> $this->user->company_id,'ca.parent_id!='=>0]);
		$this->datatables->add_column('edit', anchor('calling/status/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>', ['id' => '$1', 'title'=>'Edit']). ' ' . anchor('callingstatus/delete/$1', '<i class="fa fa-lg fa-times-circle-o"></i>', ['class' => 'confirm', 'id' => '$1', 'title'=>'Delete']), 'id');
		//echo $this->db->last_query();die;
        echo $this->datatables->generate();
    }

    public function add()
    {
		// For dropdown
		$callingstatus_dropdown = $this->__callingstatus_dropdown();
		//print_r($callingstatus);	die;
		//print '<pre>';print_r($this->user);print '</pre>';die;
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('callingstatus_name', 'Name', 'trim|required|callback_name_check');
		
        if ($this->form_validation->run() == TRUE)
        {
			$callingstatus_name = $this->input->post('callingstatus_name', TRUE);
            $parent_id = $this->input->post('parent_id', TRUE);
			$status = $this->input->post('active', TRUE);
            $data = [
                'companies_id' => $this->user->company_id,
                'created_by'=> $this->user->user_id,
                'name'   	=> $callingstatus_name,
                'parent_id' => $parent_id,
				'status'    => $status,
                'created_at'=> date('Y-m-d H:i:s')                
            ];

            $id = $this->callingstatus_model->insert($data);

            if ($id){
                $this->flasher->set_success(lang('calling_status_insert_successful'), 'calling/status/', false);
            }else{
                $this->flasher->set_warning_extra(lang('calling_status_insert_failed'), 'calling/status/add', TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push('Add Calling Status', 'calling/status/add');

            $this->template->title(lang('calling_status_heading_add'))
                           ->set_css(['formvalidation.min','bootstrap-checkbox-radio.min'])
                           ->set_js(['formvalidation.min','formvalidation-bootstrap.min'])
                          ->set('callingstatus_dropdown', ['' => '--Select--'] + $callingstatus_dropdown)
                           ->build('callingstatus/add', $this->data);
        }
    }
	public function name_check($name)
    {	
		$parent_id = $this->input->post('parent_id', TRUE);
		$name = trim($name);
		$id = $this->uri->segment(4);
		if(isset($id) && $id>0){
			if($id==$parent_id){
				$this->form_validation->set_message('name_check', $this->lang->line('calling_status_parent_invalid'));
				return FALSE;
			}
			$query = $this->callingstatus_model->fields('id')
					->where('name', $name)
					->where('parent_id', 0)
					->where('id!=', $id)
					->where('companies_id', $this->user->company_id)
					->get();
		}else{
			 $query = $this->callingstatus_model->fields('id')
					->where('name', $name)
					->where('parent_id', 0)
					->where('companies_id', $this->user->company_id)
					->get();
		}
		if ($query)
        {
            $this->form_validation->set_message('name_check', $this->lang->line('calling_status_name_exists'));

            return FALSE;
        }

        return TRUE;

        unset($query);
    }
	private function __callingstatus_dropdown(){
		$callingstatus_dropdown=array();
		$callingstatus = $this->callingstatus_model->fields('id, name')
					->where('parent_id =', 0)
					->where('status =', 1)
					->where('companies_id =', $this->user->company_id)
					->order_by('name', 'desc')
					->get_all();
		if($callingstatus){
			foreach ($callingstatus as $calling){
				$callingstatus_dropdown[$calling['id']] = $calling['name'];
			}
		}
		return $callingstatus_dropdown;
		
	}
	
	/// Delete the Risk Assessment Matrix and Details V20180108
	public function delete()
    {
		if ( ! $this->ion_auth->in_group([2,3]) )
        {
            $this->flasher->set_warning_extra(lang('calling_status_access_denied'), 'dashboard', TRUE);
        }

        $id = $this->uri->segment(4);

        if ( !$id )
        {
            $this->flasher->set_info(lang('calling_status_invalid_id'), 'calling/status', TRUE);
        }
		if (trim($id)==1)
        {
            $this->flasher->set_warning_extra(lang('calling_status_access_denied'), 'calling/status', TRUE);
        }
		
		$callingstatus = $this->callingstatus_model->fields('id, name')
					->where('parent_id =', 0)
					->where('status =', 1)
					->where('companies_id =', $this->user->company_id)
					->order_by('name', 'desc')
					->get_all();
					
        $callingstatus = $this->callingstatus_model->fields('companies_id')->get($id);
		
		/*$ra = $this->callingstatus_model->fields('id')->where('id', $id)->get();
		//print '<pre>';print_r($ra);die;
		if ($ra['id']>0)
        {
           $this->flasher->set_danger(lang('calling_status_delete_failed'), 'calling/status', TRUE);
        }*/
		
		if ($callingstatus['companies_id'] !== $this->user->company_id)
        {
            $this->flasher->set_warning_extra(lang('calling_status_invalid_company_to_delete'), 'calling/status', TRUE);
        }
		
        if ($this->callingstatus_model->delete($id))
        {
			$this->callingstatus_model->where('parent_id', $id)->delete();
			$this->flasher->set_success(lang('calling_status_delete_successful'), 'calling/status', TRUE);
        }
        else
        {
            $this->flasher->set_danger(lang('calling_status_delete_failed'), 'calling/status', TRUE);
        }
    }
	/***
	*** For Edit The Calling Status V20180125
	***/
    public function edit()
    {
		// For dropdown
		$callingstatus_dropdown = $this->__callingstatus_dropdown();
		
        $id = $this->uri->segment(4);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('calling_status_invalid_id'), 'calling/status', TRUE);
        }

        $calling_status = $this->callingstatus_model->get($id);

        if ( ! $calling_status)
        {
            $this->flasher->set_warning_extra(lang('calling_status_not_found'), 'calling/status', TRUE);
        }

        $this->load->library('form_validation');
        $this->load->helper('form');

         $this->form_validation->set_rules('callingstatus_name', 'Name', 'trim|required|callback_name_check');
		 
        if ($this->form_validation->run() == TRUE)
        {
            $callingstatus_name = $this->input->post('callingstatus_name');
			$parent_id = $this->input->post('parent_id', TRUE);
			$status = $this->input->post('active', TRUE);
            
			$calling_status_data = [
                'companies_id' => $this->user->company_id,
                'updated_by'=> $this->user->user_id,
                'name'   	=> $callingstatus_name,
                'parent_id' => $parent_id,
				'status'    => $status
            ];
			
            if ($this->callingstatus_model->update($calling_status_data, $id))
            {
                $this->flasher->set_success(lang('calling_status_update_successful'), 'calling/status', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('calling_status_update_failed'), 'calling/status/edit/' . $id, TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push(lang('calling_status_heading_edit'), 'risk_assessments/edit/');

            $this->template->title(lang('calling_status_heading_edit'))
                           ->set_css(['formvalidation.min','bootstrap-checkbox-radio.min', 'bootstrap-select.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min', 'bootstrap-select.min'])
						   ->set('callingstatus_dropdown', ['' => '--Select--'] + $callingstatus_dropdown)
                           ->set('calling_status', $calling_status)
						   ->build('callingstatus/edit', $this->data);
        }
    }

}

/* End of file Callingstatus.php */
/* Location: ./application/controllers/calling/Callingstatus.php */