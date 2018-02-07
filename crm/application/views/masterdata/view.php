<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $data_master['head_title'] ?></h3>
				<?php if ($this->ion_auth->in_group([2,3]) || $data_master['users_id'] == $this->user->id): ?>
                    <a href="<?= site_url('masterdata/edit/'.$data_master['id']) ?>" title="Edit Master Data" class="pull-right no-print"><i class="fa fa-pencil"></i></a>
                <?php endif; ?>
            </div>
            <div class="box-body">
			<?php 
				//print '<pre>';print_r($data_master);print '</pre>';die;
					echo '<p><strong>Unique Id:</strong> '.$data_master['unique_id'].'</p>';
				
				
					echo '<p><strong>Address:</strong> '.$data_master['address'].
					'<br/>'.$data_master['districts'].
					'<br/>'.$data_master['states'].
					'<br/>'.$data_master['countries'].
					'-'.$data_master['pincode'].
					'</p>';
				
				
					echo '<p><strong>Website:</strong> '.$data_master['website'].'</p>';
				
				
					echo '<p><strong>No of Employee:</strong> '.$data_master['no_of_employee'].'</p>';
				
				
					echo '<p><strong>No of PC/Computer:</strong> '.$data_master['no_of_pc'].'</p>';
				
				
					echo '<p><strong>Industry:</strong> '.$data_master['industrytype'].'</p>';
				
				
					echo '<p><strong>Sub Industry:</strong> '.$data_master['sub_industrytype'].'</p>';
				
				
					echo '<p><strong>Data Source:</strong> '.$data_master['datasource'].'</p>';
				
			?> 
                
            </div>
        </div>
    </div>
	<?php
	//print '<pre>';print_r($datamaster_contact);print '</pre>';die;
	if(count($datamaster_contact)>0){
	foreach($datamaster_contact as $dmc){
		
	?>
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="box box-warning">
		 <div class="box-header with-border">
		        <h3 class="box-title"><strong>Job Title:</strong> <?php echo ucfirst(strtolower($dmc['job_title'])); ?></h3>
		
			<?php if ($this->ion_auth->in_group([2,3]) || $data_master['users_id'] == $this->user->id): ?>
					<a href="<?= site_url('masterdata/mdcdelete/'.$data_master['id'].'/'.$dmc['id']) ?>" title="Delete Contact" class="btn pull-right no-print"><i class="fa fa-trash"></i></a>
					
                    <a href="<?= site_url('masterdata/mdcedit/'.$data_master['id'].'/'.$dmc['id']) ?>" title="Edit Contact" class="btn pull-right no-print"><i class="fa fa-pencil"></i></a>
					 
                <?php endif; ?>
			 </div>	
            <div class="box-body">
			<?php
			
				echo '<p><strong>Name:</strong> '
				.ucfirst(strtolower($dmc['salutation'])).' '
				.ucfirst(strtolower($dmc['first_name'])).' '
				.ucfirst(strtolower($dmc['last_name'])).
				'</p>';
				$job_function_value='';
				if (array_key_exists($dmc['job_function'], $job_function)){
					$job_key = $dmc['job_function'];
					$job_function_value = $job_function[$job_key];
				}
				echo '<p><strong>Job Function:</strong> '
				.ucfirst(strtolower($job_function_value)).'</p>';
			
			
				echo '<p><strong>Office Email:</strong> '
				.ucfirst(strtolower($dmc['email_office'])).'</p>';
			
			
				echo '<p><strong>Personal Email:</strong> '
				.ucfirst(strtolower($dmc['email_personal'])).'</p>';
			
			
				echo '<p><strong>Office Phone No.:</strong> '
				.ucfirst(strtolower($dmc['phone_office'])).'</p>';
			 
			
				echo '<p><strong>Personal Phone No.:</strong> '
				.ucfirst(strtolower($dmc['phone_personal'])).'</p>';
			
			
				echo '<p><strong>Department:</strong> '
				.ucfirst(strtolower($dmc['department'])).'</p>';
			
			
				echo '<p><strong>Last Updated Date:</strong> '
				.date('jS M Y', strtotime($dmc['updated_at'])).'</p>';
			
			
				echo '<p><strong>Calling Status:</strong> '
				.ucfirst(strtolower($dmc['callingstatus'])).'</p>';
			
			
				echo '<p><strong>Updated By:</strong> '
				.ucfirst(strtolower($dmc['updated_by'])).'</p>';
			
			?>
			</div>
        </div>
    </div>
	<?php } }else{ ?>
		<p>No contact are assigned to this.</p>
	<?php } ?>
</div>