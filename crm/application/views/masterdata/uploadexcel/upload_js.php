<script type="text/javascript">
    $("#logo").fileinput({
        fileActionSettings: {
            showZoom: false,
            showRemove: false,
        },
        overwriteInitial: true,
        maxFileSize: 2048,
        showClose: false,
        showRemove: false,
        // showUpload: false,
        showCaption: false,
        showBrowse: false,
        browseOnZoneClick: true,
        resizeImage: true,
        maxImageWidth: 160,
        maxImageHeight: 160,
        resizePreference: 'width',
        elErrorContainer: '#upload-errors',
        msgErrorClass: 'alert alert-danger',
        defaultPreviewContent: '<img src="<?= $logo ?>" alt="Company logo" class="img-responsive"><h6 class="text-muted">Click to select</h6>',
        allowedFileTypes: ['image'],
        allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "bmp"],
        uploadUrl: '<?= site_url('company/logo') ?>',
        uploadAsync: true,
        maxFileCount: 1,
        uploadExtraData: {company_id: <?= $company_id ?>}
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>