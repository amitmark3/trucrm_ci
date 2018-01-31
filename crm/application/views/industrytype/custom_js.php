<script type="text/javascript">
    $('#<?=$form_name?>').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        fields: {
            
        }
    });

    <?php $this->load->view('partials/dashboard/fade_out_flash_message_js'); ?>
</script>