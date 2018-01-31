<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#departments').DataTable( {
            "searching": true,
            "processing": true,
            "serverSide": true,
            "pageLength": 25,
            "order": [1, "asc"],
            ajax: {
                "url": "<?= $url ?>",
                "type": "POST"
            },
            <?php $this->load->view('partials/dashboard/datatables_dom'); ?>
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1,2,3]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [1,2,3]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1,2,3]
                    }
                }
            ],
            "columnDefs": [
                {
                    "targets": 0,
                    "sortable": false,
                    "searchable": false,
                    "render": function ( data ) {
                        return '<a href="<?= site_url('departments/view') ?>/'+data+'">'+data+'</a>';
                    },
                },
                {
                    "targets": 4,
                    "sortable": false,
                    "searchable": false,
                },
            ]
        });

        table.buttons().container().appendTo( '#departments_wrapper .col-sm-6:eq(0)' );
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('departments/delete') ?>';
        bootbox.dialog({
            message: "Are you sure you want to delete this department?",
            title: "Please Confirm",
            buttons: {
                danger: {
                    label: "Cancel",
                    className: "btn-danger"
                },
                main: {
                    label: "Delete Department",
                    className: "btn-success",
                    callback: function() {
                        window.location = url+'/'+id;
                    }
                }
            }
        });
    });
</script>