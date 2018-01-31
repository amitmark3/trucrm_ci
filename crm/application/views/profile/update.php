<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('profile/update', ['id' => 'update-profile-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('profile_first_name_label'), 'first_name'); ?>
                        <?= form_input($first_name, 'first_name', set_value('first_name')); ?>
                        <?= form_error('first_name'); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('profile_last_name_label'), 'last_name'); ?>
                        <?= form_input($last_name, 'last_name', set_value('last_name')); ?>
                        <?= form_error('last_name'); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('profile_email_address_label'), 'email_address'); ?>
                        <?= form_input($email_address, 'email_address', set_value('email_address')); ?>
                        <?= form_error('email_address'); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('profile_job_title_label'), 'job_title'); ?>
                        <?= form_input($job_title, 'job_title', set_value('job_title')); ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('profile_employee_number_label'), 'employee_number'); ?>
                        <?= form_input($employee_number, 'employee_number', set_value('employee_number')); ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'profile']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>