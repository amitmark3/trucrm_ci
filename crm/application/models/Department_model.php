<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Department_model extends MY_Model
{
    public $table = 'departments';
    public $primary_key = 'id';
    public $protected = ['id'];

    public function __construct()
    {
        $this->has_one['company'] = [
            'foreign_model' => 'company_model',
            'foreign_table' => 'companies',
            'foreign_key'   => 'id',
            'local_key'     => 'company_id',
        ];
        $this->has_many_pivot['courses'] = [
            'foreign_model'     => 'Course_model',
            'pivot_table'       => 'courses_departments',
            'local_key'         => 'id',
            'pivot_local_key'   => 'department_id',
            'foreign_key'       => 'id',
            'pivot_foreign_key' => 'course_id',
            'get_relate'        => FALSE
        ];
        $this->return_as = 'array';
        parent::__construct();
    }
}

/* End of file Department_model.php */
/* Location: ./application/models/Department_model.php */