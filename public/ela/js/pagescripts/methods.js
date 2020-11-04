function loadSubData(mode, object_id) {
    let device = {};

    if (mode == 'port') {
        device = $('#easy_device').text().trim();
    }

    if (device == 'отсутствует') {
        alert('Сначала необходимо выбрать контроллер');
        mode = 'device';
    }

    let data = {};
    data['mode'] = mode;
    data['device'] = device;
    data['object_id'] = object_id;

    $.ajax({
        url: sub_data_url,
        data: data,
        success: function (data) {
            $('#method_data').html(data.html);
            $('#title_action').html(data.title_action);
        }
    });
}

function showModalError(message) {
    $('#methods_error_text').text(message);
    $('#methods_error_div').show();
}

function clearModal() {
    $('#easy_device').text('отсутствует');
    $('#easy_port').text('отсутствует');
    $('#easy_action').text('отсутствует');
    $("input[name=actions][value=none]").prop("checked",true);
    $("#none_button").removeClass("active");
    $("#script_button").removeClass("active");
    $("#easy_button").removeClass("active");
    $('#easy_div').hide();
    $('#script_div').hide();
    $('#methods_error_div').hide();
}

function showAddModal() {
    $('#m_id').val('');
    clearModal();
    $("#none_button").addClass("active");
    $('input[name=m_name]').val('');
    $('input[name=m_comment]').val('');
    $('#method_modal_title').text('Добавление метода');
    $('#apply_btn').text('Добавить метод');
    $('#init_method_btn').click();
}

function showEditModal(data) {
    clearModal();


    $.ajax({
        url: url_device,
        data: {'_token': _token, 'id_device': data.device_id},
        success: function (datares) {

            if(datares.type == 'Hite-pro')
                $('#portordevice').text('Устройство: ');
            else
                $('#portordevice').text('Порт: ');

        }
    });

    $('#m_id').val(data.id);
    $('#method_modal_title').text('Редактирование метода');
    $('#apply_btn').text('Сохранить изменения');

    $('input[name=m_name]').val($('#name'+data.id).text().trim());
    $('input[name=m_comment]').val($('#comment'+data.id).text().trim());
    if (data.type === 'script') {
        $('select[name=m_script]').val(data.script_id);
        $("input[name=actions][value=script]").prop("checked",true);
        $("#script_button").addClass("active");
        $('#script_div').show();
    } else if (data.type === 'easy') {
        $("input[name=actions][value=easy]").prop("checked",true);
        $("#easy_button").addClass("active");
        $('#easy_div').show();
        $('#easy_device').text(data.device_id);
        $('#easy_port').text(data.port);
        $('#easy_action').text(data.action);
    } else if (data.type === 'none') {
        $("input[name=actions][value=none]").prop("checked",true);
        $("#none_button").addClass("active");
    }

    $('#init_method_btn').click();
}



function addMethod(data) {
    const id_html = !is_super_admin ? '' :
        `<label class="col-md-1" id="methodid${data.id}}">${data.id}</label>`;
    const col_id_html = is_super_admin ? 1 : 2;
    const html = `<div class="form-group row" id="div${data.id}">
                     ${id_html}
                     <label class="col-md-3" id="name${data.id}">${data.name}</label>
                     <div class="col-md-3" id="easy${data.id}">${data.easy}</div>
                     <div class="col-md-2" id="script${data.id}">${data.script_name}</div>
                     <div class="col-md-2" id="comment${data.id}">${data.comment}</div>
                     <div class="col-md-${col_id_html} text-right">
                         <button type="button" data-id="${data.id}"
                                data-type="${data.type}"
                                data-script-id="${data.script_id}"
                                data-device="${data.device_id}"
                                data-port="${data.port}"
                                data-action="${data.action}"
                                class="btn btn-info btn-sm btn-rounded edit_btn">
                                            <i class="fa fa-cog fa-lg"></i></button>
                         <button type="button" data-id="${data.id}" data-name="${data.name}" class="btn btn-danger btn-rounded btn-sm del_btn">
                                            <i class="fa fa-trash fa-lg"></i></button>
                     </div>
                </div>`;

    $('#methods_div').append(html);
}

function editMethod(data) {
    $('#name'+data.id).text(data.name);
    $('#script'+data.id).text(data.script_name);
    $('#comment'+data.id).text(data.comment);
    $('#easy'+data.id).text(data.easy);
}



function validateMethod(data) {
    if (data.name == '') {
        return 'Не указано название';
    }

    if (data.type === 'script' && data.script_id == "") {
        return 'Не указан скрипт';
    }

    if (data.type === 'easy') {
        if (data.device_id === 'отсутствует') {
            return 'Не указан контроллер';
        }
        if (data.port === 'отсутствует') {
            return 'Не указан порт';
        }
        if (data.action === 'отсутствует') {
            return 'Не указано действие';
        }
    }

    return '';
}




function getModalData() {
    let data = {};

    data.object_id = object_id;
    data.id = $('input[name=m_id]').val();
    data.name = $('input[name=m_name]').val().trim();
    data.comment = $('input[name=m_comment]').val().trim();

    if (data.comment == '') {
        data.comment = data.name;
    }

    data.type = $("input[name=actions]:checked").val();

    if (data.type === 'script') {
        data.script_id = $('select[name=m_script]').val();
    } else if (data.type === 'easy') {
        data.device_id = $('#easy_device').text().trim();
        data.port = $('#easy_port').text().trim();
        data.action = $('#easy_action').text().trim();
    }

    return data;
}




function clickApplyBtn() {

    let data = getModalData();
    let message = validateMethod(data);

    if (message !== '') {
        showModalError(message);
        return false;
    }

    $.ajax({
        url: store_url,
        data: {'_token': _token, 'data': data},
        success: function (resp) {
            if (resp.result) {
                if (data.id) {
                    editMethod(resp.data);
                } else {
                    addMethod(resp.data);
                }
            }
            cancel_btn.click();
        }
    });
}



function clickEditBtn() {
    let data = {};

    data.id = $(this).attr('data-id');
    data.type = $(this).attr('data-type');
    data.script_id = $(this).attr('data-script-id');
    data.device_id = $(this).attr('data-device');
    data.port = $(this).attr('data-port');
    data.action = $(this).attr('data-action');

    showEditModal(data);
}


function changeRadioActions() {
    if (this.value === 'easy') {
        $('#script_div').hide();
        $('#easy_div').show();
    } else if (this.value === 'script') {
        $('#easy_div').hide();
        $('#script_div').show();
    } else {
        $('#easy_div').hide();
        $('#script_div').hide();
    }
    $('#methods_error_div').hide();
}

function clickDelBtn() {
    $('#del_cancel_btn').click();

    //Если указан id, значит удаляем метод
    if (del_id) {
        $.ajax({
            url: del_url,
            data: { '_token': _token, 'id': del_id },
            success: function (data) {
                if (data.result) {
                    $('#methods_div #div'+del_id).remove();
                    $('#events_div #ediv'+del_id).remove();
                } else {
                    showErrorModal('Ошибка при удалении метода');
                }
            }
        });
    }

    //Если указан метод вызова уведомеления, то удаляем это уведомление
    if(del_message) {

        $.ajax({
            url: del_message_url,
            data: { '_token': _token, 'message': del_message, 'id_object': object_id },
            success: function (data) {
                if (data.result) {
                    $('#message-txt-'+del_message).text('Нет оповещения');
                } else {
                    showErrorModal('Ошибка при удалении уведомления');
                }
            }
        });
    }




}