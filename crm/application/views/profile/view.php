<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <div class="row">
                    <div class="col-xs-12" id="avatar-form">
                        <div id="upload-errors" class="center-block"></div>
                        <form class="text-center" action="" method="post" enctype="multipart/form-data">
                            <div class="kv-avatar center-block">
                                <input id="avatar" name="avatar" type="file" class="file-loading">
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-12" id="profile-info">
                        <h3 class="profile-username"><?= $user['profile']['first_name']. ' ' .$user['profile']['last_name'] ?></h3>
                        <?php if ($user['profile']['job_title']) : ?>
                            <p class="text-muted"><?= $user['profile']['job_title'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">Email <a href="mailto:<?= $user['email'] ?>" class="pull-right"><?= $user['email'] ?></a></li>
                    <li class="list-group-item">Joined <span class="pull-right"><?= date('jS M Y', strtotime($user['created_at'])) ?></span></li>
                    <?php if ($user['profile']['employee_number']) : ?>
                    <li class="list-group-item">Employee Number <span class="pull-right"><?= $user['profile']['employee_number'] ?></span></li>
                    <?php endif; ?>
                    <?php
                    $notify_by = ($user['notify_by'] == 'both') ? 'Email &amp; Website' : $user['notify_by'];
                    ?>
                    <li class="list-group-item">Notify By <span class="pull-right"><?= ucfirst($notify_by) ?></span></li>
                </ul>
                <a href="<?= site_url('profile/update') ?>" class="btn btn-warning btn-block"><b>Update Profile</b></a>
                <a href="<?= site_url('profile/change_password') ?>" class="btn btn-primary btn-block"><b>Change Password</b></a>
                <a href="<?= site_url('profile/preferences') ?>" class="btn bg-navy btn-block"><b>Notify Preferences</b></a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
            <li class="active"><a href="#reports" data-toggle="tab" aria-expanded="true">Reports</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="early_reports">
                    <p>Coming soon...</p>
                </div>
            </div>
        </div>
    </div>
</div>