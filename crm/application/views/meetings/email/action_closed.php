<?php $this->load->view('partials/emails/header') ?>

    <h2>Meeting Action Closed</h2>
    <p>The meeting action "<?= $meeting_action_details ?>" was closed.</p>
    <p>The closure details are:</p>
    <p><?= nl2br($meeting_close_details) ?></p>
    <br>
    <p class="text-center"><a href="<?= site_url('meetings/view_action/' . $action_id) ?>" class="btn" target="_blank">View Meeting Action</a></p>

<?php $this->load->view('partials/emails/footer') ?>