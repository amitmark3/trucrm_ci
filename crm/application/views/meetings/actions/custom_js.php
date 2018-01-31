<script type="text/javascript">
    $('#<?=$form_name?>').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            details: {
                validators: {
                    notEmpty: {
                        message: 'Please enter the details.'
                    },
                }
            }
        }
    });

    $('.input-group.date').datepicker({
        format: "yyyy-mm-dd",
        orientation: "bottom auto",
        // daysOfWeekDisabled: "0,6",
        autoclose: true,
        todayHighlight: true
    });
</script>