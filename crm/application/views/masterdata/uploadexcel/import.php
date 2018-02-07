<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="box box-success">
            <div class="box-body">
                <p><?= lang('masterdata_import_intro') ?></p>
                <div class="row">
                    <div class="col-xs-12 col-lg-8">
                        <?= form_open_multipart('masterdata/uploadexcel', ['id' => 'setup-import-form', 'role' => 'form']) ?>
                            <div class="form-group">
                                <input required ="true" type="file" id="importfile" name="excel_file">
                            </div>
                            <input type="submit" value="Upload" class="btn btn-success" />
							<?= anchor('downloads/template_master_data_xls.xls', 'Download CSV Template', ['class' => 'btn btn-success']) ?>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
		<div class="row">				
			<div class="row"> <div class="col-md-12">&nbsp;</div></div>				
			<div class="row"> 
				<div class="col-md-2">&nbsp;</div>
				<div class="col-md-8"><?php //echo  $msg_error;  ?></div>
				<div class="col-md-2">&nbsp;</div>
			</div>
			<div class="row"> <div class="col-md-12">&nbsp;</div></div>				
			<div class="row"> 
				<div class="col-md-2">&nbsp;</div>
				<div class="col-md-8"><?php //echo  $msg_invalid;  ?></div>
				<div class="col-md-2">&nbsp;</div>
			</div>	
		</div>	
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Uploading Information</h3>
            </div>
            <div class="box-body">
                <p>When creating the CSV file the first line <strong>MUST</strong> contain the following items:</p>
                <ul>
                    <li>Unique-ID</li>
					<li>Company Name</li>
					<li>Address</li>
					<li>City</li>
					<li>State or Province</li>
					<li>Postal Code</li>
					<li>Country</li>
					<li>Salutation</li>
					<li>First Name</li>
					<li>Last Name</li>
					<li>Title (Designation)</li>
					<li>Job-Function</li>
					<li>Email Address</li>
					<li>Business Phone</li>
					<li>Mobile</li>
					<li>No. Of Employee</li>
					<li>No. Of Pc</li>
					<li>Website	Industry</li>
					<li>Data Source</li>
                </ul>
                <div class="alert alert-info">
                    <ul>
                        <li>If the first line does not contain the items above the import will not work correctly.</li>
                        <li>No spaces are allowed in the items on the first line ("FirstName" not "First Name").</li>
                        <li>Each item <strong>MUST</strong> be seperated by a comma.</li>
                    </ul>
                </div>
                <p>An example CSV file should look like this:</p>
                <pre>Unique-ID, Company Name, Address,City,State or Province,Postal Code,<br/>Country,Salutation,First Name,Last Name, Designation (Title),<br/>Job-Function,Email Address,Business Phone,Mobile,No. Of Employee,<br/>No. Of Pc,Website,Industry</pre>
                <p>When adding users to the CSV file each user must be entered on a separate line.</p>
                <div class="alert alert-warning">
                    <p>Any lines that do not contain the correct number of items will be ignored.</p>
                </div>
                <h4>What are Data source?</h4>
                <p>Data Source import data from another website</p>
                <p>There are data source to choose from:</p>
                <p>1. DNA-DATA <br>2. FUNDOO-DATA etc.</p>
               
                <?= anchor('downloads/template_master_data_xls.xls', 'Download CSV Template', ['class' => 'btn btn-success']) ?>
                <!-- <?= anchor('downloads/import.xlsx', 'Download Excel Template', ['class' => 'btn btn-success']) ?> -->
            </div>
        </div>
    </div>
</div>