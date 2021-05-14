
function clickEditEventBtn() {
    let data = {};

    data.id = $(this).attr('data-id');
    data.id_object = $(this).attr('data-id_object');
    data.event = $(this).attr('data-event');
    data.property = $(this).attr('data-property');
    data.comparison = $(this).attr('data-comparison');
    data.value = $(this).attr('data-value');

    $('#data-holder').val(data.id);
    $('#event_mode').val('edit');
    showEditEventModal(data);
}

function addActionBtn() {

    darkingBackWindow();
    hideAllElements();

    $('#type_action option:first').prop('selected', true);
    $('#type_action_selected').val('');
    $('#init_action_btn').click();
}


/**
 * Показ окна редактирования метода
 */

function showEditEventModal(data) {
    clearEventModal();

    $('#event_id').val(data.id);
    $('#method_modal_title').text('Редактирование события');
    $('#applyEvent_btn').text('Сохранить изменения');

    $('input[name=event_name]').val($('#name'+data.id).text().trim());

    if (data.event)
        $('#m_event option[value=' + data.event + ']').prop('selected', true);

    if (data.property)
        $('#m_property option[value=' + data.property +']').prop('selected', true);

    $('#m_comparison option[value="' + data.comparison + '"]').prop('selected', true);
    $('#event_value').val(data.value).text().trim();


    loadActions(data.id);


    $('#init_event_btn').click();

}

/**
* Загрука всех действий для события для отображения в модальном окне создания или редактирования
 */
function loadActions(idEvent, outputElement, actions) {


    $('#actions_div').html('');
    $(outputElement).html('');


            //Запрос всех доступных действий для выбранного события
            $.ajax({
                url: url_actions,
                data: {'_token': _token, 'id_event': idEvent, 'actions': actions},
                success: function (datares) {

                     datares.actions.forEach(function(action, i, arr) {
                             fillActionsOnEventModal(action, outputElement);
                         });
                     }
            });


}



/**
* Заполнение блока actions у модального окна событий
 */
function fillActionsOnEventModal(item, outputElement) {

        var output_string;
        var output_image;
        var link;


        if (item.type == 'script') {
            output_string = $('#actions_div').html() +
                '<br><b>&#xf085; Cкрипт: </b><i>"' + item.nameValue + '"</i>';

            output_image = $(outputElement).html() + '<label title="Cкрипт: '+ item.nameValue + '">&#xf085</label>&nbsp;';
            //link = '/scripts';
        }

        if (item.type == 'method') {

            output_string = $('#actions_div').html() +
                '<br><b>&#xf0c1; Метод: </b><i>"' + item.nameValue +
                '"</i><b> объекта </b><i>"' + item.objectName + '"</i>';

            output_image = $(outputElement).html() + '<label title="Метод: '+ item.nameValue + ' объекта ' +
                item.objectName + '">&#xf0c1</label>&nbsp;';


            // link = '/objects/';
        }


        if (item.type == 'notification') {
            output_string = $('#actions_div').html() +
                '<br><b>&#xf1d8; Уведомление:</b> <i>"' + item.nameValue + '...' + '"</i>';

            output_image = $(outputElement).html() + '<label title="Уведомление: '+ item.nameValue + '">&#xf1d8</label>&nbsp;';
        }

        if (item.type == 'sound') {

            output_string = $('#actions_div').html() +
                '<br><b>&#xf0f3; Звук: </b><i>"' + item.nameValue.substr(0, 40) + '...' + '"</i>';

            output_image = $(outputElement).html() + '<label title="Звук: '+ item.nameValue + '">&#xf0f3</label>&nbsp;';
        }


        if (item.type == 'property') {

            output_string = $('#actions_div').html() +
                '<br><b>&#xf1e8; Свойство: </b><i>"' + item.nameValue + '"</i><b> объекта </b><i>"' + item.objectName + '"</i>';

            output_image = $(outputElement).html() + '<label title="Свойство: '+ item.nameValue +
                ' объекта ' + item.objectName + '">&#xf1e8</label>&nbsp;';

        }


        if (item.type == 'view') {

            output_string = $('#actions_div').html() +
                '<br><b>&#xf247; Отображение: </b><i>"' + item.objectName + '"</i><b> установить статус </b><i>"' + item.nameValue + '"</i>';

            output_image = $(outputElement).html() + '<label title="Отображение: '+ item.objectName +
                ' установить статус ' + item.nameValue + '">&#xf247</label>&nbsp;';
        }

        if (item.type == 'log') {

            output_string = $('#actions_div').html() +
                '<br><b>&#xf044; Запись в лог: </b><i>"' + item.nameValue.substr(0, 40) + '...' + '"</i>';

            output_image = $(outputElement).html() + '<label title="Запись в лог: '+ item.nameValue + '">&#xf044</label>&nbsp;';

        }


        //Если указано, то заполняем actions в указанный элемент, иначе заполняем в модальное окошко события
        if(outputElement != undefined)

            $(outputElement).html(function() {
                return output_image;
            });

        else
            $('#actions_div').html(function() {

                return output_string +
                    //' <a href="' + link + '" target="_blank"><i class="fa fa-pencil fa-lg" style="color:#00aff0; cursor: pointer" ></i>' +
                    ' <i class="fa fa-trash-o fa-lg del_action" data-id="' + item.id + '" data-type="' + item.type + '" style="color:#a94442; cursor: pointer" ></i>';

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


    //Загрузка всех actions для всех events при загрузке страницы
    events = $('#allevents').val();
    if(events) {
        eventsArray = events.split(',');
        eventsArray.forEach(function(item, i, arr) {
            if(item)
                loadActions(item, '#action' + item);
        });
    }



    //Добавление нового события
    $('#addEvent_btn').click(showEventAddModal);

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

/**
 * Показать окно добавления события
 */
function showEventAddModal() {
    $('#event_id').val('');
    $('#event_name').val('');
    $('#event_mode').val('new');
    $('#m_event option:first').prop('selected', true);
    $('#m_property option:first').prop('selected', true);
    $('#m_comparison option:first').prop('selected', true);
    $('#event_value').val('');
    $('#event_modal_title').html('Добавление события');
    $('#actions_div').html('');
    $('#data-holder').val('');

    //очищаем массив с временными actions
    tempActions = [];

    $('#init_event_btn').click();
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
            loadActions(Number($('#data-holder').val()));
        }
    });


}


/**
 * Удаление события
 */
function deleteEvent() {


    $.ajax({
        url: url_deleteEvent,
        data: {'_token': _token, 'id_event': del_id},
        success: function (data) {
            $('#events_div #div'+del_id).remove();
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


/**
 * Сохранение изменений события или добавление нового события
 */

function clickApplyEventBtn() {

  id_event = Number($('#data-holder').val());
  name = $('#event_name').val();
  event = $('#m_event').val();
  property = $('#m_property').val();
  comparison = $('#m_comparison').val();
  value = $('#event_value').val();
  id_object =  $('#event_idobject').val();


  if(name) {

      //Если создание события или редактирование события
      if ($('#event_mode').val() == 'new')
          url = url_eventCreate;
      else url = url_eventUpdate;

      $.ajax({
          url: url,
          data: {'_token': _token, 'id_event': id_event, 'name': name, 'event': event,
              'property': property, 'comparison': comparison, 'value': value, 'id_object': id_object, 'tempActions': tempActions},
          success: function (resp) {

              if(resp.result == true) {

                  $('#name'+id_event).text(name);
                  $('#condition'+id_event).text(property+comparison+value);


                  if ($('#event_mode').val() == 'new')
                  {
                      addEvent(resp.data);
                      id_event = resp.data.id;
                  }


                  loadActions(id_event, '#action'+id_event);

                  cancelEvent_btn.click();
              }
          }
      });

  } else
      $('#alert_div').show();

}

/**
 * Динамическое добавление события на страницу событий
 */
function addEvent(data) {

    const html = `<div class="form-group row" id="div${data.id}">
                     <label class="col-md-1" id="eventid${data.id}">${data.id}</label>
                     <label class="col-md-3" id="name${data.id}">${data.name}</label>
                     <label class="col-md-3" id="condition${data.id}">${data.property} ${data.comparison} ${data.value}</label>
                     <div class="col-md-2"  style="font-family: 'FontAwesome', Helvetica;" id="action${data.id}"></div>
                     <div class="col-md-1 text-right">
                         <button type="button" data-id="${data.id}"
                         data-id_object="${data.id_object}"
                         data-event="${data.event}"
                         data-property="${data.property}"
                         data-comparison="${data.comparison}"
                         data-value="${data.value}"
                         class="btn btn-info btn-sm btn-rounded editEvent_btn">
                         <i class="fa fa-cog fa-lg"></i>
                         </button>
                         <button type="button" data-id="${data.id}" data-name="${data.name}" class="btn btn-danger btn-rounded btn-sm delEvent_btn">
                         <i class="fa fa-trash fa-lg"></i>
                         </button>
                     </div>
                </div>`;

    $('#events_div').append(html);
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

/**
*сохранение нового действия для события
 */
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

    let dataAction = {'typeAction': typeAction, 'action_object': action_object,
        'action_script': action_script, 'action_method': action_method, 'action_notif': action_notif,
        'action_sound': action_sound, 'action_property': action_property, 'action_value': action_value,
        'action_view': action_view, 'action_view_status': action_view_status, 'action_log': action_log};

    if(typeAction)
        //Если нет id_event, значит это содание нового события
        if(id_event == '') {
            //Заносим данные об action во временный массив и храним до тех пор, пока не появится id_event
            tempActions.push(dataAction);
            //Добавляем новый action в модальное окно события
            loadActions(null,null,tempActions);
        }else //Создание action при редактировании event с известным id_event
            createActionAjax(id_event, dataAction);
}




/**
 * AJAX запрос на создание нового action
 * @param id_event
 * @param dataAction
 */
function createActionAjax(id_event, dataAction) {

    $.ajax({
        url: url_createAction,
        data: {'_token': _token, 'id_event': id_event, 'data': dataAction},
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
