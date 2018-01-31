<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('users/add', ['id' => 'add-user-form']) ?>
            <div class="box-body">
                <?= form_error('department_id'); ?>
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <?= form_label(lang('users_first_name_label'), 'first_name'); ?>
                            <?= form_input($first_name, 'first_name', set_value('first_name')); ?>
                            <?= form_error('first_name'); ?>
                        </div>
                        <div class="form-group">
                            <?= form_label(lang('users_last_name_label'), 'last_name'); ?>
                            <?= form_input($last_name, 'last_name', set_value('last_name')); ?>
                            <?= form_error('last_name'); ?>
                        </div>
                        <div class="form-group">
                            <?= form_label(lang('users_email_address_label'), 'email_address'); ?>
                            <?= form_input($email_address, 'email_address', set_value('email_address')); ?>
                            <?= form_error('email_address'); ?>
                        </div>
                        <div class="form-group">
                            <?= form_label(lang('users_job_title_label'), 'job_title'); ?>
                            <?= form_input($job_title, 'job_title', set_value('job_title')); ?>
                        </div>
                        <div class="form-group">
                            <?= form_label(lang('users_employee_number_label'), 'employee_number'); ?>
                            <?= form_input($employee_number, 'employee_number', set_value('employee_number')); ?>
                        </div>
                        <div class="form-group">
                            <?= form_label(lang('users_group_label'), 'group_id'); ?>
                            <?= form_dropdown('group_id', $groups, null, ['id' => 'group']); ?>
                        </div>
                        <div class="form-group" id="departments_dropdown">
                            <?= form_label(lang('users_department_label'), 'department_id'); ?>
                            <?= form_dropdown('department_id', $departments, null, ['id' => 'department_id']); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'users']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>