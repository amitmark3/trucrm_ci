<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notify_lib
{

    var $CI;
    var $error_message;

    // -------------------------------------------------------------------
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('notification_model');
        
        log_message('debug', "Notifications Library Initialized");
    }

    // -------------------------------------------------------------------
    public function get_IDs($IDs)
    {
        $IDs = implode(",", (array) $IDs);

        $query = $this->CI->db->query("SELECT DISTINCT id FROM users WHERE (notify_by = 'website' OR notify_by = 'both') AND id IN ($IDs)");

        $IDs = array_column($query->result_array(), 'id');

        return $IDs;
    }

    // -------------------------------------------------------------------
    public function send($IDs, $type, $title, $link = NULL)
    {
        $batch_data = []; $i = 0;

        foreach ($IDs as $id)
        {
            $batch_data[$i]['user_id'] = $id;
            $batch_data[$i]['company_id'] = $this->CI->user->company_id;
            $batch_data[$i]['type'] = $type;
            $batch_data[$i]['title'] = $title;
            $batch_data[$i]['link'] = $link;
            $i++;
        }

        $this->CI->notification_model->insert($batch_data);
    }

    // -------------------------------------------------------------------
    public function get($id)
    {
        return $this->CI->notification_model->where('id', $id)->get();
    }

    // -------------------------------------------------------------------
    public function get_all($user_id)
    {
        return $this->CI->notification_model->where('user_id', $user_id)->get_all();
    }

    // -------------------------------------------------------------------
    public function get_unread($user_id)
    {
        return $this->CI->notification_model->fields('content, type, created_at')->where(['user_id' => $user_id, 'viewed' => 0])->get_all();
    }

    // -------------------------------------------------------------------
    public function mark_as_read($id)
    {
        return $this->CI->notification_model->where('id', $id)->update(['viewed' => 1]);
    }

    // -------------------------------------------------------------------
    public function mark_all_as_read($user_id)
    {
        return $this->CI->notification_model->where('user_id', $user_id)->update(['viewed' => 1]);
    }

    // -------------------------------------------------------------------
    public function count_all()
    {
        return $this->CI->notification_model->where('user_id', $this->CI->user->id)->count_rows();
    }

    // -------------------------------------------------------------------
    public function count_unread()
    {
        return $this->CI->notification_model->where(['user_id' => $this->CI->user->id, 'viewed' => 0])->count_rows();
    }
}

/* End of file Notify_lib.php */
/* Location: ./application/libraries/Notify_lib.php */