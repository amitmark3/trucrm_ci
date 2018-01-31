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
                <a href="#"><i class="fa fa-bullhorn"></i> <span>Go to record</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('')?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?=site_url('')?>"><i class="fa fa-plus"></i>Submit Report</a></li>
                </ul>
            </li>
			<li class="treeview">
                <a href="#"><i class="fa fa-calendar"></i> <span>Callback Calendar</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?=site_url('')?>"><i class="fa fa-eye"></i> View All</a></li>
                    
                </ul>
            </li>
			
            <li class="treeview">
                <a href="#"><i class="fa fa-medkit"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-eye"></i>Reports</a></li>
                    <li><a href="#"><i class="fa fa-plus"></i> Submit Report</a></li>
                </ul>
            </li>
			
			Callback calendar
        </ul>
    </section>
</aside>