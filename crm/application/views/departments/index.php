<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <?= anchor('departments/add', 'Add Department', ['class' => 'btn btn-success pull-right']) ?>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="departments">
                        <thead>
                            <tr>
                                <th class="no-print">View</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Department Manager</th>
                                <th class="no-print">Edit / Delete</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>