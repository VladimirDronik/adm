function isEmptyInput(name) {
    return $('input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#auto_sel_'+name).val().trim() == '';
}

function validateCount() {
    if ($("input[name=type]").length && !$("input[name=type]:checked").val()) {
        return 'Не указан тип';
    }
    if (isEmptyInput('name')) {
        return 'Не указано название';
    }
    if (isEmptyAutoSelect('id_object')) {
        return 'Не указан объект';
    }
    if (isEmptyInput('impulse')) {
        return 'Не указано количество импульсов';
    }
    if ($("input[name=unit]").length && isEmptyInput('unit')) {
        return 'Не указана единица измерения';
    }
    if (isEmptyInput('today_value')) {
        return 'Не указано значение за сегодня';
    }
    if (isEmptyInput('total_value')) {
        return 'Не указано общее значение';
    }
    return '';
}

function initCountForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});

    $('button[type=submit]').click(function(){
        let message = validateCount();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });
}