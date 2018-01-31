<script type="text/javascript">
    $('#add-user-form').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            group_id: {
                validators: {
                    notEmpty: {
                        message: 'Please choose a <?= strtolower(lang('users_role_placeholder')) ?>.'
                    },
                }
            },
            company_id: {
                validators: {
                    notEmpty: {
                        message: 'Please choose a <?= strtolower(lang('users_company_placeholder')) ?>.'
                    },
                }
            },
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
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $('#group_id').change(function()
    {
        var group_id = $(this).val();
        // console.log(group_id);
        $('#company_id, #department_id').prop('selectedIndex', 0);
        if (group_id >= 3)
        {
            $("#company_dropdown, #user_extras").removeClass('hidden');
            $("#departments_dropdown").addClass('hidden');
            $('#add-user-form').formValidation('addField', 'company_id',
            {
                validators: {
                    notEmpty: {
                        message: 'Please choose a <?= strtolower(lang('users_company_placeholder')) ?>.'
                    },
                }
            });
        }
        else
        {
            $("#company_dropdown, #departments_dropdown, #user_extras").addClass('hidden');
            $('#add-user-form').formValidation('removeField', 'company_id');
        }
    });

    $('#company_id').change(function()
    {
        var company_id = $(this).val();
        $.post('<?= site_url('admin/users/get_company_departments') ?>',
            {
                'company_id' : company_id
            },
            function(output)
            {
                $('#department_id').html("<option value='' selected='selected'>Please choose a department</option>");
                $('#department_id').append(output);
            }
        );
        var group_id = $('#group_id option:selected').val();
        // console.log(group_id);
        if (group_id > 2)
        {
            $("#departments_dropdown").removeClass('hidden');
            $('#add-user-form').formValidation('addField', 'department_id',
            {
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
        }
    });
</script>