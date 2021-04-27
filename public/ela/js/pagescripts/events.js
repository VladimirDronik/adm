
function clickEditEventBtn() {
    let data = {};

    data.id = $(this).attr('data-id');
    data.id_object = $(this).attr('data-id_object');
    data.event = $(this).attr('data-event');
    data.property = $(this).attr('data-property');
    data.comparison = $(this).attr('data-comparison');
    data.value = $(this).attr('data-value');

    $('#data-holder').val(data.id);
    showEditEventModal(data);
}

function addActionBtn() {

    darkingBackWindow();
    hideAllElements();

    $('#init_action_btn').click();
}




function showEditEventModal(data) {
    clearEventModal();



    $('#m_id').val(data.id);
    $('#method_modal_title').text('Редактирование события');
    $('#apply_btn').text('Сохранить изменения');

    $('input[name=m_name]').val($('#name'+data.id).text().trim());

    if (data.event)
        $('#m_event option[value=' + data.event + ']').prop('selected', true);

    if (data.property)
        $('#m_property option[value=' + data.property +']').prop('selected', true);

    $('#m_comparison option[value="' + data.comparison + '"]').prop('selected', true);
    $('#m_value').val(data.value).text().trim();


    loadActions(data.id);


    $('#init_event_btn').click();

}

/**
* Загрука всех действий для события для отображения в модальном окне создания или редактирования
 */
function loadActions(id) {


    $('#actions_div').html('');

    //Запрос всех доступных действий для выбранного события
    $.ajax({
        url: url_actions,
        data: {'_token': _token, 'id_event': id},
        success: function (datares) {

            datares.actions.forEach(function(item, i, arr) {

                var output_string;
                var link;

                if (item.type == 'script') {
                    output_string = $('#actions_div').html() +
                        '<br><b>&#xf085; Cкрипт: </b><i>"' + item.nameValue + '"</i>';
                    //link = '/scripts';
                }

                if (item.type == 'method') {

                    output_string = $('#actions_div').html() +
                        '<br><b>&#xf0c1; Метод: </b><i>"' + item.nameValue +
                        '"</i><b> объекта </b><i>"' + item.objectName + '"</i>';

                   // link = '/objects/';
                }


                if (item.type == 'notification')
                    output_string = $('#actions_div').html() +
                        '<br><b>&#xf1d8; Уведомление:</b> <i>"' + item.nameValue + '...' + '"</i>';


                if (item.type == 'sound')
                    output_string = $('#actions_div').html() +
                        '<br><b>&#xf0f3; Звук: </b><i>"' + item.nameValue.substr(0, 40) + '...' + '"</i>';

                if (item.type == 'property')
                    output_string = $('#actions_div').html() +
                        '<br><b>&#xf1e8; Свойство: </b><i>"' + item.nameValue + '"</i><b> объекта </b><i>"' + item.objectName + '"</i>';

                if (item.type == 'view')
                    output_string = $('#actions_div').html() +
                        '<br><b>&#xf247; Отображение: </b><i>"' + item.objectName + '"</i><b> установить статус </b><i>"' + item.nameValue + '"</i>';

                if (item.type == 'log')
                    output_string = $('#actions_div').html() +
                        '<br><b>&#xf044; Запись в лог: </b><i>"' + item.nameValue.substr(0, 40) + '...' + '"</i>';



                $('#actions_div').html(function() {

                    return output_string +
                        //' <a href="' + link + '" target="_blank"><i class="fa fa-pencil fa-lg" style="color:#00aff0; cursor: pointer" ></i>' +
                        ' <i class="fa fa-trash-o fa-lg del_action" data-id="' + item.id + '" data-type="' + item.type + '" style="color:#a94442; cursor: pointer" ></i>';

                });
            });
        }
    });
}

function clearEventModal() {
    /*
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
    */
}


function initActionModal() {


    $("#auto_sel_action_script").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_action_object").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_action_method").chosen({width:"100%", no_results_text: "Не найдено"});
    $("#auto_sel_action_view").chosen({width:"100%", no_results_text: "Не найдено"});

    let del_id;

    // edit event
    $('body').on('click', '.editEvent_btn', clickEditEventBtn);

    // add action window
    $('body').on('click', '.addAction_btn', addActionBtn);

    // add action
    $('body').on('click', '.createAction_btn', createAction);

    // del action open confirm window
    $('body').on('click', '.del_action', deleteActionModal);

    //Delete Event
    $('body').on('click', '.delEvent_btn', deleteEventModal);


    //del action AJAX functions on press confirm
    $('#del_modal_btn').click(deleteActionOrEvent);

    $('#applyEvent_btn').click(clickApplyEventBtn);

    closeActionModal('#action_modal');



    $('#type_action').change(function(){

        switch($(this).val()) {

            case 'script':
                hideAllElements();
                $('#type_action_selected').val('script');
                $('#script_action_div').show();
                break;

            case 'method':
                hideAllElements();
                $('#type_action_selected').val('method');
                $('#object_action_div').show();
                $('#method_action_div').show();
                break;

            case 'notification':
                hideAllElements();
                $('#type_action_selected').val('notification');
                $('#notif_action_div').show();
                break;

            case 'sound':
                hideAllElements();
                $('#type_action_selected').val('sound');
                $('#sound_action_div').show();
                break;

            case 'property':
                hideAllElements();
                $('#type_action_selected').val('property');
                $('#object_action_div').show();
                $('#property_action_div').show();
                $('#property_value_div').show()
                break;

            case 'view':
                hideAllElements();
                $('#type_action_selected').val('view');
                $('#view_action_div').show();
                break;

            case 'log':
                hideAllElements();
                $('#type_action_selected').val('log');
                $('#log_action_div').show();
                break;
        }


    });



    $("#auto_sel_action_object").chosen().change(function() {
        let object_id = $(this).val();
        $.ajax({
            url: url_methods,
            data: {'_token': _token, 'object_id': object_id},
            success: function (data) {
                methods = data.methods;
                createActionMethodSelect('#auto_sel_action_method', data.methods, -1);
                $('#auto_sel_action_method').trigger("chosen:updated");

            }
        });


        $.ajax({
            url: url_properties,
            data: {'_token': _token, 'object_id': object_id},
            success: function (data) {
                createActionProperties('#auto_sel_action_property', data.properties, -1);
                $('#auto_sel_action_property').trigger("chosen:updated");
            }
        });

    });

}



function deleteActionModal() {
    del_id = $(this).attr('data-id');
    del_type = 'action';

    darkingBackWindow();
    $('#del_modal_body').html('<span class="text-danger"> Удалить действие: '+$(this).attr('data-type')+' - '+
        $(this).attr('data-id') +' ?</span>');

    $('#del_init_btn').click();

}


function deleteEventModal() {

    del_id = $(this).attr('data-id');
    del_type = 'event';

    $('#del_modal_body').html('<span class="text-danger"> Удалить событие: ' + $(this).attr('data-name') +' ?</span>');
    $('#del_init_btn').click();
}

/**
 * Удаление action
 */
function deleteAction() {

    $.ajax({
        url: url_deleteAction,
        data: {'_token': _token, 'id_action': del_id},
        success: function (data) {
        }
    });

    loadActions(Number($('#data-holder').val()));
}


/**
 * Удаление события
 */
function deleteEvent() {


    $.ajax({
        url: url_deleteEvent,
        data: {'_token': _token, 'id_event': del_id},
        success: function (data) {

            //if()
        }
    });

}


function deleteActionOrEvent() {

    if (del_type == 'action')
        deleteAction();
    else if (del_type == 'event')
        deleteEvent();

    closeActionModal('#del_modal');
}


//Сохранение изменений события
function clickApplyEventBtn() {

  id_event = Number($('#data-holder').val());
  name = $('#event_name').val();
  event = $('#m_event').val();
  property = $('#m_property').val();
  comparison = $('#m_comparison').val();
  value = $('#m_value').val();


if ($('#event_mode').val() == 'new')
    url ='Ajax.event.create';
    else url = url_eventUpdate;

    $.ajax({
        url: url,
        data: {'_token': _token, 'id_event': id_event, 'name': name, 'event': event,
        'property': property, 'comparison': comparison, 'value': value},
        success: function (resp) {

            currentUrl = window.location.href;
            if(currentUrl.split("/").pop() == 4)
            $newUrl = currentUrl;
            else $newUrl = currentUrl + '/4';

            if(resp.result == true) {
                document.location.href = $newUrl; //select tab 4
                cancelEvent_btn.click();
            }
        }
    });

}


//Затемнение заднего окна
function darkingBackWindow() {
    $('#event_modal_content').css('backgroundColor', '#807d7d');
    $('#event_modal_content').css('opacity', '0.1');
}

//Осветление заднего окна
function lightingBackWindow() {
    $('#event_modal_content').css('backgroundColor', '#FFFFFF');
    $('#event_modal_content').css('opacity', '1');
}

//Закрытие модального окна, которое поверх другого окна
function closeActionModal(modal) {
    $(modal).on('hidden.bs.modal', function (e) {
        lightingBackWindow();
    })
}

//сохранение нового действия для события
function createAction() {


    let typeAction = $('#type_action_selected').val();
    let id_event = $('#data-holder').val();
    let action_object = $("#auto_sel_action_object").val();
    let action_script = $("#auto_sel_action_script").val();
    let action_method = $("#auto_sel_action_method").val();
    let action_notif = $("#action_notif").val();
    let action_sound = $("#auto_sel_action_sound").val();
    let action_property = $("#auto_sel_action_property").val();
    let action_value = $("input[name='action_value']").val();
    let action_view = $("#auto_sel_action_view").val();
    let action_view_status = $('input[name="view_status"]:checked').val();
    let action_log = $("#action_log").val();



    $.ajax({
        url: url_createAction,
        data: {'_token': _token, 'id_event': id_event, 'typeAction': typeAction, 'action_object': action_object,
        'action_script': action_script, 'action_method': action_method, 'action_notif': action_notif,
        'action_sound': action_sound, 'action_property': action_property, 'action_value': action_value,
        'action_view': action_view, 'action_view_status': action_view_status, 'action_log': action_log},
        success: function (data) {

            if(data.result == true){
                loadActions(Number($('#data-holder').val()));
                closeActionModal('#action_modal');
            }

        }
    });
}




function hideAllElements() {
    $('#script_action_div').hide();
    $('#object_action_div').hide();
    $('#property_value_div').hide();
    $('#method_action_div').hide();
    $('#notif_action_div').hide();
    $('#sound_action_div').hide();
    $('#property_action_div').hide();
    $('#view_action_div').hide();
    $('#log_action_div').hide();

}



function createActionMethodSelect(target, options, selected) {
    let sel = $(target);
    sel.html('');
    let s = '<option value="">Не выбрано</option>';
    for (let i = 0; i < options.length; i++) {
        if (selected == options[i].id)
            s += '<option selected value="' + options[i].id + '">' + options[i].name + '</option>';
        else
            s += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
    }
    sel.append(s);
}


function createActionProperties(target, options, selected) {

    let sel = $(target);
    sel.html('');
    let s = '<option value="">Не выбрано</option>';
    for (let i = 0; i < options.length; i++) {
        if(options[i].name[2] == true) //Если разрешена запись для свойства
            if (selected == options[i].id)
                s += '<option selected value="' + options[i].id + '">' + options[i].name[0] + '</option>';
            else
                s += '<option value="' + options[i].id + '">' + options[i].name[0] + '</option>';
    }
    sel.append(s);
}
