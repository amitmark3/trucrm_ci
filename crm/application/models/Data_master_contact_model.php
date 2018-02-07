<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Data_master_contact_model extends MY_Model
{

    public $table = 'data_master_contact_';
    public $primary_key = 'id';
	
	public function __construct()
    {
        $this->return_as = 'array';
        parent::__construct();
    }
	/***
	* Display all RECORDS of Data Master Contact V20180201
	***/
	function fetchAllDataMasterContact() {
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
		$this->db->from($table.' DMC '); 
		//For Where
		//$this->db->where('eu.is_status', '1'); 
		if (!empty($param['where'])) {
            $where_string = trim($param['where']);
            $this->db->where($where_string, NULL, FALSE); 
        }
		//for order by
        $orderby = !empty($param['orderby'])?$param['orderby'] : 'DMC.id';
		
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
			//$last_query = $this->db->last_query();
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
	function delete_masterdata_contact($id){
		$companies_id = $this->user->company_id;
		$table = $this->table.$companies_id;
		if($id>0){
			if ($this->db->where('id', $id)->delete($table)){
				return true;
			}
		}
		
		return false;
	}
	function fetchOneDataMasterContact($where='') {
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
		$this->db->from($table.' DMC '); 
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
	/** 
	 * Update Data Master Contact V20180202
	*/	
	public function update_data_master_contact($post_data, $update_row_id)
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
}
 
/* End of file Data_master_contact_model.php */
/* Location: ./application/models/Data_master_contact_model.php */
