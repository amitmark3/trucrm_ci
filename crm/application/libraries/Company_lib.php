<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Company_lib
{
    public $CI;
    public $company_id;

    protected $messages;
    protected $errors;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->CI =& get_instance();

        $this->company_id = $this->CI->session->company_id;
        
        log_message('debug', "Company Library Initialized");
    }

    /**
     * Get name of folder
     *
     * @param  string
     * @param  bool
     * @return string
     */
    public function get_uploads_folder($company_id, $with_avatars = FALSE)
    {
        $company = $this->CI->company_model->fields('uploads_folder')->get($company_id);

        if ($with_avatars == TRUE)
        {
            return FILE_PATH.$company['uploads_folder'] . DIRECTORY_SEPARATOR . 'avatars';
        }
        else
        {
            return FILE_PATH.$company['uploads_folder'];
        }
    } 
	public function get_uploads_folder_size($company_id, $with_avatars = FALSE)
    {
        $company = $this->CI->company_model->fields('uploads_folder')->get($company_id);

        if ($with_avatars == TRUE)
        {
            return FCPATH . 'uploads'. DIRECTORY_SEPARATOR . $company['uploads_folder'] . DIRECTORY_SEPARATOR . 'avatars';
        }
        else
        {
            return FCPATH . 'uploads'. DIRECTORY_SEPARATOR . $company['uploads_folder'];
        }
    }

    /**
     * Get size of uploads folder
     *
     * @param  string
     * @return string
     */
    public function get_dir_size($directory)
    {
        $size = 0;

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file)
        {
            $size += $file->getSize();
        }

        return $size;
    }

    /**
     * Get percentage of disk space used
     *
     * @param  string
     * @return int
     */
    public function percentage_used($company_id)
    {
        $this->CI->load->model('price_plan_model');

        $company = $this->CI->company_model->fields('id, price_plan_id')->get($company_id);

        $price_plan = $this->CI->price_plan_model->get($company['price_plan_id']);

        $price_plan_space = ($price_plan['space_unit'] == 'MB')
                             ? $price_plan['space_allotted'] * pow(1024, 2)
                             : $price_plan['space_allotted'] * pow(1024, 3);

        $dir_size = $this->get_dir_size($this->get_uploads_folder_size($company['id']));

        if ($dir_size)
        {
            $percent = $dir_size / $price_plan_space;
            $percent = number_format($percent * 100, 2);
        }
        else
        {
            $percent = 0;
        }

        return $percent;
    }

    public function get_company_logo()
    {
        $company = $this->CI->company_model->fields('uploads_folder, logo')->get($this->company_id);

        if ($company['logo'])
        {
            return site_url("uploads/{$company['uploads_folder']}/avatars/{$company['logo']}");
        }
        else
        {
            return site_url("assets/img/email-logo.png");
        }
    }

    /**
     * Get safety manager details
     *
     * @param  string
     * @return array
     */
    public function get_company_admin($fields = 'id, email, notify_by')
    {
        return $this->CI->user_model->fields($fields)->where(['company_id' => $this->company_id, 'is_company_admin' => 1])->with_profile('fields:first_name,last_name')->get();
    }

    /**
     * Get department manager details
     *
     * @param  string
     * @param  string
     * @return array
     */
    public function get_department_manager($fields = 'id, email, notify_by', $department_id)
    {
        return $this->CI->user_model
                    ->fields($fields)
                    ->where(['company_id' => $this->company_id, 'is_dep_manager' => 1, 'department_id' => $department_id])
                    ->with_profile('fields: first_name, last_name')
                    ->get();
    }

    /**
     * Get users from a specific department
     *
     * @param  string
     * @return array
     */
    public function get_department_users($department_id)
    {
        $users = $this->CI->user_model
                      ->fields('id')
                      ->where(['company_id' => $this->company_id, 'department_id' => $department_id])
                      ->with_profile('fields: first_name, last_name')
                      ->get_all();

        if ($users)
        {
            $assignees = '';

            foreach ($users as $user)
            {
                $assignees .= "<option value=\"{$user['id']}\">{$user['profile']['first_name']} {$user['profile']['last_name']}</option>";
            }

            return $assignees;
        }
    }

    /**
     * Get all users
     *
     * @param  string
     * @return array
     */
    public function get_company_users($active = NULL)
    {
        if ($active !== NULL)
        {
            $this->CI->user_model->where('active', $active);
        }

        $company_users = $this->CI->user_model->where('company_id', $this->company_id)->with_profile('fields:first_name, last_name')->get_all();

        $users = [];
		if(isset($company_users) && $company_users!=''){
			foreach ($company_users as $user)
			{
				$users[$user['id']] = $user['profile']['first_name'] . ' ' . $user['profile']['last_name'];
			}
		}

        return $users;
    }

    /**
     * Get all company departments
     *
     * @return array
     */
    public function get_company_departments()
    {
        $this->CI->load->model('department_model');

        return $this->CI->department_model->where('company_id', $this->company_id)->as_dropdown('name')->order_by('name', 'asc')->get_all();
    }

    /**
     * Set a message
     */
    public function set_message($message)
    {
        $this->messages[] = $message;
        return $message;
    }

    /**
     * Get the messages
     */
    public function messages()
    {
        $_output = '';

        foreach ($this->messages as $message)
        {
            $_output .= $message;
        }

        return $_output;
    }

    /**
     * Clear messages
     */
    public function clear_messages()
    {
        $this->messages = [];
        return TRUE;
    }

    /**
     * Set an error message
     */
    public function set_error($error)
    {
        $this->errors[] = $error;
        return $error;
    }

    /**
     * Get the error message
     */
    public function errors()
    {
        $_output = '';

        foreach ($this->errors as $error)
        {
            $_output .= $error;
        }

        return $_output;
    }

    /**
     * Clear Errors
     */
    public function clear_errors()
    {
        $this->errors = [];
        return TRUE;
    }
}

/* End of file Company_lib.php */
/* Location: ./application/libraries/Company_lib.php */