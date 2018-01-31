<div class="row">
    <div class="col-xs-6">
        <div class="box">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td width="40%">Name:</td>
                            <td><?= $user['profile']['first_name'].' '.$user['profile']['last_name'] ?></td>
                        </tr>
                        <tr>
                            <td>Company:</td>
                            <td><?= $user['company']['name'] ?></td>
                        </tr>
                        <tr>
                            <td>Joined:</td>
                            <td><?= date('jS M Y', strtotime($user['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <td>Active:</td>
                            <td id="active-status">
                                <input id="active" type="checkbox" name="active" <?= ($user['active'] == 1) ? 'checked="checked"' : '' ?> class="toggle-status" data-url="<?= site_url('admin/users/set_active_status/') ?>">
                                <i class="fa fa-spinner fa-spin fa-fw hidden"></i>
                                <i class="fa fa-check hidden"></i>
                                <i class="fa fa-exclamation-triangle hidden"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>Email Address:</td>
                            <td><a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></td>
                        </tr>
                        <tr>
                            <td>Job Title:</td>
                            <td><?= isset($user['profile']['job_title']) ? $user['profile']['job_title'] : '&ndash;' ?></td>
                        </tr>
                        <tr>
                            <td>Employee Number:</td>
                            <td><?= isset($user['profile']['employee_number']) ? $user['profile']['employee_number'] : '&ndash;' ?></td>
                        </tr>
                        <?php if ($user['department'] != NULL) : ?>
                        <tr>
                            <td>Department:</td>
                            <td><?= $user['department']['name'] ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title with-border">Actions</h3>
            </div>
            <div class="box-body">
                <a href="<?= site_url('admin/users/edit/'.$user['id']) ?>" class="btn btn-warning">Edit User</a>
                <a href="<?= site_url('admin/users/change_password/'.$user['id']) ?>" class="btn bg-navy">Change Password</a>
            </div>
        </div>
    </div>
</div>