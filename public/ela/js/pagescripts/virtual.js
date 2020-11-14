function isEmptyInput(name) {
    return $('#relay_form input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#lamp_form #auto_sel_'+name).val().trim() == '';
}

function validateLamp() {
    /*
    if ($("#lamp_form input[name=type]").length && !$("#relay_form input[name=type]:checked").val()) {

        return 'Не указан тип';
    }
     */
    if (isEmptyInput('name')) {
        return 'Не указано название';
    }

    if ( $('#lamp_form input[name=object_type]').length && $('#lamp_form input[name=object_type]:checked').val() === 'manual'
            && isEmptyAutoSelect('id_object')) {
        return 'Не указан объект';
    }

    return '';
}

function initRelayForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});

    $('#lamp_form button[type=submit]').click(function(){

        let message = validateLamp();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });
}