/**
 * Скрипты для шаблона device.blade.php
 */

//Вызов модального окна с объектами
$('button[type=button][name=object]').click(function () {

    let object_val = this.value;
    let port_id = this.id;
    let object_arr = object_val.split(',');

    let data = {};
    data['object'] = object_val;

    ajax_html(data, objects_url, '#objectframe');

    if (object_arr[0] != 'empty') {
        $('#selected_object').html('Выбран объект: '+ object_arr[1] +
            '   <button type="button" class="btn btn-danger m-b-2 btn-xs" data-dismiss="modal" ' +
            'id = "reset_object"  value="'+ port_id + '" onclick="reset_object(\''+port_id+'\',\''+object_arr[2]+'\');">Убрать</button>');
    } else {
        $('#selected_object').html('Объект не выбран');
    }
});

function reset_object(id, port) {
    //Внесение изменений в БД
    select_object(null, null);

    $('#'+id).html('Отсутствует');
    $('#'+id).attr({"class": "btn btn-default  m-b-10 btn-sm"});
    $( '#'+port).val('empty,empty,' + id);
}

// Модальное окно с действиями - выбор действия
$('input[type=radio][name=actions]').change(function(){
    select_method(this.value,$('#id_port').val(),$('#value').val());
});

function click_port_method(mode, port_id, value) {
    $('#cur_method').val(mode);
    select_method(mode, port_id, value)
}

function select_method(mode, port_id, value) {

    $('#easy_button').attr({"class": "btn btn-success"});
    $('#script_button').attr({"class": "btn btn-success"});
    $('#method_button').attr({"class": "btn btn-success"});
    $('#none_button').attr({"class": "btn btn-success"});

    $('#'+mode+'_button').attr({"class": "btn btn-success active"});

    $('#id_port').val(port_id);
    $('#value').val(value);

    let data = {};
    data['methodmode'] = mode;
    data['port_id'] = port_id;
    data['value'] = value;
    data['cur_method'] = $('#cur_method').val();

    ajax_html(data, '/getmethod', '#mode');
}

function resetObjectButton(port_id) {
    $('#portobj_'+port_id).html('Отсутствует').attr({"class": "btn btn-default  m-b-10 btn-sm"})
        .val('empty,empty,portobj_' + port_id);
}

function setMethodButton(port_id, color, html) {
    $('#method_btn_' + port_id).attr({"class": "btn btn-"+color+" m-b-10 btn-sm"}).html(html);
}

//Сохранение выбранного метода для порта при закрытии главного модального окна
function storeMethod() {

    let action = $('#action_text').val();
    let data = {};
    let port_id = $('#port_id').val();

    data['methodmode'] = action;
    data['id_port'] = $('#id_port').val();

    if (action === 'easy') {

        let devicearr = ($('#dev_select_button').html()).split(': ');
        let portarr = ($('#port_btn').html()).split(': ');
        let actarr = ($('#action_btn').html()).split(': ');
        data['device'] = devicearr[1];
        data['port'] = portarr[1];
        data['act'] = actarr[1];

        setMethodButton(port_id, 'success',
            'Простое: ' + data['device'] + ';' + data['port'] + ':' + data['act']);

        resetObjectButton(port_id);

    } else if (action === 'method') {

        data['id_object'] = $('#id_object').val();
        data['method_id'] = $('#method_id').val();

        setMethodButton(port_id, 'warning', '<b>Метод: '+$('#method_name').val()+'</b>');

    } else if (action === 'script') {

        let script = ($('#script_btn').html()).split(': ');
        data['script_name'] = script[1];
        data['id_script'] = $('#id_script').val();

        setMethodButton(port_id, 'info', '<b>'+script[1]+'</b>');

        resetObjectButton(port_id);

    } else if (action === 'none') {

        setMethodButton(port_id, 'default', 'Отсутствует');

        resetObjectButton(port_id);
    }

    ajax_html(data, '/savemethod', '');
}

function getPortComment(id_port) {
    var name_port = $("#name_port_"+id_port).text().trim();

    if (name_port == '') {
        name_port = 'Отсутствует';
    }

    $("#name_modal_data").val(name_port);
    sessionStorage.setItem('id_port', id_port);
}

function updatePortComment() {
    var data = {};

    data['port_id'] = sessionStorage.getItem('id_port');
    data['comment'] = $("#name_modal_data").val().trim();
    data['device_id'] = device_id;

    ajax_html(data, port_comment_url, '#name_port_'+data['port_id']);
}

//Убрать в поле название порта
function setDefaultComment() {
    $("#name_modal_data").val('Отсутствует');
}

// Модальное окно с действиями - выбор действия
$('input[type=radio][name=typedev]').change(function(){
    sessionStorage.setItem('typedev', this.value);
});
