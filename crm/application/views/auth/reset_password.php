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
                <p class="login-box-msg"><?= lang('reset_password_subheading') ?></p>
                <?php if ($this->session->flashdata('message')): ?>
                <div class="row">
                    <div class="col-xs-12">
                        <?= $this->flasher->get_message() ?>
                    </div>
                </div>
                <?php endif; ?>
                <?= form_open("reset_password/" . $code, array('id' => 'reset-password-form')) ?>
	                <?= form_input($user_id) ?>
					<?= form_hidden($csrf) ?>
                    <div class="form-group">
                        <?= form_input($new_password) ?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group">
                        <?= form_password($new_password_confirm) ?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-success btn-block" id="submitButton"><?= lang('reset_password_submit_btn') ?></button>
                        </div>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
        <?= $template['js'] ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#reset-password-form').formValidation({
                    framework: 'bootstrap',
                    excluded: ':disabled',
                    fields: {
                        new: {
                            validators: {
                                notEmpty: {
                                    message: 'Please choose a password.'
                                },
                                stringLength: {
                                    min: 6,
                                    message: 'Password must be at least 6 characters long.'
                                }
                            }
                        },
                        new_confirm: {
                            validators: {
                                notEmpty: {
                                    message: 'Please confirm your password.'
                                },
                                identical: {
                                    field: 'new',
                                    message: 'The passwords do not match.'
                                }
                            }
                        },
                    }
                });
            });
        </script>
    </body>
</html>
