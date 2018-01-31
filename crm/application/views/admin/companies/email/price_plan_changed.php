<?php $this->load->view('partials/emails/header') ?>

    <h2>Your price plan on Trucrm has been changed.</h2>
    <p>Hi,</p>
    <p>The price plan for your company has been changed by an administrator.</p>
    <p>
        <strong>Previous Price Plan:</strong> <?= $old_price_plan ?><br>
        <strong>New Price Plan:</strong> <?= $new_price_plan ?>
    </p>
    <br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>