<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('users/edit/'.$user['id'], ['id' => 'edit-user-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <?= form_label(lang('users_first_name_label'), 'first_name'); ?>
                            <?= form_input($first_name, 'first_name'); ?>
                            <?= form_error('first_name'); ?>
                        </div>
                        <div class="form-group">
                            <?= form_label(lang('users_last_name_label'), 'last_name'); ?>
                            <?= form_input($last_name, 'last_name'); ?>
                            <?= form_error('last_name'); ?>
                        </div>
                        <div class="form-group">
                            <?= form_label(lang('users_email_address_label'), 'email_address'); ?>
                            <?= form_input($email_address, 'email_address'); ?>
                            <?= form_error('email_address'); ?>
                        </div>
                        <div class="form-group">
                            <?= form_label(lang('users_job_title_label'), 'job_title'); ?>
                            <?= form_input($job_title, 'job_title'); ?>
                        </div>
                        <div class="form-group">
                            <?= form_label(lang('users_employee_number_label'), 'employee_number'); ?>
                            <?= form_input($employee_number, 'employee_number'); ?>
                        </div>
                        <div class="form-group">
                            <?= form_label(lang('users_group_label'), 'group'); ?>
                            <?= form_dropdown('group_id', $groups, $group_id, ['id' => 'group']); ?>
                        </div>
                        <?php $class = ($group_id > 2) ? '' : ' hidden'; ?>
                        <div class="form-group<?= $class ?>" id="departments_dropdown">
                            <?= form_label(lang('users_department_label'), 'department_id'); ?>
                            <?= form_dropdown('department_id', $departments, $user['department_id'], ['id' => 'department_id']); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'users']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>