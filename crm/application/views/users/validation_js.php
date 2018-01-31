<script type="text/javascript">
    $('#<?= $form_name ?>').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'Please enter a <?= strtolower(lang('users_first_name_placeholder')) ?>.'
                    },
                }
            },
            last_name: {
                validators: {
                    notEmpty: {
                        message: 'Please enter a <?= strtolower(lang('users_last_name_placeholder')) ?>.'
                    },
                }
            },
            email_address: {
                validators: {
                    notEmpty: {
                        message: 'Please enter an <?= strtolower(lang('users_email_address_placeholder')) ?>.'
                    },
                    emailAddress: {
                        message: 'Please enter a valid <?= strtolower(lang('users_email_address_placeholder')) ?>.'
                    }
                }
            },
            group_id: {
                validators: {
                    notEmpty: {
                        message: 'Please enter a <?= strtolower(lang('users_group_placeholder')) ?>.'
                    },
                }
            },
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $('#group').change(function()
    {
        var group_id = $(this).val();
        // console.log(group_id);
        if (group_id != 2)
        {
            $("#departments_dropdown").removeClass('hidden');
            $('#add-user-form').formValidation('addField', 'department_id', {
                validators: { 
                    notEmpty: {
                        message: 'Please choose a <?= strtolower(lang('users_department_placeholder')) ?>.'
                    },
                }
            });
        }
        else
        {
            $("#departments_dropdown").addClass('hidden');
            $('#add-user-form').formValidation('removeField', 'department_id');
        }
    });
</script>