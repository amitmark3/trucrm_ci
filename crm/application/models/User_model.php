<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class User_model extends MY_Model
{
    public $table = 'users';
    public $primary_key = 'id';

    public function __construct()
    {
        $this->has_one['profile'] = [
            'foreign_model' => 'profile_model',
            'foreign_table' => 'profiles',
            'foreign_key'   => 'user_id',
            'local_key'     => 'id',
        ];
        $this->has_one['company'] = [
            'foreign_model' => 'company_model',
            'foreign_table' => 'companies',
            'foreign_key'   => 'id',
            'local_key'     => 'company_id',
        ];
        $this->has_one['department'] = [
            'foreign_model' => 'department_model',
            'foreign_table' => 'departments',
            'foreign_key'   => 'id',
            'local_key'     => 'department_id',
        ];
        $this->return_as = 'array';
        parent::__construct();
    }
}
 
/* End of file User_model.php */
/* Location: ./application/models/User_model.php */