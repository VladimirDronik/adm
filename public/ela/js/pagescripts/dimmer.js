function isEmptyInput(name) {
    return $('#dimmer_form input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#dimmer_form #auto_sel_'+name).val().trim() == '';
}

function validateDimmer() {
    if (isEmptyInput('name')) {
        return 'Не указано название';
    }

    if ( $('#dimmer_form input[name=object_type]').length && $('#dimmer_form input[name=object_type]:checked').val() === 'manual'
            && isEmptyAutoSelect('id_object')) {
        return 'Не указан объект';
    }

    if (isEmptyInput('value')) {
        return 'Не указано значение';
    }

    if (isEmptyInput('speed')) {
        return 'Не указана скорость';
    }
    return '';
}

function initDimmerForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});

    $('#dimmer_form button[type=submit]').click(function(){
        let message = validateDimmer();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });
}