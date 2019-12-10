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
        return 'Не указана оптимальная температура';
    }
    if (isEmptyInput('gisteresis')) {
        return 'Не указан гистерезис';
    }
    if (!$("input[name=thermostat]:checked").val()) {
        return 'Не указан режим';
    }
    if (isEmptyInput('min_threshold')) {
        return 'Не указана минимальная температура';
    }
    if (isEmptyInput('max_threshold')) {
        return 'Не указана максимальная температура';
    }
    if (isEmptyInput('min_alarm')) {
        return 'Не указана мин. аварийная температура';
    }
    if (isEmptyInput('max_alarm')) {
        return 'Не указана макс. аварийная температура';
    }

    if ($('#termostat_form input[name=object_type]').length && $('#termostat_form input[name=object_type]:checked').val() === 'manual'
        && isEmptyAutoSelect('id_object')) {
        return 'Не указан объект термостата';
    }

    if (isEmptyAutoSelect('object')) {
        return 'Не указан объект влияния';
    }
    if (isEmptyAutoSelect('method_on')) {
        return 'Не указан метод при включении';
    }
    if (isEmptyAutoSelect('method_off')) {
        return 'Не указан метод при выключении';
    }

    return '';
}

function initTermostatForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_object").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_method_on").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_method_off").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});

    $("#auto_sel_object").chosen().change(function() {
        let object_id = $(this).val();

        $.ajax({
            url: url_methods,
            data: {'_token': _token, 'object_id': object_id},
            success: function (data) {
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
}