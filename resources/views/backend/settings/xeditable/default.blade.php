<script type="text/javascript">
    function xEditableResponseHandle(response, $unhandle_error_msg) {
        if (response.hasOwnProperty('responseJSON')){
            if (response.responseJSON.hasOwnProperty('errors')){
                var errors = response.responseJSON.errors;
                var list = '';
                var c = 0;
                $.each(errors, function (i, v) {
                    list += '- '+v+'\n';
                    c++;
                });
                return list;
            }
            else if(response.responseJSON.hasOwnProperty('message')){
                return response.responseJSON.message
            }
        }
        return $unhandle_error_msg;
    }

    $.fn.editableform.template = '<form class="editableform">' +
        '<div class="control-group" style="white-space: nowrap">' +
        '<div class="editable-input"></div> <div class="editable-buttons"></div>' +
        '<div class="editable-error-block"></div>' +
        '</div> ' +
        '</form>';
    $.fn.editableform.buttons =
        '<button type="submit" class="btn btn-primary btn-icon editable-submit"><i class="icon-check"></i></button>' +
        '<button type="button" class="btn btn-default btn-icon editable-cancel"><i class="icon-x"></i></button>';
</script>