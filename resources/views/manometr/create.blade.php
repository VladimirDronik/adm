@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление датчика УГ', 'links' => [ route('manometr.index') => 'Манометр']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('manometr.index') }}" class="btn btn-success m-b-10 m-l-5">Список манометров</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'manometr.store', 'method' => 'post',
                            'id' => 'manometr_form', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}


                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix" for="id_object">
                                    <strong>Размещение манометра:</strong>
                                </label>


                                <div class="col-md-6" id="single_port_div">
                                    {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id'),
                                       false, false, [], null) }}

                                    {{ Form::bs_autoselect('port', 'Порт:', [], old('port'),
                                        false, false, [], null) }}

                                </div>


                            </div>



                            <div style="height: 10px;">&nbsp;</div>
                            <hr>
                            <div style="height: 40px;">&nbsp;</div>


                            {{ Form::bs_text('calibration', 'Калибровка*:', old('max_threshold', 0), ['required' => true],
                              '') }}

                            <div style="height: 60px;">&nbsp;</div>

                            {{ Form::bs_number('low_value', 'Нижний аварийный порог*:', old('low_value', 50), ['min' => 0, 'max' => 1000, 'required' => true]) }}

                            {{ Form::bs_autoselect('low_object', 'Объект влияния:', $objects, old('low_object'),
                              false, false, [],  null, 'Объект, у которого меняем состояние при достищении нижнего порога') }}

                            {{ Form::bs_autoselect('low_method', 'Метод объекта:', [], old('low_method'),
                                false, false, [], null, 'Метод объекта влияния при достижении нижнего порога') }}

                            <div class="form-group row" id="low_method_params_div"
                                 @if(!old('low_method')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="low_method_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="low_method_params_label" for="low_method_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="low_method_params" name="low_method_params"
                                                   type="text" value="{{ old('low_method_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="height: 60px;">&nbsp;</div>

                            {{ Form::bs_number('high_value', 'Верхний аварийный порог*:', old('high_value', 100), ['min' => 0, 'max' => 54612, 'required' => true],
                                '') }}

                            {{ Form::bs_autoselect('high_object', 'Объект влияния:', $objects, old('high_object'),
                             false, false, [],  null, 'Объект, у которого меняем состояние при достищении верхнего порога') }}


                            {{ Form::bs_autoselect('high_method', 'Метод объекта:', [], old('high_method'),
                                false, false, [], null, 'Метод объекта влияния при достижении верхнего порога') }}

                            <div class="form-group row" id="high_method_params_div"
                                 @if(!old('high_method')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="high_method_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="high_method_params_label" for="high_method_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="high_method_params" name="high_method_params"
                                                   type="text" value="{{ old('high_method_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div style="height: 60px;">&nbsp;</div>


                            {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', -1), false, false) }}

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
    @include('components.create_object_modal', compact('object_types'))
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/manometr.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        let modal_btn_index = -1;
        let methods = [];

        $(document).ready(function () {
            initManometrForm();

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_low_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_high_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_low_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_high_method").chosen({width:"100%", no_results_text: "Не найдено"});


            $("#auto_sel_low_object").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('low_method_params');

                getMethods(object_id, '#auto_sel_low_method');
            });

            $("#auto_sel_low_method").chosen().change(function() {
                loadMethods($(this).val(), 'low_method_params', '#manometr_form');
            });

            $("#auto_sel_high_object").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('high_method_params');

                getMethods(object_id, '#auto_sel_high_method');
            });

            $("#auto_sel_high_method").chosen().change(function() {
                loadMethods($(this).val(), 'high_method_params', '#manometr_form');
            });



            $("#auto_sel_device_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                    success: function (data) {
                        createMethodSelect('#auto_sel_port', data.ports, -1);
                        $('#auto_sel_port').trigger("chosen:updated");
                    }
                });
            });

            $('#auto_sel_btn_id_object').click(function() {
                modal_btn_index = 1;
                clearCreateObjectModal();
                $('#create_object_modal_init_btn').click();
                return false;
            });

            $('#auto_sel_btn_object').click(function() {
                modal_btn_index = 2;
                clearCreateObjectModal();
                $('#create_object_modal_init_btn').click();
                return false;
            });

            $('#create_object_modal_btn').click(function() {
                let message = validateCreateObject();
                if (message !== '') {
                    showCreateObjectError(message);
                    return false;
                }

                storeObject();
            });

            function storeObject() {
                const name = $("#create_object_modal input[name=object_name]").val().trim();
                const type = $("#create_object_modal input[name=object_type]:checked").val().trim();

                $.ajax({
                    url: storeObjectUrl,
                    data: {'_token': _token, 'name': name, 'type': type},
                    success: function (data) {
                        if (data.result) {
                            hideCreateObjectError();
                            updateObjectSelects(data.objects, data.id);
                            $('#create_object_cancel_btn').click();
                        } else {
                            showCreateObjectError(data.message);
                        }
                    },
                    error: function () {
                        showCreateObjectError('Сервер временно недоступен');
                    }
                });
            }

            function updateObjectSelects(objects, selected) {
                let id = false;

                if (modal_btn_index === 1) {
                    id = $('#auto_sel_id_object').val();
                } else if (modal_btn_index === 2) {
                    id = $('#auto_sel_object').val();
                }

                if (id) {
                    selected = id;
                }

                createObjectSelect('#auto_sel_id_object', objects, modal_btn_index === 1 ? selected : $('#auto_sel_id_object').val());
                createObjectSelect('#auto_sel_object', objects, modal_btn_index === 2 ? selected : $('#auto_sel_object').val());
            }

            $('#lightstat_form [name=object_type]').change(function(){
                if ($(this).val() === 'manual') {
                    $('#auto_object_div').hide();
                    $('#manual_object_div').show();
                } else {
                    $('#manual_object_div').hide();
                    $('#auto_object_div').show();
                }
                return true;
            });



        });
    </script>
@endsection
