<?php defined('BASEPATH') OR exit('No direct script access allowed');

// TODO: Change default error pages
// TODO: Enable CSRF and check all forms
// TODO: Move upload method from controllers to library
// TODO: Create priority & status helpers
// TODO: Add more info to session data (company name & id, group_id, department name & id (if applicable), SM name & id)
// TODO: Make sure all tables in DB use ON_CASCADE DELETE where appropriate.

// TODO: Add redirect url to login process (V2)
// TODO: Add unit testing (V2)
// TODO: Add migrations and seeding (V2)
// TODO: Replace flash messages with toastrjs (V2)
// TODO: Look into model observers (https://github.com/avenirer/CodeIgniter-MY_Model#observers) (V2)
// TODO: Import Laravel's Eloquent ORM (https://www.youtube.com/watch?v=mLlCdMYDLTk) (V2)

class MY_Controller extends CI_Controller
{
    public $user;
    public $user_group;
    public $company;

    public function __construct()
    {
        parent::__construct();
        // AdminLTE colour choices: black, red, green, purple, yellow, blue (all in light too)
        $this->template->set_css(array('bootstrap-3.3.6.min', 'AdminLTE.min', 'skin-red.min', 'style', 'print'));
        $this->template->set_js(array('jquery-2.1.4.min', 'bootstrap-3.3.6.min', 'app.min'));

        if ($this->ion_auth->logged_in())
        {
            // Get the current logged in user's info
            $this->user = $this->ion_auth->user()->row();

            $query = $this->user_model->with_department('fields: name')->get($this->user->id);
            $this->data['user_department'] = $query['department']['name'];
            unset($query);

            // Get profile of logged in user
            $this->data['profile'] = $this->profile_model->where('user_id', $this->user->id)->get();

            // Get the groups the user is in
            $this->user_group = json_decode(json_encode($this->ion_auth->get_users_groups()->result()), TRUE)[0];

            // Get company info
            $this->company = $this->company_model->get($this->user->company_id);

            // Count unread notifications
            $this->data['note_count'] = $this->notification_model->where(['user_id' => $this->user->id, 'viewed' => 0])->count_rows();
            $this->data['unread_notes'] = $this->notification_model->where(['user_id' => $this->user->id, 'viewed' => 0])->order_by('created_at', 'desc')->get_all();
        }
        else
        {
            redirect('login');
        }
    }
}

class Setup_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->template->set_layout('setup');
        $this->template->set_partial('header', 'setup/partials/header')
                       ->set_css('progress-wizard.min')
                       ->set_partial('footer', 'setup/partials/footer');
    }
}

class Account_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if ($this->company['price_plan_id'] == 0 && !$this->ion_auth->is_admin())
        {
            redirect('setup/billing');
        }

        if ( !$this->ion_auth->is_admin() )
        {
            switch ($this->company['setup_step'])
            {
                case '0':
                    redirect('setup/billing');
                    break;
                case '1':
                    redirect('setup/price_plan');
                    break;
                case '2':
                    redirect('setup/checkout');
                    break;
                case '3':
                    redirect('setup/confirmation');
                    break;
                case '4':
                    redirect('setup/company');
                    break;
                case '5':
                    redirect('setup/import');
                    break;
                case '6':
                    redirect('setup/finish');
                    break;
            }
        }

        if ( $this->ion_auth->is_admin() )
        {
            $sidebar = 'sidebar-admin';
        }
        elseif ( $this->ion_auth->in_group([2]) )
        {
            $sidebar = 'sidebar-manager';
        }
        elseif ( $this->ion_auth->in_group([3]) )
        {
            $sidebar = 'sidebar-department-manager';
        }
        elseif ( $this->ion_auth->in_group([4]) )
        {
            $sidebar = 'sidebar-staff';
        }

        $this->template->set_layout('dashboard');
        $this->template->set_partial('header', 'partials/dashboard/header')
                       ->set_partial('sidebar', 'partials/dashboard/' . $sidebar)
                       ->set_partial('control-sidebar', 'partials/dashboard/control-sidebar')
                       ->set_partial('footer', 'partials/dashboard/footer');
    }
}

class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if ( ! $this->ion_auth->is_admin() )
        {
            // TODO: Email the user info of the person trying to view to real admin (V2)
            $this->flasher->set_warning('You are not authorised to view that page.', 'dashboard', TRUE);
        }

        $this->template->set_layout('dashboard');
        $this->template->set_partial('header', 'partials/dashboard/header')
                       ->set_partial('sidebar', 'partials/dashboard/sidebar-admin')
                       ->set_partial('control-sidebar', 'partials/dashboard/control-sidebar')
                       ->set_partial('footer', 'partials/dashboard/footer');
    }
}
