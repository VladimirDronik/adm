function isEmptyInput(name) {
    return $('#manometr_form input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#manometr_form #auto_sel_'+name).val().trim() == '';
}

function validateDrycontact() {
    if ($("#manometr_form input[name=type]").length && !$("#manometr_form input[name=type]:checked").val()) {
        return 'Не указан тип';
    }
    if (isEmptyInput('name')) {
        return 'Не указано название';
    }

    if ( $('#manometr_form input[name=object_type]').length && $('#manometr_form input[name=object_type]:checked').val() === 'manual'
            && isEmptyAutoSelect('id_object')) {
        return 'Не указан объект';
    }

    return '';
}

function hideParamsFields(id) {
    $('#manometr_form #'+id+'_div').hide();
    $('#manometr_form #'+id).val('');
}

function showParamsFields(id, params) {
    $('#manometr_form #'+id+'_label').text(params+'*:');
    $('#manometr_form #'+id).val('');
    $('#manometr_form #'+id+'_div').show();
}

function getMethodParams(methodId) {

    for (let i = 0; i < methods.length; i++) {
        if (methods[i].id === methodId) {
            return methods[i].params ? methods[i].params : '';
        }
    }

    return '';
}

function initManometrForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});

    $('#manometr_form button[type=submit]').click(function(){

        let message = validateDrycontact();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });
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









