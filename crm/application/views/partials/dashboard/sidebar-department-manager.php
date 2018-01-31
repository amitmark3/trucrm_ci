<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="image">
                <?php if ( ! is_null($this->company['logo'])) : ?>
                    <img src="<?= site_url("uploads/{$this->company['uploads_folder']}/avatars/{$this->company['logo']}") ?>" class="user-image img-thumbnail" alt="<?= $this->company['name'] ?>">
                <?php endif; ?>
            </div>
        </div>
        <ul class="sidebar-menu">
			 <li class="treeview">
                <a href="#"><i class="fa fa-calendar"></i> <span>Meetings</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('meetings')?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?=site_url('meetings/add')?>"><i class="fa fa-plus"></i> Add Meeting</a></li>
                </ul>
            </li>
			<li class="treeview">
                <a href="#"><i class="fa fa-users"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('users')?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?=site_url('users/add')?>"><i class="fa fa-plus"></i> Add User</a></li>
                </ul>
            </li>
			<li class="header">Manage Data</li>
			<li class="treeview">
                <a href="#"><i class="fa fa-file-text"></i> <span>Data Allocation</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('')?>"><i class="fa fa-eye"></i> Manage Filter Criteria</a></li>
                    <li><a href="<?=site_url('')?>"><i class="fa fa-upload"></i> Allocate Data Calling</a></li>
					<li><a href="<?=site_url('')?>"><i class="fa fa-upload"></i> View Allocation History</a></li>
                </ul>
            </li>
			<li class="treeview">
                <a href="#"><i class="fa fa-exclamation-circle"></i> <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
					<li><a href="<?=site_url('')?>"><i class="fa fa-eye"></i>View Master Data</a></li>
                    <li><a href="<?=site_url('')?>"><i class="fa fa-plus"></i>Lead Data</a></li>
                    <li><a href="<?=site_url('')?>"><i class="fa fa-plus"></i>Calling Data</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>