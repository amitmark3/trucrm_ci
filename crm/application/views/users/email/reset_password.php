<?php $this->load->view('partials/emails/header') ?>

    <h2>Password Reset on Trucrm!</h2>
    <p>Your password has been reset.</p>
    <p>Password: <strong><?= $password; ?></strong></p>
    <br><br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>