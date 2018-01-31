<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-responsive" id="meeting_actions">
                        <thead>
                            <tr>
                                <th width="5%" class="no-print">View</th>
                                <th>Meeting</th>
                                <th>Details</th>
                                <th>Close Details</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Estimated Completion Date</th>
                                <?php if ($this->user_group['id'] == 2) : ?>
                                <th>Assigned To</th>
                                <th width="5%" class="no-print">Edit</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>