<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('admin/documents/edit/' . $document['id'], ['id' => 'add-document-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-6">
                        <?= form_label('Name <span class="asterisk">*</span>', 'name') ?>
                        <?= form_input($name) ?>
                        <?= form_error('name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <?= form_label('Description', 'description') ?>
                        <?= form_textarea($description, set_value('description', $document['description'])) ?>
                        <?= form_error('description') ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'admin/documents']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>