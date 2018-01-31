<?php defined('BASEPATH') OR exit('No direct script access allowed');

// TODO: Log user activities
// TODO: Add feedback feature

class Admin extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->language('admin/admin');

        $this->load->library('admin/admin_lib');
    }

    // TODO: Finish dashboard
    public function index()
    {
        $company_count = $this->admin_lib->count('companies');

        $user_count = $this->admin_lib->count('users');

        $payment_total = $this->db->select_sum('amount')->from('payments')->get()->row_array();

        $this->data['count'] = [
            'companies' => $company_count,
            'users' => $user_count,
            'payment_total' => $payment_total,
        ];

        $company_months = $this->admin_lib->custom_query(
            "SELECT YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total
             FROM companies
             WHERE created_at <= CURDATE()
             AND created_at > CURDATE() - INTERVAL 12 MONTH
             GROUP BY YEAR(created_at), MONTH(created_at)"
        );

        $this->template->title(lang('admin_heading_index'))
                       ->set_css('datatables.bootstrap.min')
                       ->set_js(['chart.min', 'datatables.jquery.min', 'datatables.bootstrap.min', 'moment'])
                       ->set_partial('custom_js', 'admin/chartjs')
                       ->set('company_months', json_encode($company_months))
                       ->build('admin/index', $this->data);
    }

}

/* End of file Admin.php */
/* Location: ./application/controllers/admin/Admin.php */