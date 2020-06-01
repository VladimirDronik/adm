function showEditMessageModal(data) {
    clearModal();

    $('#m_id').val(data.id);
    $('#message_modal_title').text('Редактирование оповещения');

    $('#apply_btn').text('Сохранить изменения');

    $('input[name=m_message]').val($('#message-txt-'+data.state).text().trim());
    $('input[name=m_object]').val(data.object);
    $('input[name=m-state]').val(data.state);

    data.priority = $('#data-priority-'+data.state).val();

    if (data.priority === '1') {
        $("input[name=priority][value=hight]").prop("checked",true);
        $("input[name=priority][value=low]").prop("checked", false);
        $("#hight_button").addClass("active");
        $("#low_button").removeClass("active");
    } else {
        $("input[name=priority][value=low]").prop("checked", false);
        $("input[name=priority][value=hight]").prop("checked",false);
        $("#hight_button").removeClass("active");
        $("#low_button").addClass("active");
    }

    $('#init_message_btn').click();

}

function editMessage(data, state) {
    $('#message-txt-'+state).text(data);
}

function validateMessage(data) {
    if (data.message == '') {
        return 'Не указано сообщение';
    }

    return '';
}

function getMessageModalData() {
    let data = {};

    data.object_id = object_id;

    if($("input[name=priority]:checked").val() == 'hight')
        data.priority = 1;
    else data.priority = 2;

    data.message = $('input[name=m_message]').val().trim();
    data.state = $('input[name=m-state]').val().trim();

    return data;
}


function clickApplyMessageBtn() {

    let data = getMessageModalData();
    let message = validateMessage(data);

    if (message !== '') {
        showModalError(message);
        return false;
    }

    $('#data-priority-'+data.state).val(data.priority);

    $.ajax({
        url: store_message_url,
        data: {'_token': _token, 'data': data},
        success: function (resp) {
            if (resp.result) {
                editMessage(resp.data, data.state);
            }
            cancel_message_btn.click();
        }
    });
}

/**
 * Функция настройки отправки сообщения пользователю по акому-либо событию
 */

function clickEditMessageBtn() {

    let data = {};

    //data.priority = $(this).attr('data-priority'); //Приоритет оповещения, 1 - важное, 2 - обычные, 3 -все сообщения
    data.state = $(this).attr('data-state'); // Режим объекта, на который реагировать (включение, выключение и т.д.)
    data.mess = $(this).attr('data-message'); // Сообщение, которое отправлять пользователю

    showEditMessageModal(data);

}
