/**
 * Скрипты для шаблона device.blade.php
 */

//Вызов модального окна с объектами
$('button[type=button][name=object]').click(function () {

    var object_val = this.value;
    var port_id = this.id;
    var object_arr = object_val.split(',');

    var dataarr = {};
    dataarr['object'] = object_val;

    ajax_html(dataarr, '/getobject', '#objectframe');

    if (object_arr[0]!='empty') {
        $('#selected_object').html('Выбран объект: '+ object_arr[1] +
            '   <button type="button" class="btn btn-danger  m-b-10 btn-xs" data-dismiss="modal" ' +
            'id = "reset_object"  value="'+ port_id + '" onclick="reset_object(\''+port_id+'\',\''+object_arr[2]+'\');">убрать</button>');
    } else {
        $('#selected_object').html('Объект не выбран');
    }
});

function reset_object(id,port) {
    //Внесение изменений в БД
    select_object(null, null);

    $('#'+id).html('Отсутсвует');
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

//Получение название порта
function get_name_port(id_port) {
    var name_port = $("#name_port_"+id_port).html();

    if (name_port == '') {
        name_port = 'Без названия';
    }

    $("#name_modal_data").val(name_port);
    sessionStorage.setItem('id_port', id_port);
}

//Сохраниение названия порта
function save_name_port() {
    var dataarr = {};

    dataarr['id_port'] = sessionStorage.getItem('id_port');
    dataarr['nameport'] = $("#name_modal_data").val();

    ajax_html(dataarr, '/savenameport', '#name_port_'+dataarr['id_port']);
}

//Убрать в поле название порта
function no_name() {
    $("#name_modal_data").val('Без названия');
}

//Сохранение настроек устройства
function save_device_settings() {
    var description = $("#descr_device").val().trim();
    var ip_device = $("#ip_device").val().trim();

    if (description === '' || ip_device === '') {
        return false;
    }

    var data = {};

    data['id_device'] = $("#id_device").val();
    data['description'] = description;
    data['ip_device'] = ip_device;

    ajax_html(data, '/savedevicesettings', '');
}

// Модальное окно с действиями - выбор действия
$('input[type=radio][name=typedev]').change(function(){
    sessionStorage.setItem('typedev', this.value);
});
