<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('admin/users/add', ['id' => 'add-user-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-md-4">
                        <?= form_label(lang('users_group_label'), 'group_id'); ?>
                        <?= form_dropdown('group_id', $groups, set_value('group_id'), ['id' => 'group_id']); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-4 hidden" id="company_dropdown">
                        <?= form_label(lang('users_company_label'), 'company_id'); ?>
                        <?= form_dropdown('company_id', $companies, set_value('company_id'), ['id' => 'company_id']); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-4 hidden" id="departments_dropdown">
                        <?= form_label(lang('users_department_label'), 'department_id'); ?>
                        <?= form_dropdown('department_id', ['' => 'Please choose a company first'], set_value('department_id'), ['id' => 'department_id']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-4">
                        <?= form_label(lang('users_first_name_label'), 'first_name'); ?>
                        <?= form_input($first_name, set_value('first_name')); ?>
                        <?= form_error('first_name'); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-4">
                        <?= form_label(lang('users_last_name_label'), 'last_name'); ?>
                        <?= form_input($last_name, set_value('last_name')); ?>
                        <?= form_error('last_name'); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-4">
                        <?= form_label(lang('users_email_address_label'), 'email_address'); ?>
                        <?= form_input($email_address, set_value('email_address')); ?>
                        <?= form_error('email_address'); ?>
                    </div>
                </div>
                <div class="row hidden" id="user_extras">
                    <div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('users_job_title_label'), 'job_title'); ?>
                        <?= form_input($job_title, set_value('job_title')); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('users_employee_number_label'), 'employee_number'); ?>
                        <?= form_input($employee_number, set_value('employee_number')); ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'admin/users']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>