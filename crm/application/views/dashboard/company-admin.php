<?php
$errors = '';
if ($count['departments'] == 0)
{
    $errors .= '<li>No departments have been created yet. Please <strong><a href="'.site_url('departments/add').'">add a department</a></strong> now.</li>';
}
if ($count['users'] == 0)
{
    $errors .= '<li>No users have been added yet. Please <strong><a href="'.site_url('users/add').'">add a user</a></strong> now.</li>';
}
?>
<?php if ( ! empty($errors) ) : ?>
<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-warning-extra">
            <ul>
                <?= $errors ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="row">
 <div class="col-xs-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-calendar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Meetings <span class="pull-right"><a href="<?= site_url('meetings') ?>" class="small-box-footer" title="View all meetings">View All <i class="fa fa-arrow-circle-right"></i></a></span></span>
                <span class="info-box-number" style="font-size: 28px;"><?= ($count['meetings'] >= 1) ? $count['meetings'] : '0' ?></span>
            </div>
        </div>

    </div>
	<div class="col-xs-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Users <span class="pull-right"><a href="<?= site_url('users') ?>" class="small-box-footer" title="View all users">View All <i class="fa fa-arrow-circle-right"></i></a></span></span>
                <span class="info-box-number" style="font-size: 28px;"><?= ($count['users'] >= 1) ? $count['users'] : '0' ?></span>
            </div>
        </div>
        <!-- <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= ($count['users'] >= 1) ? $count['users'] : '0' ?></h3>
                <p>Users</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people"></i>
            </div>
            <a href="<?= site_url('users') ?>" class="small-box-footer" title="View all users">View All <i class="fa fa-arrow-circle-right"></i></a>
        </div> -->
    </div>
</div>
<div class="row">

  <div class="col-xs-12 col-md-6">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Latest Meetings</h3>
                <div class="box-tools pull-right">
                    <a href="<?= site_url('meetings') ?>" class="btn btn-sm btn-default">View All</a>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php if (isset($meetings) && is_array($meetings)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered no-margin">
                        <thead>
                            <tr>
                                <th width="5%" class="no-print">View</th>
                                <th width="20%">Name</th>
                                <th width="60%">Description</th>
                                <th width="15%">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($meetings as $meeting) : ?>
                            <tr>
                                <td><a href="<?= site_url('meetings/view/'.$meeting['id']) ?>" title="View Meeting"><?= $meeting['id'] ?></a></td>
                                <td><?= $meeting['name'] ?></td>
                                <td><?= word_limiter($meeting['description'], 15) ?></td>
                                <td><?= date('jS M Y', strtotime($meeting['date'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else : ?>
                No meetings were found.
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Latest Meeting Actions</h3>
                <div class="box-tools pull-right">
                    <a href="<?= site_url('meetings/actions') ?>" class="btn btn-sm btn-default">View All</a>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php if (isset($meeting_actions) && is_array($meeting_actions)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered no-margin">
                        <thead>
                            <tr>
                                <th width="5%" class="no-print">View</th>
                                <th width="25%">Meeting</th>
                                <th width="30%">Details</th>
                                <th width="15%">Priority</th>
                                <th width="15%">Status</th>
                                <th width="10%">Estimated Completion Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($meeting_actions as $action) : ?>
                            <tr>
                                <td><a href="<?= site_url('meetings/view_action/'.$action['id']) ?>"><?= $action['id'] ?></a></td>
                                <td><?= $action['meeting']['name'] ?></td>
                                <td><?= word_limiter($action['details'], 15) ?></td>
                                <td>
                                    <?php
                                    switch ($action['priority'])
                                    {
                                        case 'low':
                                            $p_bg_color = 'default';
                                            break;
                                        case 'medium':
                                            $p_bg_color = 'yellow';
                                            break;
                                        case 'high':
                                            $p_bg_color = 'orange';
                                            break;
                                        case 'urgent':
                                            $p_bg_color = 'red';
                                            break;
                                        default:
                                            $p_bg_color = 'default';
                                            break;
                                    }
                                    ?>
                                    <span class="badge bg-<?= $p_bg_color ?>"><?= ucfirst($action['priority']) ?></span>
                                </td>
                                <td>
                                    <?php
                                    switch ($action['status'])
                                    {
                                        case 'open':
                                            $s_bg_color = 'red';
                                            break;
                                        case 'in_progress':
                                            $s_bg_color = 'orange';
                                            break;
                                        case 'closed':
                                            $s_bg_color = 'green';
                                            break;
                                        default:
                                            $s_bg_color = 'default';
                                            break;
                                    }
                                    ?>
                                    <span class="badge bg-<?= $s_bg_color ?>"><?= ucwords(str_replace('_', ' ', $action['status'])) ?></span>
                                </td>
                                <td><?= date('jS M Y', strtotime($action['ecd'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else : ?>
                No meeting actions were found.
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>