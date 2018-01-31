<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('admin/cronjobs/edit/'.$cronjob['id'], ['id' => 'edit-cron-job-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('name_label'), 'name') ?>
                        <?= form_input('name', set_value('name', $cronjob['name']), ['id' => 'name', 'class' => 'form-control']) ?>
                        <?= form_error('name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('command_label'), 'command') ?>
                        <?= form_input('command', set_value('command', $cronjob['command']), ['id' => 'command', 'class' => 'form-control']) ?>
                        <?= form_error('command') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('interval_label'), 'interval') ?>
                        <?= form_input('interval', set_value('interval', $cronjob['interval_sec']), ['id' => 'interval', 'class' => 'form-control', 'placeholder' => lang('interval_placeholder')]) ?>
                        <?= form_error('interval') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <p><?= form_label(lang('active_label')) ?></p>
                        <div class="radio radio-success radio-inline">
                            <input type="radio" id="radio_yes" value="1" name="active"<?= $cronjob['is_active'] == 1 ? ' checked="checked"' : '' ?>>
                            <label for="radio_yes">Yes</label>
                        </div>
                        <div class="radio radio-danger radio-inline">
                            <input type="radio" id="radio_no" value="0" name="active"<?= $cronjob['is_active'] == 0 ? ' checked="checked"' : '' ?>>
                            <label for="radio_no">No</label>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'admin/cronjobs']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>