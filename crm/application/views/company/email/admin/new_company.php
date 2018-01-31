<?php $this->load->view('partials/emails/header') ?>

    <h2>A New Company Has Registered on Trucrm</h2>
    <p>The company details are:</p>
    <p>
        Company Name: <?= $name ?><br />
        Company Email Address: <?= $email ?>
    </p>

<?php $this->load->view('partials/emails/footer') ?>