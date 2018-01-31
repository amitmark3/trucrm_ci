<div class="row">
    <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="box box-danger box-onboarding">
            <div class="box-body text-center">
                <?php $this->load->view('setup/partials/data_progress', ['current_step' => 6]); ?>
                <h1 class="uppercase">Setup Complete!</h1>
                <p class="intro">The setup process is now complete. Click continue below to see your dashboard.</p>
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3">
                        <?= form_open('setup/finish') ?>
                            <?= form_submit('submit', 'Continue', ['class' => 'btn btn-success btn-lg btn-block']); ?>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>