<script type="text/javascript">
    $('#payments').dataTable( {
        "searching": true,
        "processing": true,
        "serverSide": true,
        "pageLength": 25,
        "order": [5, "asc"],
        ajax: {
            "url": "<?= $url ?>",
            "type": "POST"
        },
        "columnDefs": [
            {
                "targets": [0,6],
                "sortable": false,
                "searchable": false,
            },
            {
                "targets": 0,
                "visible": false,
            },
            {
                "targets": [4,5],
                "render": function ( data )
                {
                    if (data)
                    {
                        var mDate = moment(data);
                        return (mDate && mDate.isValid()) ? mDate.format("MMM Do YYYY") : "";
                    }
                    return "";
                },
            }
        ]
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('admin/payments/delete') ?>';
        bootbox.dialog({
            message: "Are you sure you want to delete this payment?",
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