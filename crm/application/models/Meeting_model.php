<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Meeting_model extends MY_Model
{

    public $table = 'meetings';
    public $primary_key = 'id';

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
        ]; // TODO: remove the above and use relationship query
        $this->has_one['user'] = [
            'foreign_model' => 'user_model',
            'foreign_table' => 'users',
            'foreign_key'   => 'id',
            'local_key'     => 'user_id',
        ];
        $this->has_many['attendees'] = [
            'foreign_model' => 'meeting_user_model',
            'foreign_table' => 'meeting_users',
            'foreign_key'   => 'meeting_id',
            'local_key'     => 'id',
        ];
        $this->has_many['agendas'] = [
            'foreign_model' => 'meeting_agenda_model',
            'foreign_table' => 'meeting_agendas',
            'foreign_key'   => 'meeting_id',
            'local_key'     => 'id',
        ];
        $this->has_many['actions'] = [
            'foreign_model' => 'meeting_action_model',
            'foreign_table' => 'meeting_actions',
            'foreign_key'   => 'meeting_id',
            'local_key'     => 'id',
        ];
        $this->return_as = 'array';
        parent::__construct();
    }

}
 
/* End of file Meeting_model.php */
/* Location: ./application/models/Meeting_model.php */
