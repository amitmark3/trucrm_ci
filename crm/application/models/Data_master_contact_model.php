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
	
}
 
/* End of file Data_master_contact_model.php */
/* Location: ./application/models/Data_master_contact_model.php */
