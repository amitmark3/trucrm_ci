<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Payment_model extends MY_Model
{

    public $table = 'payments';
    public $primary_key = 'id';
    // public $protected = [];

    public function __construct()
    {
        $this->has_one['company'] = [
            'foreign_model' => 'company_model',
            'foreign_table' => 'companies',
            'foreign_key'   => 'id',
            'local_key'     => 'company_id',
        ];
        $this->has_one['profile'] = [
            'foreign_model' => 'profile_model',
            'foreign_table' => 'profiles',
            'foreign_key'   => 'user_id',
            'local_key'     => 'user_id',
        ];
        $this->has_one['company_admin'] = [
            'foreign_model' => 'user_model',
            'foreign_table' => 'users',
            'foreign_key'   => 'id',
            'local_key'     => 'user_id',
        ];
        $this->return_as = 'array';
        parent::__construct();
    }

}
 
/* End of file Payment_model.php */
/* Location: ./application/models/Payment_model.php */
