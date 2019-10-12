@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование устройства «'. $device->description.'»',
        'links' => [ route('devices.index') => 'Устройства'],
        'last_link' => 'Редактирование устройства'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('devices.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок устройств</a>
                        <a href="{{ route('devices.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить устройство</a>
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
                <button type="button" class="btn btn-success m-b-10 m-l-5" data-toggle="modal" data-target="#device_modal">Сохранить</button>
                <button type="button" class="btn btn-danger m-b-10 m-l-5 pull-right"  data-toggle="modal" data-target="#delete_modal">Удалить устройство</button>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Порты устройства</h4></div>
            <div class="card-body">
                @if(count($device->ports))
                <div class="table-responsive">
                    <table class="table  table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Тип</th>
                                <th>Описание</th>
                                <th>Связанный объект</th>
                                <th>Действие</th>
                                <th class="text-center">Длит. нажатие</th>
                                <th class="text-center">Двойн. нажатие</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($device->ports as $port)
                            <tr>
                                <th scope="row"> {{ $port->num_port }}</th>
                                <td>
                                    @if($port->status === 'out')
                                        <span class="badge badge-primary">{{ $port->status }}</span>
                                    @elseif ($port->status === 'in')
                                        <span class="badge badge-success">{{ $port->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#name_modal"
                                       id="name_port_{{ $port->id }}" onclick="getPortComment('{{ $port->id }}');">
                                        @if($port->is_empty_comment)
                                            <i>{{ $port->comment != '' ? $port->comment : 'Отсутствует'}}</i>
                                        @else
                                            <span style="color: #455a64;">{{ $port->comment }}</span>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    @if($port->eobject)
                                        <button type="button" class="btn btn-warning m-b-10 btn-sm"
                                                name="object" id="portobj_{{ $port->id }}"
                                                data-toggle="modal" data-target="#objectsModal"
                                                value="{{ $port->object}},{{$port->eobject->name}},portobj_{{ $port->id }}">
                                            <b>{{ optional($port->eobject)->name }}</b>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-default m-b-10 btn-sm"
                                                name="object" id="portobjempty_{{ $port->id }}"
                                                data-toggle="modal" data-target="#objectsModal"
                                                value="empty,empty,portobjempty_{{ $port->id }}">
                                            Отсутствует
                                        </button>
                                    @endif
                                </td>
                                <td>
{{--                                    @if($port->eobject && $port->status !== 'out')--}}
{{--                                        <button type="button" id="method_btn_{{ $port->id }}" class="btn btn-warning m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('method', {{ $port->id }}, '{{ optional($port->eobject)->name }}');">--}}
{{--                                            @if($port->method) <b>Метод: {{ optional($port->emethod)->name }}</b> @else <b class="text-danger">Метод не выбран</b> @endif--}}
{{--                                        </button>--}}
{{--                                    @elseif($port->status !== 'out')--}}
{{--                                        <button type="button" id="method_btn_{{ $port->id }}" class="btn btn-default m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('none', {{ $port->id }}, 'none');">Отсутствует</button>--}}
{{--                                    @endif--}}
                                    @if($port->eobject && $port->status !== 'out')
                                        <button type="button" id="viewmethod_{{ $port->id }}"
                                                name="method" class="btn btn-warning m-b-10 btn-sm" data-toggle="modal"
                                                value="{{ $port->method}},{{optional($port->emethod)->name}},viewmethod_{{ $port->id }}"
                                                data-target="#methodsModal">
                                            @if($port->method)<b>Метод: {{ optional($port->emethod)->name }}</b>@else <b class="text-danger">Метод не выбран</b> @endif
                                        </button>
                                    @elseif($port->status !== 'out')
                                        <button type="button" id="viewmethodempty_{{ $port->id }}"
                                                name="method" class="btn btn-default m-b-10 btn-sm" data-toggle="modal"
                                                value="empty,empty,viewmethodempty_{{ $port->id }}"
                                                data-target="#methodsModal">
                                            Отсутствует</button>
                                    @endif
                                </td>
                                @if($port->status !== 'out')
                                    <td class="text-center">
                                        <input type="checkbox" class="long_checkbox" data-id="{{ $port->id }}" style="cursor: pointer;" autocomplete="off" value="1" @if($port->longclick) checked @endif></td>
                                    <td class="text-center">
                                        <input type="checkbox" class="double_checkbox" data-id="{{ $port->id }}" style="cursor: pointer;" autocomplete="off" value="1" @if($port->doubleclick) checked @endif> </td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif
                         </tr>
                        @endforeach
                        </tbody>
                        @if(count($device->ports)>10)
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Тип</th>
                                <th>Описание</th>
                                <th>Связанный объект</th>
                                <th>Действие</th>
                                <th class="text-center">Длит. нажатие</th>
                                <th class="text-center">Двойн. нажатие</th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                    <p class="text-right">Найдено: {{ count($device->ports) }}</p>
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
                    <h4 class="modal-title">Сохранить название и ip адрес устройства?</h4>
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
                    <h4 class="modal-title">Удалить устройство?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="deleteDevice();" >Удалить</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/device.js') }}"></script>
    <script>
        let device_id = '{{ $device->id }}';
        let port_comment_url = '{{ route('ajax.ports.update.comment') }}';
        let objects_url = '{{ route('ajax.objects.view.all') }}';
        let methods_url = '{{ route('ajax.ports.method.all') }}';

        function deleteDevice() {
            $.ajax({
                url: '{{ route('ajax.devices.delete') }}',
                data: {'_token': _token, 'id': device_id},
                success: function (data) {
                    if (data.result) {
                        window.location = '{{ route('devices.index') }}';
                    } else {
                        showErrorModal('Ошибка при удалении устройства');
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
                return 'Не указано название устройства';
            }
            if (description === '') {
                return 'Не указано название устройства';
            }
            if (ip_address === '') {
                return 'Не указан ip адрес устройства';
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
    </script>
@endsection