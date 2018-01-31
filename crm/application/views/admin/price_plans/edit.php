<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('admin/price_plans/edit/' . $price_plan['id'], ['id' => 'edit-price-plan-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('name_label'), 'name') ?>
                        <?= form_input($name, set_value('name', $price_plan['name'])) ?>
                        <?= form_error('name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('description_label'), 'description') ?>
                        <?= form_textarea($description, set_value('description', $price_plan['description'])) ?>
                        <?= form_error('description') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('price_label'), 'price') ?>
                        <?= form_input($price, set_value('price', $price_plan['price'])) ?>
                        <?= form_error('price') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <?= form_label(lang('space_label'), 'space') ?>
                        <?= form_input($space, set_value('price', $price_plan['space_allotted'])) ?>
                        <div class="radio radio-default radio-inline">
                            <input type="radio" id="radio_mb" value="mb" name="space_unit" <?= $price_plan['space_unit'] == 'MB' ? 'checked="checked"' : '' ?>>
                            <label for="radio_mb">MB</label>
                        </div>
                        <div class="radio radio-default radio-inline">
                            <input type="radio" id="radio_gb" value="gb" name="space_unit" <?= $price_plan['space_unit'] == 'GB' ? 'checked="checked"' : '' ?>>
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