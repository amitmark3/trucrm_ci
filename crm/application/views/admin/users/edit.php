<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('admin/users/edit/'.$user['id'], ['id' => 'edit-user-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-md-4">
                        <?= form_label(lang('users_first_name_label'), 'first_name'); ?>
                        <?= form_input($first_name, set_value('first_name', $user['profile']['first_name'])); ?>
                        <?= form_error('first_name'); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-4">
                        <?= form_label(lang('users_last_name_label'), 'last_name'); ?>
                        <?= form_input($last_name, set_value('last_name', $user['profile']['last_name'])); ?>
                        <?= form_error('last_name'); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-4">
                        <?= form_label(lang('users_email_address_label'), 'email_address'); ?>
                        <?= form_input($email_address, set_value('email', $user['email'])); ?>
                        <?= form_error('email_address'); ?>
                    </div>
                </div>
                <div class="row" id="user_extras">
                    <div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('users_job_title_label'), 'job_title'); ?>
                        <?= form_input($job_title, set_value('job_title', $user['profile']['job_title'])); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('users_employee_number_label'), 'employee_number'); ?>
                        <?= form_input($employee_number, set_value('employee_number', $user['profile']['employee_number'])); ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'admin/users']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>