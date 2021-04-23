
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



function showEditEventModal(data) {
    clearEventModal();



    $('#m_id').val(data.id);
    $('#method_modal_title').text('Редактирование события');
    $('#apply_btn').text('Сохранить изменения');

    $('input[name=m_name]').val($('#name'+data.id).text().trim());

    $('#m_event option[value=' + data.event + ']').prop('selected', true);
    $('#m_property option[value=' + data.property +']').prop('selected', true);
    $('#m_comparison option[value="' + data.comparison + '"]').prop('selected', true);
    $('#m_value').val(data.value).text().trim();


    loadActions(data.id);



    //$('#actions_div').innerHTML();

    //$('input[name=m_comment]').val($('#comment'+data.id).text().trim());
    /*
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
*/
    $('#init_event_btn').click();

}


function loadActions(id) {

    //Запрос всех доступных действий для выбранного события
    $.ajax({
        url: url_actions,
        data: {'_token': _token, 'id_event': id},
        success: function (datares) {


            datares.actions.forEach(function(item, i, arr) {

                var output_string;
                var link;

                if (item.type == 'script') {
                    output_string = '<b>&#xf085; Cкрипт: </b><i>"' + item.nameValue + '"</i>';
                    //link = '/scripts';
                }

                if (item.type == 'method') {

                    output_string = $('#actions_div').html() + '<br><b>&#xf0c1; Метод: </b><i>"' + item.nameValue +
                        '"</i><b> объекта </b><i>"' + item.objectName + '"</i>';

                   // link = '/objects/';
                }


                if (item.type == 'notification')
                    output_string = $('#actions_div').html() + '<br><b>&#xf1d8; Уведомление:</b> <i>"' + item.nameValue.substr(0, 40) + '...' + '"</i>';


                if (item.type == 'sound')
                    output_string = $('#actions_div').html() + '<br><b>&#xf0f3; Звук: </b><i>"' + item.nameValue.substr(0, 40) + '...' + '"</i>';

                if (item.type == 'property')
                    output_string = $('#actions_div').html() + '<br><b>&#xf1e8; Свойство: </b><i>"' + item.nameValue + '"</i><b> объекта </b><i>"' + item.objectName + '"</i>';

                if (item.type == 'view')
                    output_string = $('#actions_div').html() + '<br><b>&#xf247; Отображение: </b><i>"' + item.objectName + '"</i><b> установить статус </b><i>"' + item.nameValue + '"</i>';

                if (item.type == 'log')
                    output_string = $('#actions_div').html() + '<br><b>&#xf1d8; Уведомление: </b><i>"' + item.nameValue.substr(0, 40) + '...' + '"</i>';





                $('#actions_div').html(function() {

                    return output_string +
                        //' <a href="' + link + '" target="_blank"><i class="fa fa-pencil fa-lg" style="color:#00aff0; cursor: pointer" ></i>' +
                        ' <i class="fa fa-trash-o fa-lg" style="color:#a94442; cursor: pointer" ></i>';

                });
            });
        }
    });
}

function clearEventModal() {
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