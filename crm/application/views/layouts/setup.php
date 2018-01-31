<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title><?=$template['title']?></title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="shortcut icon" type="image/x-icon" href="<?= site_url('favicon.ico') ?>">
        <?= $template['css'] ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-red">
        <div class="wrapper setup-wrapper">
            <?= $template['partials']['header'] ?>
            <div class="content-wrapper setup-content-wrapper">
                <section class="content-header setup-content-header">
                    <noscript>
                        <br>
                        <div class="alert alert-warning">
                            <p><i class="fa fa-warning fa-lg"></i> Javascript is required for this website to function correctly. Please enable it and refresh the page.</p>
                        </div>
                    </noscript>
                </section>
                <section class="content">
                    <?php if ($this->session->flashdata('message')): ?>
                    <div class="row">
                        <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                            <?= $this->flasher->get_message() ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?= $template['body'] ?>
                </section>
            </div>
            <?= $template['partials']['footer'] ?>
        </div>
        <?= $template['js'] ?>
        <?php
        if (isset($template['partials']['custom_js']))
        {
            echo $template['partials']['custom_js'];
        }
        ?>
    </body>
</html>