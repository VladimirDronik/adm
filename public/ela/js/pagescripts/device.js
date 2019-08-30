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

function reset_object(id,port) {
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

    var dataarr = {};
    dataarr['methodmode'] = mode;
    dataarr['port_id'] = port_id;
    dataarr['value'] = value;
    dataarr['cur_method'] = $('#cur_method').val();

    ajax_html(dataarr, '/getmethod', '#mode');
}

//Сохранение выбранного метода для порта
function save_method() {

    var action = $('#action_text').val();
    var dataarr = {};

    dataarr['methodmode'] = action;
    dataarr['id_port'] = $('#id_port').val();

    if (action === 'easy') {

        var devicearr = ($('#dev_select_button').html()).split(': ');
        var portarr = ($('#port_btn').html()).split(': ');
        var actarr = ($('#action_btn').html()).split(': ');
        dataarr['device'] = devicearr[1];
        dataarr['port'] = portarr[1];
        dataarr['act'] = actarr[1];

        $('#method_btn_' + $('#port_id').val()).attr({"class": "btn btn-success  m-b-10 btn-sm"});
        $('#method_btn_' + $('#port_id').val()).html('Простое: ' + dataarr['device'] + ';' + dataarr['port'] + ':' + dataarr['act']);

    } else if (action === 'method') {

        dataarr['id_object'] = $('#id_object').val();

        $('#method_btn_' + $('#port_id').val()).attr({"class": "btn btn-warning  m-b-10 btn-sm"});
        $('#method_btn_' + $('#port_id').val()).html('<b><< Выполнять действие объекта</b>');

    } else if (action === 'script') {

        var script = ($('#script_btn').html()).split(': ');
        dataarr['script_name'] = script[1];
        dataarr['id_script'] = $('#id_script').val();

        $('#method_btn_' + $('#port_id').val()).attr({"class": "btn btn-info  m-b-10 btn-sm"});
        $('#method_btn_' + $('#port_id').val()).html('<b>'+script[1]+'</b>');

    } else if (action === 'none') {
        $('#method_btn_' + $('#port_id').val()).attr({"class": "btn btn-default  m-b-10 btn-sm"});
        $('#method_btn_' + $('#port_id').val()).html('Отсутствует');
    }

    ajax_html(dataarr, '/savemethod', '');
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
