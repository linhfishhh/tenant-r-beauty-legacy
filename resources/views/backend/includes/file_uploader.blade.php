@unique('wa_file_uploader')
<form id="wa_file_uploader_form" style="display: none">
    <input type="file" name="file">
</form>
<script type="text/javascript">
    function wa_file_uploader(configs, after_upload) {
        $('#wa_file_uploader_form input').click();
    }
</script>
@endunique