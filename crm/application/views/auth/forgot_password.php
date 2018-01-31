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
                <?= img("assets/img/logo-password.png") ?>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg"><?= lang('forgot_password_subheading') ?></p>
                <?php if ($this->session->flashdata('message')): ?>
                <div class="row">
                    <div class="col-xs-12">
                        <?= $this->flasher->get_message() ?>
                    </div>
                </div>
                <?php endif; ?>
                <?= form_open("forgot_password", ['id' => 'forgot-password-form']) ?>
                    <div class="form-group">
                        <?= form_input($email); ?>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-success btn-block" id="submitButton"><?= lang('forgot_password_submit_btn') ?></button>
                        </div>
                    </div>
                <?= form_close() ?>
                <hr>
                <a href="<?= base_url('login') ?>" class="btn btn-default btn-block"><?= lang('forgot_password_login') ?></a><br>
        </div>
        <?= $template['js'] ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#forgot-password-form').formValidation({
                    framework: 'bootstrap',
                    excluded: ':disabled',
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
                        }
                    }
                });
            });
        </script>
    </body>
</html>