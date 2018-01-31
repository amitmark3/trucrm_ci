<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Countries_model extends MY_Model
{

    public $table = 'countries';
    public $primary_key = 'id';

    public function __construct()
    {
		$this->has_one['company'] = [
            'foreign_model' => 'company_model',
            'foreign_table' => 'companies',
            'foreign_key'   => 'id',
            'local_key'     => 'companies_id',
        ];
		$this->return_as = 'array';
        parent::__construct();
    }

}
 
/* End of file Countries_model.php */
/* Location: ./application/models/Countries_model.php */
