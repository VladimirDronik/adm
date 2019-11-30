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

    // $("#auto_sel_object").chosen().change(function() {
    //     let object_id = $(this).val();
    //
    //     $.ajax({
    //         url: url_methods,
    //         data: {'_token': _token, 'object_id': object_id},
    //         success: function (data) {
    //             createMethodSelect('#auto_sel_method_on', data.methods, -1);
    //             $('#auto_sel_method_on').trigger("chosen:updated");
    //             createMethodSelect('#auto_sel_method_off', data.methods, -1);
    //             $('#auto_sel_method_off').trigger("chosen:updated");
    //         }
    //     });
    // });

    $('button[type=submit]').click(function(){
        let message = validateCount();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });
}