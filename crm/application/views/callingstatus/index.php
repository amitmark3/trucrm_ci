<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <?= anchor('calling/status/add', lang('calling_status_heading_add'), ['class' => 'btn btn-success pull-right']) ?>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="calling_status_data">
                        <thead>
                            <tr>
                                <th class="no-print">View</th>
                                <th>Title</th>
                                <th>Parent Title</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>