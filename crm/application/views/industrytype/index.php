<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <?= anchor('industrytype/add', lang('industry_type_heading_add'), ['class' => 'btn btn-success pull-right']) ?>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="industry_type_data">
                        <thead>
                            <tr>
                                <th class="no-print">View</th>
                                <th>Industry 1</th>
								<th>Industry 2</th>
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