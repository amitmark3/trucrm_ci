<div class="row">
    <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="box">
            <div class="box-body">
                <br>
                <?php foreach ($price_plans as $plan) : ?>
                <div class="col-xs-12 col-sm-6">
                    <div class="plan">
                        <div class="plan-header text-center">
                             <h3 style="margin: 0.5em 0;"><?= $plan['name'] ?></h3>
                            <div class="plan-price"><i class="fa fa-inr"></i><?= $plan['price'] ?></div> Per Year
                        </div>
                        <?php if ($plan['description']) : ?>
                            <div class="plan-body">
                                <p style="font-size: 16px; margin: 2em;"><?= $plan['description'] ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="plan-footer text-center">
                            <?php if ($plan['price'] > $old_plan['price']) : ?>
                            <?= form_open('company/change_price_plan'); ?>
                                <input type="hidden" name="price_plan_id" value="<?= $plan['id'] ?>">
                                <!--input type="submit" value="Choose Plan" class="btn btn-block btn-lg"-->
								<input type="submit" value="Choose Plan" class="btn btn-block btn-lg" disabled="disabled">
                            <?= form_close(); ?>
                            <?php elseif ($plan['price'] == $old_plan['price']) : ?>
                                <a class="btn btn-success btn-block btn-lg" style="border-radius: 0; border: 0;" disabled="disabled">Current Plan</a>
                            <?php else: ?>
                                <a class="btn btn-warning btn-block btn-lg" style="border-radius: 0; border: 0;" disabled="disabled" title="It is not possible to downgrade your price plan at this time." data-toggle="tooltip" data-placement="bottom">Unavailable</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>