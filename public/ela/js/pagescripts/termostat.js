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

function validateTermostat() {
    if (isEmptyInput('name')) {
        return 'Не указано название';
    }
    if (isEmptyInput('optimal')) {
        return 'Не указана оптимальная температура на вкладке свойств';
    }
    if (isEmptyInput('gisteresis')) {
        return 'Не указан гистерезис на вкладке свойсвт';
    }
    if (!$("input[name=thermostat]:checked").val()) {
        return 'Не указан режим';
    }
    if (isEmptyInput('min_threshold')) {
        return 'Не указана минимальная температура на вкладке свойсвт';
    }
    if (isEmptyInput('max_threshold')) {
        return 'Не указана максимальная температура на вкладке свойсвт';
    }
    if (isEmptyInput('min_alarm')) {
        return 'Не указана мин. аварийная температура на вкладке свойсвт';
    }
    if (isEmptyInput('max_alarm')) {
        return 'Не указана макс. аварийная температура на вкладке свойсвт';
    }

    if ($('#termostat_form input[name=object_type]').length
        && $('#termostat_form input[name=object_type]:checked').val() === 'manual'
        && isEmptyAutoSelect('id_object')) {
        return 'Не указан объект термостата';
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

    let on_params = $("#termostat_form #method_on_params");
    if (on_params.is(":visible") && on_params.val().trim() === '') {
        return 'Не указан параметр метода при включении на вкладке методов';
    }
    on_params_int = parseInt(on_params.val().trim());
    if (on_params.is(":visible") &&
            (on_params.val().trim() != on_params_int || on_params_int < 0 || on_params_int > 100)) {
        return 'Недопустимое значение параметра метода при включении на вкладке методов';
    }
    let off_params = $("#termostat_form #method_off_params");
    if (off_params.is(":visible") && off_params.val().trim() === '') {
        return 'Не указан параметр метода при выключении на вкладке методов';
    }
    off_params_int = parseInt(off_params.val().trim());
    if (off_params.is(":visible") &&
            (off_params.val().trim() != off_params_int || off_params_int < 0 || off_params_int > 100)) {
        return 'Недопустимое значение параметра метода при выключении на вкладке методов';
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

function initTermostatForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_object").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_method_on").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_method_off").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});

    $("#auto_sel_object").chosen().change(function() {
        let object_id = $(this).val();
        hideParamsFields('method_on_params');
        hideParamsFields('method_off_params');
        $.ajax({
            url: url_methods,
            data: {'_token': _token, 'object_id': object_id},
            success: function (data) {
                methods = data.methods;
                createMethodSelect('#auto_sel_method_on', data.methods, -1);
                $('#auto_sel_method_on').trigger("chosen:updated");
                createMethodSelect('#auto_sel_method_off', data.methods, -1);
                $('#auto_sel_method_off').trigger("chosen:updated");
            }
        });
    });

    $('button[type=submit]').click(function(){
        let message = validateTermostat();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });

    // params

    function getMethodParams(methodId) {
        for (let i = 0; i < methods.length; i++) {
            if (methods[i].id === methodId) {
                return methods[i].params ? methods[i].params : '';
            }
        }

        return '';
    }

    function hideParamsFields(id) {
        $('#termostat_form #'+id+'_div').hide();
        $('#termostat_form #'+id).val('');
    }

    function showParamsFields(id, params) {
        $('#termostat_form #'+id+'_label').text(params+'*:');
        $('#termostat_form #'+id).val('');
        $('#termostat_form #'+id+'_div').show();
    }

    $("#auto_sel_method_on").chosen().change(function() {
        const methodId = parseInt($(this).val());
        const params = getMethodParams(methodId);

        if (params === '') {
            hideParamsFields('method_on_params');
        } else {
            showParamsFields('method_on_params', params);
        }
    });

    $("#auto_sel_method_off").chosen().change(function() {
        const methodId = parseInt($(this).val());
        const params = getMethodParams(methodId);

        if (params === '') {
            hideParamsFields('method_off_params');
        } else {
            showParamsFields('method_off_params', params);
        }
    });
}