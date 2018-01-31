<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <?php if ($this->user_group['id'] < 4): ?>
                <div class="pull-right no-print">
                    <a href="<?= site_url('meetings/edit/'.$meeting['id']) ?>" title="Edit this meeting"><i class="fa fa-pencil no-print"></i></a>
                </div>
                <?php endif; ?>
                <h3 class="box-title" style="margin-bottom: 1em;">
                    <?= $meeting['name'] ?>
                    <?php
                    switch ($meeting['open'])
                    {
                        case '1':
                            $badge_colour = ' bg-green';
                            $status = 'Open';
                            break;
                        
                        default:
                            $badge_colour = '';
                            $status = 'Closed';
                            break;
                    }
                    ?>
                    <span class="badge<?= $badge_colour ?>"><?= $status ?></span>
                </h3>
                <p style="margin-bottom: 2em;"><i class="fa fa-calendar"></i> <span class="date"><?= date('jS F Y', strtotime($meeting['date'])) ?></span></p>
                <p><?= $meeting['description'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Meeting Attendees</h3>
                <div class="box-tools pull-right no-print">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="meeting_attendees">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
                                <th class="no-print">Remove</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( ! empty($meeting['attendees']) ) : ?>
                                <?php foreach ($meeting['attendees'] as $user) : ?>
                                <tr>
                                    <td><?= $user['profile']['first_name'] . ' ' . $user['profile']['last_name'] ?></td>
                                    <td><?= ucwords($user['status']) ?></td>
                                    <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
                                    <td class="no-print"><a href="<?= site_url('meetings/remove_attendee/'.$user['user_id']) ?>" id="<?= $user['user_id'] ?>" class="btn btn-default remove_attendee" title="Remove Attendee" data-meeting-id="<?= $meeting['id'] ?>"><i class="fa fa-times-circle"></i></a></td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="no_attendees_found"><td colspan="3">No attendees have been added.</td></tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
                        <tfoot class="no-print">
                            <?= form_open('meetings/add_attendee', ['id' => 'add-attendee-form']) ?>
                            <?= form_hidden('meeting_id', $meeting['id']) ?>
                            <tr>
                                <td><?= form_dropdown('user_id', $company_users, '', ['class' => 'form-control selectpicker', 'id' => 'user_id', 'data-container' => 'body']) ?></td>
                                <td>
                                    <select name="status" id="status" class="form-control">
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="excluded">Excluded</option>
                                        <option value="holiday">Holiday</option>
                                    </select>
                                </td>
                                <td><button type="submit" id="submitButton" class="btn btn-default add_attendee"><i class="fa fa-plus"></i></button></td>
                            </tr>
                            <?= form_close() ?>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Meeting Agendas</h3>
                <div class="box-tools pull-right no-print">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="meeting_agendas">
                        <thead>
                            <tr>
                                <th>Topic</th>
                                <th>Presented By</th>
                                <th>Allotted Time</th>
                                <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
                                <th class="no-print">Delete</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( ! empty($meeting['agendas']) ) : ?>
                                <?php foreach ($meeting['agendas'] as $agenda) : ?>
                                <tr>
                                    <td><?= ucfirst($agenda['topic']) ?></td>
                                    <td><?= $agenda['profile']['first_name'] . ' ' . $agenda['profile']['last_name'] ?></td>
                                    <td><?= $agenda['allotted_time'] ?></td>
                                    <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
                                    <td class="no-print"><a href="<?= site_url('meetings/remove_agenda/'.$agenda['id']) ?>" id="<?= $agenda['id'] ?>" class="btn btn-default remove_agenda" title="Remove Agenda" data-meeting-id="<?= $meeting['id'] ?>"><i class="fa fa-times-circle"></i></a></td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="no_agendas_found"><td colspan="4">No agendas have been added.</td></tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
                        <tfoot class="no-print">
                            <?= form_open('meetings/add_agenda', ['id' => 'add-agenda-form']) ?>
                            <?= form_hidden('meeting_id', $meeting['id']) ?>
                            <tr>
                                <td><input type="text" name="topic" id="topic" class="form-control" placeholder="Enter the topic..."></td>
                                <td><?= form_dropdown('presenter_user_id', $company_users, '', ['class' => 'form-control selectpicker', 'id' => 'presenter_user_id', 'data-container' => 'body']) ?></td>
                                <td>
                                    <?php
                                    $intervals = array(5,10,15,20,25,30,35,40,45,50,55,60);
                                    foreach ($intervals as $val)
                                    {
                                        $time[$val] = @$val;
                                    }
                                    ?>
                                    <?= form_dropdown('allotted_time', $time, set_value('allotted_time', '30'), ['id' => 'allotted_time']) ?>
                                </td>
                                <td><button type="submit" id="submitButton" class="btn btn-default add_agenda"><i class="fa fa-plus"></i></button></td>
                            </tr>
                            <?= form_close() ?>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Meeting Actions</h3>
                <div class="box-tools pull-right no-print">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="meeting_actions">
                        <thead>
                            <tr>
                                <th class="no-print">View</th>
                                <th>Details</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Completion Date</th>
                                <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
                                <th class="no-print">Delete</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( ! empty($meeting['actions']) ) : ?>
                                <?php foreach ($meeting['actions'] as $action) : ?>
                                    <?php
                                    switch ($action['status'])
                                    {
                                        case 'open':
                                            $bg_color = 'danger';
                                            break;
                                        case 'in_progress':
                                            $bg_color = 'warning';
                                            break;
                                        case 'closed':
                                            $bg_color = 'success';
                                            break;
                                        default:
                                            $bg_color = 'default';
                                            break;
                                    }
                                    ?>
                                <tr class="<?= $bg_color ?>">
                                    <td class="no-print"><a href="<?= site_url('meetings/view_action/'.$action['id']) ?>"><i class="fa fa-eye"></i></a></td>
                                    <td><?= ucfirst($action['details']) ?></td>
                                    <td><?= $action['profile']['first_name'] . ' ' . $action['profile']['last_name'] ?></td>
                                    <td><?= ucwords(str_replace('_', ' ', $action['status'])) ?></td>
                                    <td><?= ucwords($action['priority']) ?></td>
                                    <td><?= date('jS F Y', strtotime($action['ecd'])) ?></td>
                                    <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
                                    <td class="no-print"><a href="<?= site_url('meetings/remove_action/'.$action['id']) ?>" id="<?= $action['id'] ?>" class="btn btn-default remove_action" title="Remove Action" data-meeting-id="<?= $meeting['id'] ?>"><i class="fa fa-times-circle"></i></a></td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="no_actions_found"><td colspan="7">No actions have been added.</td></tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
                        <tfoot class="no-print">
                            <?= form_open('meetings/add_action', ['id' => 'add-action-form']) ?>
                            <?= form_hidden('meeting_id', $meeting['id']) ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td><input type="text" name="details" id="details" class="form-control" placeholder="Enter the details..."></td>
                                <td><?= form_dropdown('assigned_user_id', $company_users, NULL, ['class' => 'form-control selectpicker', 'data-container' => 'body']) ?></td>
                                <td>
                                    <select name="action_status" id="action_status" class="form-control">
                                        <option value="open">Open</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="priority" id="priority" class="form-control">
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group date">
                                        <input type="text" class="form-control" name="ecd" id="ecd" value="<?= date('Y-m-d') ?>">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </td>
                                <td><button type="submit" id="submitButton" class="btn btn-default add_action"><i class="fa fa-plus"></i></button></td>
                            </tr>
                            <?= form_close() ?>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php if ($uploads) : $i = 0; ?>
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <div class="h3 box-title">Files Uploaded</div>
                <div class="box-tools pull-right no-print">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php foreach ($uploads as $upload) : ?>
                    <div class="img-wrap" id="img-wrap-<?= $i ?>">
                        <input type="hidden" name="file_name" value="<?= $upload['file_name'] ?>">
                        <?php if ($this->user_group['id'] < 4) : ?>
                        <span class="close delete" title="Delete File">&times;</span>
                        <?php endif; ?>
                        <a href="<?= site_url('uploads/'.$this->company['uploads_folder'].'/'.$upload['file_name']) ?>" target="_blank" title="<?= $upload['file_name'] ?>">
                            <?php
                            $ext = strtolower(pathinfo($upload['file_name'], PATHINFO_EXTENSION));
                            $image_types = ['png', 'gif', 'jpg', 'jpeg', 'bmp'];
                            $file_types = ['txt', 'csv', 'doc', 'docx', 'xsl', 'xslx', 'ppt', 'pptx', 'psd', 'zip', 'rar', 'pdf'];
                            if (in_array($ext, $image_types)) :
                            ?>
                                <?php if ($upload['file_name_thumb'] !== NULL) : ?>
                                <img src="<?= site_url('uploads/'.$this->company['uploads_folder'].'/'.$upload['file_name_thumb']) ?>" class="img-responsive img-thumbnail" width="125px">
                                <?php else : ?>
                                <img src="<?= site_url('uploads/'.$this->company['uploads_folder'].'/'.$upload['file_name']) ?>" class="img-responsive img-thumbnail" width="125px">
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (in_array($ext, $file_types)) : ?>
                                <img src="<?= site_url("assets/img/icons/{$ext}-lg.png") ?>" class="img-responsive img-thumbnail" width="125px">
                            <?php endif; ?>
                        </a>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
    <div class="col-xs-12 no-print">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Upload Files</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?= form_open_multipart('meetings/upload') ?>
                    <div class="form-group">
                        <?= form_hidden('meeting_id', $meeting['id']) ?>
                        <?= form_upload('userfile[]', '', ['id' => 'image_upload', 'class' => 'form-control file-loading', 'multiple' => 'multiple']) ?>
                        <span class="help-block">Select all files you wish to upload in one go and NOT individually.<br>
                        Hold down CTRL (CMD on Mac) to select multiple files.</span>
                        <?= form_submit('submit', 'Submit', ['class' => 'btn btn-success']) ?>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($meeting['open'] == 1 && $this->user_group['id'] < 4) : ?>
    <div class="col-xs-12 no-print">
        <div class="box box-default">
            <div class="box-footer">
                <a href="<?= site_url('meetings/close/'.$meeting['id']) ?>" class="btn btn-success pull-right confirm_close" id="<?= $meeting['id'] ?>">Close Meeting</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($meeting['open'] == 0 && $this->user_group['id'] < 4) : ?>
    <div class="col-xs-12 no-print">
        <div class="box box-default">
            <div class="box-footer">
                <a href="javascript:pdfMake.createPdf(docDefinition).download('<?= $meeting['name'] . ' - ' . date("jS F Y", strtotime($meeting['date'])) . ' - #' . $meeting['id'] ?>.pdf');" class="btn btn-success no-print download-pdf" id="<?= $meeting['id'] ?>" title="Download PDF"><i class="fa fa-file-pdf-o"></i>&nbsp; Download PDF</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
if ($meeting['open'] == 0):
    $data = [
        'uploads'   => isset($uploads) ? $uploads : NULL,
        'meeting'   => $meeting
    ];
    $this->load->view("meetings/pdf/index", $data);
endif
?>