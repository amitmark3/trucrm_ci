<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="box box-success">
            <div class="box-body">
                <p><?= lang('company_import_intro') ?></p>
                <div class="row">
                    <div class="col-xs-12 col-lg-8">
                        <?= form_open_multipart('company/import', ['id' => 'setup-import-form', 'role' => 'form']) ?>
                            <div class="form-group">
                                <input type="file" id="importfile" name="file">
                            </div>
                            <input type="submit" value="Upload" class="btn btn-success" />
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Importing Information</h3>
            </div>
            <div class="box-body">
                <p>When creating the CSV file the first line <strong>MUST</strong> contain the following items:</p>
                <ul>
                    <li>FirstName</li>
                    <li>LastName</li>
                    <li>EmailAddress</li>
                    <li>Department</li>
                    <li>Role</li>
                </ul>
                <div class="alert alert-info">
                    <ul>
                        <li>If the first line does not contain the items above the import will not work correctly.</li>
                        <li>No spaces are allowed in the items on the first line ("FirstName" not "First Name").</li>
                        <li>Each item <strong>MUST</strong> be seperated by a comma.</li>
                    </ul>
                </div>
                <p>An example CSV file should look like this:</p>
                <pre>FirstName,LastName,EmailAddress,Department,Role<br>John,Doe,john@example.com,Warehouse,1<br>Jane,Doe,jane.doe@example.com,Customer Service,2</pre>
                <p>When adding users to the CSV file each user must be entered on a separate line.</p>
                <div class="alert alert-warning">
                    <p>Any lines that do not contain the correct number of items will be ignored.</p>
                </div>
                <h4>What are roles?</h4>
                <p>Trucrm uses roles to determine what each user can do in the system.</p>
                <p>There are 2 roles to choose from:</p>
                <p>1. Manager<br>2. General Employee</p>
                <p>Use the number associated with the role in the csv file.</p>
                <?= anchor('downloads/import.csv', 'Download CSV Template', ['class' => 'btn btn-success']) ?>
                <!-- <?= anchor('downloads/import.xlsx', 'Download Excel Template', ['class' => 'btn btn-success']) ?> -->
            </div>
        </div>
    </div>
</div>