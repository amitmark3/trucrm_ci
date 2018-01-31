<div class="row">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $meeting_action['details'] ?>
                    <?php
                    switch ($meeting_action['status'])
                    {
                        case 'open':
                            $class = 'danger';
                            break;
                        case 'in_progress':
                            $class = 'warning';
                            break;
                        default:
                            $class = 'success';
                            break;
                    }
                    ?>
                    &nbsp;&nbsp;<span class="label label-<?= $class ?>"><?= ucwords(str_replace('_', ' ', $meeting_action['status'])) ?></span>
                </h3>
            </div>
            <div class="box-body">
                <br>
                <table class="table table-striped table-bordered" id="meeting_action">
                    <tbody>
                        <tr>
                            <td width="25%">Meeting Name:</td>
                            <td width="75%"><a href="<?= site_url('meetings/view/'.$meeting['id']) ?>"><?= $meeting['name'] ?></a></td>
                        </tr>
                        <tr>
                            <td>Assigned To:</td>
                            <td><?= $meeting_action_user['first_name']. ' ' .$meeting_action_user['last_name'] ?></td>
                        </tr>
                        <tr>
                            <td>Action Details:</td>
                            <td><?= $meeting_action['details'] ?></td>
                        </tr>
                        <tr>
                            <td>Priority:</td>
                            <td><?= ucfirst($meeting_action['priority']) ?></td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td><?= ucwords(str_replace('_', ' ', $meeting_action['status'])) ?></td>
                        </tr>
                        <tr>
                            <td>Estimated Completion Date:</td>
                            <td><?= date('jS F Y', strtotime($meeting_action['ecd'])) ?></td>
                        </tr>
                        <?php if(!empty($meeting_action['close_details'])) : ?>
                        <tr>
                            <td>Closure Details:</td>
                            <td><?= $meeting_action['close_details'] ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php
                    switch ($meeting_action['status'])
                    {
                        case 'open':
                            $show_close_button = TRUE;
                            break;
                        case 'in progress':
                            $show_close_button = TRUE;
                            break;
                        
                        default:
                            $show_close_button = FALSE;
                            break;
                    }
                    if ($this->session->user_id == $meeting_action['user_id'] && $show_close_button == TRUE) : ?>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <a href="#" class="btn btn-success close_action" id="<?= $meeting_action['id'] ?>" data-toggle="modal" data-target="#close_action">Close Action</a>
                            </td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="close_action" tabindex="-1" role="dialog" aria-labelledby="Close Action Modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Close Meeting Action</h4>
            </div>
            <form action="<?= site_url('meetings/close_action/') ?>" method="POST" id="close-action-form">
                <input type="hidden" name="meeting_id" value="<?= $meeting['id'] ?>">
                <input type="hidden" name="action_id" value="<?= $meeting_action['id'] ?>">
                <div class="modal-body">
                    <p>Please enter details for closing the meeting action below.</p>
                        <div class="form-group has-feedback">
                            <label for="close_details">Closure Details</label>
                            <textarea name="close_details" id="close_details" cols="20" rows="6" class="form-control"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="reset" name="reset" value="Cancel" class="btn btn-default" data-dismiss="modal">&nbsp;
                    <input type="submit" class="btn btn-success" id="submitButton" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>