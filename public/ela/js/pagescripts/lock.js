function isEmptyInput(name) {
    return $('#lock_form input[name='+name+']').val().trim() == '';
}


function validateLock() {

    if ($("#lock_form input[name=type]").length && !$("#lock_form input[name=type]:checked").val()) {

        return 'Не указан тип';
    }

    if (isEmptyInput('name')) {
        return 'Не указано название';
    }


    return '';
}

function initLockForm() {

    $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});

    $('#lock_form button[type=submit]').click(function(){

        let message = validateLock();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#init_btn').click();
            return false;
        }
    });
}