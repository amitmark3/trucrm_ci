<div class="row">
    <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="box box-danger box-onboarding">
            <div class="box-body text-center">
                <?php $this->load->view('setup/partials/billing_progress', ['current_step' => 3]); ?>
                <h1 class="uppercase"><?= lang('setup_confirmation_heading') ?></h1>
                <p class="intro"><?= lang('setup_billing_complete') ?></p>
                <!-- <p>You payment reference number is XXXXXX. Please print this page for your records.</p> -->
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3">
                        <?= form_open('setup/confirmation') ?>
                            <?= form_submit('submit', 'Import Users', ['class' => 'btn btn-success btn-lg btn-block']); ?>
                        <?= form_close() ?>
                        <br>
                        <?= form_open('setup/import', ['role' => 'form'], ['step' => 6]) ?>
                            <div class="form-group">
                                <input type="submit" value="Finish Setup" class="btn btn-lg btn-block btn-info" />
                            </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>