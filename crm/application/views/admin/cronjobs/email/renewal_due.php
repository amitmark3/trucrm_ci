<?php $this->load->view('partials/emails/header') ?>

    <h2>Renewal Due Soon!</h2>
    <p>Hi <?= $first_name ?>,</p>
    <p>The price plan for your company is due for renewal next month.</p>
    <p>Please login to Trucrm and renew it as soon as possible.</p>
    <br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>