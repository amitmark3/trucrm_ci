<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <?= form_open('project/edit/' . $project['id'], ['id' => $form_name]) ?>
            <div class="box-body">
                <div class="row">
					<div class="form-group col-xs-12 col-md-6">
						<?= form_label(lang('project_type_label'), 'project_type') ?>
                        <?= form_dropdown('project_type', $project_type_dropdown, set_value('project_type', $project['project_type']), ['id' => 'project_type','onchange'=>'project_type_showhide(this.value)']); ?>
                    </div>					
					<div class="form-group col-xs-12 col-md-6">
                        <?= form_label(lang('project_title_label'), 'project_name') ?>
                         <?= form_input(['name'=>'project_name','type' => 'text','id' => 'project_name',  'class' => 'form-control' ,'required'=>'required','maxlength'=>'150'], set_value('project_name',$project['name'])) ?>
                        <?= form_error('project_name') ?>
                    </div>
                </div>
				
				<div class="row">
					<div class="col-xs-12 col-md-6">
					<div class="form-group">
                        <?= form_label(lang('project_start_date_label'), 'start_date') ?>
						 <div class="input-group date">
                         <?= form_input(['name'=>'start_date','type' => 'text','id' => 'start_date',  'class' => 'form-control' ,'required'=>'required','maxlength'=>'50'], set_value('start_date',$project['start_date'])) ?>
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
						</div>
                        <?= form_error('start_date') ?>
					</div>	
                    </div>
					
					<div class="col-xs-12 col-md-6">
					<div class="form-group">
                        <?= form_label(lang('project_end_date_label'), 'end_date') ?>
						 <div class="input-group date">
                         <?= form_input(['name'=>'end_date','type' => 'text','id' => 'end_date',  'class' => 'form-control' ,'required'=>'required','maxlength'=>'50'], set_value('end_date',$project['end_date'])) ?>
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
						</div>
                        <?= form_error('end_date') ?>
					</div>	
                    </div>
                </div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-4">
                        <?= form_label(lang('project_productivity_label'), 'productivity_required') ?>
						<?= form_input(['name'=>'productivity_required','type' => 'number','id' => 'productivity_required',  'class' => 'form-control' ,'required'=>'required','min'=>'1','max'=>'10000000'], set_value('productivity_required',$project['productivity_required'])) ?>
                        <?= form_error('productivity_required') ?>
                    </div>
					<div class="form-group col-xs-12 col-md-4">
                        <?= form_label(lang('project_user_allocated_label'), 'user_allocated') ?>
						<?= form_input(['name'=>'user_allocated','type' => 'number','id' => 'user_allocated',  'class' => 'form-control' ,'required'=>'required','min'=>'1','max'=>$total_users], set_value('user_allocated',$project['user_allocated'])) ?>
                        <?= form_error('user_allocated') ?>
                    </div>
					<div class="form-group col-xs-12 col-md-4">
                        <?= form_label(lang('project_per_day_label'), 'per_day_required') ?>
						<?= form_input(['name'=>'per_day_required','type' => 'number','id' => 'per_day_required',  'class' => 'form-control' ,'required'=>'required','min'=>'1','max'=>'1000'], set_value('per_day_required',$project['per_day_required'])) ?>
                        <?= form_error('per_day_required') ?>
                    </div>
                </div>
				
				<div id="show_hide_project_type" style="<?php if($project['project_type']==2){ ?>display:none; <?php } ?>">
				<!-- Start the Purchasing Time Add More-->
				<div class="box box-default">
				<div class="box-header with-border">
				  <h3 class="box-title"><i class="fa fa-calendar-check-o"></i> <?= form_label(lang('project_purchasing_time_label')) ?></strong></h3>
				</div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-3">
                        <strong><?= form_label(lang('project_interval_from_label'), 'per_day_required') ?></strong>
                    </div>
					<div class="form-group col-xs-12 col-md-3">
                        <strong><?= form_label(lang('project_interval_to_label'), 'per_day_required') ?></strong>
                    </div>
					<div class="form-group col-xs-12 col-md-3">
                       <strong><?= form_label(lang('project_interval_type_label'), 'per_day_required') ?></strong>
                    </div>
					<div class="form-group col-xs-12 col-md-3">	
						<a href="javascript:void(0);" class="add_button_pt" title="Add field"><i class="fa  fa-plus-square">&nbsp;Add More</i></a>
					</div>
				</div>
				<?php 
				if($project_pt[0]['id']>0 && count($project_pt)>0){ 
						foreach($project_pt as $pt){						
				?>
				<div class="row" id="project_time_<?php echo $pt['id'];?>">
					<div class="form-group col-xs-12 col-md-3">
                        <?= form_input(['name'=>'interval_from[]','type' => 'number','id' => 'interval_from[]', 'class' => 'form-control' ,'min'=>'1','max'=>'1000'], set_value('interval_from[]',$pt['interval_from'])) ?>
						
                    </div>
					<div class="form-group col-xs-12 col-md-3">
                        <?= form_input(['name'=>'interval_to[]','type' => 'number','id' => 'interval_to[]',  'class' => 'form-control' ,'min'=>'1','max'=>'1000'], set_value('interval_to[]',$pt['interval_to'])) ?>
						<?= form_error('interval_to') ?>
                    </div>
					<div class="form-group col-xs-12 col-md-3">
                        <?= form_dropdown('interval_type[]', $interval_type_dropdown, $pt['interval_type'], ['id' => 'interval_type[]']); ?>
						<?= form_error('interval_type') ?>
                    </div>
					<div class="form-group col-xs-12 col-md-3">	
						<a href="javascript:void(0);" onclick="remove_project_time(<?php echo $pt['id'];?>)" title="Remove field"><i class="fa  fa-remove "></i></a>
					</div>
					
				</div>
				<?php } } ?>
				
				<div class="row">
					<div class="form-group col-xs-12 col-md-12 field_wrapper_pt"></div>
				</div>
				
				</div>
				<!-- End the Purchasing Time Add More-->
				
				<!-- Start the Requirement Criteria Add More-->
				<div class="box box-default">
				<div class="box-header with-border">
				  <h3 class="box-title"><i class="fa fa-user-secret"></i> <?= form_label(lang('project_rc_label')) ?></strong></h3>
				</div>
				<div class="row">
					<div class="form-group col-xs-12 col-md-3">
                       <strong><?= form_label(lang('project_rc_type_label')) ?></strong>
                    </div>
					<div class="form-group col-xs-12 col-md-3">
                        <strong><?= form_label(lang('project_rc_label_label')) ?></strong>
                    </div>
					<div class="form-group col-xs-12 col-md-3">
                        <strong><?= form_label(lang('project_rc_value_label')) ?></strong>
                    </div>
					<div class="form-group col-xs-12 col-md-3">	
						<a href="javascript:void(0);" class="add_button_rc" title="Add field"><i class="fa  fa-plus-square">&nbsp;Add More</i></a>
					</div>
				</div>
				<?php if($project_rc[0]['id']>0 && count($project_rc)>0){ 
						foreach($project_rc as $rc){						
				?>
				<div class="row" id="requirement_criteria_<?php echo $rc['id'];?>">
					<div class="form-group col-xs-12 col-md-3">
                        <?= form_dropdown('input_type[]', $rc_type_dropdown, $rc['input_type'], ['id' => 'input_type[]']); ?>
						<?= form_error('input_type') ?>
                    </div>
					<div class="form-group col-xs-12 col-md-3">
                        <?= form_input(['name'=>'input_label[]','type' => 'text','id' => 'input_label[]',  'class' => 'form-control' ,'maxlength'=>'100'], set_value('input_label[]',$rc['input_label'])) ?>
						<?= form_error('input_label') ?>
                    </div>
					<div class="form-group col-xs-12 col-md-3">
                        <?= form_input(['name'=>'input_value[]','type' => 'text','id' => 'input_value[]',  'class' => 'form-control' ,'maxlength'=>'100'], set_value('input_value[]',$rc['input_value'])) ?>
						<?= form_error('input_value') ?>
                    </div>
					
					<div class="form-group col-xs-12 col-md-3">	
						<a href="javascript:void(0);" onclick="remove_requirement_criteria(<?php echo $rc['id'];?>)" title="Remove field"><i class="fa  fa-remove "></i></a>
					</div>
				</div>
				<?php } } ?>
				<div class="row">
					<div class="form-group col-xs-12 col-md-12 field_wrapper_rc"></div>
				</div>
				
				</div>
				<!-- End the Requirement Criteria Add More-->
				</div>
				
				
				<div class="row">
                    <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                        <p><strong><?= lang('project_status_label') ?></strong></p>
                        <div class="radio radio-success radio-inline">
                            <input type="radio" id="radio_yes" value="1" name="active" <?php if ($project['status'] == 1) echo ' checked="checked"' ?>>
                            <label for="radio_yes"> Yes </label>
                        </div>
                        <div class="radio radio-danger radio-inline">
                            <input type="radio" id="radio_no" value="0" name="active" <?php if ($project['status'] == 0) echo ' checked="checked"' ?>>
                            <label for="radio_no"> No </label>
                        </div>
                    </div>
                </div>
                
            </div>
            <?php $this->load->view('partials/dashboard/form-footer', ['url' => 'project']) ?>
            <?= form_close() ?>
        </div>
    </div>
</div>