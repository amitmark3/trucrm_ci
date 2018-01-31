<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Price_plan_model extends MY_Model
{

    public $table = 'price_plans';
    public $primary_key = 'id';

    public function __construct()
    {
        $this->return_as = 'array';
        parent::__construct();
    }

}
 
/* End of file Price_plan_model.php */
/* Location: ./application/models/Price_plan_model.php */
