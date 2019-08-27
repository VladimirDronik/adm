@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование устройства № '. $device->id .' «'. $device->description.'»',
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
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                Название: <input name="description" id="descr_device" value="{{ $device->description}}" size="15">
                ip адрес: <input name="ip_address" id="ip_device" value="{{ $device->ip_address }}" size="15">
                <input type="hidden" id="id_device" value="{{ $device->id }}">
                Тип: <span class="text-capitalize">{{ $device->devtype->name }}</span>
                <button type="button" class="btn btn-success m-b-10 m-l-5" data-toggle="modal" data-target="#device-settings-Modal">Сохранить</button>
                <button type="button" class="btn btn-danger m-b-10 m-l-5 pull-right"  data-toggle="modal" data-target="#delete-device-Modal">Удалить устройство</button>
            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <h4>Порты устройства </h4>
            </div>
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
                                <th class="text-center">Длит</th>
                                <th class="text-center">Двойн</th>
                                <th>Настройка</th>
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
                                <td><a href="#" data-toggle="modal" data-target="#name_modal" id="name_port_{{ $port->id }}" onclick="get_name_port('{{ $port->id }}'); ">
                                        @if($port->comment != '')
                                            {{ $port->comment }}
                                        @else
                                            Без названия
                                        @endif
                                    </a>
                                </td>
                                <td >
                                    @if (optional($port->eobject)->name)
                                        <button type="button" class="btn btn-warning  m-b-10 btn-sm" name="object" id="portobj_{{ $port->id }}" data-toggle="modal" data-target="#objectsModal"  value="{{ $port->object}},{{$port->eobject->name}},portobj_{{ $port->id }}"> <b>{{ $port->eobject->name }}</b></button>
                                    @else
                                        <button type="button" class="btn btn-default  m-b-10 btn-sm" name="object" id="portobjempty_{{ $port->id }}" data-toggle="modal" data-target="#objectsModal" value="empty,empty,portobjempty_{{ $port->id }}">Отсутствует</button>
                                    @endif
                                </td>
                                <td>
                                    @if($port->easy)
                                        <button type="button"  id="method_btn_{{ $port->id }}" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('easy', {{ $port->id }}, '{{ $port->easy }}');"><b>Простое: {{ $port->easy }}</b></button>
                                    @elseif($port->script)
                                        <button type="button"  id="method_btn_{{ $port->id }}" class="btn btn-info  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('script', {{ $port->id }}, '{{ optional($port->escript)->name }}');"><b>{{ optional($port->escript)->name }}</b></button>
                                    @elseif(optional($port->eobject)->name != '' && $port->status !== 'out')
                                        <button type="button" id="method_btn_{{ $port->id }}" class="btn btn-warning  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('method', {{ $port->id }}, '{{ optional($port->eobject)->name }}');"><b><< Выполнять действие объекта</b></button>
                                    @elseif($port->status !== 'out')
                                        <button type="button" id="method_btn_{{ $port->id }}" class="btn btn-default  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('none', {{ $port->id }}, 'none');">Отсутсвует</button>
                                    @endif
                                </td>
                                @if($port->status !== 'out')
                                    <td align="center">
                                        <input type="checkbox" style="cursor: pointer;" value="{{ $port->longclick }}"></td>
                                    <td align="center">
                                        <input type="checkbox" style="cursor: pointer;" value="{{ $port->doubleclick }}"></td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif

                                <td align="center"><button type="button" class="btn btn-info btn-sm btn-rounded"><i class="fa fa-cog fa-lg"></i></button></td>
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
                                <th align="center">Длит</th>
                                <th align="center">Двойн</th>
                                <th>Настройка</th>
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

    <!-- HTML-код модального окна выбор объекта -->
    <div id="objectsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Выбор привязанного объекта</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <label id="selected_object"></label><br>
                    </div>
                    <div id="objectframe">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

<!-- модальное окно выбора действия -->
<div id="actionModal" class="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Действие при активации порта</h4>
            </div>
            <div class="modal-body">
                <div class="btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-success" id="easy_button" >
                        <input type="radio" name="actions"  autocomplete="off" value="easy"> Простое действие
                    </label>
                    <label class="btn btn-success" id="method_button">
                        <input type="radio" name="actions"  autocomplete="off" value="method"> Метод объекта
                    </label>
                    <label class="btn btn-success" id="script_button">
                        <input type="radio" name="actions"  autocomplete="off" value="script"> Скрипт
                    </label>
                    <label class="btn btn-success" id="none_button">
                        <input type="radio" name="actions"  autocomplete="off" value="none"> Отсутствует
                    </label>
                </div>
                <br><br><br>
                <div id="mode">
                </div>
                <div id="object" class="d-none">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal"  onclick="save_method();">Сохранить изменения</button>
                <input type="hidden" value="" id="id_port">
                <input type="hidden" value="" id="value">
                <input type="hidden" value="" id="cur_method">
            </div>
        </div>
    </div>
</div>

    <!-- модальное окно выбора действия для порта -->
    <div id="methodsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title_action"></h4>
                </div>
                <div class="modal-body" id="method_data">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button"   class="btn btn-primary" >Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>

    <!-- модальное окно изменения имени у порта-->
    <div id="name_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Описание порта</h4>
                </div>
                    <div class="modal-body" >
                        <input type="text" class="form-control input-default " id="name_modal_data" placeholder="Input Default">
                        <button type="button" class="btn btn-default" onclick="no_name();">Убрать</button>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button"   class="btn btn-primary" data-dismiss="modal" onclick="save_name_port();" >Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>

    <!-- модальное окно сохранения настроек устройства -->
    <div id="device-settings-Modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Сохранить настройки устройства?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button"   class="btn btn-primary" data-dismiss="modal" onclick="save_device_settings();" >Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- модальное окно удаления устройства -->
    <div id="delete-device-Modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Удалить устройство?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button"   class="btn btn-primary" data-dismiss="modal" onclick="deleteDevice();" >Удалить</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/device.js') }}"></script>
    <script>
        function deleteDevice() {
            let del_id = '{{ $device->id }}';
            $.ajax({
                url: '{{ route('ajax.devices.delete') }}',
                data: { '_token': _token, 'id': del_id },
                success: function (data) {
                    if (data.result) {
                        window.location = '{{ route('devices.index') }}';
                    } else {
                        showErrorModal('Ошибка при удалении устройства');
                    }
                }
            });
        }
    </script>
@endsection