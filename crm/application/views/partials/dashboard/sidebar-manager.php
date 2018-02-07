<?php
//echo $this->uri->segment();die;
$class_active = $this->uri->rsegments[1];
?>
<aside class="main-sidebar no-print hidden-print">
    <section class="sidebar">
        <div class="user-panel">
            <div class="image">
                <?php if ( ! is_null($this->company['logo'])) : ?>
                    <img src="<?= site_url("uploads/{$this->company['uploads_folder']}/avatars/{$this->company['logo']}") ?>" class="user-image img-thumbnail" alt="<?= $this->company['name'] ?>">
                <?php endif; ?>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
			<li class="treeview">
                <a href="#"><i class="fa fa-bank"></i> <span>My Company</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('company')?>"><i class="fa fa-eye"></i> Overview</a></li>
                    <li><a href="<?=site_url('company/edit')?>"><i class="fa fa-eye"></i> Edit Details</a></li>
                    <li><a href="<?=site_url('company/change_price_plan')?>"><i class="fa fa-eye"></i> Change Price Plan</a></li>
                </ul>
            </li>
            
			<?php /*
			<!--
           
            <li class="treeview">
                <a href="#"><i class="fa fa-bullhorn"></i> <span>Early Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('early_reports')?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?=site_url('early_reports/add')?>"><i class="fa fa-plus"></i> Submit Report</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-exclamation-circle"></i> <span>Risk Assessments</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('risk_assessments')?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?=site_url('risk_assessments/add')?>"><i class="fa fa-plus"></i> Add Assessment</a></li>
                </ul>
            </li>
			-->
            <li class="treeview">
                <a href="#"><i class="fa fa-fire-extinguisher"></i> <span>Safety Walks</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('safety_walks')?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?=site_url('safety_walks/start')?>"><i class="fa fa-plus"></i> Start Safety Walk</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-graduation-cap"></i> <span>Staff Training</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('training')?>"><i class="fa fa-eye"></i> View Completed</a></li>
                    <li><a href="<?=site_url('training/required')?>"><i class="fa fa-eye"></i> View Required</a></li>
                    <li><a href="<?=site_url('training/add')?>"><i class="fa fa-plus"></i> Add Completed Training</a></li>
                    <!-- <li><a href="<?=site_url('training/import')?>"><i class="fa fa-plus"></i> Import Completed Training</a></li> -->
                </ul>
            </li>
           
            <li class="header">REPORTS</li>
            <li class="treeview">
                <a href="#"><i class="fa fa-bar-chart"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('reports/accidents')?>"><i class="fa fa-medkit"></i> Accidents</a></li>
                    <li><a href="<?=site_url('reports/early_reports')?>"><i class="fa fa-bullhorn"></i> Early Reports</a></li>
                    <li><a href="<?=site_url('reports/risk_assessments')?>"><i class="fa fa-question-circle"></i> Risk Assessments</a></li>
                    <li><a href="<?=site_url('reports/meetings')?>"><i class="fa fa-calendar"></i> Safety Meetings</a></li>
                    <li><a href="<?=site_url('reports/safety_walks')?>"><i class="fa fa-fire-extinguisher"></i> Safety Walks</a></li>
                    <li><a href="<?=site_url('reports/training')?>"><i class="fa fa-graduation-cap"></i> Staff Training</a></li>
                </ul>
            </li>
			 

			*/?>
            <li class="header">MANAGE</li>
           
            <li class="treeview">
                <a href="#"><i class="fa fa-map"></i> <span>Departments </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('departments')?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?=site_url('departments/add')?>"><i class="fa fa-plus"></i> Add Department</a></li>
                </ul>
            </li>
			<li class="treeview <?php if($class_active=='users') echo 'active';?>">
                <a href="#"><i class="fa fa-users"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('users')?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?=site_url('users/add')?>"><i class="fa fa-plus"></i> Add User</a></li>
                    <li><a href="<?=site_url('company/import')?>"><i class="fa fa-cloud-upload"></i> Import Users</a></li>
                </ul>
            </li>
			<li class="treeview">
                <a href="#"><i class="fa fa-calendar"></i> <span>Meetings</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('meetings')?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?=site_url('meetings/add')?>"><i class="fa fa-plus"></i> Add Meeting</a></li>
                </ul>
            </li>
			<!--
			
			
			-->
			<li class="header">Manage Industry Type</li>
			<li class="treeview <?php if($class_active=='industrytype') echo 'active';?>">
				<a href="#"><i class="fa fa-exclamation-circle"></i> <span>Industry Type</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="<?=site_url('industrytype')?>"><i class="fa fa-eye"></i> View Industry Type</a></li>
					<li><a href="<?=site_url('industrytype/add')?>"><i class="fa fa-plus"></i>Create Industry Type</a></li>
				</ul>
			</li>
			<li class="header">Manage Calling</li>
			<li class="treeview <?php if($class_active=='callingstatus') echo 'active';?>">
				<a href="#"><i class="fa fa-exclamation-circle"></i> <span>Calling Status</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="<?=site_url('calling/status')?>"><i class="fa fa-eye"></i> View Calling Status</a></li>
					<li><a href="<?=site_url('calling/status/add')?>"><i class="fa fa-plus"></i>Create Calling Status</a></li>
				</ul>
			</li>
			
			<li class="header">Manage Project</li>

			<li class="treeview">
				<a href="#"><i class="fa fa-bullhorn"></i> <span>Project</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="<?=site_url('project/')?>"><i class="fa fa-eye"></i> View Project</a></li>
					<li><a href="<?=site_url('project/add')?>"><i class="fa fa-plus"></i> Create New Project</a></li>
				</ul>
			</li>
			
			<!--li class="treeview">
				<a href="#"><i class="fa fa-file-text"></i> <span>Calling Lot </span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="<?=site_url('')?>"><i class="fa fa-eye"></i> View Calling Lot</a></li>
					<li><a href="<?=site_url('')?>"><i class="fa fa-plus"></i> Create Calling Lot</a></li>
				</ul>
			</li-->
			
			<!--li class="treeview">
				<a href="#"><i class="fa fa-graduation-cap"></i> <span>Calling Batch</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="<?=site_url('')?>"><i class="fa fa-eye"></i> View Calling Batch</a></li>
					<li><a href="<?=site_url('')?>"><i class="fa fa-plus"></i>Create Calling Batch</a></li>
				</ul>
			</li-->			
			
			<li class="header">Manage Data</li>
			
			<li class="treeview">
                <a href="#"><i class="fa fa-file-text"></i> <span>Data Allocation</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('dataallocation')?>"><i class="fa fa-eye"></i> Manage Filter Criteria</a></li>
                    <li><a href="<?=site_url('')?>"><i class="fa fa-upload"></i> Allocate Data Calling</a></li>
					<li><a href="<?=site_url('')?>"><i class="fa fa-upload"></i> View Allocation History</a></li>
					<li><a href="<?=site_url('')?>"><i class="fa fa-upload"></i> Manage Close File</a></li>
                </ul>
            </li>
			<li class="treeview">
                <a href="#"><i class="fa fa-medkit"></i> <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
					<li><a href="<?=site_url('masterdata/uploadexcel')?>"><i class="fa fa-cloud-upload"></i>Upload Master Data</a></li>
					<li><a href="<?=site_url('masterdata')?>"><i class="fa fa-eye"></i>View Master Data</a></li>
                    <li><a href="<?=site_url('')?>"><i class="fa fa-plus"></i>Lead Data</a></li>
                    <li><a href="<?=site_url('')?>"><i class="fa fa-plus"></i>Calling Data</a></li>
                    <li><a href="<?=site_url('')?>"><i class="fa fa-plus"></i>Freeze Data</a></li>
                </ul>
            </li>
			
        </ul>
    </section>
</aside>