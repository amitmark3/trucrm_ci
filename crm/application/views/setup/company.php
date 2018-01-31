<div class="row">
    <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="box box-danger box-onboarding">
            <div class="box-body text-center">
                <?php $this->load->view('setup/partials/data_progress', ['current_step' => 0]); ?>
                <h1 class="uppercase">Welcome to Trucrm</h1>
                <p class="intro">Thanks for signing up to Trucrm. Just a few more steps to complete.</p>
                <h3 class="uppercase">Let's Get You Started</h3>
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3">
                        <?= form_open('setup/company') ?>
                            <?= form_submit('submit', 'Import Users', ['class' => 'btn btn-success btn-lg btn-block']); ?>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>