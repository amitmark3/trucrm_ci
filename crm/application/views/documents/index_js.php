<script type="text/javascript">
    $(window).load(function() {
        $('.grid').masonry({
            itemSelector: '.grid-item',
            columnWidth: 200,
            gutter: 20
        });
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
	
	$(document).on("click", ".zoom_image", function(e) {
		var imgsrc= $(this).attr("data-id");
		 var file_name = $(this).attr("title");
		$("#img_src_val").html('<img src="'+imgsrc+'" width="500" >');
		
		bootbox.dialog({
			onEscape: true,
			// size: 'large',
			title: file_name,
			message: $('#add_action_image'),
			show: false
		}).on('shown.bs.modal', function() {
			$('#add_action_image').show();
			$('#add_action_image').formValidation('resetForm', true);
		}).on('hide.bs.modal', function() {
			$(this).removeData('bs.modal');
			$('#add_action_image').trigger('reset').hide().appendTo('body');
		}).modal('show'); 
	});
	
	/* Functionality to delete the folder */
	$(document).on("click", ".confirm", function(e) {
        e.preventDefault();
        var folder = $(this).attr('data-folder');
        var url = '<?= site_url('documents/delete_folder') ?>';
        bootbox.dialog({
            onEscape: true,
            title: "Please Confirm",
            message: "Are you sure you want to delete this folder?",
            buttons: {
                danger: {
                    label: "Cancel",
                    className: "btn-danger"
                },
                main: {
                    label: "Delete Folder",
                    className: "btn-success",
                    callback: function() {
                        window.location = url+'/'+folder;
                    }
                }
            }
        });
    });

    $(document).on("click", ".delete", function(e) {
        var element = $(this);
        var file_name = element.closest('.img-wrap').find('a').attr('title');
        var div_id = element.closest('.img-wrap').attr('id');
		var folder_name = element.closest('.img-wrap').find('a').attr('data-folder');
		
        bootbox.dialog({
            message: "Are you sure you want to delete this document?",
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
                        $.ajax({
                            url: "<?= site_url('documents/delete') ?>",
                            type: "POST",
                            data: {
                                'file_name' : file_name,
								'folder_name' : folder_name,
                            },
                            success: function(response) {
                                if (response == "deleted") {
                                    $('#' + div_id).remove();
                                    bootbox.alert('The file has been deleted.');
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