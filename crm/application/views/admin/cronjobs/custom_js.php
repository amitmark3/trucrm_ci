<script type="text/javascript">
    $('#<?= $form_name ?>').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'Please enter a name.'
                    },
                }
            },
            command: {
                validators: {
                    notEmpty: {
                        message: 'Please enter the command.'
                    },
                }
            },
            interval: {
                validators: {
                    notEmpty: {
                        message: 'Please enter the interval in seconds.'
                    },
                }
            },
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>