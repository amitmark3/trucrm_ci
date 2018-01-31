<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Company_model extends MY_Model
{
    public $table = 'companies';
    public $primary_key = 'id';

    public function __construct()
    {
        $this->has_one['price_plan'] = [
            'foreign_model'     => 'price_plan_model',
            'foreign_table'     => 'price_plans',
            'foreign_key'       => 'id',
            'local_key'         => 'price_plan_id',
        ];
        $this->has_many['users'] = [
            'foreign_model'     => 'user_model',
            'foreign_table'     => 'users',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->has_many['departments'] = [
            'foreign_model'     => 'department_model',
            'foreign_table'     => 'departments',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->has_many['courses'] = [
            'foreign_model'     => 'course_model',
            'foreign_table'     => 'courses',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->has_many['accidents'] = [
            'foreign_model'     => 'accident_model',
            'foreign_table'     => 'accidents',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->has_many['early_reports'] = [
            'foreign_model'     => 'early_report_model',
            'foreign_table'     => 'early_reports',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->has_many['meetings'] = [
            'foreign_model'     => 'meeting_model',
            'foreign_table'     => 'meetings',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->has_many['risk_assessments'] = [
            'foreign_model'     => 'risk_assessment_model',
            'foreign_table'     => 'risk_assessments',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->has_many['safety_walks'] = [
            'foreign_model'     => 'safety_walk_model',
            'foreign_table'     => 'safety_walks',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->has_many['training'] = [
            'foreign_model'     => 'training_model',
            'foreign_table'     => 'training',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->has_many['training_required'] = [
            'foreign_model'     => 'training_required_model',
            'foreign_table'     => 'training_required',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->has_many['payments'] = [
            'foreign_model'     => 'payment_model',
            'foreign_table'     => 'payments',
            'foreign_key'       => 'company_id',
            'local_key'         => 'id',
        ];
        $this->return_as = 'array';
        parent::__construct();
    }
}
 
/* End of file Company_model.php */
/* Location: ./application/models/Company_model.php */