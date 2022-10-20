function isEmptyInput(name) {
    return $('#relay_form input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#lamp_form #auto_sel_'+name).val().trim() == '';
}

function validateCurtain() {

    if ($("#curtain_form input[name=type]").length && !$("#curtain_form input[name=type]:checked").val()) {

        return 'Не указан тип';
    }

    if (isEmptyInput('name')) {
        return 'Не указано название';
    }

    if (isEmptyInput('time')) {
        return 'Не указано время открытия или закрытия';
    }



    return '';
}

function initCurtainForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});

    $('#curtain_form button[type=submit]').click(function(){

        let message = validateCurtain();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });
}