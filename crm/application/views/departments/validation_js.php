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
        }
    });

    $('.selectpicker').selectpicker({
        'liveSearch': true,
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>