<script type="text/javascript">
    $('.input-group.date').datepicker({
        format: "yyyy-mm-dd",
        orientation: "bottom auto",
        daysOfWeekDisabled: "0,6",
        autoclose: true,
        todayHighlight: true
    });

    $('.selectpicker').selectpicker({
        'liveSearch': true,
    });

    $(document).on("click", ".confirm_close", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var url = '<?= site_url('meetings/close') ?>';
        bootbox.dialog({
            message: "Are you sure you want to close this meeting?",
            title: "Please Confirm",
            buttons: {
                danger: {
                    label: "Cancel",
                    className: "btn-danger"
                },
                main: {
                    label: "Close Meeting",
                    className: "btn-success",
                    callback: function() {
                        window.location = url+'/'+id;
                    }
                }
            }
        });
    });

    $(function() {
        $('.add_attendee').click(function(e) {
            e.preventDefault();
            $('tr.attendees_error').remove();
            $('tr.no_attendees_found').remove();
            var user_id = $('select[name=user_id]').val();
            var status = $('select[name=status]').val();
            var meeting_id = $('input[name=meeting_id]').val();
            $.post("<?= site_url('meetings/add_attendee') ?>",
                {
                    'user_id' : user_id,
                    'status' : status,
                    'meeting_id' : meeting_id
                },
                function(html) {
                    $('#meeting_attendees > tbody').append(html);
                }, "text"
            );
        });
    });

    $(function() {
        $('#meeting_attendees').on('click', '.remove_attendee', function(e) {
            e.preventDefault();
            var user_id = $(this).attr('id');
            var meeting_id = $(this).data('meeting-id');
            var tableRow = $(this).closest('tr');
            $.post("<?= site_url('meetings/remove_attendee') ?>",
                {
                    'user_id' : user_id,
                    'meeting_id' : meeting_id
                },
                function(html) {
                    if (html == 'success')
                    {
                        tableRow.find('td').fadeOut('slow',
                            function() { 
                                tableRow.remove();
                            }
                        );
                    }
                    else
                    {
                        $('#meeting_attendees > tbody').append('<tr><td colspan="3"><p class="text-red">There was a problem removing the attendee.</p></td></tr>');
                    }
                }, "text"
            );
        });
    });

    $(function() {
        $('.add_agenda').click(function(e) {
            e.preventDefault();
            $('tr.agendas_error').remove();
            $('tr.no_agendas_found').remove();
            var topic = $('input[name=topic]').val();
            var presenter_user_id = $('select[name=presenter_user_id]').val();
            var allotted_time = $('select[name=allotted_time]').val();
            var meeting_id = $('input[name=meeting_id]').val();
            $.post("<?= site_url('meetings/add_agenda') ?>",
                {
                    'topic' : topic,
                    'presenter_user_id' : presenter_user_id,
                    'allotted_time' : allotted_time,
                    'meeting_id' : meeting_id
                },
                function(html) {
                    $('#meeting_agendas > tbody').append(html);
                    $('input[name=topic]').val('');
                    $('select[name=presenter_user_id], select[name=allotted_time]').prop('selectedIndex',0);
                }, "text"
            );
        });
    });

    $(function() {
        $('#meeting_agendas').on('click', '.remove_agenda', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var meeting_id = $(this).data('meeting-id');
            var tableRow = $(this).closest('tr');
            $.post("<?= site_url('meetings/remove_agenda') ?>",
                {
                    'id' : id,
                    'meeting_id' : meeting_id
                },
                function(html) {
                    if (html == 'success')
                    {
                        tableRow.find('td').fadeOut('slow',
                            function() { 
                                tableRow.remove();
                            }
                        );
                    }
                    else
                    {
                        $('#meeting_agendas > tbody').append('<tr><td colspan="4"><p class="text-red">There was a problem removing the agenda.</p></td></tr>');
                    }
                }, "text"
            );
        });
    });

    $(function() {
        $('.add_action').click(function(e) {
            e.preventDefault();
            $('tr.actions_error').remove();
            $('tr.no_actions_found').remove();
            var details = $('input[name=details]').val();
            var user_id = $('select[name=assigned_user_id]').val();
            var status = $('select[name=action_status]').val();
            var priority = $('select[name=priority]').val();
            var ecd = $('input[name=ecd]').val();
            var meeting_id = $('input[name=meeting_id]').val();
            $.post("<?= site_url('meetings/add_action') ?>",
                {
                    'details' : details,
                    'user_id' : user_id,
                    'status' : status,
                    'priority' : priority,
                    'ecd' : ecd,
                    'meeting_id' : meeting_id
                },
                function(html) {
                    $('#meeting_actions > tbody').append(html);
                    $('input[name=details]').val('');
                    $('select[name=user_id], select[name=action_status], select[name=priority]').prop('selectedIndex',0);
                }, "text"
            );
        });
    });

    $(function() {
        $('#meeting_actions').on('click', '.remove_action', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var meeting_id = $(this).data('meeting-id');
            var tableRow = $(this).closest('tr');
            $.post("<?= site_url('meetings/remove_action') ?>",
                {
                    'id' : id,
                    'meeting_id' : meeting_id
                },
                function(html) {
                    if (html == 'success')
                    {
                        tableRow.find('td').fadeOut('slow',
                            function() { 
                                tableRow.remove();
                            }
                        );
                    }
                    else
                    {
                        $('#meeting_actions > tbody').append('<tr><td colspan="4"><p class="text-red">There was a problem removing the action.</p></td></tr>');
                    }
                }, "text"
            );
        });
    });

    $("#image_upload").fileinput({
        fileActionSettings: {
            showZoom: false,
        },
        previewFileType: 'image',
        showUpload: false,
        showCaption: false,
        browseLabel: 'Select files...',
        allowedPreviewTypes: 'image',
        allowedFileTypes: ['image', 'text', 'pdf', 'other'],
        allowedFileExtensions: ['gif','jpg','jpeg','png','bmp','pdf','doc','docx','xls','xlsx','ppt','pptx','txt','csv'],
        maxFileSize: '<?= $this->config->item('max_file_size') ?>'
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>

    $(document).on("click", ".delete", function(e) {
        var element = $(this);
        var file_name = element.closest('.img-wrap').find('a').attr('title');
        var div_id = element.closest('.img-wrap').attr('id');

        bootbox.dialog({
            onEscape: true,
            title: "Please Confirm",
            message: "Are you sure you want to delete this image?",
            buttons: {
                danger: {
                    label: "Cancel",
                    className: "btn-danger"
                },
                main: {
                    label: "Delete Image",
                    className: "btn-success",
                    callback: function() {
                        $.ajax({
                            url: "<?= site_url('meetings/delete_image') ?>",
                            type: "POST",
                            data: {
                                'file_name' : file_name,
                                'meeting_id' : <?= $meeting['id'] ?>,
                            },
                            success: function(response) {
                                if (response == "deleted") {
                                    $('#' + div_id).remove();
                                    bootbox.alert('The image has been deleted.');
                                }
                            },
                            dataType: "text"
                        });
                    }
                }
            }
        });
    });
</script>