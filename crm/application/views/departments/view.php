<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $department['name'] ?></h3>
            </div>
            <div class="box-body">
                <p><strong>Manager:</strong></p>
                <p>
                    <?php if ($department_manager) : ?>
                    <?= $department_manager['profile']['first_name'] . ' ' . $department_manager['profile']['last_name'] ?>
                    <?php else : ?>
                        &ndash;
                    <?php endif; ?>
                </p>
                <p><strong>Description:</strong></p>
                <?php if (!empty($department['description'])) : ?>
                <p><?= $department['description'] ?></p>
                <?php else : ?>
                    &ndash;
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Assigned Users</h3>
            </div>
            <div class="box-body">
                <?php if ($department_users) : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <!-- <thead>
                            <tr>
                                <th>Name</th>
                            </tr>
                        </thead> -->
                        <tbody>
                            <?php foreach ($department_users as $user) : ?>
                                <tr>
                                    <td><a href="<?= site_url('users/view/'.$user['id']) ?>"><?= $user['profile']['first_name'] . ' ' . $user['profile']['last_name'] ?></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else : ?>
                    <p>No users are assigned to this department.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>