function clearCreateObjectModal() {
    $('#create_object_modal input[name=object_type]').prop('checked', false);
    $('#create_object_modal input[name=object_name]').val('');
    $('#create_object_modal_body .btn-group-toggle label').removeClass('active');
    hideCreateObjectError();
}

function validateCreateObject() {
    if ($("#create_object_modal input[name=object_type]").length && !$("#create_object_modal input[name=object_type]:checked").val()) {
        return 'Не указан тип';
    }
    if ($("#create_object_modal input[name=object_name]").val().trim() == '') {
        return 'Не указано название';
    }
    return '';
}

function showCreateObjectError(message) {
    $('#create_object_modal_error').text(message);
    $('#create_object_modal_error_div').show();
}

function hideCreateObjectError() {
    $('#create_object_modal_error_div').hide();
}

function createObjectSelect(target, options, selected) {
    let sel = $(target);
    sel.html('');
    let s = '<option value="">Не выбрано</option>';
    for (let i = 0; i < options.length; i++) {
        if (selected == options[i].id)
            s += '<option selected value="' + options[i].id + '">' + options[i].name + '</option>';
        else
            s += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
    }
    sel.append(s);
    sel.trigger("chosen:updated");
}