<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <div class="row">
                    <div class="col-xs-12" id="logo-form">
                        <div id="upload-errors" class="center-block"></div>
                        <form class="text-center" action="" method="post" enctype="multipart/form-data">
                            <div class="kv-avatar center-block">
                                <input id="logo" name="logo" type="file" class="file-loading">
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-12" id="profile-info">
                        <h3 class="profile-username"><?= $company['name'] ?></h3>
                    </div>
                </div>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">Address <span class="pull-right"><?= $company['address'] ?></span></li>
                    <li class="list-group-item">Phone Number <span class="pull-right"><?= (!empty($company['phone_number']) ? $company['phone_number'] : '&mdash;') ?></span></li>
                    <li class="list-group-item">Website <span class="pull-right"><?= ($company['website_url'] !== NULL) ? $company['website_url'] : '&mdash;' ?></span></li>
                </ul>
                <a href="<?= site_url('company/edit') ?>" class="btn btn-primary btn-block"><b>Edit Details</b></a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
            <li class="active"><a href="#price_plan" data-toggle="tab" aria-expanded="false">Price Plan Details</a></li>
            <li><a href="#disk_space" data-toggle="tab" aria-expanded="true">Disk Space Overview</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="price_plan">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td><?= $price_plan['name'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Cost:</strong></td>
                                    <td><i class="fa fa-inr"></i><?= $price_plan['price'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Renewal Date:</strong></td>
                                    <td><?= date('jS F Y', strtotime($payment['renewal_date'])) ?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><a href="<?= site_url('company/change_price_plan') ?>" class="btn btn-success">Change Price Plan</a></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="disk_space">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td width="33%"><strong>Space Allotted:</strong></td>
                                    <td><?= $price_plan['space_allotted'] . ' ' . $price_plan['space_unit'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Space Used:</strong></td>
                                    <td><?= $percent_used ?>%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>