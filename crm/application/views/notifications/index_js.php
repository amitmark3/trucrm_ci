<script type="text/javascript">
    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $(document).on("click", ".mark_as_read", function(e) {
        e.preventDefault();
        var link = $(this);
        var id = link.attr('id');
        $.ajax({
            type: 'POST',
            url: '<?= site_url("notifications/mark_as_read") ?>',
            data: { 'id' : id },
            success: function(response) {
                if (response == 'done')
                {
                    link.find('i').remove();
                    link.parent('td').html('<i class="fa fa-check-circle fa-lg"></i>');
                }
            },
            error: function() {
                alert('There was a problem marking the notification as read.');
            },
            dataType: 'text'
        });
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    // $(document).ready(function () {
    //     $('#noteModal').on('show.bs.modal', function(e) {
    //         var noteid = $(e.relatedTarget).data('note-id');
    //         var url = "<?= site_url('notifications/get') ?>";
    //         var jqxhr = $.getJSON(url + '/' + noteid, function(json) {
    //             $('#noteModal .modal-body').html(json["data"]);
    //         });
    //     });
    // });
</script>