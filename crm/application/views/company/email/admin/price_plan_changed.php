<?php $this->load->view('partials/emails/header') ?>

    <h2><?= $company ?> Has Changed Their Price Plan</h2>
    <p>The price plan change is:</p>
    <p>
        Old Plan: <?= $old_plan ?><br />
        New Plan: <?= $new_plan ?>
    </p>

<?php $this->load->view('partials/emails/footer') ?>