<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <?= anchor('admin/price_plans/add', lang('heading_add'), ['class' => 'btn btn-success pull-right']) ?>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="price_plans">
                        <thead>
                            <tr>
                                <th width="5%" class="no-print">View</th>
                                <th width="15%">Name</th>
                                <th width="33%">Description</th>
                                <th width="15%">Price (in <i class="fa fa-inr"></i>)</th>
                                <th width="15%">Space Allotted</th>
                                <th width="12%">Unit of Space</th>
                                <th width="5%">Edit / Delete</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>