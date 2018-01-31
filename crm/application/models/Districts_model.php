<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Districts_model extends MY_Model
{

    public $table = 'districts';
    public $primary_key = 'id';

    public function __construct()
    {
		$this->has_one['company'] = [
            'foreign_model' => 'company_model',
            'foreign_table' => 'companies',
            'foreign_key'   => 'id',
            'local_key'     => 'companies_id',
        ];
		$this->has_one['countries'] = [
            'foreign_model' => 'countries_model',
            'foreign_table' => 'countries',
            'foreign_key'   => 'id',
            'local_key'     => 'country_id',
        ];
		$this->has_one['states'] = [
            'foreign_model' => 'states_model',
            'foreign_table' => 'states',
            'foreign_key'   => 'id',
            'local_key'     => 'state_id',
        ];
        
        
        $this->return_as = 'array';
        parent::__construct();
    }

}
 
/* End of file Districts_model.php */
/* Location: ./application/models/Districts_model.php */
