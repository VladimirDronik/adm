function isEmptyInput(name) {
    return $('#drycontact_form input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#drycontact_form #auto_sel_'+name).val().trim() == '';
}

function validateDrycontact() {
    if ($("#drycontact_form input[name=type]").length && !$("#drycontact_form input[name=type]:checked").val()) {
        return 'Не указан тип';
    }
    if (isEmptyInput('name')) {
        return 'Не указано название';
    }

    if ( $('#drycontact_form input[name=object_type]').length && $('#drycontact_form input[name=object_type]:checked').val() === 'manual'
            && isEmptyAutoSelect('id_object')) {
        return 'Не указан объект';
    }

    return '';
}


function getMethodParams(methodId) {

    for (let i = 0; i < methods.length; i++) {
        if (methods[i].id === methodId) {
            return methods[i].params ? methods[i].params : '';
        }
    }

    return '';
}

function initDrycontactForm() {
    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});

    $('#drycontact_form button[type=submit]').click(function(){

        let message = validateDrycontact();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });




}









