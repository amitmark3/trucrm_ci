<script type="text/javascript">
    $('#<?=$form_name?>').formValidation({
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
        }
    });

    $('.input-group.date').datepicker({
        format: "yyyy-mm-dd",
        orientation: "bottom auto",
        // daysOfWeekDisabled: "0,6",
        autoclose: true,
        todayHighlight: true
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>