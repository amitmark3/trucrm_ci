<div class="row">
    <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="box box-danger box-onboarding">
            <div class="box-body text-center">
                <?php $this->load->view('setup/partials/data_progress', ['current_step' => 4]); ?>
                <h1 class="uppercase"><?= lang('setup_import_heading') ?></h1>
                <p class="intro"><?= lang('setup_import_intro') ?></p>
                <p class="intro">Please <a href="#" title="Click for instructions" data-toggle="modal" data-target="#import_modal">read the instructions</a> before continuing.</p>
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-3">
                        <?= form_open_multipart('setup/import', ['id' => 'setup-import-form', 'role' => 'form']) ?>
                            <div class="form-group">
                                <input type="file" name="file" id="importfile">
                            </div>
                            <input type="submit" value="Upload" class="btn btn-lg btn-block btn-success" />
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="import_modal" tabindex="-1" role="dialog" aria-labelledby="Import Help" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Importing Instructions</h4>
            </div>
            <div class="modal-body">
                <p>When creating the CSV file the first line <strong>MUST</strong> contain the following items:</p>
                <ul>
                    <li>FirstName</li>
                    <li>LastName</li>
                    <li>EmailAddress</li>
                    <li>Department</li>
                    <li>Role</li>
                </ul>
                <div class="alert alert-danger">
                    <ul>
                        <li>The first line of the CSV file must read: FirstName,LastName,EmailAddress,Department,Role.</li>
                        <li>No spaces are allowed in the items on the first line (FirstName not First Name).</li>
                        <li>Each item <strong>MUST</strong> be seperated by a comma.</li>
                        <li>Each user <strong>MUST</strong> be entered on a separate line.</li>
                        <li>Any lines that do not contain the correct number of items will be ignored.</li>
                    </ul>
                </div>
                <p>An example CSV file should look like this:</p>
                <pre>FirstName,LastName,EmailAddress,Department,Role<br>Amit,Kumar,amit@example.com,I.T.,Department Manager<br>Rajiv,Rai,rajiv.rai@example.com,Customer Service,Department Manager<br>Jennifer,Doe,jenny@example.com,Customer Service,General Employee</pre>
                <h4>What are roles?</h4>
                <p>Trucrm uses roles to determine what each user can do in the system.</p>
                <p>There are 2 roles to choose from:</p>
                <ul>
                    <li>Department Manager</li>
                    <li>General Employee</li>
                </ul>
            </div>
            <div class="modal-footer">
                <?= anchor('downloads/import.csv', 'Download CSV Template', ['class' => 'btn btn-success pull-left']) ?>
                <!-- <?= anchor('downloads/import.xlsx', 'Download Excel Template', ['class' => 'btn btn-success']) ?> -->
                <button type="button" class="btn bg-navy" data-dismiss="modal">Close Instructions</button>
            </div>
        </div>
    </div>
</div>