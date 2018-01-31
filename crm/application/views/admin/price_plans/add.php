<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('admin/price_plans/add', ['id' => 'add-price-plan-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('name_label'), 'name', set_value('name')) ?>
                        <?= form_input($name) ?>
                        <?= form_error('name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('description_label'), 'description') ?>
                        <?= form_textarea($description) ?>
                        <?= form_error('description') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('price_label'), 'price') ?>
                        <?= form_input($price) ?>
                        <?= form_error('price') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('space_label'), 'space') ?>
                        <?= form_input($space) ?>
                        <div class="radio radio-default radio-inline">
                            <input type="radio" id="radio_mb" value="mb" name="space_unit" checked="checked">
                            <label for="radio_mb">MB</label>
                        </div>
                        <div class="radio radio-default radio-inline">
                            <input type="radio" id="radio_gb" value="gb" name="space_unit">
                            <label for="radio_gb">GB</label>
                        </div>
                        <?= form_error('space') ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'admin/price_plans']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>