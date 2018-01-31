<header class="main-header">
    <?php
    if ($this->ion_auth->is_admin())
    {
        $url = base_url('admin');
    }
    else
    {
        $url = base_url('dashboard');
    }
    ?>
    <a href="<?= $url ?>" class="logo">
        <!--strong>T<span class="glyphicon glyphicon-eye-open" style="font-size: 14px"></span>ucrm</strong-->
		<img src="<?php echo site_url('/assets/img/logo-login.png');?>" alt="Trucrm">
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <i class="fa fa-user fa-lg"></i>
                                        </div>
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li> -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <?php $class = ($note_count > 0) ? '' : ' hidden'; ?>
                        <span class="label label-success<?= $class ?>"><?= ($note_count > 0) ? $note_count : '' ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if ($note_count > 0) : ?>
                            <li class="header">You have <?= $note_count ?> new notifications.</li>
                            <li>
                                <ul class="menu">
                                    <?php foreach ($unread_notes as $note) : ?>
                                    <?php
                                    switch ($note['type']) {
                                        case 'accident':
                                            $icon = 'medkit';
                                            $colour = 'red';
                                            break;
                                        case 'early_report':
                                            $icon = 'bullhorn';
                                            $colour = 'yellow';
                                            break;
                                        case 'risk_assessment':
                                            $icon = 'warning';
                                            $colour = 'orange';
                                            break;
                                        case 'meeting':
                                            $icon = 'calendar';
                                            $colour = 'green';
                                            break;
                                        case 'safety_walk':
                                            $icon = 'fire-extinguisher';
                                            $colour = 'red';
                                            break;
                                        case 'training_required':
                                            $icon = 'graduation-cap';
                                            $colour = 'light-blue';
                                            break;
                                        case 'training_completed':
                                            $icon = 'graduation-cap';
                                            $colour = 'light-blue';
                                            break;
                                        case 'inspection':
                                            $icon = 'wrench';
                                            $colour = 'muted';
                                            break;
                                        default:
                                            $icon = 'info';
                                            $colour = 'muted';
                                            break;
                                    }
                                    ?>
                                    <li>
                                        <a href="<?= $note['link'] ?>" title="Click to view it"><i class="fa fa-<?= $icon ?> text-<?= $colour ?>"></i> <?= $note['title'] ?></a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="header">You have no new notifications.</li>
                        <?php endif; ?>
                        <li class="footer"><a href="<?= site_url('notifications') ?>">View All Notifications</a></li>
                    </ul>
                </li>
                <!-- <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-info">9</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 9 tasks</li>
                        <li>
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <h3>
                                            Design some buttons
                                            <small class="pull-right">20%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
                        </li>
                    </ul>
                </li> -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php if ( ! is_null($profile['avatar'])) : ?>
                            <?php if ( ! $this->ion_auth->is_admin()) : ?>
                                <img src="<?= site_url('uploads/'.$this->company['uploads_folder'].'/avatars/'.$profile['avatar']) ?>" class="user-image" alt="Avatar">
                            <?php else: ?>
                                <img src="<?= site_url('uploads/'.$profile['avatar']) ?>" class="user-image" alt="Avatar">
                            <?php endif; ?>
                        <?php else : ?>
                            <i class="fa fa-user"></i>
                        <?php endif; ?>
                        <span class="hidden-xs"><?= $profile['first_name'] ?> <?= $profile['last_name'] ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <?php if ( ! is_null($profile['avatar'])) : ?>
                                <?php if ( ! $this->ion_auth->is_admin()) : ?>
                                    <img src="<?= site_url('uploads/'.$this->company['uploads_folder'].'/avatars/'.$profile['avatar']) ?>" class="img-circle" alt="Avatar">
                                <?php else: ?>
                                    <img src="<?= site_url('uploads/'.$profile['avatar']) ?>" class="user-image" alt="Avatar">
                                <?php endif; ?>
                            <?php else : ?>
                                <img src="<?= site_url('assets/img/icons/user.png') ?>" class="img-circle" alt="User Image">
                            <?php endif; ?>
                            <p>
                                <?= $profile['first_name'] ?> <?= $profile['last_name'] ?>
                                <?= (isset($profile['job_title'])) ? '&nbsp;'.$profile['job_title'] : '' ?>
                                <?php if ($this->ion_auth->in_group([3,4])): ?>
                                <small>Department / Location: <?= $user_department ?></small>
                                <?php endif ?>
                                <small>Member since <?= date('jS M Y', strtotime($this->user->created_at)) ?></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= anchor('profile', '<i class="fa fa-image"></i> Profile', ['class' => 'btn bg-teal']) ?>
                            </div>
                            <div class="pull-right">
                            <?= anchor('logout', '<i class="fa fa-sign-out"></i> Logout', ['class' => 'btn bg-navy']) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- <li><a href="feedback" title="Send us Feedback"><i class="fa fa-question-circle"></i></a></li> -->
            </ul>
        </div>
    </nav>
</header>