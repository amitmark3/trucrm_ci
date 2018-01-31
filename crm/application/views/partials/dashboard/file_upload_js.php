$("#image_upload").fileinput({
    'fileActionSettings': {
        'showZoom': false,
        'showRemove': false,
    },
    'showUpload': false,
    'previewFileType': 'any',
    'showCaption': false,
    'showClose': false,
    'browseLabel': 'Select images...',
    'allowedFileTypes': [<?= $this->config->item('allowed_file_types') ?>],
    'allowedFileExtensions': [<?= $this->config->item('allowed_file_extentions') ?>],
    'maxFileSize': <?= $this->config->item('max_file_size') ?>
});
