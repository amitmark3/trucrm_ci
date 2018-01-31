<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Company Details</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <!-- <tr>
                                <td>Uploads Folder</td>
                                <td><?= $company_folder ?></td>
                            </tr> -->
                            <tr>
                                <td width="33%">Name</td>
                                <td><?= $company['name'] ?></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td><?= isset($company['address']) ? nl2br($company['address']) : '&ndash;' ?></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td><?= isset($company['description']) ? nl2br($company['description']) : '&ndash;' ?></td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <td><?= isset($company['phone_number']) ? nl2br($company['phone_number']) : '&ndash;' ?></td>
                            </tr>
                            <tr>
                                <td>Website</td>
                                <td><?= isset($company['website_url']) ? nl2br($company['website_url']) : '&ndash;' ?></td>
                            </tr>
                            <tr>
                                <td>Is Active?</td>
                                <td id="active-status">
                                    <input id="active" type="checkbox" name="active" <?= ($company['active'] == 1) ? 'checked="checked"' : '' ?> class="toggle-status" data-url="<?= site_url('admin/companies/set_active_status') ?>">
                                    <i class="fa fa-spinner fa-spin fa-fw hidden"></i>
                                    <i class="fa fa-check hidden"></i>
                                    <i class="fa fa-exclamation-triangle hidden"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>Setup Complete?</td>
                                <td id="setup-status">
                                    <input id="setup" type="checkbox" name="setup_step" <?= ($company['setup_step'] == 7) ? 'checked="checked"' : '' ?> class="toggle-status" data-url="<?= site_url('admin/companies/set_setup_status') ?>">
                                    <i class="fa fa-spinner fa-spin fa-fw hidden"></i>
                                    <i class="fa fa-check hidden"></i>
                                    <i class="fa fa-exclamation-triangle hidden"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="box-footer"><a href="<?= site_url("admin/companies/delete/{$company['id']}/confirmation") ?>" class="btn bg-navy btn-block" id="<?= $company['id'] ?>"><i class="fa fa-trash"></i> Delete Company</a></div> -->
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-2">
        <div class="box box-default">
            <div class="box-body">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= ($company['users'][0]['counted_rows'] > 0) ? $company['users'][0]['counted_rows'] : '0' ?></h3>
                        <p>Users</p>
                    </div>
                    <div class="icon"><i class="ion ion-ios-people"></i></div>
                </div>
                <div class="small-box bg-maroon">
                    <div class="inner">
                        <?php if (isset($percent_used)) : ?>
                        <h3><?= $percent_used ?><sup style="font-size: 20px">%</sup></h3>
                        <p>Space Used</p>
                        <?php else : ?>
                        <h3>!</h3>
                        <p>No price plan chosen yet, so no disk space allotted.</p>
                        <?php endif; ?>
                    </div>
                    <div class="icon"><i class="ion ion-filing"></i></div>
                </div>
                <!--div class="small-box bg-green">
                    <div class="inner">
                        <h3><!?= ($company['departments'][0]['counted_rows'] > 0) ? $company['departments'][0]['counted_rows'] : '0' ?></h3>
                        <p>Departments</p>
                    </div>
                    <div class="icon"><i class="ion ion-cube"></i></div>
                </div-->
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Payment Details</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr>
                                <td width="50%">Price Plan</td>
                                <td><?= ($company['price_plan']['name'] !== NULL) ? $company['price_plan']['name'] : 'Not Chosen Yet' ?></td>
                            </tr>
                            <tr>
                                <td>Last Payment</td>
                                <td><?= (! is_null($company['payments']) ? date('jS F Y', strtotime($company['payments'][0]['created_at'])) : 'None') ?></td>
                            </tr>
                            <tr>
                                <td>Renewal Date</td>
                                <td><?= (! is_null($company['payments']) ? date('jS F Y', strtotime($company['payments'][0]['renewal_date'])) : 'None') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
