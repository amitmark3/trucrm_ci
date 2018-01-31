<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $template['title'] ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= $template['css'] ?>
    </head>
    <body class="skin-red hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <?= img("assets/img/logo-login.png") ?>
            </div>
            <div class="login-box-body">
                <!-- <p class="login-box-msg"><?= lang('login_message_box') ?></p> -->
                <?php if ($this->session->flashdata('message')): ?>
                <div class="row">
                    <div class="col-xs-12">
                        <?= $this->flasher->get_message() ?>
                    </div>
                </div>
                <?php endif; ?>
                <?= form_open("login", array('id' => 'login-form')) ?>
                    <div class="form-group">
                        <?= form_input($email) ?>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group">
                        <?= form_password($password) ?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="checkbox"><label><input type="checkbox" name="remember" id="remember" checked="checked"> <?= lang('login_remember_label') ?></label></div>
                        </div>
                        <div class="col-xs-5">
                            <button type="submit" class="btn btn-success btn-block" id="submitButton"><?= lang('login_submit_btn') ?></button>
                        </div>
                    </div>
                <?= form_close() ?>
                <hr>
                <a href="<?= base_url('forgot_password') ?>" class="btn btn-default"><?= lang('login_forgot_password') ?></a>
                <!--a href="<!--?= base_url('signup') ?>" class="pull-right btn bg-maroon"><!--?= lang('login_sign_up') ?></a--><br>
            </div>
        </div>
        <?= $template['js'] ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#login-form').formValidation({
                    framework: 'bootstrap',
                    fields: {
                        email: {
                            validators: {
                                notEmpty: {
                                    message: 'Please enter your email address.'
                                },
                                emailAddress: {
                                    message: 'Please enter a valid email address.'
                                }
                            }
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: 'Please enter your password.'
                                }
                            }
                        },
                    }
                });
            });
        </script>
    </body>
</html>