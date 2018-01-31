<script type="text/javascript">
    $('.toggle-status').change(function() {
        var active = $(this).prop('checked') == true ? 1 : 0;
        var url = $(this).data('url');
        $('#active-status i.fa-spinner').removeClass('hidden');
        $('#active-status i.fa-check, #active-status i.exclamation-triangle').addClass('hidden');
        // console.log(data);
        $.ajax({
            type: "POST",
            url: url,
            data: {
                'active': active,
                'user_id': <?= $user['id'] ?>
            },
            dataType: 'text',
            success: function (result) {
                if (result == 'TRUE')
                {
                    $('#active-status i.fa-spinner').addClass('hidden');
                    $('#active-status i.fa-check').removeClass('hidden');
                }
                else
                {
                    $('#active-status i.fa-spinner').addClass('hidden');
                    $('#active-status i.exclamation-triangle').removeClass('hidden');
                }
            }
        });
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $('.toggle-status').bootstrapToggle({
        on: 'Yes',
        off: 'No',
        onstyle: 'success',
        offstyle: 'danger',
    });

    $(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('admin/users/delete') ?>';
        bootbox.dialog({
            message: "<p>Are you sure you want to delete this user?</p>",
            title: "Please Confirm",
            buttons: {
                danger: {
                    label: "No",
                    className: "btn-default"
                },
                main: {
                    label: "Yes, Delete It",
                    className: "btn-success",
                    callback: function() {
                        window.location = url+'/'+id;
                    }
                }
            }
        });
    });
</script>