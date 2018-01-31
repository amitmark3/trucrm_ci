<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
				<div class="col-xs-12">
					<?= form_open('documents/create_folder', ['id' => 'create-directory-form']) ?>
						<div class="col-xs-6">
							<div class="form-group">
								<?= form_input('directory_name', '', ['size'=>'40', 'required'=>'required', 'id' => '', 'class' => '']) ?>
							</div>
						</div>
						<div class="col-xs-6">			
							<div class="form-group">	
								<?= form_submit('create_directory', 'Create Directory', ['id' => '', 'class' => '']) ?>
							</div>
						</div>	
					<?= form_close() ?>
				</div>
            
            </div>
        </div>
    </div>
</div>