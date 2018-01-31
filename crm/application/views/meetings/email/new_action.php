<?php $this->load->view('partials/emails/header') ?>

    <h2><?= lang('new_meeting_action_email_subject'); ?></h2>
    <p><?= nl2br(sprintf(lang('new_meeting_action_email_body'), $first_name)); ?></p>
    <p>The details of the action are:</p>
    <p>"<?= nl2br($action_details) ?>"</p>
    <br>
    <p class="text-center"><a href="<?= site_url('meetings/view_action/' . $action_id) ?>" class="btn" target="_blank">View Meeting Action</a></p>

<?php $this->load->view('partials/emails/footer') ?>