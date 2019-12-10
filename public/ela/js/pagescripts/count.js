function isEmptyInput(name) {
    return $('#count_form input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#count_form #auto_sel_'+name).val().trim() == '';
}

function validateCount() {
    if ($("#count_form input[name=type]").length && !$("#count_form input[name=type]:checked").val()) {
        return 'Не указан тип';
    }
    if (isEmptyInput('name')) {
        return 'Не указано название';
    }

    let object_type = $('#count_form input[name=object_type]');

    if (object_type.length && $('#count_form input[name=object_type]:checked').val() === 'manual'
            && isEmptyAutoSelect('id_object')) {
        return 'Не указан объект';
    }

    if (isEmptyInput('impulse')) {
        return 'Не указано значение за один импульс';
    }
    if (!$.isNumeric($('input[name=impulse]').val().trim())) {
        return 'Недопустимое значение за один импульс';
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
    if (!$.isNumeric($('input[name=today_value]').val().trim())) {
        return 'Недопустимое значение за сегодня';
    }
    if (!$.isNumeric($('input[name=total_value]').val().trim())) {
        return 'Недопустимое общее значение';
    }
    return '';
}

function replaceCommaToDot() {
    let today_value_element = $('#count_form input[name=today_value]');
    today_value_element.val(today_value_element.val().trim().replace(',', '.'));
    let total_value_element = $('#count_form input[name=total_value]');
    total_value_element.val(total_value_element.val().trim().replace(',', '.'));
    let impulse = $('#count_form input[name=impulse]');
    impulse.val(impulse.val().trim().replace(',', '.'));
}

function initCountForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});

    $('#count_form button[type=submit]').click(function(){

        replaceCommaToDot();

        let message = validateCount();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });
}