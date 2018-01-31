<script type="text/javascript">
    $('#<?= $form_name ?>').formValidation({
        framework: 'bootstrap',
        fields: {
            company_id: {
                validators: {
                    notEmpty: {
                        message: 'Please select a company.'
                    },
                }
            },
            price_plan_id: {
                validators: {
                    notEmpty: {
                        message: 'Please select a price plan.'
                    },
                }
            },
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>