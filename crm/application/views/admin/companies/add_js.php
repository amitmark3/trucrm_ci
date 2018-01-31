<script type="text/javascript">
    $('#company-form').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'Please enter the company name.'
                    },
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'Please enter the email address.'
                    },
                    emailAddress: {
                        message: 'Please enter a valid email address.'
                    }
                }
            },
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>