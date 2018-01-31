<script type="text/javascript">
    $('#<?= $form_name ?>').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'Please enter your <?= strtolower(lang('profile_first_name_placeholder')) ?>.'
                    },
                }
            },
            last_name: {
                validators: {
                    notEmpty: {
                        message: 'Please enter your <?= strtolower(lang('profile_last_name_placeholder')) ?>.'
                    },
                }
            },
            email_address: {
                validators: {
                    notEmpty: {
                        message: 'Please enter your <?= strtolower(lang('profile_email_address_placeholder')) ?>.'
                    },
                    emailAddress: {
                        message: 'Please enter a valid <?= strtolower(lang('profile_email_address_placeholder')) ?>.'
                    }
                }
            },
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>