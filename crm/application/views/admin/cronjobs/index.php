<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <a href="<?= site_url('admin/cronjobs/add') ?>" class="btn btn-success pull-right"><i class="fa fa-plus"></i> <?= lang('heading_add') ?></a>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="cronjobs">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="20%">Name</th>
                                <th width="15%">Command</th>
                                <th width="10%">Interval</th>
                                <th width="15%">Last Run</th>
                                <th width="15%">Next Run</th>
                                <th width="15%">Active</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>