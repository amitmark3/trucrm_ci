<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model(['payment_model', 'company_model', 'price_plan_model']);
        $this->load->language('admin/payments');
        $this->load->library('admin/admin_lib');

        $this->breadcrumbs->push('Admin', 'admin');
        $this->breadcrumbs->push('Payments', 'admin/payments');

        $this->template->set('companies', $this->admin_lib->get_companies($active = 1));
        $this->template->set('price_plans', $this->admin_lib->get_price_plans());
    }

    public function index()
    {
        $this->template->title(lang('heading_index'))
                       ->set_css('datatables.bootstrap.min')
                       ->set_js(['datatables.jquery.min', 'datatables.bootstrap.min', 'bootbox-4.4.0.min', 'moment'])
                       ->set_partial('custom_js', 'admin/payments/datatables_js', ['url' => site_url('admin/payments/datatables')])
                       ->build('admin/payments/index', $this->data);
    }

    public function datatables()
    {
        $this->load->library("datatables");
        $this->datatables->select("payments.id, companies.name, payments.amount, payments.description, payments.created_at, payments.renewal_date");
        $this->datatables->join("companies", "payments.company_id = companies.id", "left");
        $this->datatables->from("payments");
        $this->datatables->add_column('actions', anchor('admin/payments/delete/$1', '<i class="fa fa-lg fa-times"></i>', ['title' => 'Delete Payment', 'class' => 'confirm', 'id' => '$1']), 'id');
        echo $this->datatables->generate();
    }

    // TODO: Should the company status be updated?
    public function add()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->form_validation->set_rules('company_id', 'company_id', 'trim');
        $this->form_validation->set_rules('price_plan_id', 'price_plan_id', 'trim');

        if ($this->form_validation->run() == TRUE)
        {
            $company_id     = $this->input->post('company_id', TRUE);
            $price_plan_id  = $this->input->post('price_plan_id', TRUE);

            $company = $this->company_model->fields('name')->get($company_id);
            $user = $this->admin_lib->get_company_manager($company_id);
            $price_plan = $this->price_plan_model->fields('name, price')->get($price_plan_id);
            $date = new DateTime('today');
            $date->add(new DateInterval('P1Y'));

            $payment_data = [
                'company_id'    => $company_id,
                'user_id'       => $user['id'],
                'amount'        => $price_plan['price'],
                'description'   => $price_plan['name'] . ' Plan ['.$company['name'].']',
                'renewal_date'  => $date->format('Y-m-d')
            ];

            if ($this->payment_model->insert($payment_data))
            {
                $this->flasher->set_success(lang('add_successful'), 'admin/payments', TRUE);
            }
            else
            {
                $this->flasher->set_danger(lang('add_failed'), 'admin/payments/add', TRUE);
            }
        }
        else
        {
            $this->breadcrumbs->push('Add Payment', 'payments/add');

            $this->template->title(lang('heading_add'))
                           ->set_css('formvalidation.min')
                           ->set_js(['formvalidation.min', 'formvalidation-bootstrap.min'])
                           ->set_partial('custom_js', 'admin/payments/custom_js', ['form_name' => 'add-payment-form'])
                           ->build('admin/payments/add', $this->data);
        }
    }

    // TODO: Should the company status be updated?
    public function delete()
    {
        $id = $this->uri->segment(4);

        if ( ! $id )
        {
            $this->flasher->set_warning_extra(lang('invalid_id'), 'admin/payments', TRUE);
        }

        $payment = $this->payment_model->get($id);

        if ( ! $payment )
        {
            $this->flasher->set_warning_extra(lang('not_found'), 'admin/payments', TRUE);
        }

        if ($this->payment_model->delete($id))
        {
            $this->flasher->set_success(lang('delete_successful'), 'admin/payments', TRUE);
        }
        else
        {
            $this->flasher->set_warning_extra(lang('delete_failed'), 'admin/payments', TRUE);
        }
    }

}

/* End of file Payments.php */
/* Location: ./application/controllers/Payments.php */