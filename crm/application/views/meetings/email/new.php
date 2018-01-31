<?php $this->load->view('partials/emails/header') ?>

    <h2><?= lang('new_meeting_email_subject'); ?></h2>
    <p><?= nl2br(sprintf(lang('new_meeting_email_body'), $name)); ?></p>
    <br>
    <p class="text-center"><a href="<?= site_url('meetings/view/' . $id) ?>" class="btn" target="_blank">View Meeting</a></p>

<?php $this->load->view('partials/emails/footer') ?>