<?php $this->load->view('partials/emails/header') ?>

    <h2>Company Registration Error!</h2>
    <p>There was a problem creating the company uploads folder.</p>
    <p>The company name is <?= $name ?> and the folder string is <?= $string ?>.</p>
    <br>

<?php $this->load->view('partials/emails/footer') ?>