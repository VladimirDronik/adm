function isEmptyInput(name) {
    return $('#conditioner_form input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#conditioner_form #auto_sel_'+name).val().trim() == '';
}

function validateConditioner() {
    if (isEmptyAutoSelect('vendor_id')) {
        return 'Не указан производитель';
    }

    if (isEmptyAutoSelect('model_id')) {
        return 'Не указана модель';
    }

    if (isEmptyAutoSelect('room_id')) {
        return 'Не указано помещение';
    }

    if (isEmptyAutoSelect('device_id')) {
        return 'Не указан контроллер';
    }

    if (isEmptyAutoSelect('id_object')) {
        return 'Не указан объект';
    }

    if (isEmptyInput('wb_mir')) {
        return 'Не указан адрес WB-MIR';
    }

    return '';
}

function initConditionerForm() {
    $('#conditioner_form button[type=submit]').click(function(){

        let message = validateConditioner();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });
}