<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Admin_lib
{
    public $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    // -------------------------------------------------------------------

    public function get_companies($active = NULL)
    {
        $this->CI->load->model('company_model');

        if ($active !== NULL)
        {
            $this->CI->company_model->where('active', $active);
        }

        return $this->CI->company_model->as_dropdown('name')->order_by('name', 'asc')->get_all();
    }

    // -------------------------------------------------------------------

    public function get_price_plans()
    {
        $this->CI->load->model('price_plan_model');

        return $this->CI->price_plan_model->as_dropdown('name')->order_by('name', 'asc')->get_all();
    }

    // -------------------------------------------------------------------

    public function get_company_manager($company_id, $fields = 'id')
    {
        return $this->CI->user_model->fields($fields)->where(['company_id' => $company_id, 'is_company_admin' => 1])->get();
    }

    // -------------------------------------------------------------------

    public function get_company_users($company_id)
    {
        // TODO: Finish
    }

    // -------------------------------------------------------------------

    public function count($table)
    {
        return $this->CI->db->count_all($table);
    }

    // -------------------------------------------------------------------

    public function custom_query($query)
    {
        return ($this->CI->db->query($query) !== FALSE) ? $this->CI->db->query($query)->result_array() : FALSE;
    }

}

/* End of file Admin_lib.php */
/* Location: ./application/libraries/admin/Admin_lib.php */