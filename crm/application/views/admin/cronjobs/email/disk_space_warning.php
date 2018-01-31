<?php $this->load->view('partials/emails/header') ?>

    <h2>Disk Space Almost Full!</h2>
    <p>Hi <?= $first_name ?>,</p>
    <p>Your allotted disk space is almost full (75% used).</p>
    <p>Consider upgrading to a more advanced price plan or delete some of the files.</p>
    <br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>