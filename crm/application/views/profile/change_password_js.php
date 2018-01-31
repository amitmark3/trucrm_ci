<script type="text/javascript">
    $('#<?= $form_name ?>').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            old_password: {
                validators: {
                    notEmpty: {
                        message: 'Please enter your old password.'
                    },
                }
            },
            new_password: {
                validators: {
                    notEmpty: {
                        message: 'Please enter your new password.'
                    },
                    stringLength: {
                        min: 6,
                        message: 'The password must be at least 6 characters long.'
                    }
                }
            },
            new_password_confirm: {
                validators: {
                    notEmpty: {
                        message: 'Please confirm your new password.'
                    },
                    identical: {
                        field: 'new_password',
                        message: 'The passwords do not match.'
                    }
                }
            },
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>