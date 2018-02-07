<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Datasource_model extends MY_Model
{

    public $table = 'datasource';
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
 
/* End of file Datasource_model.php */
/* Location: ./application/models/Datasource_model.php */
