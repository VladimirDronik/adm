@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование устройства DALI № '. $daliDevice->id_object,
        'links' => [ route('illumination.index') => 'Устройства освещения'],
        'last_link' => 'Редактирование устройства DALI'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('illumination.index') }}" class="btn btn-success m-b-10 m-l-5">Список устройств освещения</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($daliDevice, ['route' => ['mod_bus.dali_devices.update', $daliDevice->id], 'id' => 'dali_device_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_text('name', 'Название*:', old('name', $daliDevice->name), ['required' => true]) }}

                            @if(!$daliDevice->is_group)
                                {{ Form::bs_autoselect('room', 'Размещение:', $rooms, old('room', $daliDevice->room), false, false, []) }}
                            @endif

                            {{ Form::bs_simple_text('Адрес:', ($daliDevice->is_group ? 'G' : 'A') . $daliDevice->address) }}

                            {{ Form::bs_simple_text('Шлюз:', $daliDevice->modbusSlaver->name) }}

                            @if(!$daliDevice->is_group)
                                {{ Form::bs_simple_text('Неисправность:', $daliDevice->failure ? 'Да' : 'Нет') }}
                            @endif

                            @if($daliDevice->object)
                                <div class="form-group row">
                                    <label class="control-label text-right col-md-3 label-fix" for="">Состояние:</label>
                                    <div class="col-md-9">
                                        <button type="button" class="btn btn-success m-b-10 m-l-5" id="switchStatus">{{ $daliDevice->object->status }}</button>
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    <label class="control-label text-right col-md-3 label-fix" for="brightness">Яркость:</label>
                                    <div class="col-md-9">
                                        <input class="form-control" style="width: 30%; display: inline-block;" autocomplete="off" name="brightness" type="number" min="0" max="100" value="{{ old('brightness', $daliDevice->brightness) }}">
                                        <button type="button" class="btn btn-success m-b-10 m-l-5" id="setBrightness">Применить</button>
                                    </div>
                                </div>

                                @if($daliDevice->is_cct)
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-3 label-fix" for="cct">Цветовая температура:</label>
                                        <div class="col-md-9">
                                            <input class="form-control" style="width: 30%; display: inline-block;" autocomplete="off" name="cct" type="number" min="1000" max="10000" value="{{ old('cct', $daliDevice->cct) }}">
                                            <button type="button" class="btn btn-success m-b-10 m-l-5" id="setCct">Применить</button>
                                        </div>
                                    </div>
                                @endif
                            @else
                                {{ Form::bs_simple_text('Яркость:', $daliDevice->brightness) }}

                                @if($daliDevice->is_cct)
                                    {{ Form::bs_simple_text('Цветовая температура:', $daliDevice->cct) }}
                                @endif
                            @endif
                        </div>

                        @if($daliDevice->is_group)
                            <hr>
                            <br>
                            <h4>Устройства в группе:</h4>
                            @if($daliDevice->daliDevices->isNotEmpty())
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50%;">Название</th>
                                            <th style="width: 40%;">Адрес</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($daliDevice->daliDevices as $relatedDaliDevice)
                                        <tr id="tr{{$relatedDaliDevice->id}}">
                                            <td>
                                                <a href="{{ route('mod_bus.dali_devices.edit', [$relatedDaliDevice->id]) }}">{{ $relatedDaliDevice->name }}</a>
                                            </td>
                                            <td>
                                                A{{ $relatedDaliDevice->address }}
                                            </td>
                                            <td align="center">
                                                <a href="{{ route('mod_bus.dali_devices.edit', [$relatedDaliDevice->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                    <i class="fa fa-cog fa-lg"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-id_object="{{ $relatedDaliDevice->id_object }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <br>
                            <div class="form-group row {{ $errors->has('dali_device_object_id') ? ' has-error' : '' }}">
                                <label class="control-label text-right col-md-3 label-fix" for="dali_device_object_id">Новое устройство:</label>
                                <div class="col-sm-9">
                                    <select autocomplete="off" id="auto_sel_dali_device_object_id" data-placeholder="не выбрано" name="dali_device_object_id" class="chosen-select form-control" style="width:350px;">
                                        <option value="">Не выбрано</option>
                                        @foreach ($daliDevices as $daliDeviceIdObject => $daliDeviceName)
                                            <option value="{{ $daliDeviceIdObject }}">{{ $daliDeviceName }}</option>
                                        @endforeach
                                    </select>
                                    {{ Form::bs_field_error('dali_device_object_id') }}
                                    <button type="button" class="btn btn-success m-b-10 m-l-5" id="addDaliDevice">Добавить</button>
                                </div>
                            </div>
                        @else
                            @if($daliDevice->groups->isNotEmpty())
                                <hr>
                                <br>
                                <h4>Группы в которые входит данное устройство:</h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50%;">Название</th>
                                            <th style="width: 40%;">Адрес</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($daliDevice->groups as $group)
                                        <tr id="tr{{$group->id}}">
                                            <td>
                                                <a href="{{ route('mod_bus.dali_devices.edit', [$group->id]) }}">{{ $group->name }}</a>
                                            </td>
                                            <td>
                                                G{{ $group->address }}
                                            </td>
                                            <td align="center">
                                                <a href="{{ route('mod_bus.dali_devices.edit', [$group->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                    <i class="fa fa-cog fa-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @endif

                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
        </div>
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        const id_object = '{{ $daliDevice->id_object }}';
        const group_address = '{{ $daliDevice->address }}';
        const id = '{{ $daliDevice->id }}';

        $(document).ready(function () {
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_dali_device_object_id").chosen({width:"70%", no_results_text: "Не найдено"});

            $('#addDaliDevice').click(function () {
                let dali_device_id_object = $("#auto_sel_dali_device_object_id").chosen().val();
                if (dali_device_id_object) {
                    $.ajax({
                        url: "{{ route('ajax.mod_bus.slavers.add_dali_device_to_group') }}",
                        data: {'_token': _token, 'dali_device_id_object': dali_device_id_object, 'group_id_object': id_object, 'group_address': group_address},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal(data.message ?? 'Ошибка добавления устройства в группу');
                                }
                            }
                    });
                } else {
                    showErrorModal('Сначала выберите устройство для добавления');
                }
            });

            $('.del_btn').click(function () {
                let del_dali_device_id_object = $(this).attr('data-id_object');
                if (del_dali_device_id_object) {
                    $.ajax({
                        url: "{{ route('ajax.mod_bus.slavers.remove_dali_device_from_group') }}",
                        data: {'_token': _token, 'dali_device_id_object': del_dali_device_id_object, 'group_id_object': id_object, 'group_address': group_address},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal(data.message ?? 'Ошибка удаления устройства из группы');
                                }
                            }
                    });
                }
            });

            $('#switchStatus').click(function () {
                if (id_object) {
                    $.ajax({
                        url: '{{ route('ajax.mod_bus.slavers.switch_status') }}',
                        data: {'_token': _token, 'id_object': id_object},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal(data.message ?? 'Ошибка смены состояния');
                                }
                            }
                    });
                }
            });

            $('#setBrightness').click(function () {
                var value = $('#dali_device_form [name=brightness]').val();

                if (value && value >= 0 && value <= 100) {
                    $.ajax({
                        url: '{{ route('ajax.mod_bus.slavers.set_brightness') }}',
                        data: {'_token': _token, 'id': id, 'brightness': value},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal(data.message ?? 'Ошибка установки яркости');
                                }
                            }
                    });
                } else {
                    showErrorModal('Значение яркости должно быть целым числом в диапазоне от 0 до 100');
                }
            });

            $('#setCct').click(function () {
                var value = $('#dali_device_form [name=cct]').val();

                if (value && value >= 1000 && value <= 10000) {
                    $.ajax({
                        url: '{{ route('ajax.mod_bus.slavers.set_cct') }}',
                        data: {'_token': _token, 'id': id, 'cct': value},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal(data.message ?? 'Ошибка установки цветовой температуры');
                                }
                            }
                    });
                } else {
                    showErrorModal('Значение цветовой температуры должно быть целым числом в диапазоне от 1000 до 10000');
                }
            });
        });
    </script>
@endsection
