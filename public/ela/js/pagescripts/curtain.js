function isEmptyInput(name) {
    return $('#curtain_form input[name='+name+']').val().trim() == '';
}

function isEmptyAutoSelect(name) {
    return $('#curtain_form #auto_sel_'+name).val().trim() == '';
}

function validateCurtain() {

    if ($("#curtain_form input[name=place]").length && !$("#curtain_form input[name=place]:checked").val()) {

        return 'Не указан тип управления';
    }

    if (isEmptyInput('name')) {
        return 'Не указано название';
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

function portFields() {
    $('#port_id_div').removeAttr("hidden");
    $('#device_id_div').removeAttr("hidden");
    $('#auto_sel_port_id_open').removeAttr("disabled");
    $('#auto_sel_port_id_close').removeAttr("disabled");
    $('#auto_sel_device_id').removeAttr("disabled");

    $('#bus_id_div').attr("hidden", true);
    $('#rs_485_div').attr("hidden", true);
    $('#phase_time_div').attr("hidden", true);
    $('#auto_sel_bus_id').attr("disabled", true);
    $('#curtain_form input[name=address]').attr("disabled", true);
    $('#curtain_form input[name=group]').attr("disabled", true);
    $('#curtain_form input[name=time]').attr("disabled", true);
    $('#curtain_form input[name=type]').attr("disabled", true);
    $('#curtain_form input[name=is_inverse]').attr("disabled", true);
    $('#auto_sel_vendor').attr("disabled", true);
}

function phaseFields() {
    $('#port_id_div').removeAttr("hidden");
    $('#device_id_div').removeAttr("hidden");
    $('#auto_sel_port_id_open').removeAttr("disabled");
    $('#auto_sel_port_id_close').removeAttr("disabled");
    $('#auto_sel_device_id').removeAttr("disabled");
    $('#phase_time_div').removeAttr("hidden");
    $('#curtain_form input[name=time]').removeAttr("disabled");

    $('#bus_id_div').attr("hidden", true);
    $('#rs_485_div').attr("hidden", true);
    $('#auto_sel_bus_id').attr("disabled", true);
    $('#curtain_form input[name=address]').attr("disabled", true);
    $('#curtain_form input[name=group]').attr("disabled", true);
    $('#curtain_form input[name=type]').attr("disabled", true);
    $('#curtain_form input[name=is_inverse]').attr("disabled", true);
    $('#auto_sel_vendor').attr("disabled", true);
}

function rs485Fields() {
    $('#rs_485_div').removeAttr("hidden");
    $('#bus_id_div').removeAttr("hidden");
    $('#curtain_form input[name=address]').removeAttr("disabled");
    $('#curtain_form input[name=group]').removeAttr("disabled");
    $('#curtain_form input[name=type]').removeAttr("disabled");
    $('#curtain_form input[name=is_inverse]').removeAttr("disabled");
    $('#auto_sel_vendor').removeAttr("disabled");

    $('#port_id_div').attr("hidden", true);
    $('#phase_time_div').attr("hidden", true);
    $('#device_id_div').attr("hidden", true);
    $('#auto_sel_port_id_open').attr("disabled", true);
    $('#auto_sel_port_id_close').attr("disabled", true);
    $('#auto_sel_device_id').attr("disabled", true);
    $('#curtain_form input[name=time]').attr("disabled", true);
}
