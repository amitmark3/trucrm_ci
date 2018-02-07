<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('masterdata/edit/' . $data_master['id'], ['id' => 'edit-masterdata-form']) ?>
            <div class="box-body">
                <div class="row">
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('masterdata_title_label'), 'head_title') ?>
                         <?= form_input(['name'=>'head_title','type' => 'text','id' => 'head_title',  'class' => 'form-control' ,'required'=>'required','maxlength'=>'250'], set_value('head_title', $data_master['head_title'])) ?>
                        <?= form_error('head_title') ?>
					</div>
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('masterdata_address_label'), 'address') ?>
                         <?= form_input(['name'=>'address','type' => 'text','id' => 'address',  'class' => 'form-control' ,'required'=>'required','maxlength'=>'250'], set_value('address', $data_master['address'])) ?>
                        <?= form_error('address') ?>
                    </div>
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('masterdata_landmark_label'), 'landmark') ?>
                         <?= form_input(['name'=>'landmark','type' => 'text','id' => 'landmark',  'class' => 'form-control' ,'required'=>'required','maxlength'=>'250'], set_value('landmark', $data_master['landmark'])) ?>
                        <?= form_error('landmark') ?>
					</div>
					<div class="form-group col-xs-12 col-md-6">
                            <?= form_label(lang('masterdata_countries_label'), 'countries_id'); ?>
                            <?= form_dropdown('countries_id', $countries_dropdown,set_value('countries_id', $data_master['countries_id']), ['class' => 'form-control selectpicker', 'data-container' => 'body']) ?>
                    </div>
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                            <?= form_label(lang('masterdata_states_label'), 'states_id'); ?>
                            <?= form_dropdown('states_id', $states_dropdown,set_value('states_id', $data_master['states_id']), ['class' => 'form-control selectpicker', 'data-container' => 'body']) ?>
                    </div>
					<div class="form-group col-xs-12 col-md-6">
                            <?= form_label(lang('masterdata_districts_label'), 'districts_id'); ?>
                            <?= form_dropdown('districts_id', $districts_dropdown,set_value('districts_id', $data_master['districts_id']), ['class' => 'form-control selectpicker', 'data-container' => 'body']) ?>
                    </div>
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('masterdata_pincode_label'), 'pincode') ?>
                         <?= form_input(['name'=>'pincode','type' => 'number','id' => 'pincode',  'class' => 'form-control' ,'required'=>'required','min'=>'1','max'=>'999999','maxlength'=>'6'], set_value('pincode', $data_master['pincode'])) ?>
                        <?= form_error('pincode') ?>
					</div>
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('masterdata_website_label'), 'website') ?>
                         <?= form_input(['name'=>'website','type' => 'url','id' => 'website',  'class' => 'form-control' ,'maxlength'=>'250'], set_value('website', $data_master['website'])) ?>
                        <?= form_error('website') ?>
                    </div>
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('masterdata_no_of_employee_label'), 'no_of_employee') ?>
                         <?= form_input(['name'=>'no_of_employee','type' => 'number','id' => 'no_of_employee',  'class' => 'form-control' ,'min'=>'0','max'=>'9999','maxlength'=>'4'], set_value('no_of_employee', $data_master['no_of_employee'])) ?>
                        <?= form_error('no_of_employee') ?>
					</div>
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('masterdata_no_of_pc_label'), 'no_of_pc') ?>
                         <?= form_input(['name'=>'no_of_pc','type' => 'number','id' => 'no_of_pc',  'class' => 'form-control' ,'min'=>'0','max'=>'9999','maxlength'=>'4'], set_value('no_of_pc', $data_master['no_of_pc'])) ?>
                        <?= form_error('no_of_pc') ?>
					</div>
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                            <?= form_label(lang('masterdata_industrytype_label'), 'industrytype_id'); ?>
                            <?= form_dropdown('industrytype_id', $industrytype_dropdown,set_value('industrytype_id', $data_master['industrytype_id']), ['class' => 'form-control selectpicker', 'data-container' => 'body']) ?>
                    </div>
					<div class="form-group col-xs-12 col-md-6">
                            <?= form_label(lang('masterdata_sub_industrytype_label'), 'sub_industrytype_id'); ?>
                            <?= form_dropdown('sub_industrytype_id', $sub_industrytype_dropdown,set_value('sub_industrytype_id', $data_master['sub_industrytype_id']), ['class' => 'form-control selectpicker', 'data-container' => 'body']) ?>
                    </div>
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-6">
                            <?= form_label(lang('masterdata_datasource_label'), 'datasource_id'); ?>
                            <?= form_dropdown('datasource_id', $datasource_dropdown,set_value('datasource_id', $data_master['datasource_id']), ['class' => 'form-control selectpicker', 'data-container' => 'body']) ?>
                    </div>
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <p><strong><?= lang('masterdata_status_label') ?></strong></p>
                        <div class="radio radio-success radio-inline">
                            <input type="radio" id="radio_yes" value="1" name="active" <?php if ($data_master['status'] == 1) echo ' checked="checked"' ?>>
                            <label for="radio_yes"> Yes </label>
                        </div>
                        <div class="radio radio-danger radio-inline">
                            <input type="radio" id="radio_no" value="0" name="active" <?php if ($data_master['status'] == 0) echo ' checked="checked"' ?>>
                            <label for="radio_no"> No </label>
                        </div>
                    </div>
                </div>
                
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'masterdata']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>