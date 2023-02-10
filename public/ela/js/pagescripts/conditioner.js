function isEmptyInput(name) {
    return $('#conditioner_form input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#conditioner_form #auto_sel_'+name).val().trim() == '';
}

function validateConditioner() {
    if (isEmptyAutoSelect('vendor_id')) {
        return 'Не указан производитель';
    }

    if (isEmptyAutoSelect('model_id')) {
        return 'Не указана модель';
    }

    if (isEmptyAutoSelect('room_id')) {
        return 'Не указано помещение';
    }

    if ( $('#conditioner_form input[name=object_type]').length && $('#conditioner_form input[name=object_type]:checked').val() === 'manual'
            && isEmptyAutoSelect('id_object')) {
        return 'Не указан объект';
    }

    return '';
}



function initConditionerForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});

    $('#conditioner_form button[type=submit]').click(function(){

        let message = validateConditioner();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });




}