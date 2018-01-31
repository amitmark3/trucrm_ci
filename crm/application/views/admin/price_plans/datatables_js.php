<script type="text/javascript">
    $('#price_plans').dataTable( {
        "searching": true,
        "processing": true,
        "serverSide": true,
        "order": [1, "asc"],
        ajax: {
            "url": "<?= $url ?>",
            "type": "POST"
        },
        "columnDefs": [
            {
                "targets": 0,
                "sortable": false,
                "visible": false,
            },
            {
                "targets": [5,6],
                "sortable": false,
                "searchable": false,
            }
        ]
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('admin/price_plans/delete') ?>';
        bootbox.dialog({
            message: "Are you sure you want to delete this price plan?",
            title: "Please Confirm",
            buttons: {
                danger: {
                    label: "Cancel",
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