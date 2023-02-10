Array.prototype.insert = function ( index, item ) {
    this.splice( index, 0, item );
};

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function placeErrorLabel(error, element, field) {

    // Styled checkboxes, radios, bootstrap switch
    if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container') ) {
        if(element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
            error.appendTo( element.parent().parent().parent().parent() );
        }
        else {
            error.appendTo( element.parent().parent().parent().parent().parent() );
        }
    }
    
    // Unstyled checkboxes, radios
    else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
        error.appendTo( element.parent().parent().parent() );
    }
    
    // Input with icons and Select2
    else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
        error.appendTo( element.parent() );
    }
    
    // Inline checkboxes, radios
    else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
        error.appendTo( element.parent().parent() );
    }
    
    // Input group, styled file input
    else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
        error.appendTo( element.parent().parent() );
    }
    else if (element.parents('.custom-error-wrapper').length > 0) {
        error.appendTo( element.parents('.custom-error-wrapper').find('.error-placeholder'));
    }
    else {
        error.insertAfter(element);
    }
}

function cleanErrorMessage(form) {
    $(form).find('.handled-error-label').remove();
}

function handleErrorMessage(form, response) {
    if (response.hasOwnProperty('responseJSON')){
        if (response.responseJSON.hasOwnProperty('errors')){
            $(form).find('.handled-error-label').remove();
            var errors = response.responseJSON.errors;
            $.each(errors, function (field, messages){
                var tfield = field.split('.');
                if (tfield.length>1){
                    field = tfield[0];
                    $.each(tfield, function (i, v) {
                        if (v.length == 0 || i == 0){
                            return true;
                        }
                        field += '\\['+v+'\\]';
                    });
                }
                var message = messages[0];
                var error = '<label class="validation-error-label handled-error-label" for="'+field+'">'+message+'</label>';
                error = $(error);
                var element = $(form).find('[name='+field+']');
                placeErrorLabel(error, element, field);
            });
        }
    }
}

function getJSONErrorMessage(response, unhandle) {
    var rs = '';
    if (response.hasOwnProperty('responseJSON')){
        if(response.responseJSON.hasOwnProperty('message')){
            rs = response.responseJSON.message;
        }
        else{
            rs = response.responseJSON;
        }
    }
    if (rs.trim().length == 0){
        if (unhandle){
            rs = unhandle;
        }
    }
    return rs;
}

function fileSizeFormat(bytes) {
    var i = Math.floor(Math.log(bytes) / Math.log(1024)),
        sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    return (bytes / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + sizes[i];
}

function fixSelect2(node) {
    var select2Instance = $(node).data('select2');
    select2Instance.on('results:message', function(params){
        this.dropdown._resizeDropdown();
        this.dropdown._positionDropdown();
    });
}


