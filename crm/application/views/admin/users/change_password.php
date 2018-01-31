<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('admin/users/change_password/'.$user['id'], ['id' => 'change-password-form']) ?>
            <div class="box-body">
                <br>
                <div class="row">
                    <div class="form-group has-feedback col-xs-12 col-lg-4">
                        <?= form_label('New Password <span class="asterisk">*</span>', 'new_password') ?>
                        <?= form_password($new_password) ?>
                        <?= form_error('new_password') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-xs-12 col-lg-4">
                        <?= form_label('Confirm New Password <span class="asterisk">*</span>', 'confirm_new_password') ?>
                        <?= form_password($confirm_new_password) ?>
                        <?= form_error('confirm_new_password') ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'admin/users/view/'.$user['id']]) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>
