<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <?php if ($this->ion_auth->in_group(2)) : ?>
                <div class="pull-right">
                    <a href="<?= site_url('users/add') ?>" class="btn btn-success"><i class="fa fa-plus"></i> Add User</a>
                    <a href="<?= site_url('company/import') ?>" class="btn bg-navy"><i class="fa fa-cloud-upload"></i> Import Users</a>
                </div>
                <?php endif; ?>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="users">
                        <thead>
                            <tr>
                                <th width="5%" class="no-print">View</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email Address</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Active</th>
                                <th width="5%" class="no-print">Edit</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>