<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Profile_model extends MY_Model
{

    public $table = 'profiles';
    public $primary_key = 'id';

    public function __construct()
    {
        $this->has_one['user'] = [
            'foreign_model' => 'user_model',
            'foreign_table' => 'users',
            'foreign_key'   => 'user_id',
            'local_key'     => 'id',
        ];
        $this->timestamps = FALSE;
        $this->return_as = 'array';
        parent::__construct();
    }

}
 
/* End of file Profile_model.php */
/* Location: ./application/models/Profile_model.php */
