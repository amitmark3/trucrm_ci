<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('admin/payments/add', ['id' => 'add-payment-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6 form-group">
                        <?= form_label(lang('company'), 'company_id') ?>
                        <?= form_dropdown('company_id', $companies, set_value('company_id'), ['class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 form-group">
                        <?= form_label(lang('price_plan'), 'price_plan_id') ?>
                        <?= form_dropdown('price_plan_id', $price_plans, set_value('price_plan_id'), ['class' => 'form-control']) ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'admin/payments']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>