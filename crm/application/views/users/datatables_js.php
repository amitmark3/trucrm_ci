<script type="text/javascript">
    $(document).ready(function() {
        <?php switch ($this->user_group['id']) {
            case 3:
                $target = 5;
                $order = 4;
                break;
            default:
                $target = 6;
                $order = 5;
                break;
        } ?>
        var table = $('#users').DataTable( {
            "searching": true,
            "processing": true,
            "serverSide": true,
            "pageLength": 25,
            "order": [<?= $order ?>, "asc"],
            ajax: {
                "url": "<?= $url ?>",
                "type": "POST"
            },
            <?php $this->load->view('partials/dashboard/datatables_dom'); ?>
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1,2,3,4,5,6]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [1,2,3,4,5,6]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1,2,3,4,5,6]
                    }
                }
            ],
            "columnDefs": [
                {
                    "targets": 0,
                    "sortable": false,
                    "render": function ( data ) {
                        return '<a href="<?= site_url('users/view') ?>/'+data+'">'+data+'</a>';
                    },
                },
                {
                    "targets": 3,
                    "render": function ( data ) {
                        return '<a href="mailto:'+data+'">'+data+'</a>';
                    },
                },
                {
                    "targets": <?= $target ?>,
                    "render": function ( data ) {
                        return data == 1 ? '<span class="badge bg-green">Yes</span>' : '<span class="badge bg-red">No</span>';
                    },
                },
                <?php if ($this->user_group['id'] < 4) : ?>
                {
                    "targets": <?= $last_column ?>,
                    "sortable": false,
                    "searchable": false,
                },
                <?php endif; ?>
            ]
        });

        table.buttons().container().appendTo( '#users_wrapper .col-sm-6:eq(0)' );
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('users/delete') ?>';
        bootbox.dialog({
            message: "Are you sure you want to delete this user?",
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