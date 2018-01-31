<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Notification_model extends MY_Model
{

    public $table = 'notifications';
    public $primary_key = 'id';

    public function __construct()
    {
        $this->has_one['profile'] = [
            'foreign_model' => 'profile_model',
            'foreign_table' => 'profiles',
            'foreign_key'   => 'user_id',
            'local_key'     => 'user_id',
        ];
        $this->return_as = 'array';
        parent::__construct();
    }

}
 
/* End of file Notification_model.php */
/* Location: ./application/models/Notification_model.php */