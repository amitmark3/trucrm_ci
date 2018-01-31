<script type="text/javascript">
    $('#users').dataTable( {
        "searching": true,
        "processing": true,
        "serverSide": true,
        "pageLength": 25,
        "order": [4, "asc"],
        ajax: {
            "url": "<?= $url ?>",
            "type": "POST"
        },
        "columnDefs": [
            {
                "targets": 0,
                "render": function ( data ) {
                    return '<a href="<?= site_url("admin/users/view") ?>/'+data+'" title="View User">'+data+'</a>';
                },
            },
            {
                "targets": 3,
                "render": function ( data ) {
                    return '<a href="mailto:'+data+'">'+data+'</a>';
                },
            },
        ]
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('admin/users/delete') ?>';
        bootbox.dialog({
            message: "Are you sure you want to delete this user?",
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