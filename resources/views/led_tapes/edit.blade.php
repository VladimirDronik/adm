@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование led ленты № '. $ledTape->object['id'] . ' «' . $ledTape->name .'»',
        'links' => [ route('led_tapes.index') => 'Led ленты'],
        'last_link' => 'Редактирование led ленты'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('led_tapes.index') }}" class="btn btn-success m-b-10 m-l-5">Список led лент</a>
                        <a href="{{ route('led_tapes.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить led ленту</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($ledTape, ['route' => ['led_tapes.update', $ledTape->id], 'id' => 'led_tape_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name', $ledTape->name), ['required' => true]) }}

                        {{ Form::bs_simple_text('Тип ленты:', $ledTape->type) }}

                        {{ Form::bs_autoselect('device_id', 'Контроллер*:', $devices, old('device_id', $deviceId), false, false, ['required' => true], null) }}

                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix" for="port_id">
                                <strong>Порт*:</strong>
                            </label>
                            <div class="col-sm-9">
                                <select autocomplete="off" id="auto_sel_port_id" required data-placeholder="Не выбрано" name="port_id" class="chosen-select form-control" style="width:350px;">
                                    <option value="">Не выбрано</option>
                                    @foreach ($deviceData['ports'] as $portData)
                                        <option value="{{ is_array($portData['id']) ? implode(',', $portData['id']) : $portData['id'] }}" @if($portData['id'] == old('port_id', $portIds)) selected @endif>
                                            {{ $portData['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix">
                            </label>
                            <div class="col-sm-9">
                                <ul id="portList">
                                    @foreach ($deviceData['ports_info'] as $portInfo)
                                        <li>{!! $portInfo['name'] !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        @if ($ledTape->type != 'W')
                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix">
                                    Текущий цвет:
                                </label>
                                <div class="col-sm-9">
                                    <div style="display: inline-block; width: 100px; height: 50px; background-color: hsl({{ $ledTape->h }}, {{ $ledTape->hsvToHsl()['s'] }}%, {{ $ledTape->hsvToHsl()['l'] }}%)"></div>
                                    <ul style="display: inline-block;">
                                        <li>&nbsp;&nbsp; h = {{ $ledTape->h }}&deg</li>
                                        <li>&nbsp;&nbsp; s = {{ $ledTape->s }}%</li>
                                        <li>&nbsp;&nbsp; v = {{ $ledTape->v }}%</li>
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if ($ledTape->type != 'RGB')
                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix">
                                    Яркость белого:
                                </label>
                                <div class="col-sm-9">
                                    <div class="mt-2">{{ $ledTape->w }}%</div>
                                </div>
                            </div>
                        @endif

                        <input type="hidden" name="id_object" value="{{ $ledTape->id_object }}">
                    </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
            </div>
        </div>
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        const url_ports = '{{ route('ajax.devices.free_wb_led_ports_by_type') }}';
        let led_type = '{{ $ledTape->type }}';
        let object_id = '{{ $ledTape->id_object }}';

        $(document).ready(function () {
            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_device_id").chosen().change(function() {
                $("#portList").empty();
                let device_id = $("#auto_sel_device_id").chosen().val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'types': 'out', 'led_type': led_type, 'object_id': object_id},
                    success: function (data) {
                        createPortSelect('#auto_sel_port_id', data.ports, -1);
                        createPortsList(data.ports_info);
                        $('#auto_sel_port_id').trigger("chosen:updated");
                    }
                });
            });

            function createPortSelect(target, options, selected) {
                let sel = $(target);
                sel.html('');
                let s = '<option value="">Не выбрано</option>';
                for (let i = 0; i < options.length; i++) {
                    if (selected == options[i].id)
                        s += '<option selected value="' + options[i].id + '">' + options[i].name + '</option>';
                    else
                        s += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
                }
                sel.append(s);
            }

            function createPortsList(data) {
                var portList = $("#portList");

                for (var i = 0; i < data.length; i++) {
                    var portInfo = data[i];
                    var text = portInfo.name;
                    var listItem = $("<li></li>");

                    listItem.html(text);
                    portList.append(listItem);
                }
            }

            function getPorts() {
            }

            function validateLedTape() {
                if (!$("#led_tape_form input[name='name']").val()) {
                    return 'Укажите название для ленты';
                }
                if (!$("#auto_sel_device_id").chosen().val()) {
                    return 'Выберите контроллер';
                }
                if (!$("#auto_sel_port_id").chosen().val()) {
                    return 'Выберите порт/порты';
                }

                return '';
            }

            $("#led_tape_form button[type=submit]").click(function() {
                console.log('asdasds')
                let message = validateLedTape();
                if (message !== '') {
                    showErrorModal(message);
                }
            });
        });
    </script>
@endsection
