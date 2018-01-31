<script type="text/javascript">
    $('#change-password-form').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            new_password: {
                validators: {
                    notEmpty: {
                        message: 'Please enter the new password.'
                    },
                    stringLength: {
                        min: 6,
                        message: 'Password must be at least 6 characters long.'
                    }
                }
            },
            confirm_new_password: {
                validators: {
                    notEmpty: {
                        message: 'Please confirm the new password.'
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