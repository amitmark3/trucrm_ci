<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Industrytype extends Account_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['industrytype_model']);
        $this->load->language('industrytype');
		if ( ! in_array($this->user_group['id'], [2,3]) ){
            $this->flasher->set_warning_extra(lang('industry_type_access_denied'), 'dashboard', TRUE);
        }
        $this->breadcrumbs->push('Home', 'dashboard');
        $this->breadcrumbs->push('Industry Type', 'industrytype');
    }

    public function index()
    {
        $this->template->title(lang('industry_type_heading_index'))
                       ->set_css(['datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min'])
                       ->set_js(['datatables.jquery.min', 'datatables.buttons.min', 'datatables.responsive.min', 'datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min', 'datatables.buttons.print.min', 'datatables.buttons.flash.min', 'datatables.buttons.html5.min', 'bootbox-4.4.0.min', 'moment', 'jszip.min', 'pdfmake.min', 'vfs_fonts'])
                       ->set_partial('custom_js', 'industrytype/datatables_js', ['url' => site_url('industrytype/datatables')])
                       ->build('industrytype/index', $this->data);
    }

    public function datatables()
    {
        $this->load->library('datatables');

        $this->datatables->select("ca.id,
		ca.name,
		(select name from industrytype where id=ca.parent_id limit 1) as sub_name,
		ca.updated_at, ca.status
		");
        $this->datatables->from("industrytype ca");
        $this->datatables->where('ca.companies_id', $this->user->company_id);
		$this->datatables->add_column('edit', anchor('industrytype/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>', ['id' => '$1', 'title'=>'Edit']). ' ' . anchor('industrytype/delete/$1', '<i class="fa fa-lg fa-times-circle-o"></i>', ['class' => 'confirm', 'id' => '$1', 'title'=>'Delete']), 'id');
		//echo $this->db->last_query();die;
        echo $this->datatables->generate();
    }

    public function add()
    {
		// For dropdown
		$industrytype_dropdown = $this->__industrytype_dropdown();
		//print_r($industrytype);	die;
		//print '<pre>';print_r($this->user);print '</pre>';die;
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('industrytype_name', 'Name', 'trim|required|callback_name_check');
		
        if ($this->form_validation->run() == TRUE)
        {
			$industrytype_name = $this->input->post('industrytype_name', TRUE);
            $parent_id = $this->input->post('parent_id', TRUE);
			$status = $this->input->post('active', TRUE);
            $data = [
                'companies_id' => $this->user->company_id,
                'created_by'=> $this->user->user_id,
                'name'   	=> $industrytype_name,
                'parent_id' => $parent_id,
				'status'    => $status,
                'created_at'=> date('Y-m-d H:i:s')                
            ];

            $id = $this->industrytype_model->insert($data);

            if ($id){
                $this->flasher->set_success(lang('industry_type_insert_successful'), 'industrytype/', false);
            }else{
                $this->flasher->set_warning_extra(lang('industry_type_insert_failed'), 'industrytype/add', TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push('Add Industry Type', 'industrytype/add');

            $this->template->title(lang('industry_type_heading_add'))
                           ->set_css(['formvalidation.min','bootstrap-checkbox-radio.min'])
                           ->set_js(['formvalidation.min','formvalidation-bootstrap.min'])
                          ->set('industrytype_dropdown', ['' => '--Select--'] + $industrytype_dropdown)
                           ->build('industrytype/add', $this->data);
        }
    }
	public function name_check($name)
    {	
		$parent_id = $this->input->post('parent_id', TRUE);
		$name = trim($name);
		$id = $this->uri->segment(3);
		if(isset($id) && $id>0){
			if($id==$parent_id){
				$this->form_validation->set_message('name_check', $this->lang->line('industry_type_parent_invalid'));
				return FALSE;
			}
			$query = $this->industrytype_model->fields('id')
					->where('name', $name)
					->where('parent_id', 0)
					->where('id!=', $id)
					->where('companies_id', $this->user->company_id)
					->get();
		}else{
			 $query = $this->industrytype_model->fields('id')
					->where('name', $name)
					->where('parent_id', 0)
					->where('companies_id', $this->user->company_id)
					->get();
		}
		if ($query)
        {
            $this->form_validation->set_message('name_check', $this->lang->line('industry_type_name_exists'));

            return FALSE;
        }

        return TRUE;

        unset($query);
    }
	private function __industrytype_dropdown(){
		$industrytype_dropdown=array();
		$industrytype = $this->industrytype_model->fields('id, name')
					->where('parent_id =', 0)
					->where('status =', 1)
					->where('companies_id =', $this->user->company_id)
					->order_by('name', 'desc')
					->get_all();
		if($industrytype){
			foreach ($industrytype as $itype){
				$industrytype_dropdown[$itype['id']] = $itype['name'];
			}
		}
		return $industrytype_dropdown;
		
	}
	
	/// Delete the Industry Type and Details V20180108
	public function delete()
    {
		if ( ! $this->ion_auth->in_group([2,3]) )
        {
            $this->flasher->set_warning_extra(lang('industry_type_access_denied'), 'dashboard', TRUE);
        }

        $id = $this->uri->segment(3);

        if ( !$id )
        {
            $this->flasher->set_info(lang('industry_type_invalid_id'), 'industrytype', TRUE);
        }
		if (trim($id)==1)
        {
            $this->flasher->set_warning_extra(lang('industry_type_access_denied'), 'industrytype', TRUE);
        }
		
		$industrytype = $this->industrytype_model->fields('id, name')
					->where('parent_id =', 0)
					->where('status =', 1)
					->where('companies_id =', $this->user->company_id)
					->order_by('name', 'desc')
					->get_all();
					
        $industrytype = $this->industrytype_model->fields('companies_id')->get($id);
		
		/*$ra = $this->industrytype_model->fields('id')->where('id', $id)->get();
		//print '<pre>';print_r($ra);die;
		if ($ra['id']>0)
        {
           $this->flasher->set_danger(lang('industry_type_delete_failed'), 'industrytype', TRUE);
        }*/
		
		if ($industrytype['companies_id'] !== $this->user->company_id)
        {
            $this->flasher->set_warning_extra(lang('industry_type_invalid_company_to_delete'), 'industrytype', TRUE);
        }
		
        if ($this->industrytype_model->delete($id))
        {
			$this->industrytype_model->where('parent_id', $id)->delete();
			$this->flasher->set_success(lang('industry_type_delete_successful'), 'industrytype', TRUE);
        }
        else
        {
            $this->flasher->set_danger(lang('industry_type_delete_failed'), 'industrytype', TRUE);
        }
    }
	/***
	*** For Edit The Industry Type V20180125
	***/
    public function edit()
    {
		// For dropdown
		$industrytype_dropdown = $this->__industrytype_dropdown();
		
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('industry_type_invalid_id'), 'industrytype', TRUE);
        }

        $industry_type = $this->industrytype_model->get($id);

        if ( ! $industry_type)
        {
            $this->flasher->set_warning_extra(lang('industry_type_not_found'), 'industrytype', TRUE);
        }

        $this->load->library('form_validation');
        $this->load->helper('form');

         $this->form_validation->set_rules('industrytype_name', 'Name', 'trim|required|callback_name_check');
		 
        if ($this->form_validation->run() == TRUE)
        {
            $industrytype_name = $this->input->post('industrytype_name');
			$parent_id = $this->input->post('parent_id', TRUE);
			$status = $this->input->post('active', TRUE);
            
			$industry_type_data = [
                'companies_id' => $this->user->company_id,
                'updated_by'=> $this->user->user_id,
                'name'   	=> $industrytype_name,
                'parent_id' => $parent_id,
				'status'    => $status
            ];
			
            if ($this->industrytype_model->update($industry_type_data, $id))
            {
                $this->flasher->set_success(lang('industry_type_update_successful'), 'industrytype', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('industry_type_update_failed'), 'industrytype/edit/' . $id, TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push(lang('industry_type_heading_edit'), 'risk_assessments/edit/');

            $this->template->title(lang('industry_type_heading_edit'))
                           ->set_css(['formvalidation.min','bootstrap-checkbox-radio.min', 'bootstrap-select.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min', 'bootstrap-select.min'])
						   ->set('industrytype_dropdown', ['' => '--Select--'] + $industrytype_dropdown)
                           ->set('industry_type', $industry_type)
						   ->build('industrytype/edit', $this->data);
        }
    }

}

/* End of file Industrytype.php */
/* Location: ./application/controllers/Industrytype.php */