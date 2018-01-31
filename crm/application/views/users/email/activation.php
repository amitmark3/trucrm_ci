<?php $this->load->view('partials/emails/header') ?>

    <h2><?= sprintf(lang('email_welcome_heading'), $first_name);?></h2>
    <p>Your login details are:</p>
    <p>
        Email Address: <strong><?= $email; ?></strong><br>
        Password: <strong><?= $password; ?></strong>
    </p>
    <br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>