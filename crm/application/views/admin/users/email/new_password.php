<?php $this->load->view('partials/emails/header') ?>

	<h2>Your password on Trucrm has been changed.</h2>
    <p>Hi <?= $first_name ?>,</p>
    <p>Your new password is <strong><?= $password ?></strong>.</p>
    <br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>