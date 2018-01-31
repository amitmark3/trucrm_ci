<div class="row">
    <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="box box-danger box-onboarding">
            <div class="box-body text-center">
                <?php $this->load->view('setup/partials/billing_progress', ['current_step' => 1]); ?>
                <h1 class="uppercase">Choose Price Plan</h1>
                <p class="intro">Choose a price plan from the options below.</p>
                <?php foreach ($price_plans as $plan) : ?>
                <div class="col-xs-12 col-sm-6">
                    <div class="plan">
                        <div class="plan-header text-center">
                            <h3 style="margin: 0.5em 0;"><?= $plan['name'] ?></h3>
                            <div class="plan-price"><i class="fa fa-inr"></i><?= $plan['price'] ?></div> Per Year
                        </div>
                        <?php if ($plan['description']) : ?>
                            <div class="plan-body">
                                <p style="margin-bottom:2em;"><?= $plan['description'] ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="plan-footer text-center">
                            <?= form_open('setup/price_plan'); ?>
                                <input type="hidden" name="price_plan_id" value="<?= $plan['id'] ?>">
                                <input type="submit" value="Choose Plan" class="btn btn-block btn-lg">
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>