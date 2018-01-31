<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('meetings/edit_action/' . $action['id'], ['id' => 'edit-meeting-action-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-5">
                        <?= form_label(lang('meetings_actions_details_label'), 'details') ?>
                        <?= form_textarea('details', set_value('details', $action['details'])) ?>
                        <?= form_error('details') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-5">
                        <?= form_label(lang('meetings_actions_close_details_label'), 'close_details') ?>
                        <?= form_textarea('close_details', set_value('close_details', $action['close_details'])) ?>
                        <?= form_error('close_details') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-5">
                        <?= form_label(lang('meetings_actions_priority_label'), 'priority') ?>
                        <?= form_dropdown('priority', ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'], set_value('priority', $action['priority']), ['class' => 'form-control']) ?>
                        <?= form_error('priority') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-5">
                        <?= form_label(lang('meetings_actions_status_label'), 'status') ?>
                        <?= form_dropdown('status', ['open' => 'Open', 'in_progress' => 'In Progress', 'closed' => 'Closed'], set_value('status', $action['status']), ['class' => 'form-control']) ?>
                        <?= form_error('status') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-5">
                        <?= form_label(lang('meetings_actions_ecd_label'), 'ecd') ?>
                        <div class="input-group date">
                            <input type="text" class="form-control" name="ecd" id="ecd" value="<?= set_value('ecd', $action['ecd']) ?>">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                        <?= form_error('ecd') ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'meetings/actions']) ?>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>