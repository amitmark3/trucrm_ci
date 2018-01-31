<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="box box-info">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <?php if (in_array($this->user_group['id'], [2,3])) : ?>
                            <tr>
                                <td>Is Active?</td>
                                <td id="active-status">
                                    <input id="active" type="checkbox" name="active" <?= ($user['active'] == 1) ? 'checked' : '' ?> class="toggle-status" data-url="<?= site_url('users/set_active_status/') ?>">
                                    <i class="fa fa-spinner fa-spin fa-fw hidden"></i>
                                    <i class="fa fa-check hidden"></i>
                                    <i class="fa fa-exclamation-triangle hidden"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>Role:</td>
                                <td><?= ucwords($user_role) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Email Address:</td>
                                <td><a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></td>
                            </tr>
                            <tr>
                                <td>Department:</td>
                                <td><?= isset($department) ? $department : '&ndash;' ?></td>
                            </tr>
                            <tr>
                                <td>Employee Number:</td>
                                <td><?= isset($user['profile']['employee_number']) ? $user['profile']['employee_number'] : '&ndash;' ?></td>
                            </tr>
                            <tr>
                                <td>Job Title</td>
                                <td><?= isset($user['profile']['job_title']) ? $user['profile']['job_title'] : '&ndash;' ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <?php if (in_array($this->user_group['id'], [2,3])) : ?>
                            <tr>
                                <td colspan="2">
                                    <a href="#" class="btn btn-primary confirm" id="<?= $user['id'] ?>">Reset Password</a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>