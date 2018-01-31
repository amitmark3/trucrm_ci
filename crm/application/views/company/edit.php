<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('company/edit', ['id' => 'edit-company-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="name"><?= lang('company_name_label') ?></label>
                        <?= form_input($name) ?>
                        <?= form_error('name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="description"><?= lang('company_description_label') ?></label>
                        <?= form_textarea($description) ?>
                        <?= form_error('description') ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="address"><?= lang('company_address_label') ?></label>
                        <?= form_textarea($address) ?>
                        <?= form_error('address') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="phone_number"><?= lang('company_phone_number_label') ?></label>
                        <?= form_input($phone_number) ?>
                        <?= form_error('phone_number') ?>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="website_url"><?= lang('company_website_address_label') ?></label>
                        <?= form_input($website_url) ?>
                        <?= form_error('website_url') ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'company']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>