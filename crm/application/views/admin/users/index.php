<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <?= anchor('admin/users/add', 'Add User', ['class' => 'btn btn-success pull-right']) ?>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="users">
                        <thead>
                            <tr>
                                <th width="5%" class="no-print">View</th>
                                <th width="15%">First Name</th>
                                <th width="15%">Last Name</th>
                                <th width="25%">Email Address</th>
                                <th width="20%">Company</th>
                                <th width="20%">Role</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>