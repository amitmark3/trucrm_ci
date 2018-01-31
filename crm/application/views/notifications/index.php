<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <br>
                <?php if (isset($notifications) && $notifications!='') : ?>
                <div class="row">
                    <!-- <div class="col-xs-3">
                        <div class="btn-group">
                            <?= form_open('notifications') ?>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                Show 10 entries &nbsp;<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">25 entries</a></li>
                                <li><a href="#">50 entries</a></li>
                            </ul>
                            <?= form_close() ?>
                        </div>
                        <br><br>
                    </div>
                    <div class="col-xs-9"> -->
                    <div class="col-xs-12">
                        <div class="pull-right" id="top-pagination">
                            <?= $pagination ?>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <!-- <th width="2%">
                                    <div class="checkbox" style="margin-top:0;margin-bottom:0;">
                                        <input type="checkbox" id="checkAll" class="styled" />
                                        <label for="checkAll"></label>
                                    </div>
                                </th> -->
                                <th width="5%">
                                    <a href="<?= site_url('notifications/type/'.(($sort_order == 'desc' && $sort_by == 'type') ? 'asc' : 'desc')) ?>">
                                        Type
                                        <?php
                                        if ($sort_by == 'type')
                                        {
                                            echo ($sort_order == 'desc') ? '<i class="fa fa-angle-down"></i>' : '<i class="fa fa-angle-up"></i>';
                                        }
                                        ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?= site_url('notifications/title/'.(($sort_order == 'desc' && $sort_by == 'title') ? 'asc' : 'desc')) ?>">
                                        Title
                                        <?php
                                        if ($sort_by == 'title')
                                        {
                                            echo ($sort_order == 'desc') ? '<i class="fa fa-angle-down"></i>' : '<i class="fa fa-angle-up"></i>';
                                        }
                                        ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?= site_url('notifications/created_at/'.(($sort_order == 'desc' && $sort_by == 'created_at') ? 'asc' : 'desc')) ?>">
                                        Time
                                        <?php
                                        if ($sort_by == 'created_at')
                                        {
                                            echo ($sort_order == 'desc') ? '<i class="fa fa-angle-down"></i>' : '<i class="fa fa-angle-up"></i>';
                                        }
                                        ?>
                                    </a>
                                </th>
                                <th width="10%">
                                    <a href="<?= site_url('notifications/viewed/'.(($sort_order == 'desc' && $sort_by == 'viewed') ? 'asc' : 'desc')) ?>">
                                        Viewed
                                        <?php
                                        if ($sort_by == 'viewed')
                                        {
                                            echo ($sort_order == 'desc') ? '<i class="fa fa-angle-down"></i>' : '<i class="fa fa-angle-up"></i>';
                                        }
                                        ?>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notifications as $note) : ?>
                            <?php switch ($note['type']) {
                                    case 'meeting':
                                        $icon = 'calendar';
                                        $colour = 'green';
                                        break;
                                   
                                    default:
                                        $icon = 'info';
                                        $colour = 'muted';
                                        break;
                                } ?>
                            <tr>
                                <!-- <td>
                                    <div class="checkbox">
                                        <input id="note" class="styled" type="checkbox">
                                        <label for="note"></label>
                                    </div>
                                </td> -->
                                <td><i class="fa fa-<?= $icon ?> text-<?= $colour ?>"></i></td>
                                <td><?= $note['title'] ?></td>
                                <td><?= strtolower(timespan(strtotime($note['created_at']), time())) . ' ago' ?></td>
                                <td>
                                    <?php if ($note['viewed'] == 0) : ?>
                                    <a href="javascript:void(0)" data-url="<?= site_url('notifications/mark_as_read/') ?>" title="Mark As Read" id="<?= $note['id'] ?>" class="mark_as_read" data-toggle="tooltip" data-placement="left"><i class="fa fa-times fa-lg"></i></a>
                                    <?php else: ?>
                                        <i class="fa fa-check-circle fa-lg"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <!-- <div class="col-xs-4">
                        <br>
                        <div class="input-group">
                            <select name="" id="" class="form-control">
                                <option value="">Mark As Read</option>
                                <option value="">Delete</option>
                            </select>
                            <span class="input-group-btn"><button class="btn">Apply</button></span>
                        </div>
                    </div>
                    <div class="col-xs-8"> -->
                    <div class="col-xs-12">
                        <div class="pull-right">
                            <?= $pagination ?>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <p><?= lang('notifications_none_found') ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>