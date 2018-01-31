<aside class="main-sidebar">
    <section class="sidebar">
        <!-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form> -->
        <ul class="sidebar-menu">
            <!-- <li class="header">ADMIN</li> -->
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-institution"></i>
                    <span>Companies</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/companies') ?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?= site_url('admin/companies/add') ?>"><i class="fa fa-plus"></i> Add Company</a></li>
                </ul>
            </li>
            <!-- <li class="treeview">
                <a href="#">
                    <i class="fa fa-server"></i>
                    <span>Cronjobs</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/cronjobs') ?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?= site_url('admin/cronjobs/add') ?>"><i class="fa fa-plus"></i> Add Cron Job</a></li>
                </ul>
            </li> -->
            <li class="treeview">
                <a href="#">
					<i class="fa fa-inr"></i>
                    <span>Price Plans</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/price_plans') ?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?= site_url('admin/price_plans/add') ?>"><i class="fa fa-plus"></i> Add Price Plan</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-credit-card"></i>
                    <span>Payments</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/payments') ?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?= site_url('admin/payments/add') ?>"><i class="fa fa-plus"></i> Add Payment</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Users</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('admin/users') ?>"><i class="fa fa-eye"></i> View All</a></li>
                    <li><a href="<?= site_url('admin/users/add') ?>"><i class="fa fa-circle-o"></i> Add User</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>