<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class States_model extends MY_Model
{

    public $table = 'states';
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
        $this->return_as = 'array';
        parent::__construct();
    }

}
 
/* End of file States_model.php */
/* Location: ./application/models/States_model.php */
