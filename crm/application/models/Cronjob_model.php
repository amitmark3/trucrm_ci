<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Cronjob_model extends MY_Model
{

    public $table = 'cronjobs';
    public $primary_key = 'id';

    public function __construct()
    {
        $this->timestamps = FALSE;
        $this->return_as = 'array';
        parent::__construct();
    }

}
 
/* End of file Cronjob_model.php */
/* Location: ./application/models/Cronjob_model.php */