<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends Account_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->language('notifications');
        $this->breadcrumbs->push('Home', 'dashboard');
        $this->breadcrumbs->push('Notifications', 'notifications');
    }

    // TODO: Figure out bulk marking as read
    public function index()
    {
        $this->load->library(['pagination', 'form_validation']);
        $this->load->helper('form');

        $sort_by = $this->uri->segment(2, 'created_at');
        $sort_order = $this->uri->segment(3, 'desc');
        $limit = $this->uri->segment(4, 10);
        $offset = $this->uri->segment(5, 0);
        $sort_columns = ['type', 'title', 'viewed', 'created_at'];
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'created_at';
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';

        $this->data['notifications'] = $this->notification_model
                                            ->limit($limit, $offset)
                                            ->order_by([$sort_by => $sort_order])
                                            ->where('user_id', $this->session->user_id)
                                            ->get_all();

        $config['base_url'] = site_url("notifications/$sort_by/$sort_order/$limit");
        $config['total_rows'] = $this->notification_model->where(['user_id' => $this->user->id])->count_rows();
        $config['per_page'] = $limit;
        $config['uri_segment'] = 5;
        $this->pagination->initialize($config);

        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['sort_order'] = $sort_order;
        $this->data['sort_by'] = $sort_by;

        $this->template->title(lang('notifications_heading_index'))
                       ->set_partial('custom_js', 'notifications/index_js')
                       ->build('notifications/index', $this->data);
    }

    public function mark_as_read()
    {
        $id = $this->input->post('id', TRUE);

        $note = $this->notification_model->fields('user_id')->get($id);

        if ($note['user_id'] != $this->user->id)
        {
            echo "You are not authorised to mark it as read.";
        }
        else
        {
            if ($this->notification_model->update(['viewed' => 1], $id))
            {
                echo "done";
            }
            else
            {
                echo "There was a problem marking it as read.";
            }
        }
    }

}

/* End of file Notifications.php */
/* Location: ./application/controllers/Notifications.php */