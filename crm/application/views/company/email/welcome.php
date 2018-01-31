<?php $this->load->view('partials/emails/header') ?>

    <h2>Welcome to Trucrm by Mark3!</h2>
    <p>Your company account on Trucrm has been setup.</p>
    <p>Your login details are as follows:</p>
    <p>
        <strong>Email Address:</strong> <?= $email; ?><br>
        <strong>Password:</strong> <?= $password; ?>
    </p>
    <br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>