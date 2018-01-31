<div class="row">
    <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="box box-danger box-onboarding">
            <div class="box-body text-center">
                <?php $this->load->view('setup/partials/billing_progress', ['current_step' => 2]); ?>
                <h1 class="uppercase" style="margin-bottom:0.6em;">Checkout</h1>
                <img src="<?= site_url('assets/img/stripe.png') ?>" alt="Powered By Stripe"> <a href="#" title="Click for more info" data-toggle="modal" data-target="#stripe_modal"><i class="fa fa-info-circle fa-lg"></i></a>
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <div class="box box-danger box-solid box-order-summary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Order Summary</h3>
                            </div>
                            <div class="box-body">
                                <p><?= $price_plan['name'] ?> Plan</p>
                                <p>Total: <i class="fa fa-inr"></i><?= $price_plan['price'] ?></p>
                                <form action="<?= site_url('setup/stripe_charge') ?>" method="POST">
                                    <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="<?= $this->config->item('publishableKey') ?>"
                                    data-amount="<?= $price_plan['price'] * 100 ?>"
                                    data-email="<?= $company_admin['email'] ?>"
                                    data-name="Trucrm"
                                    data-description="<?= $this->config->item('website_title').' '.$price_plan['name'].' Plan' ?>"
                                    data-locale="auto"
                                    data-currency="eur">
                                    </script>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php // $this->load->view('setup/partials/faqs'); ?>
</div>
<div class="modal fade" id="stripe_modal" tabindex="-1" role="dialog" aria-labelledby="Stripe Help" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">What is Stripe?</h4>
            </div>
            <div class="modal-body">
                <p>Stripe is a payment processor used to accept payments online.</p>
                <p>We use Stripe because it means we do not need to store credit / debit card details ourselves. This make the payment process more secure as Stripe deal with all the neccessary laws when it comes to collecting and storing card details.</p>
                <p class="alert alert-info">Your card details do not touch our server at any time.</p>
                <p><a href="https://stripe.com/about" target="_blank">Read more about Stripe here</a>.</p>
                <hr>
                <p>If you have any concerns about entering your payment details or prefer to pay by other means please feel free to contact us on <a href="tel:<?= $this->config->item('website_phone_number_link') ?>"><strong><?= $this->config->item('website_phone_number') ?></strong></a>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>