<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <?= anchor('meetings/add', lang('meetings_heading_add'), ['class' => 'btn btn-success pull-right']) ?>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-responsive" id="meetings">
                        <thead>
                            <tr>
                                <th class="no-print">View</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="no-print">Edit</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>