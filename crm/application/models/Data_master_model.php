<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Data_master_model extends MY_Model
{

	public $table = 'data_master_';
	public $table_contact = 'data_master_contact_';
    public $primary_key = 'id';
	
    public function __construct()
    {
		$this->has_one['contact'] = [
            'foreign_model' => 'data_master_contact_model',
            'foreign_table' => 'data_master_contact_',
            'foreign_key'   => 'id',
            'local_key'     => 'data_master_id',
        ];
        $this->return_as = 'array';
        parent::__construct();
    }
	/***
	** Get Data Master from table data_master_{$companies_id}
	*/
	function fetchOneDataMasterTitle($head_title) {
		//echo $head_title; die;
		$companies_id = $this->user->company_id;
		$table_data_master = trim($this->table.$companies_id);
		$result='';
		$query = $this->db->select('id')
				->from($table_data_master)
				->where(['LOWER(head_title)' => strtolower($head_title),'companies_id' => $companies_id])
				->limit(1)
				->get();
		if ($query->num_rows() > 0){
			$result_arr = $query->result();
			$result = $result_arr[0]->id;
		}
		return $result;
    }
	/** 
	 * Update Data Master
	*/	
	public function update_data_master($post_data, $update_row_id)
	{ 
		$companies_id = $this->user->company_id;
		$table = $this->table.$companies_id;
		//PRINT_R($post_data); ECHO $update_row_id;DIE;
		$this->db->trans_start();
		$this->db->where('id', $update_row_id);
		//$this->db->where('status', '0');
		$this->db->update($table, $post_data);
		$this->db->trans_complete(); 
		//echo $last_query = $this->db->last_query();die;
		return $this->db->trans_status();
	}
	/** 
	 * Save Master Data In Batch
	*/
	public function save_data_master_batch($data_master_arr){
		$companies_id = $this->user->company_id;
		$table = $this->table.$companies_id;
		//$this->db->insert_batch($this->_api_data, $api_data_arr); 
		$this->db->insert_batch($table, $data_master_arr); 
		//echo $this->db->affected_rows(); 
		$affected_rows = $this->db->affected_rows();
        if ($affected_rows > 0){
            return $affected_rows;
        }
        return FALSE;
		//print '<pre>';print($last_query);print '</pre>';		
	}
	/** 
	 * Save one by one Master Data
	*/
	public function save_data_master($data_master_arr,$data_master_contact){
		$companies_id = $this->user->company_id;
		$table = $this->table.$companies_id;
		if($this->db->insert($table, $data_master_arr)){
			$id = $this->db->insert_id();
			$data_master_contact['data_master_id']=$id;
			$this->save_data_master_contact($data_master_contact);
			//return $id;
		}
		//echo $this->db->last_query();die;
		//print '<pre>';print($last_query);print '</pre>';		
	}
	/** 
	 * Save one by one Master Data
	*/
	public function save_data_master_contact($data_master_contact){
		$companies_id = $this->user->company_id;
		$table_contact = $this->table_contact.$companies_id;
		if($this->db->insert($table_contact, $data_master_contact)){
			$id = $this->db->insert_id();
			return $id;
		}
		//echo $last_query = $this->db->last_query();die;
	}
	
	/***
	* Display all RECORDS of Master DATA V20180201
	***/
	function fetchAllDataMaster() {
		$result=array();
		$result['data']=array();
		$companies_id = $this->user->company_id;
		$table = $this->table.$companies_id;
		$args = func_get_args();
		//print_r($args);
        $param = $args[0];
		if (!empty($param['fields'])) {
			$fields = trim($param['fields']);
		}else {
			$fields = "*";
		}
		//$this->db->select($fields);
		
		$this->db->select($fields); 
		$this->db->from($table.' DM '); 
		//For Where
		//$this->db->where('eu.is_status', '1'); 
		if (!empty($param['where'])) {
            $where_string = trim($param['where']);
            $this->db->where($where_string, NULL, FALSE); 
        }
		//for order by
        $orderby = !empty($param['orderby'])?$param['orderby'] : 'DM.id';
		
		//for LIMIT RECORD PER PAGE OR PAGINATION
		$sortby = !empty($param['sortby'])?$param['sortby'] : 'DESC';
		// For Group By
		if (!empty($param['group_by'])) {
			$group_by = $param['group_by'];
			$this->db->group_by($group_by); 
		}
		$this->db->order_by($orderby, $sortby);
		$current='';
		//for LIMIT RECORD PER PAGE OR PAGINATION
		if (!empty($param['total_records'])) {
			 $total_records_query = $this->db->get();
			//$last_query = $this->db->last_query();
			//print '<pre>';print($last_query);print '</pre>';
             $result['total_records'] = $total_records_query->num_rows();
        }else{
			if (!empty($param['recPerPage'])) {
					$current = trim($param['cPage'])>0?trim($param['cPage']):'1';
					$current = !empty($current) ? $current : 0;
					$current = $current >0 ? $current-1 : $current;					
					$recPerPage = trim($param['recPerPage']);
					$limitStart = ($current*$recPerPage);
					$limitEnd = $recPerPage > 0 ? $recPerPage : 10;
					//echo $limitStart.','.$limitEnd;die;
					//$this->db->limit($limitStart, $limitEnd); 
					$this->db->limit($limitEnd, $limitStart); 
			}
			$query = $this->db->get(); 
			//  $last_query = $this->db->last_query();
			
				//print '<pre>';print($last_query);print '</pre>';die;
			if ($query->num_rows() > 0)
			{
				$result['data'] = $query->result_array();
			}
		}		
		//echo $this->_found_rows = $this->db->query('SELECT FOUND_ROWS() AS count;')->row()->count;
		unset($param);
		
		return $result;		
	}
	
	function delete_masterdata($id){
		$companies_id = $this->user->company_id;
		$table = $this->table.$companies_id;
		$table_contact = $this->table_contact.$companies_id;
		if($id>0){
			if ($this->db->where('id', $id)->delete($table)){
				$this->db->where('data_master_id', $id)->delete($table_contact);
				return true;
			}
		}
		
		return false;
	}
	function fetchOneDataMaster($where='') {
		$result=array();
		$companies_id = $this->user->company_id;
		$table = $this->table.$companies_id;
		$args = func_get_args();
		//print_r($args);
        $param = $args[0];
		if (!empty($param['fields'])) {
			$fields = trim($param['fields']);
		}else {
			$fields = "*";
		}
		//$this->db->select($fields);
		
		$this->db->select($fields); 
		$this->db->from($table.' DM '); 
		if (!empty($param['where'])) {
            $where_string = trim($param['where']);
            $this->db->where($where_string, NULL, FALSE); 
        }
		$query = $this->db->get(); 
		//$last_query = $this->db->last_query();
		//print '<pre>';print($last_query);print '</pre>';die;
		
		if ($query->num_rows() > 0)
		{
			$result_arr = $query->result_array();
			$result = $result_arr[0];
		}
		return $result;
    }
}
 
/* End of file Data_master_model.php */
/* Location: ./application/models/Data_master_model.php */
