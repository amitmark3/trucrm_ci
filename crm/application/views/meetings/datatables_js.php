<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#meetings').DataTable( {
            "searching": true,
            "processing": true,
            "serverSide": true,
            "pageLength": 25,
            "order": [3, "desc"],
            ajax: {
                "url": "<?= $url ?>",
                "type": "POST"
            },
            <?php $this->load->view('partials/dashboard/datatables_dom'); ?>
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1,2,3,4]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [1,2,3,4]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1,2,3,4]
                    }
                }
            ],
            "columnDefs": [
                {
                    "targets": [0, 5],
                    "sortable": false,
                },
                {
                    "targets": 0,
                    "render": function ( data ) {
                        return '<a href="<?= site_url('meetings/view') ?>/'+data+'">'+data+'</a>';
                    },
                },
                {
                    "targets": 3,
                    "render": function(data) {
                        if (data)
                        {
                            var mDate = moment(data);
                            return (mDate && mDate.isValid()) ? mDate.format("MMM Do YYYY") : "";
                        }
                        return "";
                    }
                },
                {
                    "targets": 4,
                    "render": function ( data )
                    {
                        return data == 0 ? "<span class='badge bg-green'>Closed</span>" : "<span class='badge bg-red'>Open</span>";
                    }
                },
                {
                    "targets": 5,
                    "searchable": false,
                },
            ]
        });

        table.buttons().container().appendTo( '#meetings_wrapper .col-sm-6:eq(0)' );
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('meetings/delete') ?>';
        bootbox.dialog({
            message: "Are you sure you want to delete this meeting?",
            title: "Please Confirm",
            buttons: {
                danger: {
                    label: "Cancel",
                    className: "btn-danger"
                },
                main: {
                    label: "Delete Meeting",
                    className: "btn-success",
                    callback: function() {
                        window.location = url+'/'+id;
                    }
                }
            }
        });
    });
</script>