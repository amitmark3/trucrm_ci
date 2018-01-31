<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('profile/change_password', ['id' => 'change-password-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('profile_old_password_label'), 'old_password') ?>
                        <?= form_password('old_password') ?>
                        <?= form_error('old_password') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('profile_new_password_label'), 'new_password') ?>
                        <?= form_password('new_password') ?>
                        <?= form_error('new_password') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('profile_new_password_confirm_label'), 'new_password_confirm') ?>
                        <?= form_password('new_password_confirm') ?>
                        <?= form_error('new_password_confirm') ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'profile']) ?>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>