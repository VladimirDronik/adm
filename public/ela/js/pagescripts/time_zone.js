function isEmptyAutoSelect(name) {
    return $('#timezone_form #auto_sel_'+name).val().trim() == '';
}

function validateTimeZone() {
    if (isEmptyAutoSelect('value')) {
        return 'Не выбран часовой пояс';
    }

    return '';
}

function initTimeZoneForm() {
    $('#timezone_form button[type=submit]').click(function(){

        let message = validateTimeZone();
        if (message !== '') {
            $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
            $('#info_init_btn').click();
            return false;
        }
    });
}