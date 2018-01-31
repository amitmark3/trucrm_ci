<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Data_master_model extends MY_Model
{

	public $table = 'data_master_';
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
}
 
/* End of file Data_master_model.php */
/* Location: ./application/models/Data_master_model.php */
