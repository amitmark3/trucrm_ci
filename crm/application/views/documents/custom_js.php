<script type="text/javascript">
    $("#userfile").fileinput({
        showUpload: false,
        previewFileType: 'image',
        showCaption: false,
        showClose: false,
        browseLabel: 'Select files...',
        maxFileSize: '<?= $this->config->item('max_file_size') ?>',
        fileActionSettings : {
            showZoom: false,
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
	
</script>