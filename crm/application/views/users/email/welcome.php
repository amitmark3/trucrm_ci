<?php $this->load->view('partials/emails/header') ?>

    <h2>Welcome to Trucrm by Mark3</h2>
    <p>Your account has been setup.</p>
    <p>Your login details are:</p>
    <p>
        Email Address: <strong><?= $email ?></strong><br>
        Password: <strong><?= $password ?></strong>
    </p>
    <br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>