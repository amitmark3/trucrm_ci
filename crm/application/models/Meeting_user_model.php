<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Meeting_user_model extends MY_Model
{

    public $table = 'meeting_users';
    public $primary_key = 'id';

    public function __construct()
    {
        $this->has_one['meeting'] = [
            'foreign_model' => 'meeting_model',
            'foreign_table' => 'meetings',
            'foreign_key'   => 'id',
            'local_key'     => 'meeting_id',
        ];
        $this->has_one['profile'] = [
            'foreign_model' => 'profile_model',
            'foreign_table' => 'profiles',
            'foreign_key'   => 'user_id',
            'local_key'     => 'user_id',
        ];
        $this->return_as = 'array';
        $this->timestamps = FALSE;
        parent::__construct();
    }

}
 
/* End of file Meeting_user_model.php */
/* Location: ./application/models/Meeting_user_model.php */
