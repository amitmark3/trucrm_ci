<?php $this->load->view('partials/emails/header') ?>

    <h2><?= $company_name ?> has successfully made payment.</h2>
    <p>The details are:</p>
    <p>
        Stripe Charge ID: <?= $stripe_charge_id ?><br />
        Stripe Customer ID: <?= $stripe_customer_id ?><br />
        Amount: <?= $amount ?><br />
        Price Plan: <?= $description ?>
    </p>

<?php $this->load->view('partials/emails/footer') ?>