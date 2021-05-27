function isEmptyInput(name) {
    return $('#switch_form input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#switch_form #auto_sel_'+name).val().trim() == '';
}

function validateSwitch() {
    if ($("#switch_form input[name=type]").length && !$("#switch_form input[name=type]:checked").val()) {
        return 'Не указан тип';
    }
    if (isEmptyInput('name')) {
        return 'Не указано название';
    }

    if ( $('#switch_form input[name=object_type]').length && $('#switch_form input[name=object_type]:checked').val() === 'manual'
            && isEmptyAutoSelect('id_object')) {
        return 'Не указан объект';
    }

    return '';
}



function initSwitchForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});

    $('#switch_form button[type=submit]').click(function(){

        let message = validateSwitch();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });




}