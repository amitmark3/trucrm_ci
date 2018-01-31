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
            description: {
                validators: {
                    notEmpty: {
                        message: 'Please enter a description.'
                    },
                }
            },
            price: {
                validators: {
                    notEmpty: {
                        message: 'Please enter a price.'
                    },
                }
            },
            space: {
                validators: {
                    notEmpty: {
                        message: 'Please enter the space allotted.'
                    },
                }
            },
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>