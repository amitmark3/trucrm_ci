<script type="text/javascript">
    $('#companies').dataTable( {
        "searching": true,
        "processing": true,
        "serverSide": true,
        "pageLength": 25,
        "order": [1, "asc"],
        ajax: {
            "url": "<?= $url ?>",
            "type": "POST"
        },
        "columnDefs": [
            {
                "targets": [0, 5],
                "sortable": false,
            },
            {
                "targets": 3,
                "render": function ( data )
                {
                     return data == 0 ? '<span class="badge bg-red">No</span>' : '<span class="badge bg-green">Yes</span>';
                }
            },
            {
                "targets": 4,
                "render": function ( data )
                {
                     return data == 7 ? '<span class="badge bg-green">Yes</span>' : '<span class="badge bg-red">No</span>';
                }
            },
            {
                "targets": 5,
                "searchable": false,
            }
        ]
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('admin/companies/delete') ?>';
        bootbox.dialog({
            message: "Are you sure you want to delete this company?",
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