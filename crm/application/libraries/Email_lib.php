<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email_lib
{
    var $CI;

    // -------------------------------------------------------------------
    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->library('email');
        
        log_message('debug', "Email Library Initialized");
    }

    // -------------------------------------------------------------------
    // Receive database IDs of all users to send an email to.
    // Query the database for those users and only get ones that have notify_by
    // set to 'email' or 'both'.
    public function get_emails($IDs)
    {
        $IDs = implode(",", (array) $IDs);

        $query = $this->CI->db->query("SELECT DISTINCT email FROM users WHERE (notify_by = 'email' OR notify_by = 'both') AND id IN ($IDs)");

        $emails = array_column($query->result_array(), 'email');

        return $emails;
    }

    // -------------------------------------------------------------------
    public function send_email($emails, $subject, $view, $data)
    {
		//echo $eddddata= $emails."<br/>".$subject."<br/>".$this->CI->load->view($view, $data, TRUE)."<br/><pre>".print_r($data)."</pre>" ;
		$emails='amit@mark3.in';
       /* if ($this->CI->config->item('send_emails') == TRUE)
        {
            $this->CI->email->clear();
            $this->CI->email->from($this->CI->config->item('website_support_email'), $this->CI->config->item('website_title'));
            $this->CI->email->to($emails);
            $this->CI->email->subject($subject);
            $this->CI->email->message($this->CI->load->view($view, $data, TRUE));
            $this->CI->email->send();
        }*/
    }
}