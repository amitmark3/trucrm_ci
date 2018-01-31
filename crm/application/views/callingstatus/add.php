<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('calling/status/add', ['id' => 'add-calling-status-form']) ?>
            <div class="box-body">
				<div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('calling_status_title_label'), 'callingstatus_name') ?>
                         <?= form_input(['name'=>'callingstatus_name','type' => 'text','id' => 'callingstatus_name',  'class' => 'form-control' ,'required'=>'required','maxlength'=>'150'], set_value('callingstatus_name')) ?>
                        <?= form_error('callingstatus_name') ?>
                    </div>
					<div class="form-group col-xs-12 col-md-6">
                            <?= form_label(lang('calling_status_sub_title_label'), 'parent_id'); ?>
                            <?= form_dropdown('parent_id', $callingstatus_dropdown, null, ['id' => 'parent_id']); ?>
                    </div>
                </div>
				<div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <p><strong><?= lang('calling_status_label') ?></strong></p>
                        <div class="radio radio-success radio-inline">
                            <input type="radio" id="radio_yes" value="1" name="active" checked="checked">
                            <label for="radio_yes"> Yes </label>
                        </div>
                        <div class="radio radio-danger radio-inline">
                            <input type="radio" id="radio_no" value="0" name="active">
                            <label for="radio_no"> No </label>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'calling/status']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>