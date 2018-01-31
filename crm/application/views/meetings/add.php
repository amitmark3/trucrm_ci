<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('meetings/add', ['id' => 'add-meeting-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('meetings_name_label'), 'name') ?>
                        <?= form_input('name', set_value('name'), ['placeholder' => lang('meetings_name_placeholder')]) ?>
                        <?= form_error('name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('meetings_description_label'), 'description') ?>
                        <?= form_textarea('description', set_value('description'), ['placeholder' => lang('meetings_description_placeholder')]) ?>
                        <?= form_error('description') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('meetings_date_label'), 'date') ?>
                        <div class="input-group date">
                            <input type="text" class="form-control" name="date" id="date" value="<?= set_value('date', date('Y-m-d')) ?>">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                        <?= form_error('date') ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'meetings']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>