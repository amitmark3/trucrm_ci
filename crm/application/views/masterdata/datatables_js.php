<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#masterdata_data').DataTable( {
            "searching": true,
            "processing": true,
            "serverSide": true,
            "pageLength": 25,
            "order": [0, "desc"],
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
                    },
                    customize: function ( win ) {
                        $(win.document.body).prepend(
                            '<table width="100%" align="center"><tr><td><img src="<?= site_url("assets/img/email-logo-cropped.png") ?>" /></td></tr></table>'
                        );
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
                    },
					<?php if ( ! is_null($this->company['logo'])) : ?>
					customize: function ( win ) {
						win.content.splice( 1, 0, {
                        margin: [ 0, 0, 0, 12 ],
                        alignment: 'center',
                        image: '<?php $path = site_url("uploads/{$this->company['uploads_folder']}/avatars/{$this->company['logo']}"); $type = pathinfo($path, PATHINFO_EXTENSION); $data = file_get_contents($path); echo $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);?>' 
                    } );
                    }
				 <?php endif; ?>	
                }
            ],
            "columnDefs": [
                {
                    "targets": [0,5],
                    "sortable": false,
                    "searchable": false,
                },
				/*{
                    "targets": [2],
                    "searchable": false,
                },*/
                {
                    "targets": 0,
                    "render": function ( data ) {
                        return '<a title="View" href="<?= site_url('masterdata/view') ?>/'+data+'">'+data+'</a>';
                    },
                },
                {
                    "targets": [3],
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
                        return data == 1 ? "<span class='badge bg-green'>Active</span>" : "<span class='badge bg-red'>Inactive</span>";
                    }
                },
            ],
            "createdRow": function( row, data ) {
                if (data[5] > '0' && data[7] == '0') {
                    var today = new Date();
                    var review_date = new Date(data[5]);
                    if ( today.getTime() > review_date.getTime() ) {
                        $(row).addClass('danger');
                    }
                }
            }
        });

        table.buttons().container().appendTo( '#risk_assessments_wrapper .col-sm-6:eq(0)' );
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('masterdata/delete') ?>';
        bootbox.dialog({
            message: "Are you sure you want to delete this master data?",
            title: "Please Confirm",
            buttons: {
                danger: {
                    label: "Cancel",
                    className: "btn-danger"
                },
                main: {
                    label: "Delete It",
                    className: "btn-success",
                    callback: function() {
                        window.location = url+'/'+id;
                    }
                }
            }
        });
    });
</script>