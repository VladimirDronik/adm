@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование устройства DALI № '. $daliDevice->id,
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

                            {{ Form::bs_autoselect('room', 'Размещение:', $rooms, old('room', $daliDevice->room), false, false, []) }}

                            {{ Form::bs_simple_text('Адрес:', $daliDevice->address) }}

                            {{ Form::bs_simple_text('Шлюз:', $daliDevice->dali_gateway) }}

                            {{ Form::bs_simple_text('Неисправность:', $daliDevice->failure ? 'Да' : 'Нет') }}

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
                                        <input class="form-control" style="width: 30%; display: inline-block;" autocomplete="off" name="brightness" type="number" min="0" max="255" value="{{ old('brightness', $daliDevice->brightness) }}">
                                        <button type="button" class="btn btn-success m-b-10 m-l-5" id="setBrightness">Применить</button>
                                    </div>
                                </div>

                                @if($daliDevice->is_cct)
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-3 label-fix" for="cct">Цветовая температура:</label>
                                        <div class="col-md-9">
                                            <input class="form-control" style="width: 30%; display: inline-block;" autocomplete="off" name="cct" type="number" min="0" max="65535" value="{{ old('cct', $daliDevice->cct) }}">
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
        const id = '{{ $daliDevice->id }}';

        $(document).ready(function () {
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});

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
                $.ajax({
                    url: '{{ route('ajax.mod_bus.slavers.set_brightness') }}',
                    data: {'_token': _token, 'id': id, 'brightness': $('#dali_device_form [name=brightness]').val()},
                        success: function (data) {
                            if (data.result) {
                                window.location.reload();
                            } else {
                                showErrorModal(data.message ?? 'Ошибка установки яркости');
                            }
                        }
                });
            });

            $('#setCct').click(function () {
                $.ajax({
                    url: '{{ route('ajax.mod_bus.slavers.set_cct') }}',
                    data: {'_token': _token, 'id': id, 'cct': $('#dali_device_form [name=cct]').val()},
                        success: function (data) {
                            if (data.result) {
                                window.location.reload();
                            } else {
                                showErrorModal(data.message ?? 'Ошибка установки цветовой температуры');
                            }
                        }
                });
            });
        });
    </script>
@endsection
