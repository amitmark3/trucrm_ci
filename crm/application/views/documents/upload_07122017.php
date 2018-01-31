<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
            <?= form_open_multipart('documents/upload', ['id' => 'upload-document-form']) ?>
                <div class="form-group">
                    <?= form_label(lang('upload_label'), 'userfile') ?>
                    <?= form_upload('userfile[]', '', ['id' => 'userfile', 'class' => 'form-control file-loading', 'multiple' => 'multiple']) ?>
                    <span class="help-block">Select all files you wish to upload in one go and NOT individually.<br>
                    Hold down CTRL (CMD on Macs) to select multiple files.</span>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'documents']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>