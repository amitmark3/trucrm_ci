<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $template['title'] ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= $template['css'] ?>
    </head>
    <body class="skin-red hold-transition register-page">
        <div class="register-box">
            <div class="register-logo">
                <?= img("assets/img/logo-signup.png") ?>
            </div>
            <div class="register-box-body">
                <!-- <p class="register-box-msg">Sign up using the form below</p> -->
                <?= form_open("signup", array('id' => 'register-form')) ?>
                    <div class="form-group">
                        <?= form_input($name) ?>
                        <span class="glyphicon glyphicon-briefcase form-control-feedback"></span>
                        <?= form_error('name') ?>
                    </div>
                    <div class="form-group">
                        <?= form_input($email) ?>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        <?= form_error('email') ?>
                    </div>
                    <div class="form-group">
                        <?= form_password($password) ?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <?= form_error('password') ?>
                    </div>
                    <div class="form-group">
                        <?= form_password($password_confirm) ?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <?= form_error('password_confirm') ?>
                    </div>
                    <!-- <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="agree" value="yes" /> I have read and agree with the terms &amp; conditions
                            </label>
                            <input type="hidden" name="agree" value="no" />
                        </div>
                    </div> -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block" name="signup" value="Sign up">Submit</button>
                    </div>
                <?= form_close() ?>
                <hr>
                <a href="<?= base_url('login') ?>" class="btn btn-default btn-block"><?= lang('signup_login') ?></a>
            </div>
        </div>
        <?= $template['js']; ?>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                $('#register-form').formValidation({
                    framework: 'bootstrap',
                    excluded: ':disabled',
                    fields: {
                        name: {
                            validators: {
                                notEmpty: {
                                    message: 'Please enter the name of the company.'
                                }
                            }
                        },
                        email: {
                            validators: {
                                notEmpty: {
                                    message: 'Please enter an email address.'
                                },
                                emailAddress: {
                                    message: 'Please enter a valid email address.'
                                }
                            }
                        },
                        password: {
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
                        password_confirm: {
                            validators: {
                                notEmpty: {
                                    message: 'Please confirm your password.'
                                },
                                identical: {
                                    field: 'password',
                                    message: 'The passwords do not match.'
                                }
                            }
                        },
                        // agree: {
                        //     excluded: false,
                        //     validators: {
                        //         callback: {
                        //             message: 'You must agree with the terms &amp; conditions',
                        //             callback: function(value, validator, $field)
                        //             {
                        //                 return value === 'yes';
                        //             }
                        //         }
                        //     }
                        // },
                    }
                });
            });
        </script>
    </body>
</html>