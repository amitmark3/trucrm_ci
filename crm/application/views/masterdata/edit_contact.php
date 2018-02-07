<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('masterdata/mdcedit/' .$md_contact['data_master_id'].'/'. $md_contact['id'], ['id' => 'edit-masterdata-form']) ?>
            <div class="box-body">
                <div class="row">
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('md_contact_job_title_label'), 'job_title') ?>
                         <?= form_input(['name'=>'job_title','type' => 'text','id' => 'job_title',  'class' => 'form-control','maxlength'=>'100'], set_value('job_title', $md_contact['job_title'])) ?>
                        <?= form_error('job_title') ?>
					</div>
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('md_contact_salutation_label'), 'salutation') ?>
                         <?= form_input(['name'=>'salutation','type' => 'text','id' => 'salutation',  'class' => 'form-control' ,'maxlength'=>'50'], set_value('salutation', $md_contact['salutation'])) ?>
                        <?= form_error('salutation') ?>
                    </div>
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('md_contact_first_name_label'), 'first_name') ?>
                         <?= form_input(['name'=>'first_name','type' => 'text','id' => 'first_name',  'class' => 'form-control' ,'required'=>'required','maxlength'=>'100'], set_value('first_name', $md_contact['first_name'])) ?>
                        <?= form_error('first_name') ?>
					</div>
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('md_contact_last_name_label'), 'last_name') ?>
                         <?= form_input(['name'=>'last_name','type' => 'text','id' => 'last_name',  'class' => 'form-control' ,'maxlength'=>'100'], set_value('last_name', $md_contact['last_name'])) ?>
                        <?= form_error('last_name') ?>
					</div>
					
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('md_contact_phone_office_label'), 'phone_office') ?>
                         <?= form_input(['name'=>'phone_office','type' => 'text','id' => 'phone_office',  'class' => 'form-control' ,'required'=>'required','maxlength'=>'15'], set_value('phone_office', $md_contact['phone_office'])) ?>
                        <?= form_error('phone_office') ?>
					</div>
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('md_contact_phone_personal_label'), 'phone_personal') ?>
                         <?= form_input(['name'=>'phone_personal','type' => 'text','id' => 'phone_personal',  'class' => 'form-control' ,'maxlength'=>'15'], set_value('phone_personal', $md_contact['phone_personal'])) ?>
                        <?= form_error('phone_personal') ?>
					</div>					
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('md_contact_email_office_label'), 'email_office') ?>
                         <?= form_input(['name'=>'email_office','type' => 'email','id' => 'email_office',  'class' => 'form-control' ,'maxlength'=>'100'], set_value('email_office', $md_contact['email_office'])) ?>
                        <?= form_error('email_office') ?>
					</div>
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('md_contact_email_personal_label'), 'email_personal') ?>
                         <?= form_input(['name'=>'email_personal','type' => 'email','id' => 'email_personal', 'class' => 'form-control' ,'maxlength'=>'100'], set_value('email_personal', $md_contact['email_personal'])) ?>
                        <?= form_error('email_personal') ?>
					</div>					
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('md_contact_department_label'), 'department') ?>
                         <?= form_input(['name'=>'department','type' => 'text','id' => 'department',  'class' => 'form-control' ,'required'=>'required','maxlength'=>'100'], set_value('department', $md_contact['department'])) ?>
                        <?= form_error('department') ?>
					</div>
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('md_contact_emp_no_label'), 'emp_no') ?>
                         <?= form_input(['name'=>'emp_no','type' => 'text','id' => 'emp_no',  'class' => 'form-control' ,'maxlength'=>'50'], set_value('emp_no', $md_contact['emp_no'])) ?>
                        <?= form_error('emp_no') ?>
					</div>					
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                            <?= form_label(lang('md_contact_callingstatus_label'), 'callingstatus_id'); ?>
                            <?= form_dropdown('callingstatus_id', $callingstatus_dropdown,set_value('callingstatus_id', $md_contact['callingstatus_id']), ['class' => 'form-control selectpicker', 'data-container' => 'body']) ?>
                    </div>
					<div class="form-group col-xs-12 col-md-6">
                            <?= form_label(lang('md_contact_sub_callingstatus_label'), 'sub_callingstatus_id'); ?>
                            <?= form_dropdown('sub_callingstatus_id', $sub_callingstatus_dropdown,set_value('sub_callingstatus_id', $md_contact['sub_callingstatus_id']), ['class' => 'form-control selectpicker', 'data-container' => 'body']) ?>
                    </div>
                </div>
				
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
						<?= form_label(lang('md_contact_job_function_label'), 'job_function'); ?>
						<?= form_dropdown('job_function', $job_function_dropdown,set_value('job_function', $md_contact['job_function']), ['class' => 'form-control selectpicker', 'data-container' => 'body']) ?>
                    </div>
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <p><strong><?= lang('masterdata_status_label') ?></strong></p>
                        <div class="radio radio-success radio-inline">
                            <input type="radio" id="radio_yes" value="1" name="active" <?php if ($md_contact['status'] == 1) echo ' checked="checked"' ?>>
                            <label for="radio_yes"> Yes </label>
                        </div>
                        <div class="radio radio-danger radio-inline">
                            <input type="radio" id="radio_no" value="0" name="active" <?php if ($md_contact['status'] == 0) echo ' checked="checked"' ?>>
                            <label for="radio_no"> No </label>
                        </div>
                    </div>
                </div>
                
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'masterdata/view/'. $md_contact['data_master_id']]) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>