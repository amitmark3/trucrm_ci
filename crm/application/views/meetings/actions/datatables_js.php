<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#meeting_actions').DataTable( {
            "searching": true,
            "processing": true,
            "serverSide": true,
            "pageLength": 25,
            "order": [6, "desc"],
            ajax: {
                "url": "<?= $url ?>",
                "type": "POST"
            },
            dom: "<'row'<'col-xs-4'l><'col-xs-4 text-center'B><'col-xs-4'f>><'row'<'col-xs-12't>><'row'<'col-xs-5'i><'col-xs-7'p>>",
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
                    "targets": 0,
                    "sortable": false,
                    "searchable": false,
                    "render": function(data) {
                        return '<a href="<?= site_url('meetings/view_action') ?>/'+data+'">'+data+'</a>';
                    },
                },
                {
                    "targets": 4,
                    "render": function (data) {
                        switch(data) {
                            case 'low':
                                return '<span class="badge bg-gray">Low</span>';
                            break;
                            case 'medium':
                                return '<span class="badge bg-yellow">Medium</span>';
                            break;
                            case 'high':
                                return '<span class="badge bg-orange">High</span>';
                            break;
                            case 'urgent':
                                return '<span class="badge bg-red">Urgent</span>';
                            break;
                            default:
                                return data;
                        }
                    }
                },
                {
                    "targets": 5,
                    "render": function (data) {
                        switch(data) {
                            case 'open':
                                return '<span class="badge bg-red">Open</span>';
                            break;
                            case 'in_progress':
                                return '<span class="badge bg-yellow">In Progress</span>';
                            break;
                            case 'closed':
                                return '<span class="badge bg-green">Closed</span>';
                            break;
                            default:
                                return data;
                        }
                    }
                },
                {
                    "targets": 6,
                    "render": function(data) {
                        if (data)
                        {
                            var mDate = moment(data);
                            return (mDate && mDate.isValid()) ? mDate.format("MMM Do YYYY") : "";
                        }
                        return "";
                    }
                },
                <?php if ($this->user_group['id'] == 2) : ?>
                {
                    "targets": 8,
                    "sortable": false,
                    "searchable": false,
                },
                <?php endif; ?>
            ]
        });

        table.buttons().container().appendTo( '#meeting_actions_wrapper .col-sm-6:eq(0)' );
    });
</script>