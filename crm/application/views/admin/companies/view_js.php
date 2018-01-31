<script type="text/javascript">
    $('.toggle-status').change(function() {
        var status = $(this).prop('checked') == true ? 7 : 0;
        var field = $(this).attr('id');
        var url = $(this).data('url');
        $('#'+field+'-status i.fa-spinner').removeClass('hidden');
        $('#'+field+'-status i.fa-check, #'+field+'-status i.exclamation-triangle').addClass('hidden');
        var data = {};
        data[field] = status;
        data['company_id'] = <?= $company['id'] ?>;
        console.log(data);
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: 'text',
            success: function (result) {
                if (result == 'TRUE')
                {
                    $('#'+field+'-status i.fa-spinner').addClass('hidden');
                    $('#'+field+'-status i.fa-check').removeClass('hidden');
                }
                else
                {
                    $('#'+field+'-status i.fa-spinner').addClass('hidden');
                    $('#'+field+'-status i.exclamation-triangle').removeClass('hidden');
                }
            }
        });
    });

    $('.toggle-status').bootstrapToggle({
        on: 'Yes',
        off: 'No',
        onstyle: 'success',
        offstyle: 'danger',
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>