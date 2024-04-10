function createMethodSelect(target, options, selected) {
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
}

function isEmptyInput(name) {
    return $('input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#auto_sel_'+name).val().trim() == '';
}

function validateLightstat() {
    if (isEmptyInput('name')) {
        return 'Не указано название';
    }
    if (isEmptyInput('optimal')) {
        return 'Не указана оптимальная освещенность';
    }
    if (isEmptyInput('gisteresis')) {
        return 'Не указан гистерезис';
    }
    if (!$("input[name=mode]:checked").val()) {
        return 'Не указан режим';
    }
    if (isEmptyInput('min_threshold')) {
        return 'Не указана минимальная освещенность';
    }
    if (isEmptyInput('max_threshold')) {
        return 'Не указана максимальная освещенность';
    }
    if (isEmptyInput('min_alarm')) {
        return 'Не указана мин. аварийная освещенность';
    }
    if (isEmptyInput('max_alarm')) {
        return 'Не указана макс. аварийная освещенность';
    }

    if ($('#lightstat_form input[name=object_type]').length
        && $('#lightstat_form input[name=object_type]:checked').val() === 'manual'
        && isEmptyAutoSelect('id_object')) {
        return 'Не указан объект датчика освещенности';
    }

    // if (isEmptyAutoSelect('object')) {
    //     return 'Не указан объект влияния';
    // }
    // if (isEmptyAutoSelect('method_on')) {
    //     return 'Не указан метод при включении';
    // }
    // if (isEmptyAutoSelect('method_off')) {
    //     return 'Не указан метод при выключении';
    // }

    let on_params = $("#lightstat_form #method_on_params");
    if (on_params.is(":visible") && on_params.val().trim() === '') {
        return 'Не указан параметр метода при включении';
    }
    on_params_int = parseInt(on_params.val().trim());
    if (on_params.is(":visible") &&
            (on_params.val().trim() != on_params_int || on_params_int < 0 || on_params_int > 100)) {
        return 'Недопустимое значение параметра метода при включении';
    }
    let off_params = $("#lightstat_form #method_off_params");
    if (off_params.is(":visible") && off_params.val().trim() === '') {
        return 'Не указан параметр метода при выключении';
    }
    off_params_int = parseInt(off_params.val().trim());
    if (off_params.is(":visible") &&
            (off_params.val().trim() != off_params_int || off_params_int < 0 || off_params_int > 100)) {
        return 'Недопустимое значение параметра метода при выключении';
    }
    return '';
}

function initMethodsVar(object_id) {
    if (object_id) {
        $.ajax({
            url: url_methods,
            data: {'_token': _token, 'object_id': object_id},
            success: function (data) {
                methods = data.methods;
            }
        });
    }
}

function initMotionsensorForm() {

    $("#auto_sel_object_normal").chosen().change(function() {
        let object_id = $(this).val();
        hideParamsFields('method_normal_params');
        getMethods(object_id, '#auto_sel_method_normal');
    });

    $("#auto_sel_object_eco").chosen().change(function() {
        let object_id = $(this).val();
        hideParamsFields('method_eco_params');
        getMethods(object_id, '#auto_sel_method_eco');
    });

    $("#auto_sel_object_night").chosen().change(function() {
        let object_id = $(this).val();
        hideParamsFields('method_night_params');

        getMethods(object_id, '#auto_sel_method_night');
    });

    $("#auto_sel_object_evening").chosen().change(function() {
        let object_id = $(this).val();
        hideParamsFields('method_evening_params');

        getMethods(object_id, '#auto_sel_method_evening');
    });

    $("#auto_sel_object_morning").chosen().change(function() {
        let object_id = $(this).val();
        hideParamsFields('method_morning_params');

        getMethods(object_id, '#auto_sel_method_morning');
    });

    $("#auto_sel_object_guard").chosen().change(function() {
        let object_id = $(this).val();
        hideParamsFields('method_guard_params');

        getMethods(object_id, '#auto_sel_method_guard');
    });

    $("#auto_sel_object_light").chosen().change(function() {
        let object_id = $(this).val();
        hideParamsFields('method_light_params');

        getMethods(object_id, '#auto_sel_method_light');
    });



    //при загрузке страницы подгружаем методы для выбранного объекта
    // getMethods($("#auto_sel_object_normal").val(), '#auto_sel_method_normal', '{{ $motionsensor->method_normal }}');
    // getMethods($("#auto_sel_object_eco").val(), '#auto_sel_method_eco', '{{ $motionsensor->method_eco }}');
    // getMethods($("#auto_sel_object_night").val(), '#auto_sel_method_night', '{{ $motionsensor->method_night }}');
    // getMethods($("#auto_sel_object_evening").val(), '#auto_sel_method_evening', '{{ $motionsensor->method_evening }}');
    // getMethods($("#auto_sel_object_morning").val(), '#auto_sel_method_morning', '{{ $motionsensor->method_morning }}');
    // getMethods($("#auto_sel_object_guard").val(), '#auto_sel_method_guard', '{{ $motionsensor->method_guard }}');
    // getMethods($("#auto_sel_object_light").val(), '#auto_sel_method_light', '{{ $motionsensor->method_light }}');


    $("#auto_sel_method_normal").chosen().change(function() {
        loadMethods($(this).val(), 'method_normal_params', '#motionsensor_form');
    });

    $("#auto_sel_method_eco").chosen().change(function() {
        loadMethods($(this).val(), 'method_eco_params', '#motionsensor_form');
    });

    $("#auto_sel_method_night").chosen().change(function() {
        loadMethods($(this).val(), 'method_night_params', '#motionsensor_form');
    });

    $("#auto_sel_method_evening").chosen().change(function() {
        loadMethods($(this).val(), 'method_evening_params', '#motionsensor_form');
    });

    $("#auto_sel_method_morning").chosen().change(function() {
        loadMethods($(this).val(), 'method_morning_params', '#motionsensor_form');
    });

    $("#auto_sel_method_guard").chosen().change(function() {
        loadMethods($(this).val(), 'method_guard_params', '#motionsensor_form');
    });

    $("#auto_sel_method_light").chosen().change(function() {
        loadMethods($(this).val(), 'method_light_params', '#motionsensor_form');
    });

}

function hideParamsFields(id) {
    $('#motionsensor_form #'+id+'_div').hide();
    $('#motionsensor_form #'+id).val('');
}
