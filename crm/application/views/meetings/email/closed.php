<?php $this->load->view('partials/emails/header') ?>

    <h2><?= lang('meeting_closed_email_subject'); ?></h2>
    <p><?= nl2br(sprintf(lang('meeting_closed_email_body'), $meeting['name'])); ?></p>
    <p><strong>Details</strong></p>
    <div class="datagrid">
        <table>
            <tbody>
                <tr>
                    <td width="30%">Meeting Name</td>
                    <td width="70%"><?= $meeting['name'] ?></td>
                </tr>
                <tr class="alt">
                    <td>Description</td>
                    <td><?= $meeting['description'] ?></td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td><?= date('jS F Y', strtotime($meeting['date'])) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <p><strong>Attendees</strong></p>
    <div class="datagrid">
        <table>
            <thead>
                <tr>
                    <th width="33%">Name</th>
                    <th width="67%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; foreach ($meeting['attendees'] as $attendee): ?>
                <tr<?= $i & 1 ? ' class="alt"' : '' ?>>
                    <td><?= $attendee['profile']['first_name'] ?> <?= $attendee['profile']['last_name'] ?></td>
                    <td><?= ucfirst($attendee['status']) ?></td>
                </tr>
                <?php $i++; endforeach; ?>
            </tbody>
        </table>
    </div>
    <p><strong>Agendas</strong></p>
    <div class="datagrid">
        <table>
            <thead>
                <tr>
                    <th width="33%">Topic</th>
                    <th width="34%">Presented By</th>
                    <th width="33%">Allotted Time</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 0; foreach ($meeting['agendas'] as $agenda): ?>
                <tr<?= $i & 1 ? ' class="alt"' : '' ?>>
                    <td><?= ucfirst($agenda['topic']) ?></td>
                    <td><?= $agenda['profile']['first_name'] ?> <?= $agenda['profile']['last_name'] ?></td>
                    <td><?= $agenda['allotted_time'] ?> mins</td>
                </tr>
            <?php $i++; endforeach; ?>
            </tbody>
        </table>
    </div>
    <p><strong>Actions</strong></p>
    <div class="datagrid">
        <table>
            <thead>
                <tr>
                    <th>Details of Action</th>
                    <th>Other Information</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; foreach ($meeting['actions'] as $action): ?>
                    <tr<?= $i & 1 ? ' class="alt"' : '' ?>>
                        <td><?= ucfirst($action['details']) ?></td>
                        <td>
                            Assigned To: <?= $action['profile']['first_name'] ?> <?= $action['profile']['last_name'] ?><br>
                            Close Details: <?= is_null(ucfirst($action['close_details'])) ? '&ndash;' : ucfirst($action['close_details']) ?><br>
                            Priority: <?= ucfirst($action['priority']) ?><br>
                            Status: <?= ucwords(str_replace('_', ' ', $action['status'])) ?><br>
                            Estimated Completion Date: <?= date('jS F Y', strtotime($action['ecd'])) ?>
                        </td>
                    </tr>
                <?php $i++; endforeach ?>
            </tbody>
        </table>
    </div>
    <br>
    <p class="text-center"><a href="<?= site_url('meetings/view/' . $meeting['id']) ?>" class="btn" target="_blank">View Meeting</a></p>

<?php $this->load->view('partials/emails/footer') ?>