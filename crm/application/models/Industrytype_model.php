<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Industrytype_model extends MY_Model
{

    public $table = 'industrytype';
    public $primary_key = 'id';

    public function __construct()
    {
        $this->has_one['company'] = [
            'foreign_model' => 'company_model',
            'foreign_table' => 'companies',
            'foreign_key'   => 'id',
            'local_key'     => 'company_id',
        ];
        $this->return_as = 'array';
        parent::__construct();
    }
	
	/** 
	 * Update 
	*/	
	public function updateIndustryType($post_data, $update_row_id)
	{ 	
		//PRINT_R($post_data); ECHO $update_row_id;DIE;
		$this->db->trans_start();
		$this->db->where('id', $update_row_id);
		//$this->db->where('status', '0');
		$this->db->update($this->table, $post_data);
		$this->db->trans_complete(); 
		//echo $last_query = $this->db->last_query();die;
		//return $this->db->trans_status();
	}

}
 
/* End of file Industrytype_model.php */
/* Location: ./application/models/Industrytype_model.php */
