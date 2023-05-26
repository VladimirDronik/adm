@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование контроллера «'.$device->description.'»',
        'links' => [ route('devices.index') => 'Контроллеры'],
        'last_link' => 'Редактирование контроллера'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('devices.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок контроллеров</a>
                        <a href="{{ route('devices.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить контроллер</a>
                        <a href="{{ route('devices.edit',[$device->id]) }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        <button type="button" id="settings_modal_btn" class="btn btn-success m-b-10 m-l-5" data-toggle="modal" data-target="#settings_modal">Настройки</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Порты контроллера</h4></div>
            <div class="card-body">
                @if(count($device->ports))
                    <ul class="nav nav-tabs customtab" role="tablist">

                        <li class="nav-item"> <a class="nav-link @if($tab==1) active @endif"  data-toggle="tab" href="#portstab1" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Порты IN</span></a> </li>
                        <li class="nav-item"> <a class="nav-link @if($tab==2) active @endif"  data-toggle="tab" href="#portstab2" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Порты OUT</span></a> </li>
                        <li class="nav-item"> <a class="nav-link @if($tab==3) active @endif"  data-toggle="tab" href="#portstab3" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Порты DIG</span></a> </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane p-20 @if($tab==1) active @endif" id="portstab1" role="tabpanel">
                            @include('devices.in_ports')
                        </div>
                        <div class="tab-pane p-20 @if($tab==2) active @endif" id="portstab2" role="tabpanel">
                            @include('devices.out_ports')
                        </div>
                        <div class="tab-pane p-20 @if($tab==3) active @endif" id="portstab3" role="tabpanel">
                            @include('devices.dig_ports')
                        </div>
                    </div>
                @else
                    <p>Порты не найдены</p>
                @endif
            </div>
        </div>
    </div>

    @include('components.info_modal')

    <div id="name_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Описание порта</h4>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control input-default"
                           id="name_modal_data" placeholder="">
                    <button type="button" class="btn btn-default" onclick="setDefaultComment();">Убрать</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button"   class="btn btn-primary" data-dismiss="modal"
                            onclick="updatePortComment();">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>

    <div id="objectsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Выбор связанного объекта</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <label id="selected_object"></label><br>
                    </div>
                    <div>
                        <input type="text" name="modal_objects_filter" class="form-control"
                               placeholder="Поиск по названию...">
                    </div>
                    <div id="objectframe"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <!-- <div id="device_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Текущая конфигурация будет загружена в контроллер.</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button"   class="btn btn-primary" data-dismiss="modal" onclick="updateDevice();" >Продолжить</button>
                </div>
            </div>
        </div>
    </div>
    <button type="button" id="device_modal_init_btn" style="display: none;" data-toggle="modal" data-target="#device_modal">&nbsp;</button> -->

    <div id="delete_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Удалить контроллер?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="deleteDevice();" >Удалить</button>
                </div>
            </div>
        </div>
    </div>
    <button type="button" id="delete_modal_init_btn" style="display: none;" data-toggle="modal" data-target="#delete_modal">&nbsp;</button>

    <div id="reloadModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Сообщение</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Конфигурация загружается в устройство... Пожалуйста, дождитесь завершения операции.
                    </div>
                </div>
                <div class="modal-footer">
{{--                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>--}}
                </div>
            </div>
        </div>
    </div>

    <button type="button" style="display: none;" id="reloadDeviceBtn" data-target="#reloadModal" data-toggle="modal" data-backdrop="static" data-keyboard="false">&nbsp;</button>

    {{-- methods modal --}}

    <div id="methodsModal" class="modal">
        <div class="modal-dialog modal-lg" style="max-width: 900px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Выбор объекта и метода</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info" id="modal_selected_method_div">
                                    <label id="modal_selected_method">Объект: <b>НазваниеОбъекта</b> Метод: <b>НазваниеМетода</b></label>
                                    <button type="button" class="btn btn-danger m-b-2 btn-xs" id="modal_delete_method_btn">Убрать</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="modal_objects_filter" class="form-control"
                                       placeholder="Поиск объекта по названию...">
                                <div style="height:350px; overflow:auto;">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Название объекта</th>
                                                <th>Тип</th>
                                            </tr>
                                            </thead>
                                            <tbody id="modal_objects_table_body">
                                                <tr class="js-object-tr" id="object_tr_40" data-name="1-й этаж.датчик_температуры">
                                                    <td>
                                                        <a href="#" class="js-object-td" id="object_40">1-й этаж.Датчик_температуры</a>
                                                    </td>
                                                    <td class="text-right">
                                                        <img title="Кнопка" src="http://adm/ela/images/objects/button.png" width="60" height="40">
                                                    </td>
                                                </tr>
                                                <tr class="js-object-tr alert-info-bg" id="object_tr_40">
                                                    <td>
                                                        <a href="#" class="js-object-td" id="object_40">1-й этаж.Датчик_температуры</a>
                                                    </td>
                                                    <td class="text-center">
                                                        <img title="Кнопка" src="http://adm/ela/images/objects/button.png" width="60" height="40">
                                                    </td>
                                                </tr>
                                                <tr class="js-object-tr" id="object_tr_40">
                                                    <td>
                                                        <a href="#" class="js-object-td" id="object_40">1-й этаж.Датчик_температуры</a>
                                                    </td>
                                                    <td class="text-right">
                                                        <img title="Кнопка" src="http://adm/ela/images/objects/button.png" width="60" height="40">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="button" id="modal_create_object_btn" onclick="redirectToCreateObject()"
                                        class="btn btn-xs btn-outline-info btn-outline m-t-5">
                                    Создать объект
                                </button>
                            </div>
                            <div class="col-md-6">
                                <div id="modal_methods_table">
                                    <input type="text" name="modal_methods_filter" class="form-control"
                                           placeholder="Поиск метода по названию...">
                                    <div style="height:350px; overflow:auto;">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left">Название метода</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="modal_methods_table_body">
                                                    <tr class="modal_method_tr" data-name="1-й этаж.Датчик_температуры">
                                                        <td class="text-left">
                                                            <a href="#" class="js-method-td" id="method_40">1-й этаж.Датчик_температуры</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <button type="button" id="modal_create_method_btn" onclick="redirectToCreateMethod()"
                                            class="btn btn-xs btn-outline-info btn-outline m-t-5" data-object-id="">
                                        Создать метод
                                    </button>
                                </div>
                                <div id="modal_no_methods_div" style="display:none;">
                                    Выберите объект
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="methods_modal_cancel_btn"
                            data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>
    <button type="button" id="methods_modal_init_btn" style="display: none;"
            data-toggle="modal" data-target="#methodsModal">&nbsp;</button>

    @include('components.params_modal')
    @include('devices.modals.settings_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/device.js') }}"></script>
    <script>
        const device_id = '{{ $device->id }}';
        const port_comment_url = '{{ route('ajax.ports.update.comment') }}';
        const objects_url = '{{ route('ajax.objects.view.all') }}';
        const methods_url = '{{ route('ajax.ports.method.all') }}';
        const edit_port_methods_url = '{{ route('ajax.ports.edit.methods') }}'
        const edit_port_method_delete_url = '{{ route('ajax.ports.edit.method.delete') }}';
        const object_methods_url = '{{ route('ajax.ports.object.methods') }}';
        const update_method_url = '{{ route('ajax.ports.update.method') }}';
        const delete_extension_module = '{{ route('ajax.devices.extension_module.delete') }}';
        const autoreload_period = 3000;
        const createObjectUrl = '{{ route('objects.create') }}';
        const createMethodInitUrl = '{{ route('objects.index') }}';
        let port_id = 0;
        let object_id = 0;
        let method_id = 0;
        let type = '';
        let sdaSclOptions = $.parseJSON('<?php echo $sdaSclOptionsJson; ?>');
        let moduleTypeOptions = $.parseJSON('<?php echo $moduleTypeOptionsJson; ?>');


        // in-port methods

        function redirectToCreateObject() {
            $('#methodsModal #methods_modal_cancel_btn').click();
            window.open(createObjectUrl, '_blank');
        }

        function redirectToCreateMethod() {
            if (object_id !== 0) {
                $('#methodsModal #methods_modal_cancel_btn').click();
                window.open(createMethodInitUrl + '/' + object_id + '/edit', '_blank');
            }
        }

        function deleteExtensionModule(extensionModuleId) {
            $.ajax({
                url: delete_extension_module,
                data: {
                    '_token': _token, 'extension_module_id': extensionModuleId,
                },
                success: function (data) {
                    if (data.result) {
                        $("#deleteExtensionModule"+extensionModuleId).parent().parent().remove();
                    }
                }
            });
        }

        $(document).ready(function () {

            function createNewFields() {
                var newFields = $('<div class="moduleFields form-group row">' +
                                    '<label class="control-label text-right col-md-1 label-fix">Тип:</label>' +
                                    '<select class="chosen-select form-control col-sm-2" name="extension_module_type_id"></select>' +
                                    '<label class="control-label text-right col-md-1 label-fix">SDA:</label>' +
                                    '<select class="chosen-select form-control col-sm-1" name="sda_port"></select>' +
                                    '<label class="control-label text-right col-md-1 label-fix">SCL:</label>' +
                                    '<select class="chosen-select form-control col-sm-1" name="scl_port"></select>' +
                                    '<div class="col-sm-3"><button class="deleteModuleBtn btn btn-outline-danger">Удалить модуль</button></div>' +
                                '</div>');
                var selectModuleType = newFields.find('select[name="extension_module_type_id"]');
                var selectSdaPort = newFields.find('select[name="sda_port"]');
                var selectSclPort = newFields.find('select[name="scl_port"]');
                $.each(sdaSclOptions, function(index, option) {
                    selectSdaPort.append('<option value="' + option.value + '">' + option.label + '</option>');
                    selectSclPort.append('<option value="' + option.value + '">' + option.label + '</option>');
                });
                $.each(moduleTypeOptions, function(index, option) {
                    selectModuleType.append('<option value="' + option.value + '">' + option.label + '</option>');
                });
                return newFields;
            }

            $("#addExtensionModuleBtn").click(function() {
                var newFields = createNewFields();
                $("#extensionModulesContainer").append(newFields);
                $(".col-sm-3 .deleteModuleBtn").click(function() {
                    $(this).parent().parent().remove();
                });
            });

            //Вызов модального окна с методами
            $('.js-method-btn').click(function () {

                let data = {};

                data.device_id = device_id;
                data.type = $(this).data('type');
                data.port_id = $(this).data('port-id');
                data.method_id = $(this).data('method-id');

                port_id = data.port_id;
                type = data.type;

                $.ajax({
                    url: edit_port_methods_url,
                    data: {'_token': _token, 'data': data},
                    success: function (data) {
                        if (data.result) {
                            method_id = data.method_id;
                            object_id = data.object_id;
                            updateMethodsModal(data);
                            $('#methods_modal_init_btn').click();
                        } else {
                            showErrorModal('Сервер временно недоступен');
                        }
                    }
                });
            });

            // $('#updateDeviceBtn').click(function() {
            //     $('#device_modal_init_btn').click();
            // });

            $('#deleteDeviceBtn').click(function() {
                $('#delete_modal_init_btn').click();
            });

            // кнопка Убрать

            $('#methodsModal #modal_delete_method_btn').click(function () {
                if (port_id === 0 || type === '') {
                    return false;
                }

                $.ajax({
                    url: edit_port_method_delete_url,
                    data: {'_token': _token, 'device_id': device_id, 'port_id': port_id, 'type': type},
                    success: function (data) {
                        if (data.result) {
                            clearMethodBtn();
                            $('#methodsModal #methods_modal_cancel_btn').click();
                        } else {
                            showErrorModal('Сервер временно недоступен');
                        }
                    }
                });

                return false;
            });

            // выбор объекта в таблице

            $('body').on('click', '.js-object-td', function () {
                if (port_id === 0 || type === '') {
                    return false;
                }
                const objectId = $(this).data('id');
                $.ajax({
                    url: object_methods_url,
                    data: {'_token': _token, 'object_id': objectId},
                    success: function (data) {
                        if (data.result) {
                            object_id = objectId;
                            method_id = 0;
                            selectObjectInTable(objectId);
                            updateMethodsTable(data.methods);
                        } else {
                            showErrorModal('Сервер временно недоступен');
                        }
                    }
                });

                return false;
            });

            // выбор метода в таблице

            function ajaxUpdateMethod(methodId, params) {
                $.ajax({
                    url: update_method_url,
                    data: {
                        '_token': _token, 'method_id': methodId, 'device_id': device_id,
                        'port_id': port_id, 'type': type, 'params': params
                    },
                    success: function (data) {
                        if (data.result) {
                            object_id = data.object_id;
                            method_id = data.method_id;
                            updateMethodBtnText(data);
                            $('#methodsModal #methods_modal_cancel_btn').click();
                        } else {
                            showErrorModal('Сервер временно недоступен');
                        }
                    }
                });
            }

            $('#paramsModal #paramsApplyBtn').click(function () {
                const params = $('#paramsModal #param').val().trim();
                const methodId = $('#paramsModal #paramsMethodId').val();
                if (params === '') {
                    $('#paramsModal #params_error_text').text('Не указано значение');
                    $('#paramsModal #params_error_div').show();
                    return false;
                }

                ajaxUpdateMethod(methodId, params);
            });

            $('body').on('click', '.js-method-td', function () {
                if (port_id === 0 || type === '' || object_id === 0) {
                    return false;
                }
                const methodId = $(this).data('id');
                const params = $(this).data('params');

                if (params === '') {
                    ajaxUpdateMethod(methodId, params);
                } else {
                    $('#paramsModal #paramsMethodId').val(methodId);
                    $('#paramsModal #paramsLabel').text(params + ':');
                    $('#paramsModal #param').val('');
                    $('#paramsModal #params_error_text').text('');
                    $('#paramsModal #params_error_div').hide();
                    $('#params_modal_init_btn').click();
                }

                return false;
            });

            // фильтры

            $('#methodsModal input[name=modal_objects_filter]').on('input', function () {
                const search = $(this).val().trim().toLowerCase();
                $("#methodsModal .js-object-tr").show();
                if (search !== "") {
                    $("#methodsModal .js-object-tr:not([data-name*='" + search + "'])").hide();
                }
            });

            $('#methodsModal input[name=modal_methods_filter]').on('input', function () {
                const search = $(this).val().trim().toLowerCase();
                $("#methodsModal .js-method-tr").show();
                if (search !== "") {
                    $("#methodsModal .js-method-tr:not([data-name*='" + search + "'])").hide();
                }
            });

            $('#objectsModal input[name=modal_objects_filter]').on('input', function () {
                const search = $(this).val().trim().toLowerCase();
                $("#objectsModal .js-object-tr").show();
                if (search !== "") {
                    $("#objectsModal .js-object-tr:not([data-name*='" + search + "'])").hide();
                }
            });
        });

        function resetOutFilter() {
            $('#objectsModal input[name=modal_objects_filter]').val('');
        }

        function updateMethodBtnText(data) {
            let btn = $('#' + type + port_id);

            btn.removeClass('btn-default');
            btn.addClass('btn-warning');

            btn.data('method-id', data.method_id);
            btn.data('object-id', data.object_id);

            let html = 'Объект: ' + data.object_name + '<br>&nbsp;&nbsp;Метод: ' + data.method_name;

            if (data.params) {
                html += ' ('+data.params+')';
            }
            btn.html(html);
        }

        function selectObjectInTable(object_id) {
            $('.js-object-tr').removeClass('alert-info-bg');
            $('#object_tr_'+object_id).addClass('alert-info-bg');
        }

        function updateMethodsModalHeader(data) {
            if (data.method_id !== 0) {
                $('#methodsModal #modal_selected_method').html('Объект: <b>'+data['object_name']+'</b> Метод: <b>'+data['method_name']+'</b>');
                $('#methodsModal #modal_selected_method_div').show();
            } else {
                $('#methodsModal #modal_selected_method_div').hide();
            }
        }

        function updateMethodsModalTables(data) {

            // фильтры

            $('#methodsModal input[name=modal_objects_filter]').val('');
            $('#methodsModal input[name=modal_methods_filter]').val('');

            // таблица объектов

            let html = '';
            let selected_class = '';
            for (let i = 0; i < data.objects.length; i++) {
                selected_class = data.objects[i].id === data.object_id ? 'alert-info-bg' : '';

                html += `<tr class="js-object-tr ${selected_class}" data-name="${data.objects[i].name.toLowerCase()}"
                                id="object_tr_${data.objects[i].id}">
                            <td>
                                <a href="#" class="js-object-td" data-id="${data.objects[i].id}"
                                    id="object_${data.objects[i].id}">${data.objects[i].name}</a>
                            </td>
                            <td class="text-center">
                                ${data.objects[i].type_img}
                            </td>
                        </tr>`;
            }

            $('#methodsModal #modal_objects_table_body').html(html);

            // таблица методов

            if (data.object_id !== 0) {
                setMethodsTableHtml(data.methods);
            } else {
                $('#methodsModal #modal_methods_table').hide();
                $('#methodsModal #modal_no_methods_div').show();
            }

            // кнопка Создать метод
            if (data.object_id !== 0) {
                $('#methodsModal #modal_create_method_btn').data('object-id', data.object_id).show();
            } else {
                $('#methodsModal #modal_create_method_btn').hide();
            }
        }

        function setMethodsTableHtml(methods) {
            html = '';
            $('#methodsModal #modal_no_methods_div').hide();
            $('#methodsModal #modal_methods_table').show();
            for (let i = 0; i < methods.length; i++) {
                selected_class = methods[i].id === method_id ? 'alert-info-bg' : '';
                html += `<tr class="js-method-tr ${selected_class}"
                            data-name="${methods[i].name.toLowerCase()}"
                            id="method_tr_${methods[i].id}">
                            <td class="text-left">
                                <a href="#" class="js-method-td" data-id="${methods[i].id}"
                                data-params="${methods[i].params ? methods[i].params : ''}"
                                id="method_${methods[i].id}">${methods[i].name}</a>
                                ${methods[i].params ? '<i class="fa fa-asterisk f-s-10" title="Метод с параметром"></i>' : ''}
                            </td>
                        </tr>`;
            }

            $('#methodsModal #modal_methods_table_body').html(html);
        }

        function updateMethodsTable(methods) {
            // фильтр
            $('#methodsModal input[name=modal_methods_filter]').val('');

            // таблица методов
            setMethodsTableHtml(methods);

            // кнопка Создать метод
            $('#methodsModal #modal_create_method_btn').data('object-id', object_id).show();
        }

        function updateMethodsModal(data) {
            updateMethodsModalHeader(data);
            updateMethodsModalTables(data);
        }

        function clearMethodBtn() {
            let btn = $('#' + type + port_id);

            btn.removeClass('btn-warning');
            btn.addClass('btn-default');

            btn.data('method-id', '');
            btn.data('object-id', '');

            btn.html('<i class="f-s-14">Метод не указан</i>');

            method_id = 0;
            object_id = 0;
            type = '';
            port_id = 0;
        }

        //

        function deleteDevice() {
            $.ajax({
                url: '{{ route('ajax.devices.delete') }}',
                data: {'_token': _token, 'id': device_id},
                success: function (data) {
                    if (data.result) {
                        window.location = '{{ route('devices.index') }}';
                    } else {
                        showErrorModal('Ошибка при удалении контроллера');
                    }
                }
            });
        }

        function isValidIP(ip) {
            if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ip)) {
                return true;
            }

            return false;
        }

        function validateDevice(description, ip_address, password) {
            if (description === '') {
                return 'Не указано название контроллера';
            }
            if (ip_address === '') {
                return 'Не указан ip адрес контроллера';
            }
            if (password === '') {
                return 'Не указан пароль контроллера';
            }
            if (ip_address.length > 15) {
                return 'Длина ip адреса не может быть больше 15';
            }
            if (!isValidIP(ip_address)) {
                return 'Недопустимый ip адрес';
            }
            return '';
        }

        function updateDevice() {

            let description = $("input[name=description]").val().trim();
            let ip_address = $("input[name=ip_address]").val().trim();
            let password = $("input[name=password]").val().trim();

            $message = validateDevice(description, ip_address, password);
            if ($message !== '') {
                showErrorModal($message);
                return false;
            } else {
                if ($("#extensionModulesContainer").children(".moduleFields").length > 0) {
                    var data = [];
                    $(".moduleFields").each(function() {
                        var extension_module_type_id = $(this).find('select[name="extension_module_type_id"]').val();
                        var sda_port = $(this).find('select[name="sda_port"]').val();
                        var scl_port = $(this).find('select[name="scl_port"]').val();
                        var item = {
                            extension_module_type_id: extension_module_type_id,
                            sda_port: sda_port,
                            scl_port: scl_port
                        };
                        data.push(item);
                    });
                }
                // $('#reloadDeviceBtn').click();
                $.ajax({
                    url: '{{ route('ajax.devices.update') }}',
                    data: {'_token': _token, 'id': device_id, 'description': description,
                        'ip_address': ip_address, 'password': password, extension_modules: data},
                    success: function (data) {
                        if (!data.result) {
                            showErrorModal(data.message);
                        } else {
                            location.reload();
                        // $('#reloadDeviceBtn').click();
                            //setTimeout(checkServer, autoreload_period);
                        }
                    }
                });
            }
        }

        function checkServer() {
            $.ajax({
                url: '{{ route('ajax.devices.check.server') }}',
                data: { '_token' : _token },
                success: function (data) {
                    if (data.result) {
                        location.reload();
                    } else {
                        setTimeout(checkServer, autoreload_period);
                    }
                },
                error: function (data) {
                    setTimeout(checkServer, autoreload_period);
                }
            });
        }
    </script>
@endsection