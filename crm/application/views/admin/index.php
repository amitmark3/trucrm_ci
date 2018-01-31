<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= ($count['companies'] >= 1) ? $count['companies'] : '0' ?></h3>
                <p>Companies</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-briefcase"></i>
            </div>
            <a href="<?= site_url('admin/companies') ?>" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="small-box bg-orange">
            <div class="inner">
                <h3><?= ($count['users'] >= 1) ? $count['users'] : '0' ?></h3>
                <p>Users</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people"></i>
            </div>
            <a href="<?= site_url('admin/users') ?>" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="small-box bg-red">
            <div class="inner">
                <h3><i class="fa fa-inr"></i><?= $count['payment_total']['amount'] ?></h3>
                <p>Total Payments</p>
            </div>
            <div class="icon">
                <i class="fa fa-inr"></i>
            </div>
            <a href="<?= site_url('admin/payments') ?>" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="small-box bg-green">
            <div class="inner">
                TODO: Figure out how to see CP space usage
                <h3>37%</h3>
                <p>Disk Space Used</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-folder"></i>
            </div>
            <a href="<?= site_url('admin/space') ?>" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div> -->
</div>
<div class="row">
    <div class="col-xs-12 col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Company Registrations During Last 12 Months</h3>
            </div>
            <div class="box-body">
                <canvas id="company_months" style="height:250px"></canvas>
                <br>
                <p class="text-center"><strong>Note:</strong> Months with no registrations are not shown.</p>
            </div>
        </div>
    </div>

</div>