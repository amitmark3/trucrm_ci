<?php $this->load->view('partials/emails/header') ?>

    <h2>Welcome to <?= $this->config->item('website_title') ?>!</h2>
    <p>
        Your account on Trucrm has been setup.<br><br>
        Your login details are as follows:
    </p>
    <p>
        Email Address: <strong><?= $email ?></strong><br>
        Password: <strong><?= $password ?></strong>
    </p>
    <br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>