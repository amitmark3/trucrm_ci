<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('admin/companies/add', ['id' => 'company-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('companies_name_label'), 'name') ?>
                        <?= form_input($name, 'name') ?>
                        <?= form_error('name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('companies_email_label'), 'email') ?>
                        <?= form_input($email, 'email') ?>
                        <?= form_error('email') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('companies_price_plan_label'), 'price_plan_id') ?>
                        <?= form_dropdown('price_plan_id', $price_plans) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <p><strong><?= lang('companies_active_label') ?></strong></p>
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
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <p><strong><?= lang('companies_setup_label') ?></strong></p>
                        <div class="radio radio-success radio-inline">
                            <input type="radio" id="radio_yes" value="7" name="setup" checked="checked">
                            <label for="radio_yes"> Yes </label>
                        </div>
                        <div class="radio radio-danger radio-inline">
                            <input type="radio" id="radio_no" value="0" name="setup">
                            <label for="radio_no"> No </label>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'admin/companies']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>