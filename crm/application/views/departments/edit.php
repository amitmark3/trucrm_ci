<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('departments/edit/' . $department['id'], ['id' => 'edit-department-form']) ?>
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('departments_name_label'), 'name') ?>
                        <?= form_input('name', set_value('name', $department['name']), ['id' => 'name']) ?>
                        <?= form_error('name') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('departments_description_label'), 'description') ?>
                        <?= form_textarea('description', set_value('description', $department['description']), ['id' => 'description']) ?>
                        <?= form_error('description') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
                        <?= form_label(lang('departments_user_label'), 'user_id') ?>
                        <?= form_dropdown('user_id', $users, set_value('user_id', $department['assigned_user_id']), ['id' => 'user_id', 'class' => 'form-control selectpicker', 'data-container' => 'body']) ?>
                    </div>
                </div>
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'departments']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>