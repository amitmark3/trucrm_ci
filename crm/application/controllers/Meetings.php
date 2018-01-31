<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Meetings extends Account_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('meeting_model');
        $this->load->language('meetings');

        $this->breadcrumbs->push('Home', 'dashboard');
        $this->breadcrumbs->push('Meetings', 'meetings');
    }

    public function index()
    {
        if ( ! in_array($this->user_group['id'], [2,3]) )
        {
            $this->flasher->set_warning_extra(lang('meetings_access_denied'), 'dashboard', TRUE);
        }

        $this->template->title(lang('meetings_heading_index'))
                       ->set_css(['datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min'])
                       ->set_js(['datatables.jquery.min', 'datatables.buttons.min', 'datatables.responsive.min', 'datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.bootstrap.responsive.min', 'datatables.buttons.print.min', 'datatables.buttons.flash.min', 'datatables.buttons.html5.min', 'bootbox-4.4.0.min', 'moment', 'jszip.min', 'pdfmake.min', 'vfs_fonts'])
                       ->set_partial('custom_js', 'meetings/datatables_js', ['url' => site_url('meetings/datatables')])
                       ->build('meetings/index', $this->data);
    }

    public function datatables()
    {
        if ( ! in_array($this->user_group['id'], [2,3]) )
        {
            $this->flasher->set_warning_extra(lang('meetings_access_denied'), 'dashboard', TRUE);
        }

        $this->load->library('datatables');

        $this->datatables->select("id, name, description, date, open");
        $this->datatables->from("meetings");
        $this->datatables->where('company_id', $this->user->company_id);
        $this->datatables->add_column('edit', anchor('meetings/edit/$1', '<i class="fa fa-lg fa-pencil-square"></i>'), 'id');

        echo $this->datatables->generate();
    }

    // TODO: Should a meeting be closeable even if actions are not closed?
    public function view()
    {
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_warning_extra(lang('meetings_invalid_id'), 'meetings', TRUE);
        }

        $meeting = $this->meeting_model
                        ->with_profile('fields: first_name, last_name')
                        ->with_attendees(['with' => [['relationship' => 'profile', 'fields' => 'first_name, last_name']]])
                        ->with_agendas(['with' => [['relationship' => 'profile', 'fields' => 'first_name, last_name']]])
                        ->with_actions(['with' => [['relationship' => 'profile', 'fields' => 'first_name, last_name']]])
                        ->get($id);

        $allowed = TRUE;

        if ($meeting['attendees'])
        {
            $attendee_IDs = array_column($meeting['attendees'], 'user_id');

            if ( ! in_array($this->user->id, $attendee_IDs) )
            {
                $allowed = FALSE;
            }
        }

        if ($this->user_group['id'] < 4)
        {
            $allowed = TRUE;
        }

        if ($meeting['company_id'] != $this->user->company_id || $allowed == FALSE)
        {
            $this->flasher->set_warning_extra(lang('meetings_invalid_company_to_view'), 'meetings', TRUE);
        }

        $this->load->library('form_validation');
        $this->load->helper(['form', 'file']);

        $this->breadcrumbs->push($meeting['name'], 'meetings/view');

        $this->template->title(lang('meetings_heading_view'))
                       ->set_css(['formvalidation.min', 'bootstrap-datepicker.min', 'bootstrap-select.min', 'fileinput.min'])
                       ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min', 'bootstrap-datepicker.min', 'bootbox-4.4.0.min', 'bootstrap-select.min', 'fileinput.min', 'pdfmake.min', 'vfs_fonts'])
                       ->set('meeting', $meeting)
                       ->set('company_users', $this->company_lib->get_company_users(1))
                       ->set('uploads', $this->db->where(['id' => $meeting['id'], 'feature' => 'meeting'])->get('uploads')->result_array())
                       ->set_partial('custom_js', 'meetings/view_js')
                       ->build('meetings/view', $this->data);
    }

    public function add()
    {
        if ( ! in_array($this->user_group['id'], [2,3]) )
        {
            $this->flasher->set_warning_extra(lang('meetings_access_denied'), 'meetings', TRUE);
        }
        else
        {
            $this->load->library('form_validation');
            $this->load->helper('form');

            $this->form_validation->set_rules('name', 'name', 'trim|required');
            $this->form_validation->set_rules('description', 'description', 'trim|required');
            $this->form_validation->set_rules('date', 'date', 'trim|required');
            $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

            if ($this->form_validation->run() == TRUE)
            {
                $name = $this->input->post('name', TRUE);
                $description = $this->input->post('description', TRUE);
                $date = $this->input->post('date', TRUE);

                $meeting_data = [
                    'company_id'    => $this->user->company_id,
                    'user_id'       => $this->user->user_id,
                    'name'          => $name,
                    'description'   => $description,
                    'date'          => $date,
                ];

                $meeting_id = $this->meeting_model->insert($meeting_data);

                if ($meeting_id)
                {
                    $company_admin = $this->company_lib->get_company_admin();

                    $this->load->library(['email_lib', 'notify_lib']);

                    $emails = $this->email_lib->get_emails(array_filter([$company_admin['id']]));
                    $this->email_lib->send_email($emails, lang('new_meeting_email_subject'), 'meetings/email/new', ['id' => $meeting_id, 'name' => $name]);

                    $IDs = $this->notify_lib->get_IDs(array_filter([$company_admin['id']]));
                    $this->notify_lib->send($IDs, 'meeting', lang('new_meeting_email_subject'), site_url("meetings/view/{$meeting_id}"));

                    $this->flasher->set_success(lang('meetings_add_successful'), 'meetings/view/' . $meeting_id, TRUE);
                }

                $this->flasher->set_danger(lang('meetings_add_failed'), 'meetings/add', TRUE);
            }
            else
            {
                $this->breadcrumbs->push(lang('meetings_heading_add'), 'meetings/add');

                $this->template->title(lang('meetings_heading_add'))
                               ->set_css(['formvalidation.min', 'bootstrap-datepicker.min'])
                               ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min', 'bootstrap-datepicker.min'])
                               ->set_partial('custom_js', 'meetings/custom_js', ['form_name' => 'add-meeting-form'])
                               ->build('meetings/add', $this->data);
            }
        }
    }

    public function edit()
    {
        if ( ! in_array($this->user_group['id'], [2,3]) )
        {
            $this->flasher->set_warning_extra(lang('meetings_access_denied'), 'dashboard', TRUE);
        }

        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('meetings_invalid_id'), 'meetings', TRUE);
        }

        $meeting = $this->meeting_model->get($id);

        if ($meeting['company_id'] != $this->session->company_id)
        {
            $this->flasher->set_warning_extra(lang('meetings_invalid_company_to_edit'), 'meetings', TRUE);
        }

        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');
        $this->form_validation->set_rules('date', 'date', 'trim|required');

        if ($this->form_validation->run() == TRUE)
        {
            $name = $this->input->post('name', TRUE);
            $description = $this->input->post('description', TRUE);
            $date = $this->input->post('date', TRUE);

            $meeting_data = [
                'name'          => $name,
                'description'   => $description,
                'date'          => $date,
            ];

            if ($this->meeting_model->update($meeting_data, $id))
            {
                $this->flasher->set_success(lang('meetings_update_successful'), 'meetings/view/' . $meeting['id'], TRUE);
            }

            $this->flasher->set_danger(lang('meetings_update_failed'), 'meetings/edit/' . $meeting['id'], TRUE);
        }
        else
        {
            $this->breadcrumbs->push(lang('meetings_heading_edit'), 'meetings/edit');

            $this->template->title(lang('meetings_heading_edit'))
                           ->set_css(['formvalidation.min', 'bootstrap-datepicker.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min', 'bootstrap-datepicker.min'])
                           ->set_partial('custom_js', 'meetings/custom_js', ['form_name' => 'edit-meeting-form'])
                           ->set('meeting', $meeting)
                           ->build('meetings/edit', $this->data);
        }
    }

    /*
    // TODO: What happens to meeting attendees, agendas and actions?
    public function delete()
    {
        if ( ! in_array($this->user_group['id'], [2,3]) )
        {
            $this->flasher->set_warning_extra(lang('meetings_access_denied'), 'dashboard', TRUE);
        }

        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('meetings_invalid_id'), 'meetings', TRUE);
        }
        else
        {
            $meeting = $this->meeting_model->fields('company_id')->get($id);

            if ($meeting['company_id'] != $this->session->company_id)
            {
                $this->flasher->set_warning_extra(lang('meetings_invalid_company_to_delete'), 'meetings', TRUE);
            }
            else
            {
                $query = $this->meeting_model->delete($id);

                if ($query)
                {
                    $this->flasher->set_success(lang('meetings_delete_successful'), 'meetings', TRUE);
                }
                else
                {
                    $this->flasher->set_danger(lang('meetings_delete_failed'), 'meetings', TRUE);
                }
            }
        }
    }
    */

    // TODO: Finish
    public function pdf()
    {
        $id = $this->uri->segment(3);
    }

    public function close()
    {
        if ( ! in_array($this->user_group['id'], [2,3]) )
        {
            $this->flasher->set_warning_extra(lang('meetings_access_denied'), 'dashboard', TRUE);
        }

        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('meetings_invalid_id'), 'meetings', TRUE);
        }

        $meeting = $this->meeting_model
                        ->with_attendees(['with' => [['relationship' => 'profile', 'fields' => 'first_name, last_name']]])
                        ->with_agendas(['with' => [['relationship' => 'profile', 'fields' => 'first_name, last_name']]])
                        ->with_actions(['with' => [['relationship' => 'profile', 'fields' => 'first_name, last_name']]])
                        ->get($id);

        // die(var_dump($meeting));

        if ($meeting['company_id'] != $this->user->company_id)
        {
            $this->flasher->set_info(lang('meetings_invalid_company_to_close'), 'meetings', TRUE);
        }

        if ($this->meeting_model->update(['open' => 0], $id))
        {

            $attendees = $this->db->select('user_id')
                                  ->distinct('user_id')
                                  ->from('meeting_users')
                                  ->where('meeting_id', $meeting['id'])
                                  ->get()
                                  ->result_array();

            if ($attendees)
            {
                $attendee_IDs = array_column($attendees, 'user_id');

                $company_admin = $this->company_lib->get_company_admin('id');

                array_push($attendee_IDs, $company_admin['id']);

                $this->load->library(['email_lib', 'notify_lib']);

                $emails = $this->email_lib->get_emails($attendee_IDs);
                $this->email_lib->send_email($emails, lang('meeting_closed_email_subject'), 'meetings/email/closed', ['meeting' => $meeting]);

                $IDs = $this->notify_lib->get_IDs($attendee_IDs);
                $this->notify_lib->send($IDs, 'meeting', lang('meeting_closed_email_subject'), site_url("meetings/view/{$meeting['id']}"));
            }

            $this->flasher->set_success(lang('meetings_close_successful'), NULL, TRUE);
        }
        else
        {
            $this->flasher->set_danger(lang('meetings_close_failed'), NULL, TRUE);
        }

        redirect('meetings/view/'.$id);
    }

    public function upload()
    {
        if ( ! empty($_POST) )
        {
            $meeting_id = $this->input->post('meeting_id', TRUE);

            if ($_FILES['userfile']['error'][0] == 0)
            {
                $this->load->library('upload');

                $upload_config['upload_path']      = $this->company_lib->get_uploads_folder($this->company['id']);
                $upload_config['allowed_types']    = 'gif|jpg|jpeg|png|bmp|pdf|doc|xls|xlsx|ppt';
                $upload_config['max_size']         = $this->config->item('max_file_size'); // 4MB
                $upload_config['file_ext_tolower'] = TRUE;

                $files = $_FILES;
                $file_count = count($_FILES['userfile']['name']);
                $documents = [];

                for ($i = 0; $i < $file_count; $i++)
                {
                    $_FILES['userfile']['name']     = $files['userfile']['name'][$i];
                    $_FILES['userfile']['type']     = $files['userfile']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                    $_FILES['userfile']['error']    = $files['userfile']['error'][$i];
                    $_FILES['userfile']['size']     = $files['userfile']['size'][$i];

                    $this->upload->initialize($upload_config);

                    $this->upload->do_upload();

                    $upload_data = $this->upload->data();

                    if ($upload_data['image_width'] > '1280')
                    {
                        $this->load->library('image_lib');

                        $image_config['image_library']  = 'gd2';
                        $image_config['maintain_ratio'] = TRUE;
                        $image_config['width']          = 1280;
                        $image_config['height']         = 1024;
                        $image_config['source_image']   = $upload_data['full_path'];

                        $this->image_lib->initialize($image_config);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                    }

                    $documents[$i]['id'] = $meeting_id;
                    $documents[$i]['company_id'] = $this->user->company_id;
                    $documents[$i]['feature'] = 'meeting';
                    $documents[$i]['file_name'] = $upload_data['file_name'];
                    $documents[$i]['file_name_thumb'] = NULL;
                    $documents[$i]['file_size'] = $upload_data['file_size'];
                }

                if ($this->db->insert_batch('uploads', $documents))
                {
                    $this->flasher->set_success(lang('meetings_upload_success'), 'meetings/view/'.$meeting_id, TRUE);
                }
            }
            else
            {
                $this->flasher->set_warning_extra('No files selected for upload.', 'meetings/view/'.$meeting_id, TRUE);
            }
        }
        else
        {
            $this->flasher->set_warning_extra('No files selected for upload.', 'meetings', TRUE);
        }
    }

    // TODO: Check if thumbnail exists and delete if it does.
    public function delete_image()
    {
        $this->load->helper('file');

        $file_name = $this->input->post('file_name', TRUE);

        $meeting_id = $this->input->post('meeting_id', TRUE);

        $uploads_folder = $this->company_lib->get_uploads_folder($this->company['id']);

        if (delete_image($file_name, $uploads_folder) == TRUE)
        {
            $query = $this->db->delete('uploads', ['file_name' => $file_name, 'id' => $meeting_id]);

            if ($query)
            {
                echo "deleted";
            }
        }
        else
        {
            echo "failed";
        }
    }

    public function add_attendee()
    {
        $attendee_data = array(
            'user_id' => $this->input->post('user_id', TRUE),
            'status' => strtolower($this->input->post('status', TRUE)),
            'meeting_id' => $this->input->post('meeting_id', TRUE)
        );

        $this->load->model('meeting_user_model');

        $query = $this->meeting_user_model->where(['user_id' => $attendee_data['user_id'], 'meeting_id' => $attendee_data['meeting_id']])->get();

        if ($query)
        {
            echo "<tr class='attendees_error'><td colspan='3'><p class='text-red'>That user is already attending this meeting.</p></td></tr>";
        }
        else
        {
            if ($this->meeting_user_model->insert($attendee_data) == TRUE)
            {
                $attendee = $this->user_model->with_profile('fields:first_name, last_name')->get($attendee_data['user_id']);

                echo "<tr>";
                echo "<td>".$attendee['profile']['first_name'].' '.$attendee['profile']['last_name']."</td>";
                echo "<td>".ucfirst($attendee_data['status'])."</td>";
                echo "<td>";
                echo anchor('meetings/remove_attendee/'.$attendee['id'], '<i class="fa fa-times-circle"></i>',
                            ['id' => $attendee['id'],
                             'class' => 'btn btn-default remove_attendee',
                             'title' => 'Remove Attendee',
                             'data-meeting-id' => $attendee_data['meeting_id']
                            ]);
                echo "</td>";
                echo "</tr>";
            }
            else
            {
                echo "<tr class='attendees_error'><td colspan='3'><p class='text-red'>There was a problem adding the attendee.</p></td></tr>";
            }
        }
    }

    public function remove_attendee()
    {
        $user_id = $this->input->post('user_id', TRUE);
        $meeting_id = $this->input->post('meeting_id', TRUE);
        $this->load->model('meeting_user_model');
        $query = $this->meeting_user_model->where(['user_id' => $user_id, 'meeting_id' => $meeting_id])->delete();
        echo ($query != FALSE) ? 'success' : 'failure';
    }

    public function add_agenda()
    {
        $topic = strtolower($this->input->post('topic', TRUE));

        $agenda_data = array(
            'topic' => $topic,
            'presenter_user_id' => $this->input->post('presenter_user_id', TRUE),
            'allotted_time' => $this->input->post('allotted_time', TRUE),
            'meeting_id' => $this->input->post('meeting_id', TRUE)
        );

        if ( empty($topic) )
        {
            echo "<tr class='agendas_error'><td colspan='4'><p class='text-red'>Please enter a topic for the agenda.</p></td></tr>";
        }
        else
        {
            $this->load->model('meeting_agenda_model');

            $agenda_id = $this->meeting_agenda_model->insert($agenda_data);

            if ($agenda_id)
            {
                $user = $this->user_model->with_profile('fields:first_name, last_name')->get($agenda_data['presenter_user_id']);

                echo "<tr>";
                echo "<td>".ucfirst($agenda_data['topic'])."</td>";
                echo "<td>".$user['profile']['first_name'].' '.$user['profile']['last_name']."</td>";
                echo "<td>".$agenda_data['allotted_time']."</td>";
                echo "<td>";
                echo anchor('meetings/remove_agenda/'.$agenda_id, '<i class="fa fa-times-circle"></i>',
                            ['id' => $agenda_id,
                             'class' => 'btn btn-default remove_agenda',
                             'title' => 'Remove Agenda',
                             'data-meeting-id' => $agenda_data['meeting_id']
                            ]);
                echo "</td>";
                echo "</tr>";
            }
            else
            {
                echo "<tr class='agendas_error'><td colspan='4'><p class='text-red'>There was a problem adding the agenda.</p></td></tr>";
            }
        }
    }

    public function remove_agenda()
    {
        $id = $this->input->post('id', TRUE);
        $meeting_id = $this->input->post('meeting_id', TRUE);
        $this->load->model('meeting_agenda_model');
        $query = $this->meeting_agenda_model->where(['id' => $id, 'meeting_id' => $meeting_id])->delete();
        echo ($query != FALSE) ? 'success' : 'failure';
    }

    // TODO: Highlight rows in datatables.
    public function actions()
    {
        switch ($this->user_group['id'])
        {
            case 4:
            case 3:
                $title = sprintf(lang('meetings_action_heading_index'), 'Your');
                break;
            case 2:
                $title = sprintf(lang('meetings_action_heading_index'), 'All');
                break;
            default:
                $title = 'Meeting Actions';
                break;
        }

        $this->breadcrumbs->push($title, 'meetings/actions');

        $this->template->title($title)
                       ->set_css(['datatables.bootstrap.min', 'datatables.bootstrap.buttons.min'])
                       ->set_js(['datatables.jquery.min', 'datatables.buttons.min', 'datatables.bootstrap.min', 'datatables.bootstrap.buttons.min', 'datatables.buttons.print.min', 'datatables.buttons.flash.min', 'datatables.buttons.html5.min', 'bootbox-4.4.0.min', 'moment', 'jszip.min', 'pdfmake.min'])
                       ->set_partial('custom_js', 'meetings/actions/datatables_js', ['url' => site_url('meetings/actions_datatables')])
                       ->build('meetings/actions/index', $this->data);
    }

    public function actions_datatables()
    {
        $this->load->library('datatables');

        $this->datatables->from("meeting_actions");
        $this->datatables->join("meetings", "meetings.id = meeting_actions.meeting_id", "left");

        switch ($this->user_group['id'])
        {
            case 4:
            case 3:
                $this->datatables->select("meeting_actions.id, meetings.name, meeting_actions.details, meeting_actions.close_details, meeting_actions.priority, meeting_actions.status, meeting_actions.ecd");
                $this->datatables->where('meeting_actions.user_id', $this->user->id);
                break;
            default:
                $this->datatables->select("meeting_actions.id, meetings.name, meeting_actions.details, meeting_actions.close_details, meeting_actions.priority, meeting_actions.status, meeting_actions.ecd, CONCAT(profiles.first_name, ' ', profiles.last_name) as user");
                $this->datatables->join("profiles", "profiles.user_id = meeting_actions.user_id", "left");
                $this->datatables->where('meeting_actions.company_id', $this->user->company_id);
                $this->datatables->add_column('edit', anchor('meetings/edit_action/$1', '<i class="fa fa-lg fa-edit"></i>', ['title' => 'Edit Meeting Action']), 'id');
                break;
        }

        echo $this->datatables->generate();
    }

    public function add_action()
    {
        $details = $this->input->post('details', TRUE);

        $action_data = array(
            'meeting_id' => $this->input->post('meeting_id', TRUE),
            'user_id' => $this->input->post('user_id', TRUE),
            'company_id' => $this->user->company_id,
            'details' => $details,
            'status' => strtolower($this->input->post('status', TRUE)),
            'priority' => strtolower($this->input->post('priority', TRUE)),
            'ecd' => $this->input->post('ecd', TRUE)
        );

        if ( empty($details) )
        {
            echo "<tr class='actions_error'><td colspan='6'><p class='text-red'>Please enter the details of the action.</p></td></tr>";
        }
        else
        {
            $this->load->model('meeting_action_model');

            $action_id = $this->meeting_action_model->insert($action_data);

            if ($action_id)
            {
                $user = $this->user_model
                             ->fields('id')
                             ->with_profile('fields:first_name, last_name')
                             ->get($action_data['user_id']);

                $email_data = [
                    'first_name' => $user['profile']['first_name'],
                    'meeting_id' => $action_data['meeting_id'],
                    'action_id' => $action_id,
                    'action_details' => $details
                ];

                $emails = $this->email_lib->get_emails(array_filter([$user['id']]));
                $this->email_lib->send_email($emails, lang('new_meeting_action_email_subject'), 'meetings/email/new_action', $email_data);

                $IDs = $this->notify_lib->get_IDs(array_filter([$user['id']]));
                $this->notify_lib->send($IDs, 'meeting', 'New Meeting Action', site_url('meetings/view_action/'.$action_id));

                switch ($action_data['status'])
                {
                    case 'open':
                        $bg_color = 'danger';
                        break;
                    case 'in_progress':
                        $bg_color = 'warning';
                        break;
                    case 'closed':
                        $bg_color = 'success';
                        break;
                    default:
                        $bg_color = 'default';
                        break;
                }

                echo "<tr class='".$bg_color."'>";
                echo "<td><a href='".site_url('meetings/view_action/'.$action_id)."'</a><i class='fa fa-eye'></i></td>";
                echo "<td>".ucfirst($action_data['details'])."</td>";
                echo "<td>".$user['profile']['first_name'].' '.$user['profile']['last_name']."</td>";
                echo "<td>".ucwords(str_replace("_", " ", $action_data['status']))."</td>";
                echo "<td>".ucfirst($action_data['priority'])."</td>";
                echo "<td>".date('jS F Y', strtotime($action_data['ecd']))."</td>";
                echo "<td>";
                echo anchor('meetings/remove_action/'.$action_id, '<i class="fa fa-times-circle"></i>',
                            ['id' => $action_id,
                             'class' => 'btn btn-default remove_action',
                             'title' => 'Remove Action',
                             'data-meeting-id' => $action_data['meeting_id']
                            ]);
                echo "</td>";
                echo "</tr>";
            }
            else
            {
                echo "<tr class='actions_error'><td colspan='6'><p class='text-red'>There was a problem adding the action.</p></td></tr>";
            }
        }
    }

    // TODO: Should somebody be emailed?
    public function edit_action()
    {
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_warning_extra(lang('meetings_invalid_action_id'), 'meetings/actions', TRUE);
        }

        $this->load->model('meeting_action_model');

        $action = $this->meeting_action_model->get($id);

        if ( ! $action )
        {
            $this->flasher->set_warning_extra(lang('meetings_action_not_found'), 'meetings/actions', TRUE);
        }

        if ($action['company_id'] !== $this->user->company_id)
        {
            $this->flasher->set_warning_extra(lang('meetings_action_unauthorised_to_edit'), 'meetings/actions', TRUE);
        }

        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('details', 'details', 'trim|required');
        $this->form_validation->set_rules('priority', 'priority', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');
        $this->form_validation->set_rules('ecd', 'estimated completion date', 'trim|required');

        if ($this->form_validation->run() == TRUE)
        {
            $details = $this->input->post('details', TRUE);
            $close_details = $this->input->post('close_details', TRUE);
            $priority = $this->input->post('priority', TRUE);
            $status = $this->input->post('status', TRUE);
            $ecd = $this->input->post('ecd', TRUE);

            $action_data = [
                'details'       => $details,
                'close_details' => ( ! empty($close_details)) ? $close_details : NULL,
                'priority'      => $priority,
                'status'        => $status,
                'ecd'           => $ecd,
            ];

            if ($this->meeting_action_model->update($action_data, $id))
            {
                $this->flasher->set_success(lang('meetings_action_edit_successful'), 'meetings/view_action/' . $id, TRUE);
            }

            $this->flasher->set_danger(lang('meetings_action_edit_failed'), 'meetings/edit_action/' . $id, TRUE);
        }
        else
        {
            $this->breadcrumbs->push('Meeting Actions', 'meetings/actions');
            $this->breadcrumbs->push(lang('meetings_action_heading_edit'), 'meetings/edit_action/' . $id);

            $this->template->title(lang('meetings_action_heading_edit'))
                           ->set_css(['formvalidation.min', 'bootstrap-datepicker.min'])
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min', 'bootstrap-datepicker.min'])
                           ->set_partial('custom_js', 'meetings/actions/custom_js', ['form_name' => 'edit-meeting-action-form'])
                           ->set('action', $action)
                           ->build('meetings/actions/edit', $this->data);
        }
    }

    public function remove_action()
    {
        $id = $this->input->post('id', TRUE);

        $meeting_id = $this->input->post('meeting_id', TRUE);

        $this->load->model('meeting_action_model');

        $query = $this->meeting_action_model->where(['id' => $id, 'meeting_id' => $meeting_id])->delete();

        echo ($query !== FALSE) ? 'success' : 'failure';
    }

    public function view_action()
    {
        $id = $this->uri->segment(3);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('meetings_invalid_id'), 'meetings', TRUE);
        }

        $this->load->model('meeting_action_model');

        $meeting_action = $this->meeting_action_model->get( $id);

        if ( ! $meeting_action )
        {
            $this->flasher->set_warning_extra(lang('meetings_invalid_action_id'), 'meetings/view/'.$meeting['id'], TRUE);
        }

        $user = $this->profile_model->fields('first_name, last_name')->where('user_id', $meeting_action['user_id'])->get();

        $meeting = $this->meeting_model->fields('id, company_id, name')->get($meeting_action['meeting_id']);

        if ($meeting['company_id'] != $this->user->company_id)
        {
            $this->flasher->set_warning_extra(lang('meetings_action_unauthorised_to_view'), 'meetings/view/'.$meeting['id'], TRUE);
        }

        $this->breadcrumbs->push($meeting['name'], 'meetings/view/'.$meeting['id']);
        $this->breadcrumbs->push('Meeting Action', 'meetings/view_action');

        $this->template->title(lang('meetings_action_heading_view'))
                       ->set_css('formvalidation.min')
                       ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                       ->set_partial('custom_js', 'meetings/actions/view_js')
                       ->set('meeting_action', $meeting_action)
                       ->set('meeting_action_user', $user)
                       ->set('meeting', $meeting)
                       ->build('meetings/actions/view', $this->data);
    }

    public function close_action()
    {
        $id = $this->input->post('action_id', TRUE);

        $meeting_id = $this->input->post('meeting_id', TRUE);

        $close_details = $this->input->post('close_details', TRUE);

        if ( ! $id )
        {
            $this->flasher->set_info(lang('meetings_invalid_action_id'), 'meetings/view/'.$meeting_id, TRUE);
        }

        $this->load->model('meeting_action_model');

        $meeting_action = $this->meeting_action_model->fields('id, user_id, details')->get($id);

        $meeting = $this->meeting_model->fields('company_id, name, user_id')->get($meeting_id);

        if ($meeting['company_id'] != $this->session->company_id || $meeting_action['user_id'] != $this->session->user_id)
        {
            $this->flasher->set_warning_extra(lang('meetings_action_unauthorised_to_close'), 'meetings/view/'.$meeting_id, TRUE);
        }

        $close_data = [
            'status' => 'closed',
            'close_details' => $close_details
        ];

        $result = $this->meeting_action_model->update($close_data, $id);

        if ($result)
        {
            $email_data = [
                'action_id' => $meeting_action['id'],
                // 'meeting_id' => $meeting['id'],
                // 'meeting_name' => $meeting['name'],
                'meeting_action_details' => $meeting_action['details'],
                'meeting_close_details' => $close_details,
            ];

            // get email address of user who needs to be informed
            $informed_user = $this->user_model->fields('email')->get($meeting['user_id']);

            $this->email_lib->send_email($informed_user['email'], 'Meeting Action Closed', 'meetings/email/action_closed', $email_data);

            // TODO: Should an in-app note be sent?

            $this->flasher->set_success(lang('meetings_action_close_successful'), 'meetings/view_action/'.$id, TRUE);
        }

        $this->flasher->set_danger(lang('meetings_action_close_failed'), 'meetings/view_action/'.$id, TRUE);
    }
}

/* End of file Meetings.php */
/* Location: ./application/controllers/Meetings.php */