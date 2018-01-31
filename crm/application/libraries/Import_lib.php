<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Import_lib
{

    var $CI;
    var $error_message;

    // -------------------------------------------------------------------
    public function __construct()
    {
        $this->CI =& get_instance();

        log_message('debug', "Import Library Initialized");
    }

    // -------------------------------------------------------------------
    public function import_users($data)
    {
        switch ($data['file_ext'])
        {
            case '.csv':
                $result = $this->parse_csv_file($data['full_path']);
                break;
            case '.xls':
            case '.xlsx':
                $result = $this->parse_excel_file($data['full_path']);
                break;
        }

        return $result;
    }

    // -------------------------------------------------------------------
    public function parse_csv_file($full_path)
    {
        $this->CI->load->library('csvreader');

        $fields = $this->CI->csvreader->parse_file($full_path);

        if ( ! empty($fields) )
        {
            foreach ($fields as $field)
            {
                $email = $field['EmailAddress'];
                $first_name = $field['FirstName'];
                $last_name = $field['LastName'];
                $department_name = $field['Department'];
                $role = $this->get_role($field['Role']);

                if ($this->check_role($role))
                {
                    if ( ! $this->check_email($email) )
                    {
                        $this->add_user();
                    }
                }
            }
        }
    }

    // -------------------------------------------------------------------
    public function parse_excel_file($full_path)
    {
        
    }

    // -------------------------------------------------------------------
    private function get_role($role)
    {
        switch (str_replace(' ', '_', strtolower($role)))
        {
            // case 'company_admin':
            //     $role = 2;
            //     break;
            case 'department_manager':
                $role = 3;
                break;
            case 'general_employee':
                $role = 4;
                break;
            default:
                $role = 4;
                break;
        }

        return $role;
    }

    // -------------------------------------------------------------------
    public function check_role($role)
    {
        return ($role >= 3);
    }

    // -------------------------------------------------------------------
    private function check_email($email)
    {
        return $this->CI->ion_auth->email_check($email);
    }

    // -------------------------------------------------------------------
    private function add_user($data)
    {
        $this->CI->load->helper('string');
        
        $salt = $this->CI->config->item('store_salt', 'ion_auth') ? $this->CI->ion_auth->salt() : FALSE;

        $password = random_string('alnum', 8);

        $hashed_password = $this->CI->ion_auth->hash_password($password, $salt);

        // User must be created first, in order to have the id
        $user_data = [
            'company_id' => $this->CI->user->company_id,
            'password' => $hashed_password,
            'email' => $email,
            'active' => 1,
            'is_dep_manager' => $role == 3 ? 1 : NULL
        ];

        $user_id = $this->CI->user_model->insert($user_data); // insert new user into users table
    }

    // -------------------------------------------------------------------
    private function add_to_group($role_id, $user_id)
    {
        // 
    }

    // -------------------------------------------------------------------
    private function send_email($email, $data)
    {
        // 
    }

    // -------------------------------------------------------------------
    private function add_profile($data)
    {
        // 
    }

    // -------------------------------------------------------------------
    private function check_department($name)
    {
        // 
    }

    // -------------------------------------------------------------------
    private function add_department($data)
    {
        // 
    }

    // -------------------------------------------------------------------
    private function update_user($department_id, $user_id)
    {
        // 
    }
}

/* End of file Import_lib.php */
/* Location: ./application/libraries/Import_lib.php */