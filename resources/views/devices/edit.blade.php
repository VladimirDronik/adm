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
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                Название: <input name="description" value="{{ $device->description}}" size="15">
                ip адрес: <input name="ip_address" value="{{ $device->ip_address }}" size="15">
                <input type="hidden" id="id_device" value="{{ $device->id }}">
                Тип: <span class="text-capitalize">{{ optional($device->devtype)->name }}</span>
                <button type="button" id="updateDeviceBtn" class="btn btn-success m-b-10 m-l-5" data-toggle="modal" data-target="#device_modal">Сохранить</button>
                <button type="button" class="btn btn-danger m-b-10 m-l-5 pull-right"  data-toggle="modal" data-target="#delete_modal">Удалить контроллер</button>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Порты контроллера</h4></div>
            <div class="card-body">
                @if(count($device->ports))
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#portstab1" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Порты IN</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#portstab2" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Порты OUT</span></a> </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane p-20 active" id="portstab1" role="tabpanel">
                            @include('devices.in_ports')
                        </div>
                        <div class="tab-pane p-20" id="portstab2" role="tabpanel">
                            @include('devices.out_ports')
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
                    <div id="objectframe"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div id="methodsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Выбор метода</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <label id="selected_method"></label><br>
                    </div>
                    <div id="methodframe"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div id="device_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Сохранить название и ip адрес контроллера?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button"   class="btn btn-primary" data-dismiss="modal" onclick="updateDevice();" >Сохранить</button>
                </div>
            </div>
        </div>
    </div>

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

    <div id="reloadModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Сообщение</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Сервер перезагружается... Пожалуйста, дождитесь завершения перезагрузки.
                    </div>
                </div>
                <div class="modal-footer">
{{--                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>--}}
                </div>
            </div>
        </div>
    </div>

    <button type="button" style="display: none;" id="reloadDeviceBtn" data-target="#reloadModal" data-toggle="modal" data-backdrop="static" data-keyboard="false">&nbsp;</button>
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/device.js') }}"></script>
    <script>
        const device_id = '{{ $device->id }}';
        const port_comment_url = '{{ route('ajax.ports.update.comment') }}';
        const objects_url = '{{ route('ajax.objects.view.all') }}';
        const methods_url = '{{ route('ajax.ports.method.all') }}';
        const autoreload_period = 3000;

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

        function validateDevice(description, ip_address) {
            if (description === '') {
                return 'Не указано название контроллера';
            }
            if (ip_address === '') {
                return 'Не указан ip адрес контроллера';
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

            $message = validateDevice(description, ip_address);
            if ($message !== '') {
                showErrorModal($message);
                return false;
            }

            $.ajax({
                url: '{{ route('ajax.devices.update') }}',
                data: {'_token': _token, 'id': device_id, 'description': description,
                    'ip_address': ip_address},
                success: function (data) {
                    if (!data.result) {
                        showErrorModal(data.message);
                    } else {
                        $('#reloadDeviceBtn').click();
                        setTimeout(checkServer, autoreload_period);
                    }
                }
            });
        }

        function updatePortCheckbox(name, port_id, value) {
            $.ajax({
                url: '{{ route('ajax.devices.ports.update') }}',
                data: {'_token': _token, 'id': device_id, 'port_id': port_id,
                    'name': name, 'value': value},
                success: function (data) {
                    if (!data.result) {
                        showErrorModal('Ошибка при сохранении изменений');
                    } else if (name === 'doubleclick' || name === 'longclick') {
                        showSuccessModal('Изменения успешно сохранены');
                    }
                }
            });
        }

        $(document).ready(function () {
            $('.long_checkbox').change(function () {
                let port_id = $(this).attr('data-id');
                let value = this.checked ? 1 : 0;

                updatePortCheckbox('longclick', port_id, value);
            });

            $('.double_checkbox').change(function () {
                let port_id = $(this).attr('data-id');
                let value = this.checked ? 1 : 0;

                updatePortCheckbox('doubleclick', port_id, value);
            });

            //Вызов модального окна с методами
            $('button[type=button][name=method]').click(function () {

                let method_val = this.value;
                let view_id = this.id;
                let method_arr = method_val.split(',');

                let data = {};
                data['method'] = method_val;
                data['id'] = view_id.split('_')[1];

                ajax_html(data, methods_url, '#methodframe');

                if (method_arr[0] != 'empty' && method_arr[0] != '') {
                    $('#selected_method').html('Выбран метод: '+ method_arr[1] +
                        '   <button type="button" class="btn btn-danger m-b-2 btn-xs" data-dismiss="modal" ' +
                        'id = "reset_method"  value="'+ view_id + '" ' +
                        'onclick="resetMethod(\''+view_id+'\',\''+method_arr[2]+'\');">Убрать</button>');
                } else {
                    $('#selected_method').html('Метод не выбран');
                }
            });

        });

        function resetMethod(id, view) {
            //Внесение изменений в БД
            selectMethod(null, null);

            $('#'+id).attr({"class": "btn btn-default  m-b-10 btn-sm"});
            $('#'+view).val('empty,empty,' + id);
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