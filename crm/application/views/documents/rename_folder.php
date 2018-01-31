<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('documents/rename_folder/') ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
                        <div class="form-group">
                            <?= form_label('Folder Name', 'foler_name') ?>
							<?= form_hidden('old_folder', $old_folder) ?>
                            <?= form_input($foler_name, 'foler_name') ?>
                            <?= form_error('foler_name') ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'documents/folder_listing']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>