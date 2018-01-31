<script type="text/javascript">
    $('#documents').dataTable( {
        "searching": false,
        "processing": true,
        "serverSide": true,
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        ajax: {
            "url": "<?= $url ?>",
            "type": "POST"
        },
        "columnDefs": [
            {
                "targets": [3],
                "sortable": false,
            },
            {
                "targets": [0],
                "visible": false,
            }
        ]
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('admin/documents/delete') ?>';
        bootbox.dialog({
            message: "Are you sure you want to delete this document?",
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