<div class="row">
    <div class="col-xs-12 col-lg-9">
        <div class="box">
            <div class="box-body">
                <?= form_open('admin/settings'); ?>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('name_label'), 'name') ?>
                        <?= form_input('name', set_value('name'), ['id' => 'name', 'class' => 'form-control']) ?>
                        <?= form_error('name') ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('email_label'), 'email') ?>
                        <?= form_input('email', set_value('email'), ['id' => 'email', 'class' => 'form-control']) ?>
                        <?= form_error('email') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('phone_label'), 'phone_number') ?>
                        <?= form_input('phone_number', set_value('phone_number'), ['id' => 'phone_number', 'class' => 'form-control']) ?>
                        <?= form_error('phone_number') ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('phone_link_label'), 'phone_link_number') ?>
                        <?= form_input('phone_link_number', set_value('phone_link_number'), ['id' => 'phone_link_number', 'class' => 'form-control']) ?>
                        <?= form_error('phone_link_number') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('url_label'), 'url') ?>
                        <?= form_input('url', set_value('url'), ['id' => 'url', 'class' => 'form-control', 'placeholder' => lang('url_placeholder')]) ?>
                        <?= form_error('url') ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('url_link_label'), 'url_link') ?>
                        <?= form_input('url_link', set_value('url_link'), ['id' => 'url_link', 'class' => 'form-control', 'placeholder' => lang('url_placeholder')]) ?>
                        <?= form_error('url_link') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('facebook_label'), 'facebook') ?>
                        <?= form_input('facebook', set_value('facebook'), ['id' => 'facebook', 'class' => 'form-control', 'placeholder' => lang('url_placeholder')]) ?>
                        <?= form_error('facebook') ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('twitter_label'), 'twitter') ?>
                        <?= form_input('twitter', set_value('twitter'), ['id' => 'twitter', 'class' => 'form-control', 'placeholder' => lang('url_placeholder')]) ?>
                        <?= form_error('twitter') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('developer_name_label'), 'developer_name') ?>
                        <?= form_input('developer_name', set_value('developer_name'), ['id' => 'developer_name', 'class' => 'form-control']) ?>
                        <?= form_error('developer_name') ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('developer_email_label'), 'developer_email') ?>
                        <?= form_input('developer_email', set_value('developer_email'), ['id' => 'developer_email', 'class' => 'form-control']) ?>
                        <?= form_error('developer_email') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <p><?= form_label(lang('send_emails_label'), 'send_emails') ?></p>
                        <div class="radio radio-success radio-inline">
                            <input type="radio" id="radio_yes" value="1" name="send_emails" checked="checked">
                            <label for="radio_yes"> Yes </label>
                        </div>
                        <div class="radio radio-danger radio-inline">
                            <input type="radio" id="radio_no" value="0" name="send_emails">
                            <label for="radio_no"> No </label>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <p><?= form_label(lang('maintenance_label'), 'maintenance_mode') ?></p>
                        <div class="radio radio-danger radio-inline">
                            <input type="radio" id="radio_on" value="1" name="maintenance_mode">
                            <label for="radio_on"> On </label>
                        </div>
                        <div class="radio radio-success radio-inline">
                            <input type="radio" id="radio_off" value="0" name="maintenance_mode" checked="checked">
                            <label for="radio_off"> Off </label>
                        </div>
                    </div>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>