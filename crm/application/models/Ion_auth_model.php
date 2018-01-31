<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Model
* Version: 2.5.2
* Author:  Ben Edmunds
*          ben.edmunds@gmail.com
*          @benedmunds
* Added Awesomeness: Phil Sturgeon
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
* Created:  10.01.2009
* Last Change: 3.22.13
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
* Requirements: PHP5 or above
*/

class Ion_auth_model extends CI_Model
{
    /**
     * Holds an array of tables used
     *
     * @var array
     **/
    public $tables = array();

    /**
     * activation code
     *
     * @var string
     **/
    public $activation_code;

    /**
     * forgotten password key
     *
     * @var string
     **/
    public $forgotten_password_code;

    /**
     * new password
     *
     * @var string
     **/
    public $new_password;

    /**
     * Where
     *
     * @var array
     **/
    public $_ion_where = array();

    /**
     * Select
     *
     * @var array
     **/
    public $_ion_select = array();

    /**
     * Like
     *
     * @var array
     **/
    public $_ion_like = array();

    /**
     * Limit
     *
     * @var string
     **/
    public $_ion_limit = NULL;

    /**
     * Offset
     *
     * @var string
     **/
    public $_ion_offset = NULL;

    /**
     * Order By
     *
     * @var string
     **/
    public $_ion_order_by = NULL;

    /**
     * Order
     *
     * @var string
     **/
    public $_ion_order = NULL;

    /**
     * Response
     *
     * @var string
     **/
    protected $response = NULL;

    /**
     * message (uses lang file)
     *
     * @var string
     **/
    protected $messages;

    /**
     * error message (uses lang file)
     *
     * @var string
     **/
    protected $errors;

    /**
     * error start delimiter
     *
     * @var string
     **/
    protected $error_start_delimiter;

    /**
     * error end delimiter
     *
     * @var string
     **/
    protected $error_end_delimiter;

    /**
     * caching of users and their groups
     *
     * @var array
     **/
    public $_cache_user_in_group = array();

    /**
     * caching of groups
     *
     * @var array
     **/
    protected $_cache_groups = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->config('ion_auth', TRUE);
        $this->load->language('ion_auth');

        // initialize db tables data
        $this->tables = $this->config->item('tables', 'ion_auth');

        // initialize data
        $this->store_salt   = $this->config->item('store_salt', 'ion_auth');
        $this->salt_length  = $this->config->item('salt_length', 'ion_auth');
        $this->join         = $this->config->item('join', 'ion_auth');

        // initialize hash method options (Bcrypt)
        $this->hash_method      = $this->config->item('hash_method', 'ion_auth');
        $this->default_rounds   = $this->config->item('default_rounds', 'ion_auth');
        $this->random_rounds    = $this->config->item('random_rounds', 'ion_auth');
        $this->min_rounds       = $this->config->item('min_rounds', 'ion_auth');
        $this->max_rounds       = $this->config->item('max_rounds', 'ion_auth');

        // initialize messages and error
        $this->messages    = array();
        $this->errors      = array();
        $delimiters_source = $this->config->item('delimiters_source', 'ion_auth');

        // load the error delimeters either from the config file or use what's been supplied to form validation
        if ($delimiters_source === 'form_validation')
        {
            // load in delimiters from form_validation
            // to keep this simple we'll load the value using reflection since these properties are protected
            $this->load->library('form_validation');
            $form_validation_class = new ReflectionClass("CI_Form_validation");

            $error_prefix = $form_validation_class->getProperty("_error_prefix");
            $error_prefix->setAccessible(TRUE);
            $this->error_start_delimiter = $error_prefix->getValue($this->form_validation);
            $this->message_start_delimiter = $this->error_start_delimiter;

            $error_suffix = $form_validation_class->getProperty("_error_suffix");
            $error_suffix->setAccessible(TRUE);
            $this->error_end_delimiter = $error_suffix->getValue($this->form_validation);
            $this->message_end_delimiter = $this->error_end_delimiter;
        }
        else
        {
            // use delimiters from config
            $this->message_start_delimiter = $this->config->item('message_start_delimiter', 'ion_auth');
            $this->message_end_delimiter   = $this->config->item('message_end_delimiter', 'ion_auth');
            $this->error_start_delimiter   = $this->config->item('error_start_delimiter', 'ion_auth');
            $this->error_end_delimiter     = $this->config->item('error_end_delimiter', 'ion_auth');
        }

        // load the bcrypt class if needed
        if ($this->hash_method == 'bcrypt')
        {
            if ($this->random_rounds)
            {
                $rand = rand($this->min_rounds, $this->max_rounds);
                $params = array('rounds' => $rand);
            }
            else
            {
                $params = array('rounds' => $this->default_rounds);
            }

            $params['salt_prefix'] = $this->config->item('salt_prefix', 'ion_auth');
            $this->load->library('bcrypt', $params);
        }
    }

    /**
     * Misc functions
     *
     * Hash password : Hashes the password to be stored in the database.
     * Hash password db : This function takes a password and validates it
     * against an entry in the users table.
     * Salt : Generates a random salt value.
     *
     * @author Mathew
     */

    /**
     * Hashes the password to be stored in the database.
     *
     * @return void
     * @author Mathew
     **/
    public function hash_password($password, $salt = false, $use_sha1_override = FALSE)
    {
        if (empty($password))
        {
            return FALSE;
        }

        // bcrypt
        if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
        {
            return $this->bcrypt->hash($password);
        }

        if ($this->store_salt && $salt)
        {
            return sha1($password . $salt);
        }
        else
        {
            $salt = $this->salt();
            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }

    /**
     * This function takes a password and validates it
     * against an entry in the users table.
     *
     * @return void
     * @author Mathew
     **/
    public function hash_password_db($id, $password, $use_sha1_override = FALSE)
    {
        if (empty($id) || empty($password))
        {
            return FALSE;
        }

        $query = $this->db->select('password, salt')
                          ->where('id', $id)
                          ->limit(1)
                          ->order_by('id', 'desc')
                          ->get($this->tables['users']);

        $hash_password_db = $query->row();

        if ($query->num_rows() !== 1)
        {
            return FALSE;
        }
		// bcrypt
        if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
        {
            if ($this->bcrypt->verify($password, $hash_password_db->password))
            {
                return TRUE;
            }

            return FALSE;
        }

        // sha1
        if ($this->store_salt)
        {
            $db_password = sha1($password . $hash_password_db->salt);
        }
        else
        {
            $salt = substr($hash_password_db->password, 0, $this->salt_length);

            $db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }

        return $db_password == $hash_password_db->password ? TRUE : FALSE;
    }

    /**
     * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
     *
     * @return void
     * @author Mathew
     **/
    public function hash_code($password)
    {
        return $this->hash_password($password, FALSE, TRUE);
    }

    /**
     * Generates a random salt value.
     *
     * Salt generation code taken from https://github.com/ircmaxell/password_compat/blob/master/lib/password.php
     *
     * @return void
     * @author Anthony Ferrera
     **/
    public function salt()
    {
        $raw_salt_len = 16;
        $buffer = '';
        $buffer_valid = false;

        if (function_exists('mcrypt_create_iv') && !defined('PHALANGER'))
        {
            $buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
            if ($buffer)
            {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes'))
        {
            $buffer = openssl_random_pseudo_bytes($raw_salt_len);
            if ($buffer)
            {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid && @is_readable('/dev/urandom'))
        {
            $f = fopen('/dev/urandom', 'r');
            $read = strlen($buffer);
            while ($read < $raw_salt_len)
            {
                $buffer .= fread($f, $raw_salt_len - $read);
                $read = strlen($buffer);
            }
            fclose($f);
            if ($read >= $raw_salt_len)
            {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid || strlen($buffer) < $raw_salt_len)
        {
            $bl = strlen($buffer);
            for ($i = 0; $i < $raw_salt_len; $i++)
            {
                if ($i < $bl)
                {
                    $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                }
                else
                {
                    $buffer .= chr(mt_rand(0, 255));
                }
            }
        }

        $salt = $buffer;

        // encode string with the Base64 variant used by crypt
        $base64_digits   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
        $bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $base64_string   = base64_encode($salt);
        $salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);
        $salt = substr($salt, 0, $this->salt_length);

        return $salt;
    }

    /**
     * Activation functions
     *
     * Activate : Validates and removes activation code.
     * Deactivae : Updates a users row with an activation code.
     *
     * @author Mathew
     */

    /**
     * activate
     *
     * @return void
     * @author Mathew
     **/
    public function activate($id, $code = FALSE)
    {
        if ($code !== FALSE)
        {
            $query = $this->db->select('id, company_id')
                              ->where('activation_code', $code)
                              ->where('id', $id)
                              ->limit(1)
                              ->order_by('id', 'desc')
                              ->get($this->tables['users']);

            $user = $query->row();

            if ($query->num_rows() !== 1)
            {
                $this->set_error('activate_unsuccessful');
                return FALSE;
            }

            $data = array(
                'activation_code' => NULL,
                'active'          => 1
            );
            $this->db->update($this->tables['users'], $data, array('id' => $id));

            $company_data = [
                'active' => 1
            ];
            $this->db->update('companies', $company_data, ['id' => $user->company_id]);
        }
        // else
        // {
        //     $data = array(
        //         'activation_code' => NULL,
        //         'active'          => 1
        //     );

        //     $this->db->update($this->tables['users'], $data, array('id' => $id));
        //     return FALSE;
        // }

        $return = $this->db->affected_rows() == 1;
        if ($return)
        {
            $this->set_message('activate_successful');
        }
        else
        {
            $this->set_error('activate_unsuccessful');
        }

        return $return;
    }

    /**
     * Deactivate
     *
     * @return void
     * @author Mathew
     **/
    public function deactivate($id = NULL)
    {
        if (!isset($id))
        {
            $this->set_error('deactivate_unsuccessful');
            return FALSE;
        }

        $this->activation_code = sha1(md5(microtime()));

        $data = array(
            'activation_code' => $this->activation_code,
            'active'          => 0
        );

        $this->db->update($this->tables['users'], $data, array('id' => $id));

        $return = $this->db->affected_rows() == 1;
        if ($return)
            $this->set_message('deactivate_successful');
        else
            $this->set_error('deactivate_unsuccessful');

        return $return;
    }

    public function clear_forgotten_password_code($code)
    {
        if (empty($code))
        {
            return FALSE;
        }

        $this->db->where('forgotten_password_code', $code);

        if ($this->db->count_all_results($this->tables['users']) > 0)
        {
            $data = array(
                'forgotten_password_code' => NULL,
                'forgotten_password_time' => NULL
            );

            $this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

            return TRUE;
        }

        return FALSE;
    }

    /**
     * reset password
     *
     * @return bool
     * @author Mathew
     **/
    public function reset_password($email, $new)
    {
        if (!$this->email_check($email))
        {
            return FALSE;
        }

        $query = $this->db->select('salt')
                          ->where('email', $email)
                          ->limit(1)
                          ->order_by('id', 'desc')
                          ->get($this->tables['users']);

        if ($query->num_rows() !== 1)
        {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

        $result = $query->row();

        $new = $this->hash_password($new, $result->salt);

        // store the new password and reset the remember code so all remembered instances have to re-login
        // also clear the forgotten password code
        $data = array(
            'password' => $new,
            'remember_code' => NULL,
            'forgotten_password_code' => NULL,
            'forgotten_password_time' => NULL,
        );

        $this->db->update($this->tables['users'], $data, array('email' => $email));

        $return = $this->db->affected_rows() == 1;
        if ($return)
        {
            $this->set_message('password_change_successful');
        }
        else
        {
            $this->set_error('password_change_unsuccessful');
        }

        return $return;
    }

    /**
     * change password
     *
     * @return bool
     * @author Mathew
     **/
    public function change_password($email, $old, $new)
    {
        $query = $this->db->select('id, password, salt')
                          ->where('email', $email)
                          ->limit(1)
                          ->order_by('id', 'desc')
                          ->get($this->tables['users']);

        if ($query->num_rows() !== 1)
        {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

        $user = $query->row();

        $old_password_matches = $this->hash_password_db($user->id, $old);

        if ($old_password_matches === TRUE)
        {
            // store the new password and reset the remember code so all remembered instances have to re-login
            $hashed_new_password = $this->hash_password($new, $user->salt);
            $data = array(
                'password' => $hashed_new_password,
                'remember_code' => NULL,
            );

            $successfully_changed_password_in_db = $this->db->update($this->tables['users'], $data, array('email' => $email));
            if ($successfully_changed_password_in_db)
            {
                $this->set_message('password_change_successful');
            }
            else
            {
                $this->set_error('password_change_unsuccessful');
            }

            return $successfully_changed_password_in_db;
        }

        $this->set_error('password_change_unsuccessful');
        return FALSE;
    }

    /**
     * Checks email
     *
     * @return bool
     * @author Mathew
     **/
    public function email_check($email = '')
    {
        if (empty($email))
        {
            return FALSE;
        }

        return $this->db->where('email', $email)
                        ->group_by("id")
                        ->order_by("id", "ASC")
                        ->limit(1)
                        ->count_all_results($this->tables['users']) > 0;
    }

    /**
     * Insert a forgotten password key.
     *
     * @return bool
     * @author Mathew
     * @updated Ryan
     * @updated 52aa456eef8b60ad6754b31fbdcc77bb
     **/
    public function forgotten_password($email)
    {
        if (empty($email))
        {
            return FALSE;
        }

        $activation_code_part = "";
        if (function_exists("openssl_random_pseudo_bytes"))
        {
            $activation_code_part = openssl_random_pseudo_bytes(128);
        }

        for ($i = 0; $i < 1024; $i++)
        {
            $activation_code_part = sha1($activation_code_part . mt_rand() . microtime());
        }

        $key = $this->hash_code($activation_code_part . $email);

        // If enable query strings is set, then we need to replace any unsafe characters so that the code can still work
        if ($key != '' && $this->config->item('permitted_uri_chars') != '' && $this->config->item('enable_query_strings') == FALSE)
        {
            // preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
            // compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
            if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($this->config->item('permitted_uri_chars'), '-'))."]+$|i", $key))
            {
                $key = preg_replace("/[^".$this->config->item('permitted_uri_chars')."]+/i", "-", $key);
            }
        }

        $this->forgotten_password_code = $key;

        $data = array(
            'forgotten_password_code' => $key,
            'forgotten_password_time' => time()
        );

        $this->db->update($this->tables['users'], $data, array('email' => $email));

        return $this->db->affected_rows() == 1;
    }

    /**
     * Forgotten Password Complete
     *
     * @return string
     * @author Mathew
     **/
    public function forgotten_password_complete($code, $salt = FALSE)
    {
        if (empty($code))
        {
            return FALSE;
        }

        $user = $this->where('forgotten_password_code', $code)->users()->row();

        if ($user)
        {
            if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0)
            {
                // Make sure it isn't expired
                $expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
                if (time() - $user->forgotten_password_time > $expiration)
                {
                    $this->set_error('forgot_password_expired');
                    return FALSE;
                }
            }

            $password = $this->salt();

            $data = array(
                'password'                => $this->hash_password($password, $salt),
                'forgotten_password_code' => NULL,
                'active'                  => 1,
             );

            $this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

            return $password;
        }

        return FALSE;
    }

    /**
     * register
     *
     * @return bool
     * @author Mathew
     **/
    public function register($email, $password, $company_id, $groups = array())
    {
        $manual_activation = $this->config->item('manual_activation', 'ion_auth');

        if ($this->email_check($email))
        {
            $this->set_error('account_creation_duplicate_email');
            return FALSE;
        }
        elseif ( !$this->config->item('default_group', 'ion_auth') && empty($groups) )
        {
            $this->set_error('account_creation_missing_default_group');
            return FALSE;
        }

        // check if the default set in config exists in database
        $query = $this->db->get_where($this->tables['groups'], array('name' => $this->config->item('default_group', 'ion_auth')), 1)->row();
        if ( !isset($query->id) && empty($groups) )
        {
            $this->set_error('account_creation_invalid_default_group');
            return FALSE;
        }

        $default_group = $query;
        $ip_address = $this->input->ip_address();
        $salt       = $this->store_salt ? $this->salt() : FALSE;
        $password   = $this->hash_password($password, $salt);

        $data = array(
            'email'      => $email,
            'password'   => $password,
            'company_id' => $company_id,
            'is_company_admin' => 1,
            'ip_address' => $ip_address,
            'active'     => ($manual_activation === false ? 1 : 0),
            'created_at' => $this->make_timestamp(),
            'updated_at' => $this->make_timestamp(),
            'deleted_at' => $this->make_timestamp()
        );

        if ($this->store_salt)
        {
            $data['salt'] = $salt;
        }

        $this->db->insert($this->tables['users'], $data);

        $id = $this->db->insert_id();

        // add in groups array if it doesn't exits and stop adding into default group if default group ids are set
        if ( isset($default_group->id) && empty($groups) )
        {
            $groups[] = $default_group->id;
        }

        if (!empty($groups))
        {
            // add to groups
            foreach ($groups as $group)
            {
                $this->add_to_group($group, $id);
            }
        }

        return (isset($id)) ? $id : FALSE;
    }

    /**
     * login
     *
     * @return bool
     * @author Mathew
     **/
    public function login($email, $password, $remember = FALSE)
    {
        if (empty($email) || empty($password))
        {
            $this->set_error('login_unsuccessful');
            return FALSE;
        }
		
        $query = $this->db->select('id, company_id, is_company_admin, is_dep_manager, password, email, last_login, active')
                          ->where('email', $email)
                          ->limit(1)
                          ->order_by('id', 'desc')
                          ->get($this->tables['users']);
		//echo $sql = $this->db->last_query();die;
        if ($this->is_time_locked_out($email))
        {
            $this->set_error('login_timeout');
            return FALSE;
        }
		
        if ($query->num_rows() === 1)
        {
            $user = $query->row();
            $password = $this->hash_password_db($user->id, $password);
			
            if ($password === TRUE)
            {
                if ($user->active == 0)
                {
                    $this->set_error('login_unsuccessful_not_active');
                    return FALSE;
                }
					
                $this->set_session($user);
                $this->update_last_login($user->id);
                $this->clear_login_attempts($email);
				
                if ($remember && $this->config->item('remember_users', 'ion_auth'))
                {
                    $this->remember_user($user->id);
                }

                $this->set_message('login_successful');
                return TRUE;
            }
        }

        $this->increase_login_attempts($email);
        $this->set_error('login_unsuccessful');
        return FALSE;
    }

    /**
     * is_max_login_attempts_exceeded
     * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
     *
     * @param string $email
     * @return boolean
     **/
    public function is_max_login_attempts_exceeded($email)
    {
        if ($this->config->item('track_login_attempts', 'ion_auth'))
        {
            $max_attempts = $this->config->item('maximum_login_attempts', 'ion_auth');
            if ($max_attempts > 0)
            {
                $attempts = $this->get_attempts_num($email);
                return $attempts >= $max_attempts;
            }
        }
        return FALSE;
    }

    /**
     * Get number of attempts to login occured from given IP-address or email
     * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
     *
     * @param   string $email
     * @return  int
     */
    function get_attempts_num($email)
    {
        if ($this->config->item('track_login_attempts', 'ion_auth'))
        {
            $ip_address = $this->input->ip_address();
            $this->db->select('1', FALSE);
            if ($this->config->item('track_login_ip_address', 'ion_auth'))
            {
                $this->db->where('ip_address', $ip_address);
                $this->db->where('login', $email);
            }
            elseif (strlen($email) > 0) $this->db->or_where('login', $email);
            $query = $this->db->get($this->tables['login_attempts']);
            return $query->num_rows();
        }
        return 0;
    }

    /**
     * Get a boolean to determine if an account should be locked out due to
     * exceeded login attempts within a given period
     *
     * @return  boolean
     */
    public function is_time_locked_out($email)
    {
        return $this->is_max_login_attempts_exceeded($email) && $this->get_last_attempt_time($email) > time() - $this->config->item('lockout_time', 'ion_auth');
    }

    /**
     * Get the time of the last time a login attempt occured from given IP-address or email
     *
     * @param   string $email
     * @return  int
     */
    public function get_last_attempt_time($email)
    {
        if ($this->config->item('track_login_attempts', 'ion_auth'))
        {
            $ip_address = $this->input->ip_address();
            $this->db->select_max('time');
            if ($this->config->item('track_login_ip_address', 'ion_auth')) $this->db->where('ip_address', $ip_address);
            elseif (strlen($email) > 0) $this->db->or_where('login', $email);
            $query = $this->db->get($this->tables['login_attempts'], 1);
            if ($query->num_rows() > 0)
            {
                return $query->row()->time;
            }
        }

        return 0;
    }

    /**
     * increase_login_attempts
     * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
     *
     * @param string $email
     **/
    public function increase_login_attempts($email)
    {
        if ($this->config->item('track_login_attempts', 'ion_auth'))
        {
            $ip_address = $this->input->ip_address();
            return $this->db->insert($this->tables['login_attempts'], array('ip_address' => $ip_address, 'login' => $email, 'time' => time()));
        }
        return FALSE;
    }

    /**
     * clear_login_attempts
     * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
     *
     * @param string $email
     **/
    public function clear_login_attempts($email, $expire_period = 86400)
    {
        if ($this->config->item('track_login_attempts', 'ion_auth'))
        {
            $ip_address = $this->input->ip_address();
            $this->db->where(array('ip_address' => $ip_address, 'login' => $email));
            // Purge obsolete login attempts
            $this->db->or_where('time <', time() - $expire_period, FALSE);
            return $this->db->delete($this->tables['login_attempts']);
        }
        return FALSE;
    }

    public function limit($limit)
    {
        $this->_ion_limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->_ion_offset = $offset;
        return $this;
    }

    public function where($where, $value = NULL)
    {
        if (!is_array($where))
        {
            $where = array($where => $value);
        }

        array_push($this->_ion_where, $where);

        return $this;
    }

    public function like($like, $value = NULL, $position = 'both')
    {
        if (!is_array($like))
        {
            $like = array($like => array(
                'value'    => $value,
                'position' => $position,
            ));
        }

        array_push($this->_ion_like, $like);

        return $this;
    }

    public function select($select)
    {
        $this->_ion_select[] = $select;
        return $this;
    }

    public function order_by($by, $order = 'desc')
    {
        $this->_ion_order_by = $by;
        $this->_ion_order    = $order;
        return $this;
    }

    public function row()
    {
        $row = $this->response->row();
        return $row;
    }

    public function row_array()
    {
        $row = $this->response->row_array();
        return $row;
    }

    public function result()
    {
        $result = $this->response->result();
        return $result;
    }

    public function result_array()
    {
        $result = $this->response->result_array();
        return $result;
    }

    public function num_rows()
    {
        $result = $this->response->num_rows();
        return $result;
    }

    /**
     * users
     *
     * @return object Users
     * @author Ben Edmunds
     **/
    public function users($groups = NULL)
    {
        if (isset($this->_ion_select) && !empty($this->_ion_select))
        {
            foreach ($this->_ion_select as $select)
            {
                $this->db->select($select);
            }

            $this->_ion_select = array();
        }
        else
        {
            // default selects
            $this->db->select(array(
                $this->tables['users'].'.*',
                $this->tables['users'].'.id as id',
                $this->tables['users'].'.id as user_id'
            ));
        }

        // filter by group id(s) if passed
        if (isset($groups))
        {
            // build an array if only one group was passed
            if (!is_array($groups))
            {
                $groups = Array($groups);
            }

            // join and then run a where_in against the group ids
            if (isset($groups) && !empty($groups))
            {
                $this->db->distinct();
                $this->db->join(
                    $this->tables['users_groups'],
                    $this->tables['users_groups'].'.'.$this->join['users'].'='.$this->tables['users'].'.id', 'inner'
                );
            }

            // verify if group name or group id was used and create and put elements in different arrays
            $group_ids = array();
            $group_names = array();
            foreach ($groups as $group)
            {
                if (is_numeric($group)) $group_ids[] = $group;
                else $group_names[] = $group;
            }
            $or_where_in = (!empty($group_ids) && !empty($group_names)) ? 'or_where_in' : 'where_in';
            // if group name was used we do one more join with groups
            if (!empty($group_names))
            {
                $this->db->join($this->tables['groups'], $this->tables['users_groups'] . '.' . $this->join['groups'] . ' = ' . $this->tables['groups'] . '.id', 'inner');
                $this->db->where_in($this->tables['groups'] . '.name', $group_names);
            }
            if (!empty($group_ids))
            {
                $this->db->{$or_where_in}($this->tables['users_groups'].'.'.$this->join['groups'], $group_ids);
            }
        }

        // run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where))
        {
            foreach ($this->_ion_where as $where)
            {
                $this->db->where($where);
            }

            $this->_ion_where = array();
        }

        if (isset($this->_ion_like) && !empty($this->_ion_like))
        {
            foreach ($this->_ion_like as $like)
            {
                $this->db->or_like($like);
            }

            $this->_ion_like = array();
        }

        if (isset($this->_ion_limit) && isset($this->_ion_offset))
        {
            $this->db->limit($this->_ion_limit, $this->_ion_offset);

            $this->_ion_limit  = NULL;
            $this->_ion_offset = NULL;
        }
        else if (isset($this->_ion_limit))
        {
            $this->db->limit($this->_ion_limit);

            $this->_ion_limit  = NULL;
        }

        // set the order
        if (isset($this->_ion_order_by) && isset($this->_ion_order))
        {
            $this->db->order_by($this->_ion_order_by, $this->_ion_order);

            $this->_ion_order    = NULL;
            $this->_ion_order_by = NULL;
        }

        $this->response = $this->db->get($this->tables['users']);

        return $this;
    }

    /**
     * user
     *
     * @return object
     * @author Ben Edmunds
     **/
    public function user($id = NULL)
    {
        // if no id was passed use the current users id
        $id = isset($id) ? $id : $this->session->userdata('user_id');

        $this->limit(1);
        $this->order_by($this->tables['users'].'.id', 'desc');
        $this->where($this->tables['users'].'.id', $id);

        $this->users();

        return $this;
    }

    /**
     * get_users_groups
     *
     * @return array
     * @author Ben Edmunds
     **/
    public function get_users_groups($id = FALSE)
    {
        // if no id was passed use the current users id
        $id || $id = $this->session->userdata('user_id');

        return $this->db->select($this->tables['users_groups'].'.'.$this->join['groups'].' as id, '.$this->tables['groups'].'.name, '.$this->tables['groups'].'.description')
                        ->where($this->tables['users_groups'].'.'.$this->join['users'], $id)
                        ->join($this->tables['groups'], $this->tables['users_groups'].'.'.$this->join['groups'].'='.$this->tables['groups'].'.id')
                        ->get($this->tables['users_groups']);
    }

    /**
     * add_to_group
     *
     * @return bool
     * @author Ben Edmunds
     **/
    public function add_to_group($group_ids, $user_id = false)
    {
        // if no id was passed use the current users id
        $user_id || $user_id = $this->session->userdata('user_id');

        if (!is_array($group_ids))
        {
            $group_ids = array($group_ids);
        }

        $return = 0;

        // Then insert each into the database
        foreach ($group_ids as $group_id)
        {
            if ($this->db->insert($this->tables['users_groups'], array( $this->join['groups'] => (float) $group_id, $this->join['users'] => (float) $user_id)))
            {
                if (isset($this->_cache_groups[$group_id]))
                {
                    $group_name = $this->_cache_groups[$group_id];
                }
                else
                {
                    $group = $this->group($group_id)->result();
                    $group_name = $group[0]->name;
                    $this->_cache_groups[$group_id] = $group_name;
                }
                $this->_cache_user_in_group[$user_id][$group_id] = $group_name;

                // Return the number of groups added
                $return += 1;
            }
        }

        return $return;
    }

    /**
     * remove_from_group
     *
     * @return bool
     * @author Ben Edmunds
     **/
    public function remove_from_group($group_ids = false, $user_id = false)
    {
        // user id is required
        if (empty($user_id))
        {
            return FALSE;
        }

        // if group id(s) are passed remove user from the group(s)
        if (!empty($group_ids))
        {
            if (!is_array($group_ids))
            {
                $group_ids = array($group_ids);
            }

            foreach ($group_ids as $group_id)
            {
                $this->db->delete($this->tables['users_groups'], array($this->join['groups'] => (float)$group_id, $this->join['users'] => (float)$user_id));
                if (isset($this->_cache_user_in_group[$user_id]) && isset($this->_cache_user_in_group[$user_id][$group_id]))
                {
                    unset($this->_cache_user_in_group[$user_id][$group_id]);
                }
            }

            $return = TRUE;
        }
        // otherwise remove user from all groups
        else
        {
            if ($return = $this->db->delete($this->tables['users_groups'], array($this->join['users'] => (float)$user_id)))
            {
                $this->_cache_user_in_group[$user_id] = array();
            }
        }
        return $return;
    }

    /**
     * groups
     *
     * @return object
     * @author Ben Edmunds
     **/
    public function groups()
    {
        // run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where))
        {
            foreach ($this->_ion_where as $where)
            {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }

        if (isset($this->_ion_limit) && isset($this->_ion_offset))
        {
            $this->db->limit($this->_ion_limit, $this->_ion_offset);

            $this->_ion_limit  = NULL;
            $this->_ion_offset = NULL;
        }
        else if (isset($this->_ion_limit))
        {
            $this->db->limit($this->_ion_limit);

            $this->_ion_limit  = NULL;
        }

        // set the order
        if (isset($this->_ion_order_by) && isset($this->_ion_order))
        {
            $this->db->order_by($this->_ion_order_by, $this->_ion_order);
        }

        $this->response = $this->db->get($this->tables['groups']);

        return $this;
    }

    /**
     * group
     *
     * @return object
     * @author Ben Edmunds
     **/
    public function group($id = NULL)
    {
        if (isset($id))
        {
            $this->where($this->tables['groups'].'.id', $id);
        }

        $this->limit(1);
        $this->order_by('id', 'desc');

        return $this->groups();
    }

    /**
     * update
     *
     * @return bool
     * @author Phil Sturgeon
     **/
    public function update($id, array $data)
    {
        $user = $this->user($id)->row();

        $this->db->trans_begin();

        if (array_key_exists('email', $data) && $this->email_check($data['email']) && $user->email !== $data['email'])
        {
            $this->db->trans_rollback();
            $this->set_error('account_creation_duplicate_email');
            $this->set_error('update_unsuccessful');

            return FALSE;
        }

        // Filter the data passed
        $data = $this->_filter_data($this->tables['users'], $data);

        if (array_key_exists('email', $data) || array_key_exists('password', $data))
        {
            if (array_key_exists('password', $data))
            {
                if( ! empty($data['password']))
                {
                    $data['password'] = $this->hash_password($data['password'], $user->salt);
                }
                else
                {
                    // unset password so it doesn't effect database entry if no password passed
                    unset($data['password']);
                }
            }
        }

        $this->db->update($this->tables['users'], $data, array('id' => $user->id));

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->set_error('update_unsuccessful');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->set_message('update_successful');
        return TRUE;
    }

    /**
    * delete_user
    *
    * @return bool
    * @author Phil Sturgeon
    **/
    public function delete_user($id)
    {
        $this->db->trans_begin();

        // remove user from groups
        $this->remove_from_group(NULL, $id);

        // delete user from users table should be placed after remove from group
        $this->db->delete($this->tables['users'], array('id' => $id));

        // if user does not exist in database then it returns FALSE else removes the user from groups
        if ($this->db->affected_rows() == 0)
        {
            return FALSE;
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->set_error('delete_unsuccessful');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->set_message('delete_successful');
        return TRUE;
    }

    /**
     * update_last_login
     *
     * @return bool
     * @author Ben Edmunds
     **/
    public function update_last_login($id)
    {
        $this->db->update($this->tables['users'], array('last_login' => time()), array('id' => $id));
        return $this->db->affected_rows() == 1;
    }

    /**
     * set_lang
     *
     * @return bool
     * @author Ben Edmunds
     **/
    public function set_lang($lang = 'en')
    {
        // if the user_expire is set to zero we'll set the expiration two years from now.
        if ($this->config->item('user_expire', 'ion_auth') === 0)
        {
            $expire = (60*60*24*365*2);
        }
        else // otherwise use what is set
        {
            $expire = $this->config->item('user_expire', 'ion_auth');
        }

        set_cookie(array(
            'name'   => 'lang_code',
            'value'  => $lang,
            'expire' => $expire
        ));

        return TRUE;
    }

    /**
     * set_session
     *
     * @return bool
     * @author jrmadsen67
     **/
    public function set_session($user)
    {
		$user_id = $user->id;
		$sessionid = md5(session_id().time().$user_id);
		
        $session_data = array(
            'user_id'           => $user->id,
            'company_id'        => $user->company_id,
            'email'             => $user->email,
            'is_company_admin' => $user->is_company_admin,
            'is_dep_manager'    => $user->is_dep_manager,
            'old_last_login'    => $user->last_login,
			'sessionid' => $sessionid
        );
		
        $this->session->set_userdata($session_data);
		$this->_set_users_ci_logins_log($session_data);
			

        return TRUE;
    }

    /**
     * remember_user
     *
     * @return bool
     * @author Ben Edmunds
     **/
    public function remember_user($id)
    {
        if (!$id)
        {
            return FALSE;
        }

        $user = $this->user($id)->row();

        $salt = $this->salt();

        $this->db->update($this->tables['users'], array('remember_code' => $salt), array('id' => $id));

        if ($this->db->affected_rows() > -1)
        {
            // if the user_expire is set to zero we'll set the expiration two years from now.
            if ($this->config->item('user_expire', 'ion_auth') === 0)
            {
                $expire = (60*60*24*365*2);
            }
            // otherwise use what is set
            else
            {
                $expire = $this->config->item('user_expire', 'ion_auth');
            }

            set_cookie(array(
                'name'   => $this->config->item('email_cookie_name', 'ion_auth'),
                'value'  => $user->email,
                'expire' => $expire
            ));

            set_cookie(array(
                'name'   => $this->config->item('remember_cookie_name', 'ion_auth'),
                'value'  => $salt,
                'expire' => $expire
            ));

            return TRUE;
        }

        return FALSE;
    }

    /**
     * login_remembed_user
     *
     * @return bool
     * @author Ben Edmunds
     **/
    public function login_remembered_user()
    {
        // check for valid data
        if (!get_cookie($this->config->item('email_cookie_name', 'ion_auth'))
            || !get_cookie($this->config->item('remember_cookie_name', 'ion_auth'))
            || !$this->email_check(get_cookie($this->config->item('email_cookie_name', 'ion_auth'))))
        {
            return FALSE;
        }

        // get the user
        $query = $this->db->select('email, id, email, last_login')
                          ->where('email', get_cookie($this->config->item('email_cookie_name', 'ion_auth')))
                          ->where('remember_code', get_cookie($this->config->item('remember_cookie_name', 'ion_auth')))
                          ->limit(1)
                          ->order_by('id', 'desc')
                          ->get($this->tables['users']);

        // if the user was found, sign them in
        if ($query->num_rows() == 1)
        {
            $user = $query->row();

            $this->update_last_login($user->id);

            $this->set_session($user);

            // extend the users cookies if the option is enabled
            if ($this->config->item('user_extend_on_login', 'ion_auth'))
            {
                $this->remember_user($user->id);
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * create_group
     *
     * @author aditya menon
    */
    public function create_group($group_name = FALSE, $group_description = '', $additional_data = array())
    {
        // bail if the group name was not passed
        if (!$group_name)
        {
            $this->set_error('group_name_required');
            return FALSE;
        }

        // bail if the group name already exists
        $existing_group = $this->db->get_where($this->tables['groups'], array('name' => $group_name))->num_rows();
        if ($existing_group !== 0)
        {
            $this->set_error('group_already_exists');
            return FALSE;
        }

        $data = array('name' => $group_name,'description' => $group_description);

        // filter out any data passed that doesnt have a matching column in the groups table
        // and merge the set group data and the additional data
        if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);

        // insert the new group
        $this->db->insert($this->tables['groups'], $data);
        $group_id = $this->db->insert_id();

        // report success
        $this->set_message('group_creation_successful');
        // return the brand new group id
        return $group_id;
    }

    /**
     * update_group
     *
     * @return bool
     * @author aditya menon
     **/
    public function update_group($group_id = FALSE, $group_name = FALSE, $additional_data = array())
    {
        if (empty($group_id)) return FALSE;

        $data = array();

        if (!empty($group_name))
        {
            // we are changing the name, so do some checks

            // bail if the group name already exists
            $existing_group = $this->db->get_where($this->tables['groups'], array('name' => $group_name))->row();
            if (isset($existing_group->id) && $existing_group->id != $group_id)
            {
                $this->set_error('group_already_exists');
                return FALSE;
            }

            $data['name'] = $group_name;
        }

        // restrict change of name of the admin group
        $group = $this->db->get_where($this->tables['groups'], array('id' => $group_id))->row();
        if ($this->config->item('admin_group', 'ion_auth') === $group->name && $group_name !== $group->name)
        {
            $this->set_error('group_name_admin_not_alter');
            return FALSE;
        }

        // IMPORTANT!! Third parameter was string type $description; this following code is to maintain backward compatibility
        // New projects should work with 3rd param as array
        if (is_string($additional_data)) $additional_data = array('description' => $additional_data);

        // filter out any data passed that doesnt have a matching column in the groups table
        // and merge the set group data and the additional data
        if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);

        $this->db->update($this->tables['groups'], $data, array('id' => $group_id));
        $this->set_message('group_update_successful');
        return TRUE;
    }

    /**
    * delete_group
    *
    * @return bool
    * @author aditya menon
    **/
    public function delete_group($group_id = FALSE)
    {
        // bail if mandatory param not set
        if (!$group_id || empty($group_id))
        {
            return FALSE;
        }
        $group = $this->group($group_id)->row();
        if ($group->name == $this->config->item('admin_group', 'ion_auth'))
        {
            $this->set_error('group_delete_notallowed');
            return FALSE;
        }

        $this->db->trans_begin();

        // remove all users from this group
        $this->db->delete($this->tables['users_groups'], array($this->join['groups'] => $group_id));
        // remove the group itself
        $this->db->delete($this->tables['groups'], array('id' => $group_id));

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $this->set_error('group_delete_unsuccessful');
            return FALSE;
        }

        $this->db->trans_commit();
        $this->set_message('group_delete_successful');
        return TRUE;
    }

    /**
     * Set the message delimiters
     *
     * @return void
     * @author Ben Edmunds
     **/
    public function set_message_delimiters($start_delimiter, $end_delimiter)
    {
        $this->message_start_delimiter = $start_delimiter;
        $this->message_end_delimiter   = $end_delimiter;
        return TRUE;
    }

    /**
     * Set the error delimiters
     *
     * @return void
     * @author Ben Edmunds
     **/
    public function set_error_delimiters($start_delimiter, $end_delimiter)
    {
        $this->error_start_delimiter = $start_delimiter;
        $this->error_end_delimiter   = $end_delimiter;
        return TRUE;
    }

    /**
     * Set a message
     *
     * @return void
     * @author Ben Edmunds
     **/
    public function set_message($message)
    {
        $this->messages[] = $message;
        return $message;
    }

    /**
     * Get the messages
     *
     * @return void
     * @author Ben Edmunds
     **/
    public function messages()
    {
        $_output = '';
        foreach ($this->messages as $message)
        {
            $messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
            // $_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
            $_output .= $messageLang;
        }

        return $_output;
    }

    /**
     * Get the messages as an array
     *
     * @return array
     * @author Raul Baldner Junior
     **/
    public function messages_array($langify = TRUE)
    {
        if ($langify)
        {
            $_output = array();
            foreach ($this->messages as $message)
            {
                $messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
                // $_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
                $_output[] = $messageLang;
            }
            return $_output;
        }
        else
        {
            return $this->messages;
        }
    }

    /**
     * Clear messages
     *
     * @return void
     * @author Ben Edmunds
     **/
    public function clear_messages()
    {
        $this->messages = array();
        return TRUE;
    }

    /**
     * Set an error message
     *
     * @return void
     * @author Ben Edmunds
     **/
    public function set_error($error)
    {
        $this->errors[] = $error;
        return $error;
    }

    /**
     * Get the error message
     *
     * @return void
     * @author Ben Edmunds
     **/
    public function errors()
    {
        $_output = '';
        foreach ($this->errors as $error)
        {
            $errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
            // $_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
            $_output .= $errorLang;
        }
        return $_output;
    }

    /**
     * Get the error messages as an array
     *
     * @return array
     * @author Raul Baldner Junior
     **/
    public function errors_array($langify = TRUE)
    {
        if ($langify)
        {
            $_output = array();
            foreach ($this->errors as $error)
            {
                $errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
                // $_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
                $_output[] = $errorLang;
            }
            return $_output;
        }
        else
        {
            return $this->errors;
        }
    }

    /**
     * Clear Errors
     *
     * @return void
     * @author Ben Edmunds
     **/
    public function clear_errors()
    {
        $this->errors = array();
        return TRUE;
    }

    protected function _filter_data($table, $data)
    {
        $filtered_data = array();
        $columns = $this->db->list_fields($table);

        if (is_array($data))
        {
            foreach ($columns as $column)
            {
                if (array_key_exists($column, $data))
                    $filtered_data[$column] = $data[$column];
            }
        }

        return $filtered_data;
    }

    public function make_timestamp()
    {
        return date("Y-m-d H:i:s", time());
    }
	// Start V20180119
	private function _set_users_ci_logins_log($session_data){
			$browser = $this->agent->browser();
			$version = $this->agent->version();
			$platform = $this->agent->platform();
			$mobile = $this->agent->mobile();
			$robot = $this->agent->robot();
			
			$users_id = $session_data['user_id'];
			$sessionid = $session_data['sessionid'];			
			$login_user_data = array(
				'users_id'  	=> $users_id,
				'sessionid'  	=> $sessionid,
				'loginDateTime' 	=> date('Y-m-d H:i:s'),	
				'browser' 	=> $browser,
				'browserversion' 	=> $version,
				'platform' 	=> $platform,
				'robot' 	=> $robot,
				'ip' 	=> $_SERVER['REMOTE_ADDR'] ,
				'full_user_agent_string' 	=> $_SERVER['HTTP_USER_AGENT']
			);
			//print'<pre>';print_r($this->db);print'</pre>';die;
			$this->db->insert('users_logins_log', $login_user_data);
			//echo $sql = $this->db->last_query();die;
	}
}
