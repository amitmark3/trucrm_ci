<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Account_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    // TODO: Add section for actions (which should include all actions from all sections;, etc)
    public function index()
    {
		$this->load->model(['department_model', 'meeting_model', 'meeting_action_model']);
		$this->load->helper('text');

        if ($this->user_group['id'] == '2') // safety manager
        {
            $department_count = $this->department_model->where('company_id', $this->company['id'])->count_rows();

            $user_count = $this->user_model->where('company_id', $this->company['id'])->count_rows();

            $meeting_count = $this->meeting_model->where('company_id', $this->company['id'])->count_rows();

            if ($meeting_count > 0)
            {
                $this->template->set('meetings', $this->meeting_model
                                                      ->where('company_id', $this->company['id'])
                                                      ->order_by('created_at', 'desc')
                                                      ->limit(5)
                                                      ->get_all());
            }

            $meeting_actions_count = $this->meeting_action_model->where('company_id', $this->company['id'])->count_rows();

            if ($meeting_actions_count > 0)
            {
                $this->template->set('meeting_actions', $this->meeting_action_model
                                                         ->where('company_id', $this->company['id'])
                                                         ->with_meeting('fields: name')
                                                         ->order_by('ecd', 'desc')
                                                         ->limit(5)
                                                         ->get_all());
            }

            $this->data['count'] = [
                'meetings' => $meeting_count,
                'meeting_actions' => $meeting_actions_count,
                'departments' => $department_count,
                'users' => $user_count,
            ];
        }
        elseif ($this->user_group['id'] == '3') // department manager
        {
            $user_count = $this->user_model->where('department_id', $this->user->department_id)->count_rows();

            $meeting_count = $this->meeting_model->where('company_id', $this->user->company_id)->count_rows();

            if ($meeting_count > 0)
            {
                $this->template->set('meetings', $this->meeting_model
                                                      ->where('company_id', $this->user->company_id)
                                                      ->order_by('created_at', 'desc')
                                                      ->limit(5)
                                                      ->get_all());
            }
			
            $meeting_actions_count = $this->meeting_action_model->where('user_id', $this->user->id)->count_rows();

            if ($meeting_actions_count > 0)
            {
                $this->template->set('meeting_actions', $this->meeting_action_model
                                                         ->where('user_id', $this->user->id)
                                                         ->with_meeting('fields: name')
                                                         ->order_by('ecd', 'desc')
                                                         ->limit(5)
                                                         ->get_all());
            }

            $this->data['count'] = [
                'meetings' => $meeting_count,
                'meeting_actions' => $meeting_actions_count,
                'users' => $user_count,
            ];
        }
        elseif ($this->user_group['id'] == '4') // staff
        {
            $meeting_action_count = $this->meeting_action_model->where('user_id', $this->user->id)->count_rows();

            if ($meeting_action_count > 0)
            {
                $this->template->set('meeting_actions', $this->meeting_action_model
                                                         ->where('user_id', $this->user->id)
                                                         ->with_meeting('fields: name')
                                                         ->order_by('ecd', 'desc')
                                                         ->limit(5)
                                                         ->get_all());
            }

            $this->data['count'] = [
                'meeting_actions' => $meeting_action_count
            ];
        }
        switch ($this->user_group['id'])
        {
            case '2':
                $view = 'company-admin';
                break;
            case '3':
                $view = 'department-manager';
                break;
            default:
                $view = 'staff';
                break;
        }
		//print '<pre>';print_r($this->data);
        $this->template->title('Dashboard')
                       ->build('dashboard/'.$view, $this->data);
    }

    public function get_notifications()
    {
        // Count notifications for user
        $unread_note_count = $this->notifications->count_unread();

        // Get all unread notifications
        $unread_notes = $this->notifications->get_unread($this->user->id);

        echo json_encode($unread_notes);
    }
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */