<script type="text/javascript">
    $('#close-action-form').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            close_details: {
                validators: {
                    notEmpty: {
                        message: 'Please enter the closure details.'
                    }
                }
            }
        }
    });

    // $('#close_action').on('hidden.bs.modal', function()
    // {
    //     $('#close-action-form').formValidation('resetForm', true);
    // });
</script>