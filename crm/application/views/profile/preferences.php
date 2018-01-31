<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="box">
            <?= form_open('profile/preferences', ['id' => 'change-preferences-form']) ?>
            <?= form_hidden('name', 'value') ?>
            <div class="box-body">
                <p><strong>Notify me by</strong></p>
                <div class="checkbox checkbox-success">
                    <input type="checkbox" id="checkbox_email" value="email" name="email" <?php if ($user['notify_by'] == 'email' || $user['notify_by'] == 'both' || $user['notify_by'] == '') { echo 'checked="checked"'; } ?>>
                    <label for="checkbox_email">Email</label>
                </div>
                <div class="checkbox checkbox-success">
                    <input type="checkbox" id="checkbox_app" value="website" name="website" <?php if ($user['notify_by'] == 'website' || $user['notify_by'] == 'both' || $user['notify_by'] == '') { echo 'checked="checked"'; } ?>>
                    <label for="checkbox_app">Website</label>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'profile']) ?>
            <?= form_close() ?>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Help</h3>
            </div>
            <div class="box-body">
                <p>Here you can choose how you would like to be notified of updates, changes, submissions, etc.</p>
                <p>If you wish to receive an email, choose 'Email'.</p>
                <p>If you choose 'Website' then all notifications will appear in the top right of the page by clicking on the Bell icon.</p>
            </div>
        </div>
    </div>
</div>