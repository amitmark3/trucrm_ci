<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_lib
{

    private $CI;

    // -------------------------------------------------------------------
    public function __construct()
    {
        $this->CI =& get_instance();

        log_message('debug', "Report Library Initialized");
    }

    // -------------------------------------------------------------------
    public function custom_query($query)
    {
        $result = $this->CI->db->query($query);

        if ($result)
        {
            return $result->result_array();
        }
        else
        {
            return FALSE;
        }
    }

    // -------------------------------------------------------------------
    public function count_accidents($for = 'company', $id = NULL, $severity = FALSE, $ra_required = FALSE, $hsa = FALSE, $query = NULL)
    {
        $this->CI->load->model('accident_model');

        switch ($for)
        {
            case 'user':
                $this->CI->accident_model->where('user_id', $id);
                break;
            case 'department':
                $this->CI->accident_model->where('department_id', $id);
                break;
            default:
                $this->CI->accident_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        if ($severity !== FALSE)
        {
            $this->CI->accident_model->where('severity', $severity);
        }

        if ($ra_required !== FALSE)
        {
            switch ($ra_required)
            {
                case 'yes':
                    $this->CI->accident_model->where('risk_assessment_required', 1);
                    break;
                case 'no':
                    $this->CI->accident_model->where('risk_assessment_required', 0);
                    break;
            }
        }

        if ($hsa !== FALSE)
        {
            switch ($hsa)
            {
                case 'yes':
                    $this->CI->accident_model->where('escalate_to_hsa', 1);
                    break;
                case 'no':
                    $this->CI->accident_model->where('escalate_to_hsa', 0);
                    break;
            }
        }

        if ($query !== NULL)
        {
            return $this->CI->accident_model
                            ->where($query,NULL,NULL,FALSE,FALSE,TRUE)
                            ->where('company_id', $this->CI->user->company_id)
                            ->count_rows();
        }
        else
        {
            return $this->CI->accident_model->count_rows();
        }
    }

    // -------------------------------------------------------------------
    /*
    public function get_accidents($for = 'company', $id = NULL, $severity = FALSE, $ra_required = FALSE, $hsa = FALSE, $sort_by = 'date', $sort_order = 'desc', $limit = 5, $offset = 0)
    {
        $this->CI->load->model('accident_model');

        switch ($for)
        {
            case 'user':
                $this->CI->accident_model->where('user_id', $id);
                break;
            case 'department':
                $this->CI->accident_model->where('department_id', $id);
                break;
            default:
                $this->CI->accident_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        if ($severity !== FALSE)
        {
            $this->CI->accident_model->where('severity', $severity);
        }

        if ($ra_required !== FALSE)
        {
            switch ($ra_required)
            {
                case 'yes':
                    $this->CI->accident_model->where('risk_assessment_required', 1);
                    break;
                case 'no':
                    $this->CI->accident_model->where('risk_assessment_required', 0);
                    break;
            }
        }

        if ($hsa !== FALSE)
        {
            switch ($hsa)
            {
                case 'yes':
                    $this->CI->accident_model->where('escalate_to_hsa', 1);
                    break;
                case 'no':
                    $this->CI->accident_model->where('escalate_to_hsa', 0);
                    break;
            }
        }

        return $records = $this->CI->accident_model
                                   // ->where($where)
                                   ->with_profile('fields:first_name, last_name')
                                   ->with_assignee('fields:first_name, last_name')
                                   ->with_department('fields:name')
                                   ->limit($limit, $offset)
                                   ->order_by([$sort_by => $sort_order])
                                   ->get_all();
    }
    */

    // -------------------------------------------------------------------
    public function count_early_reports($for = 'company', $id = NULL, $status = FALSE, $ra_required = FALSE, $safety_statement = FALSE, $query = NULL)
    {
        $this->CI->load->model('early_report_model');

        switch ($for)
        {
            case 'user':
                $this->CI->early_report_model->where('assignee_id', $id);
                break;
            case 'department':
                $this->CI->early_report_model->where('department_id', $id);
                break;
            default:
                $this->CI->early_report_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        if ($status !== FALSE)
        {
            switch ($status)
            {
                case 'open':
                    $this->CI->early_report_model->where('status', 1);
                    break;
                case 'closed':
                    $this->CI->early_report_model->where('status', 0);
                    break;
            }
        }

        if ($ra_required !== FALSE)
        {
            switch ($ra_required)
            {
                case 'no':
                    $this->CI->early_report_model->where('risk_assessment_required', 0);
                    break;
                case 'yes':
                    $this->CI->early_report_model->where('risk_assessment_required', 1);
                    break;
            }
        }

        if ($safety_statement !== FALSE)
        {
            switch ($safety_statement)
            {
                case 'no':
                    $this->CI->early_report_model->where('affects_safety_statement', 0);
                    break;
                case 'yes':
                    $this->CI->early_report_model->where('affects_safety_statement', 1);
                    break;
            }
        }

        if ($query !== NULL)
        {
            return $this->CI->early_report_model
                            ->where($query,NULL,NULL,FALSE,FALSE,TRUE)
                            ->where('company_id', $this->CI->user->company_id)
                            ->count_rows();
        }
        else
        {
            return $this->CI->early_report_model->count_rows();
        }
    }

    // -------------------------------------------------------------------
    /*
    public function get_early_reports($for = 'company', $id = NULL, $status = FALSE, $ra_required = FALSE, $safety_statement = FALSE, $sort_by = 'date', $sort_order = 'desc', $limit = 5, $offset = 0)
    {
        $this->CI->load->model('early_report_model');

        switch ($for)
        {
            case 'user':
                $this->CI->early_report_model->where('user_id', $id);
                break;
            case 'department':
                $this->CI->early_report_model->where('department_id', $id);
                break;
            default:
                $this->CI->early_report_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        if ($status !== FALSE)
        {
            switch ($status)
            {
                case 1:
                    $this->CI->early_report_model->where('status', 1);
                    break;
                case 0:
                    $this->CI->early_report_model->where('status', 0);
                    break;
            }
        }

        if ($ra_required !== FALSE)
        {
            switch ($ra_required)
            {
                case 'yes':
                    $this->CI->accident_model->where('risk_assessment_required', 1);
                    break;
                case 'no':
                    $this->CI->accident_model->where('risk_assessment_required', 0);
                    break;
            }
        }

        if ($safety_statement !== FALSE)
        {
            switch ($safety_statement)
            {
                case 'yes':
                    $this->CI->accident_model->where('affects_safety_statement', 1);
                    break;
                case 'no':
                    $this->CI->accident_model->where('affects_safety_statement', 0);
                    break;
            }
        }

        return $this->CI->early_report_model
                        ->with_submitted_by('fields:first_name, last_name')
                        ->with_assigned_to('fields:first_name, last_name')
                        ->with_department('fields:name')
                        ->limit($limit, $offset)
                        ->order_by([$sort_by => $sort_order])
                        ->get_all();
    }
    */

    // -------------------------------------------------------------------
    public function count_safety_meetings($for = 'company', $id = NULL, $status = NULL, $query = NULL)
    {
        $this->CI->load->model('meeting_model');

        switch ($for)
        {
            case 'user':
                $this->CI->meeting_model->where('user_id', $id);
                break;
            default:
                $this->CI->meeting_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        switch ($status)
        {
            case 'open':
                $this->CI->meeting_model->where('open', 1);
                break;
            case 'closed':
                $this->CI->meeting_model->where('open', 0);
                break;
            default:
                break;
        }

        if ($query !== NULL)
        {
            return $this->CI->meeting_model
                            ->where($query,NULL,NULL,FALSE,FALSE,TRUE)
                            ->where('company_id', $this->CI->user->company_id)
                            ->count_rows();
        }
        else
        {
            return $this->CI->meeting_model->count_rows();
        }
    }

    // -------------------------------------------------------------------
    /*
    public function get_safety_meetings($for = 'company', $id = NULL, $status = NULL, $sort_by = 'date', $sort_order = 'desc', $limit = 5, $offset = 0)
    {
        $this->CI->load->model('meeting_model');

        switch ($for)
        {
            case 'user':
                $this->CI->meeting_model->where('user_id', $id);
                break;
            default:
                $this->CI->meeting_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        switch ($status)
        {
            case 'open':
                $this->CI->meeting_model->where('open', 1);
                break;
            case 'closed':
                $this->CI->meeting_model->where('open', 0);
                break;
        }

        return $this->CI->meeting_model
                        ->with_profile('fields:first_name, last_name')
                        ->limit($limit, $offset)
                        ->order_by([$sort_by => $sort_order])
                        ->get_all();
    }
    */

    // -------------------------------------------------------------------
    public function count_safety_meeting_actions($for = 'company', $id = NULL, $priority = NULL, $status = NULL, $query = NULL)
    {
        $this->CI->load->model('meeting_action_model');

        switch ($for)
        {
            case 'user':
                $this->CI->meeting_action_model->where('user_id', $id);
                break;
            default:
                $this->CI->meeting_action_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        if ($priority !== NULL)
        {
            $this->CI->meeting_action_model->where('priority', $priority);
        }

        if ($status !== NULL)
        {
            $this->CI->meeting_action_model->where('status', $status);
        }

        if ($query !== NULL)
        {
            return $this->CI->meeting_action_model
                            ->where($query,NULL,NULL,FALSE,FALSE,TRUE)
                            ->where('company_id', $this->CI->user->company_id)
                            ->count_rows();
        }
        else
        {
            return $this->CI->meeting_action_model->count_rows();
        }
    }

    // -------------------------------------------------------------------
    /*
    public function get_safety_meeting_actions($for = 'company', $id = NULL, $priority = NULL, $status = NULL, $sort_by = 'ecd', $sort_order = 'desc', $limit = 5, $offset = 0)
    {
        $this->CI->load->model('meeting_action_model');

        switch ($for)
        {
            case 'user':
                $this->CI->meeting_action_model->where('user_id', $id);
                break;
            default:
                $this->CI->meeting_action_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        if ($priority !== NULL)
        {
            $this->CI->meeting_action_model->where('priority', $priority);
        }

        if ($status !== NULL)
        {
            $this->CI->meeting_action_model->where('status', $status);
        }

        return $this->CI->meeting_action_model
                        ->with_meeting('fields: name')
                        ->with_profile('fields: first_name, last_name')
                        ->limit($limit, $offset)
                        ->order_by([$sort_by => $sort_order])
                        ->get_all();
    }
    */

    // -------------------------------------------------------------------
    public function count_risk_assessments($for = 'company', $id = NULL, $status = NULL, $query = NULL)
    {
        $this->CI->load->model('risk_assessment_model');

        switch ($for)
        {
            case 'user':
                $this->CI->risk_assessment_model->where('user_id', $id);
                break;
            case 'department':
                $this->CI->risk_assessment_model->where('department_id', $id);
                break;
            default:
                $this->CI->risk_assessment_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        switch ($status)
        {
            case 'complete':
                $this->CI->risk_assessment_model->where('completed', 1);
                break;
            case 'incomplete':
                $this->CI->risk_assessment_model->where('completed', 0);
                break;
            default:
                break;
        }

        if ($query !== NULL)
        {
            return $this->CI->risk_assessment_model
                            ->where($query,NULL,NULL,FALSE,FALSE,TRUE)
                            ->where('company_id', $this->CI->user->company_id)
                            ->count_rows();
        }
        else
        {
            return $this->CI->risk_assessment_model->count_rows();
        }
    }

    // -------------------------------------------------------------------
    /*
    public function get_risk_assessments($for = 'company', $id = NULL, $status = NULL, $sort_by = 'date', $sort_order = 'desc', $limit = 5, $offset = 0)
    {
        $this->CI->load->model('risk_assessment_model');

        switch ($for)
        {
            case 'user':
                $this->CI->risk_assessment_model->where('user_id', $id);
                break;
            case 'department':
                $this->CI->risk_assessment_model->where('department_id', $id);
                break;
            default:
                $this->CI->risk_assessment_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        switch ($status)
        {
            case 'open':
                $this->CI->risk_assessment_model->where('completed', 0);
                break;
            case 'completed':
                $this->CI->risk_assessment_model->where('completed', 1);
                break;
            default:
                break;
        }

        return $this->CI->risk_assessment_model
                        ->with_department('fields:name')
                        ->limit($limit, $offset)
                        ->order_by([$sort_by => $sort_order])
                        ->get_all();
    }
    */

    // -------------------------------------------------------------------
    public function count_safety_walks($for = 'company', $id = NULL, $status = NULL, $query = NULL)
    {
        $this->CI->load->model('safety_walk_model');

        switch ($for)
        {
            case 'user':
                $this->CI->safety_walk_model->where('user_id', $id);
                break;
            default:
                $this->CI->safety_walk_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        switch ($status)
        {
            case 'incomplete':
                $this->CI->safety_walk_model->where('completed', 0);
                break;
            case 'complete':
                $this->CI->safety_walk_model->where('completed', 1);
                break;
            default:
                break;
        }

        if ($query !== NULL)
        {
            return $this->CI->safety_walk_model
                            ->where($query,NULL,NULL,FALSE,FALSE,TRUE)
                            ->where('company_id', $this->CI->user->company_id)
                            ->count_rows();
        }
        else
        {
            return $this->CI->safety_walk_model->count_rows();
        }
    }

    // -------------------------------------------------------------------
    /*
    public function get_safety_walks($for = 'company', $id = NULL, $status = NULL, $sort_by = 'date', $sort_order = 'desc', $limit = 5, $offset = 0)
    {
        $this->CI->load->model('safety_walk_model');

        switch ($for)
        {
            case 'user':
                $this->CI->safety_walk_model->where('user_id', $id);
                break;
            default:
                $this->CI->safety_walk_model->where('company_id', $this->CI->user->company_id);
                break;
        }

        switch ($status)
        {
            case 'open':
                $this->CI->safety_walk_model->where('completed', 1);
                break;
            case 'completed':
                $this->CI->safety_walk_model->where('completed', 0);
                break;
            default:
                break;
        }

        return $this->CI->safety_walk_model
                        ->with_template('fields: name')
                        ->with_profile('fields: first_name, last_name')
                        ->limit($limit, $offset)
                        ->order_by([$sort_by => $sort_order])
                        ->get_all();
    }
    */

    // -------------------------------------------------------------------
    public function count_training($for = 'company', $id = NULL, $type = 'completed', $query = NULL)
    {
        $this->CI->load->model(['training_model', 'training_required_model']);

        switch ($for)
        {
            case 'user':
                $where = ['user_id' => $id];
                break;
            case 'course':
                $where = ['course_id' => $id];
                break;
            case 'department':
                $where = ['department_id' => $id];
                break;
            default:
                $where = ['company_id' => $this->CI->user->company_id];
                break;
        }

        switch ($type)
        {
            case 'required':
                $model = 'training_required_model';
                break;
            default:
                $model = 'training_model';
                break;
        }

        if ($query !== NULL)
        {
            return $this->CI->{$model}
                            ->where($query,NULL,NULL,FALSE,FALSE,TRUE)
                            ->where($where)
                            ->where('company_id', $this->CI->user->company_id)
                            ->count_rows();
        }
        else
        {
            return $this->CI->{$model}->where($where)->count_rows();
        }
    }

    // -------------------------------------------------------------------
    /*
    public function get_training($for = 'company', $id = NULL, $type = 'completed', $sort_by = 'date', $sort_order = 'desc', $limit = 5, $offset = 0)
    {
        $this->CI->load->model(['training_model', 'training_required_model']);

        switch ($for)
        {
            case 'user':
                $where = ['user_id' => $id];
                break;
            case 'course':
                $where = ['course_id' => $id];
                break;
            case 'department':
                $where = ['department_id' => $id];
                break;
            default:
                $where = ['company_id' => $this->CI->user->company_id];
                break;
        }

        switch ($type)
        {
            case 'required':
                $model = 'training_required_model';
                break;
            
            default:
                $model = 'training_model';
                break;
        }

        return $records = $this->CI->{$model}
                                   ->where($where)
                                   ->with_profile('fields:first_name, last_name')
                                   ->with_course('fields:name')
                                   ->with_department('fields:name')
                                   ->limit($limit, $offset)
                                   ->order_by([$sort_by => $sort_order])
                                   ->get_all();
    }
    */
}