<?php $this->load->view('partials/emails/header') ?>

    <h2>Your new password for Trucrm</h2>
    <p>Hi <?= $first_name ?>,</p>
    <p>This is just a reminder of your new password.</p>
    <p>Password: <strong><?php echo $password ?></strong></p>
    <br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>