<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <?= anchor('admin/payments/add', lang('heading_add'), ['class' => 'btn btn-success pull-right']) ?>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="payments">
                        <thead>
                            <tr>
                                <th width="5%" class="no-print">View</th>
                                <th width="20%">Company</th>
                                <th width="15%">Amount (in <i class="fa fa-inr"></i>)</th>
                                <th width="25%">Description</th>
                                <th width="15%">Date Received</th>
                                <th width="15%">Renewal Date</th>
                                <th width="5%" class="no-print">Delete</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>