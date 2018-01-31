<script type="text/javascript">
    $('#edit-company-form').formValidation({
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
            address: {
                validators: {
                    notEmpty: {
                        message: 'Please enter the company address.'
                    },
                }
            },
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>