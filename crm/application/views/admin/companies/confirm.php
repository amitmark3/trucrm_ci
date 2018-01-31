<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Confirm Delete</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12">
                        <p>All information associated with the company will be deleted (listed below).</p>
                        <ul>
							<li>All client list</li>
                            <li>All uploaded documents</li>
                            <li>Departments &amp;</li>
                        </ul>
                        <p>To delete the company please type the word DELETE (in uppercase) into the box below and submit the form.</p>
                        <br>
                        <?= form_open("admin/companies/delete/{$id}/confirmation") ?>
                            <?= form_input('confirm') ?>
                            <?= form_error('confirm') ?>
                        <br>
                        <input type="submit" class="btn btn-danger btn-block" id="submitButton" value="Submit">
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
