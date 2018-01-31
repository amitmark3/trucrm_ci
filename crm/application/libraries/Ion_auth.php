<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth
* Version: 2.5.2
* Author: Ben Edmunds
*         ben.edmunds@gmail.com
*         @benedmunds
* Added Awesomeness: Phil Sturgeon
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
* Created:  10.01.2009
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
* Requirements: PHP5 or above
*/

class Ion_auth
{
    /**
     * account status ('not_activated', etc ...)
     *
     * @var string
     **/
    protected $status;

    /**
     * extra where
     *
     * @var array
     **/
    public $_extra_where = array();

    /**
     * extra set
     *
     * @var array
     **/
    public $_extra_set = array();

    /**
     * caching of users and their groups
     *
     * @var array
     **/
    public $_cache_user_in_group;

    /**
     * __construct
     *
     * @author Ben
     */
    public function __construct()
    {
        $this->load->config('ion_auth', TRUE);
        $this->load->library('email');
        $this->load->language('ion_auth');
        $this->load->helper('language');
        $this->load->model('ion_auth_model');
        $this->_cache_user_in_group =& $this->ion_auth_model->_cache_user_in_group;

        // auto-login the user if they are remembered
        if ( !$this->logged_in()
             && get_cookie($this->config->item('identity_cookie_name', 'ion_auth'))
             && get_cookie($this->config->item('remember_cookie_name', 'ion_auth')) )
        {
            $this->ion_auth_model->login_remembered_user();
        }

        // $email_config = $this->config->item('email_config', 'ion_auth');

        // if ($this->config->item('use_ci_email', 'ion_auth') && isset($email_config) && is_array($email_config))
        // {
        //     $this->email->initialize($email_config);
        // }
    }

    /**
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * @param $method
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $arguments)
    {
        if (!method_exists( $this->ion_auth_model, $method) )
        {
            throw new Exception('Undefined method Ion_auth::' . $method . '() called');
        }
        if ($method == 'create_user')
        {
            return call_user_func_array(array($this, 'register'), $arguments);
        }
        if ($method == 'update_user')
        {
            return call_user_func_array(array($this, 'update'), $arguments);
        }
        return call_user_func_array( array($this->ion_auth_model, $method), $arguments);
    }

    /**
     * Enables the use of CI super-global without having to define an extra variable.
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * @access  public
     * @param   $var
     * @return  mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }

    /**
     * @param $email
     * @return mixed boolian / array
     * @author Mathew
     */
    public function forgotten_password($email)
    {
        if ( $this->ion_auth_model->forgotten_password($email) )
        {
            $user = $this->where('email', $email)->where('active', 1)->users()->row();

            if ($user)
            {
                $data = array(
                    'email' => $user->email,
                    'forgotten_password_code' => $user->forgotten_password_code
                );

                if (!$this->config->item('use_ci_email', 'ion_auth'))
                {
                    $this->set_message('forgot_password_successful');
                    return $data;
                }
                else
                {
                    $message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password', 'ion_auth'), $data, true);
                    $this->email->clear();
                    $this->email->from($this->config->item('website_email'), $this->config->item('website_title'));
                    $this->email->to($user->email);
                    $this->email->subject($this->config->item('website_title') . ' - ' . $this->lang->line('email_forgotten_password_subject'));
                    $this->email->message($message);

                    if ($this->email->send())
                    {
                        $this->set_message('forgot_password_successful');
                        return TRUE;
                    }
                    else
                    {
                        $this->set_error('forgot_password_unsuccessful');
                        return FALSE;
                    }
                }
            }
            else
            {
                $this->set_error('forgot_password_unsuccessful');
                return FALSE;
            }
        }
        else
        {
            $this->set_error('forgot_password_unsuccessful');
            return FALSE;
        }
    }

    /**
     * forgotten_password_complete
     *
     * @param $code
     * @author Mathew
     * @return bool
     */
    public function forgotten_password_complete($code)
    {
        $user = $this->where('forgotten_password_code', $code)->users()->row();

        if (!$user)
        {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

        $new_password = $this->ion_auth_model->forgotten_password_complete($code, $user->salt);

        if ($new_password)
        {
            $data = array(
                'email'         => $user->email,
                'new_password'  => $new_password
            );
            if (!$this->config->item('use_ci_email', 'ion_auth'))
            {
                $this->set_message('password_change_successful');
                return $data;
            }
            else
            {
                $message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password_complete', 'ion_auth'), $data, true);

                $this->email->clear();
                $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                $this->email->to($user->email);
                $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_new_password_subject'));
                $this->email->message($message);

                if ($this->email->send())
                {
                    $this->set_message('password_change_successful');
                    return TRUE;
                }
                else
                {
                    $this->set_error('password_change_unsuccessful');
                    return FALSE;
                }

            }
        }

        return FALSE;
    }

    /**
     * forgotten_password_check
     *
     * @param $code
     * @author Michael
     * @return bool
     */
    public function forgotten_password_check($code)
    {
        $user = $this->where('forgotten_password_code', $code)->users()->row();

        if (!is_object($user))
        {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }
        else
        {
            if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0)
            {
                // make sure it isn't expired
                $expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
                if (time() - $user->forgotten_password_time > $expiration)
                {
                    // it has expired
                    $this->clear_forgotten_password_code($code);
                    $this->set_error('password_change_unsuccessful');
                    return FALSE;
                }
            }
            return $user;
        }
    }

    /**
     * register
     *
     * @param $email
     * @param $password
     * @param array $additional_data
     * @param array $group_ids
     * @author Mathew
     * @return bool
     */
    public function register($email, $password, $company_id, $group_ids = array())
    {
        $email_activation = $this->config->item('email_activation', 'ion_auth');

        $id = $this->ion_auth_model->register($email, $password, $company_id, $group_ids);

        if (!$email_activation)
        {
            if ($id !== FALSE)
            {
                $this->set_message('account_creation_successful');
                return $id;
            }
            else
            {
                $this->set_error('account_creation_unsuccessful');
                return FALSE;
            }
        }
        else
        {
            if (!$id)
            {
                $this->set_error('account_creation_unsuccessful');
                return FALSE;
            }

            // deactivate so the user must follow the activation flow
            $deactivate = $this->ion_auth_model->deactivate($id);

            // the deactivate method call adds a message, here we need to clear that
            $this->ion_auth_model->clear_messages();

            if (!$deactivate)
            {
                $this->set_error('deactivate_unsuccessful');
                return FALSE;
            }

            $activation_code = $this->ion_auth_model->activation_code;
            $user            = $this->ion_auth_model->user($id)->row();

            $data = array(
                'email'      => $user->email,
                'id'         => $user->id,
                'activation' => $activation_code,
            );
            if (!$this->config->item('use_ci_email', 'ion_auth'))
            {
                $this->set_message('activation_email_successful');
                return $data;
            }
            else
            {
                $message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_activate', 'ion_auth'), $data, true);

                $this->email->clear();
                $this->email->from($this->config->item('website_email'), $this->config->item('website_title'));
                $this->email->to($email);
                $this->email->subject($this->config->item('website_title') . ' - ' . $this->lang->line('email_activation_subject'));
                $this->email->message($message);

                if ($this->email->send() == TRUE)
                {
                    $this->set_message('activation_email_successful');
                    return $id;
                }

            }

            $this->set_error('activation_email_unsuccessful');
            return FALSE;
        }
    }

    /**
     * logout
     *
     * @return void
     * @author Mathew
     **/
    public function logout()
    {
		$update_data = array('logoutDateTime' => date('Y-m-d H:i:s'));
		$sessionid = $this->session->userdata('sessionid');
		$this->db->update('users_logins_log', $update_data, array('sessionid' => $sessionid));
		
        $email = $this->config->item('email', 'ion_auth');

        if (substr(CI_VERSION, 0, 1) == '2')
        {
            $this->session->unset_userdata( array($email => '', 'id' => '', 'user_id' => '', 'sessionid' => '') );
        }
        else
        {
            $this->session->unset_userdata( array($email, 'id', 'user_id', 'sessionid') );
        }

        // delete the remember me cookies if they exist
        if (get_cookie($this->config->item('email_cookie_name', 'ion_auth')))
        {
            delete_cookie($this->config->item('email_cookie_name', 'ion_auth'));
        }
        if (get_cookie($this->config->item('remember_cookie_name', 'ion_auth')))
        {
            delete_cookie($this->config->item('remember_cookie_name', 'ion_auth'));
        }

        // Destroy the session
        $this->session->sess_destroy();

        // Recreate the session
        if (substr(CI_VERSION, 0, 1) == '2')
        {
            $this->session->sess_create();
        }
        else
        {
            if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
                session_start();
            }
            $this->session->sess_regenerate(TRUE);
        }

        $this->set_message('logout_successful');
        return TRUE;
    }

    /**
     * logged_in
     *
     * @return bool
     * @author Mathew
     **/
    public function logged_in()
    {
        return (bool) $this->session->userdata('email');
    }

    /**
     * logged_in
     *
     * @return integer
     * @author jrmadsen67
     **/
    public function get_user_id()
    {
        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id))
        {
            return $user_id;
        }
        return null;
    }


    /**
     * is_admin
     *
     * @return bool
     * @author Ben Edmunds
     **/
    public function is_admin($id = false)
    {
        $admin_group = $this->config->item('admin_group', 'ion_auth');
        return $this->in_group($admin_group, $id);
    }

    /**
     * in_group
     *
     * @param mixed group(s) to check
     * @param bool user id
     * @param bool check if all groups is present, or any of the groups
     *
     * @return bool
     * @author Phil Sturgeon
     **/
    public function in_group($check_group, $id = false, $check_all = false)
    {
        $id || $id = $this->session->userdata('user_id');

        if (!is_array($check_group))
        {
            $check_group = array($check_group);
        }

        if (isset($this->_cache_user_in_group[$id]))
        {
            $groups_array = $this->_cache_user_in_group[$id];
        }
        else
        {
            $users_groups = $this->ion_auth_model->get_users_groups($id)->result();
            $groups_array = array();
            foreach ($users_groups as $group)
            {
                $groups_array[$group->id] = $group->name;
            }
            $this->_cache_user_in_group[$id] = $groups_array;
        }
        foreach ($check_group as $key => $value)
        {
            $groups = (is_string($value)) ? $groups_array : array_keys($groups_array);

            /**
             * if !all (default), in_array
             * if all, !in_array
             */
            if (in_array($value, $groups) xor $check_all)
            {
                /**
                 * if !all (default), true
                 * if all, false
                 */
                return !$check_all;
            }
        }

        /**
         * if !all (default), false
         * if all, true
         */
        return $check_all;
    }

}
