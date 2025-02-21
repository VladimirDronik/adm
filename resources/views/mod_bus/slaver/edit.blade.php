@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование устройства № '. $slaver->id,
        'links' => [ route('mod_bus.slavers.index') => 'Устройства'],
        'last_link' => 'Редактирование устройства'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.slavers.index') }}" class="btn btn-success m-b-10 m-l-5">Устройства</a>
                        <a href="{{ route('mod_bus.slavers.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить устройство</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($slaver, ['route' => ['mod_bus.slavers.update', $slaver->id], 'id' => 'slaver_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name', $slaver->name), ['required' => true]) }}

                        {{ Form::bs_simple_text('Тип:', $slaver->relatedType->name) }}

                        {{ Form::bs_autoselect('bus', 'Шина*:', $buses, old('bus', $slaver->bus), false, false, ['required' => true], null, null, 3, false, true) }}

                        @if($slaver->relatedType->protocol == 'modbus')
                            {{ Form::bs_number('address', 'Адрес*:', old('address', $slaver->address), ['min' => 1, 'max' => 247, 'required' => true]) }}
                        @elseif($slaver->relatedType->protocol == 'pulsarm')
                            {{ Form::bs_number('address', 'Адрес*:', old('address', $slaver->address), ['required' => true]) }}
                        @endif
                        <div class="form-group row">
                            <label class="control-label text-right col-md-3 label-fix" for="">Статус:</label>
                            <div class="col-md-9 align-self-center">
                                @if($slaver->active)
                                    <span class="badge badge-success">Активно</span>
                                @else
                                    <span class="badge badge-danger">Недоступно</span>
                                @endif
                            </div>
                        </div>

                        @if($slaver->relatedType->type == 'ecodim-dali-gw2')
                            <br><br>
                            {{ Form::bs_title('Сеть DALI') }}

                            <button type="button" class="btn btn-success m-b-10 m-l-5" @if(\App\Models\DaliDevice::exists()) id="networkAssemblyBtn" @else id="redirectToStartNetworkAssembly" @endif>Сборка сети</button>
                            <button type="button" class="btn btn-success m-b-10 m-l-5" id="startNetworkExpansion">Расширение сети</button>
                            <br><br>
                            <h4>Группы:</h4>
                            @if($daliDeviceGroups && $daliDeviceGroups->isNotEmpty())
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
                                    @foreach($daliDeviceGroups as $daliDeviceGroup)
                                        <tr id="tr{{$daliDeviceGroup->id}}">
                                            <td>
                                                <a href="{{ route('mod_bus.dali_devices.edit', [$daliDeviceGroup->id]) }}">{{ $daliDeviceGroup->name }}</a>
                                            </td>
                                            <td>
                                                G{{ $daliDeviceGroup->address }}
                                            </td>
                                            <td align="center">
                                                <a href="{{ route('mod_bus.dali_devices.edit', [$daliDeviceGroup->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                    <i class="fa fa-cog fa-lg"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-group_id="{{ $daliDeviceGroup->id }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <br>
                            <div class="form-group row {{ $errors->has('dali_device_group') ? ' has-error' : '' }}">
                                <label class="control-label text-right col-md-3 label-fix" for="dali_device_group">Добавить группу:</label>
                                <div class="col-sm-9">
                                    <select autocomplete="off" id="auto_sel_dali_device_group" data-placeholder="не выбрано" name="dali_device_group" class="chosen-select form-control" style="width:350px;">
                                        <option value="">Не выбрано</option>
                                        @foreach ($daliDeviceGroupsSelection as $id => $daliDeviceName)
                                            <option value="{{ $id }}">{{ $daliDeviceName }}</option>
                                        @endforeach
                                    </select>
                                    {{ Form::bs_field_error('dali_device_group') }}
                                    <button type="button" class="btn btn-success m-b-10 m-l-5" id="addDaliDeviceGroup">Добавить</button>
                                </div>
                            </div>
                        @elseif($slaver->relatedType->type == 'wb-led')
                            {{ Form::bs_select('wb_led_oper_mode', 'Режим работы*:', $wbLedOperModes, old('wb_led_oper_mode'), ['required' => true]) }}

                            <input type="hidden" name="old_wb_led_oper_mode" value="">

                        @elseif($slaver->relatedType->type == 'custom')
                            {{ Form::bs_select('purpose', 'Назначение устройства*:', $purposes, old('purpose', $slaver->relatedType->purpose), ['required' => true]) }}

                            {{ Form::bs_select('protocol', 'Протокол*:', $protocols, old('protocol', $slaver->relatedType->protocol), ['required' => true]) }}
                        @endif
                    </div>

                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.load_modal')
    @include('mod_bus.slaver.modals.network_assembly')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/jquery.bubble.text.js') }}"></script>
    <script>
        let network_assembly_url = '{{ route('ajax.mod_bus.slavers.network_assembly') }}';
        let network_expansion_url = '{{ route('ajax.mod_bus.slavers.network_expansion') }}';
        let read_register_url = '{{ route('ajax.mod_bus.registers.read') }}';
        let id = '{{ $slaver->id }}';
        let wbLedModeRegisterId = '{{ $wbLedModeRegisterId }}';

        if (wbLedModeRegisterId) {
            $.ajax({
                url: read_register_url,
                data: { '_token': _token, 'id': wbLedModeRegisterId },
                success: function (data) {
                    if (data.result) {
                        $('#slaver_form [name=wb_led_oper_mode]').find('option[value="'+ data.response +'"]').prop('selected', true);
                        $('#slaver_form [name=old_wb_led_oper_mode]').val(data.response);
                    } else {
                        showErrorModal(data.response ?? 'Ошибка чтения регистра wb_led_mode');
                    }
                }
            });
        }

        $(document).ready(function () {
            $("#auto_sel_bus").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_dali_device_group").chosen({width:"50%", no_results_text: "Не найдено"});

            $('#slaver_form select[name=protocol]').change(function() {
                var protocol = $(this).find('option:selected').val();

                if (protocol == 'modbus') {
                    $('#slaver_form input[name=address]').attr("min", 1);
                    $('#slaver_form input[name=address]').attr("max", 247);
                } else if (protocol == 'pulsarm') {
                    $('#slaver_form input[name=address]').removeAttr("min");
                    $('#slaver_form input[name=address]').removeAttr("max");
                }
            });

            $('#addDaliDeviceGroup').click(function () {
                let group_address = $("#auto_sel_dali_device_group").chosen().val();
                if (group_address) {
                    $.ajax({
                        url: "{{ route('ajax.mod_bus.slavers.create_dali_device_group') }}",
                        data: {'_token': _token, 'slaver_id': id, 'group_address': group_address},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal('Ошибка добавления группы');
                                }
                            }
                    });
                } else {
                    showErrorModal('Сначала выберите группу');
                }
            });

            $('.del_btn').click(function () {
                let del_group_id = $(this).attr('data-group_id');
                if (del_group_id) {
                    $.ajax({
                        url: "{{ route('ajax.mod_bus.slavers.remove_dali_device_group') }}",
                        data: {'_token': _token, 'group_id': del_group_id},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal('Ошибка удаления группы');
                                }
                            }
                    });
                }
            });

            bubbleText({
                element: $('#content1_modal_body'),
                newText: 'Выполняется. Пожалуйста, подождите ...',
                speed: 100,
                repeat: Infinity,
            });

            $('#networkAssemblyBtn').click(function() {
                $('#modal_network_assembly_init_btn').click();
            });

            $('#redirectToStartNetworkAssembly').click(function() {
                $('#startNetworkAssembly').click();
            });

            $('#startNetworkAssembly').click(function() {
                $('#load_modal_body').text('Сборка сети');
                $('#load_init_btn').click();

                $.ajax({
                    url: network_assembly_url,
                    data: { '_token': _token, 'id': id },
                    success: function (data) {
                        $('#dismiss_load_modal').click();
                        if (data.result) {
                            showSuccessModal('Сборка сети прошла успешно');
                        } else {
                            showErrorModal(data.message ?? 'Ошибка сборки сети');
                        }
                    }
                });
            });

            $('#startNetworkExpansion').click(function() {
                $('#load_modal_body').text('Расширение сети');
                $('#load_init_btn').click();

                $.ajax({
                    url: network_expansion_url,
                    data: { '_token': _token, 'id': id },
                    success: function (data) {
                        $('#dismiss_load_modal').click();
                        if (data.result) {
                            showSuccessModal('Расширение сети прошло успешно');
                        } else {
                            showErrorModal(data.message ?? 'Ошибка расширения сети');
                        }
                    }
                });
            });
        });
    </script>
@endsection
