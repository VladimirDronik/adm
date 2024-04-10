@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление датчика движения', 'links' => [ route('motionsensors.index') => 'Датчики движения']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('motionsensors.index') }}" class="btn btn-success m-b-10 m-l-5">Список датчиков движения</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'motionsensors.store', 'method' => 'post', 'id' => 'motionsensor_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                        <div class="col-sm-12 pr-0 mt-4">
                            {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id'), false, false, [], null) }}

                            {{ Form::bs_autoselect('port_id', 'Порт:', [], old('port_id'), false, false, [], null) }}
                        </div>

                            <div class="row" id="select_methods_div">

                                <div class="col-sm-11 pr-0">
                                    <div style="height: 10px;">&nbsp;</div>
                                    <hr>
                                    <h4>Действие при нормальном режиме</h4>
                                    <div style="height: 20px;">&nbsp;</div>
                                </div>

                                <div class="col-sm-12 pr-0 mt-4">

                                    {{ Form::bs_autoselect('object_normal', 'Объект:', $objects, old('object'),
                              false, false, [],  null, 'Объект, методы которого интересуют') }}

                                    {{ Form::bs_autoselect('method_normal', 'Метод:', [], old('method_normal'),
                                false, false, [], null, 'Метод, который вызывается при срабатывании датчика в нормальном режиме') }}
                                </div>
                            </div>

                            <div class="form-group row" id="method_normal_params_div"
                                 @if(!old('method_normal')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_normal_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_normal_params_label" for="method_normal_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_normal_params" name="method_normal_params"
                                                   type="text" value="{{ old('method_normal_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="row" id="select_methods_div">

                                <div class="col-sm-11 pr-0">
                                    <div style="height: 10px;">&nbsp;</div>
                                    <hr>
                                    <h4>Действие при эко режиме</h4>
                                    <div style="height: 20px;">&nbsp;</div>
                                </div>

                                <div class="col-sm-12 pr-0 mt-4">

                                    {{ Form::bs_autoselect('object_eco', 'Объект:', $objects, old('object'),
                              false, false, [],  null, 'Объект, методы которого интересуют') }}

                                    {{ Form::bs_autoselect('method_eco', 'Метод:', [], old('method_eco'),
                                false, false, [], null, 'Метод, который вызывается при срабатывании датчика в эко режиме') }}

                                </div>
                            </div>


                            <div class="form-group row" id="method_eco_params_div"
                                 @if(!old('method_eco')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_eco_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_eco_params_label" for="method_eco_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_eco_params" name="method_eco_params"
                                                   type="text" value="{{ old('method_eco_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row" id="select_methods_div">

                                <div class="col-sm-11 pr-0">
                                    <div style="height: 10px;">&nbsp;</div>
                                    <hr>
                                    <h4>Действие при ночном режиме</h4>
                                    <div style="height: 20px;">&nbsp;</div>
                                </div>

                                <div class="col-sm-12 pr-0 mt-4">

                                    {{ Form::bs_autoselect('object_night', 'Объект:', $objects, old('object'),
                              false, false, [],  null, 'Объект, методы которого интересуют') }}

                                    {{ Form::bs_autoselect('method_night', 'Метод:', [], old('method_night'),
                                false, false, [], null, 'Метод, который вызывается при срабатывании датчика в ночном режиме') }}

                                </div>
                            </div>

                            <div class="form-group row" id="method_night_params_div"
                                 @if(!old('method_night')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_night_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_night_params_label" for="method_night_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_night_params" name="method_night_params"
                                                   type="text" value="{{ old('method_night_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row" id="select_methods_div">

                                <div class="col-sm-11 pr-0">
                                    <div style="height: 10px;">&nbsp;</div>
                                    <hr>
                                    <h4>Действие при утреннем режиме</h4>
                                    <div style="height: 20px;">&nbsp;</div>
                                </div>

                                <div class="col-sm-12 pr-0 mt-4">

                                    {{ Form::bs_autoselect('object_morning', 'Объект:', $objects, old('object'),
                              false, false, [],  null, 'Объект, методы которого интересуют') }}


                                    {{ Form::bs_autoselect('method_morning', 'Метод:', [], old('method_morning'),
                                        false, false, [], null, 'Метод, который вызывается при срабатывании датчика в утреннем режиме (сумерки)') }}

                                </div>
                            </div>


                            <div class="form-group row" id="method_morning_params_div"
                                 @if(!old('method_morning')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_morning_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_morning_params_label" for="method_morning_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_morning_params" name="method_morning_params"
                                                   type="text" value="{{ old('method_morning_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row" id="select_methods_div">

                                <div class="col-sm-11 pr-0">
                                    <div style="height: 10px;">&nbsp;</div>
                                    <hr>
                                    <h4>Действие при вечернем режиме</h4>
                                    <div style="height: 20px;">&nbsp;</div>
                                </div>

                                <div class="col-sm-12 pr-0 mt-4">

                                    {{ Form::bs_autoselect('object_evening', 'Объект:', $objects, old('object'),
                              false, false, [],  null, 'Объект, методы которого интересуют') }}


                                    {{ Form::bs_autoselect('method_evening', 'Метод:', [], old('method_evening'),
                               false, false, [], null, 'Метод, который вызывается при срабатывании датчика в вечернем режиме (сумерки)') }}

                                </div>
                            </div>


                            <div class="form-group row" id="method_evening_params_div"
                                 @if(!old('method_evening')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_evening_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_evening_params_label" for="method_evening_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_evening_params" name="method_evening_params"
                                                   type="text" value="{{ old('method_evening_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row" id="select_methods_div">

                                <div class="col-sm-11 pr-0">
                                    <div style="height: 10px;">&nbsp;</div>
                                    <hr>
                                    <h4>Действие в режиме охраны</h4>
                                    <div style="height: 20px;">&nbsp;</div>
                                </div>

                                <div class="col-sm-12 pr-0 mt-4">

                                    {{ Form::bs_autoselect('object_guard', 'Объект:', $objects, old('object'),
                              false, false, [],  null, 'Объект, методы которого интересуют') }}


                                    {{ Form::bs_autoselect('method_guard', 'Метод:', [], old('method_evening'),
                               false, false, [], null, 'Метод, который вызывается при срабатывании датчика в режиме охраны') }}

                                </div>
                            </div>


                            <div class="form-group row" id="method_guard_params_div"
                                 @if(!old('method_guard')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_guard_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_guard_params_label" for="method_guard_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_guard_params" name="method_guard_params"
                                                   type="text" value="{{ old('method_guard_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row" id="select_methods_div">

                                <div class="col-sm-11 pr-0">
                                    <div style="height: 10px;">&nbsp;</div>
                                    <hr>
                                    <h4>Действие при пороговом значении датчика освещенности</h4>
                                    <div style="height: 20px;">&nbsp;</div>
                                </div>

                                <div class="col-sm-12 pr-0 mt-4">

                                    {{ Form::bs_autoselect('lightstat', 'Датчик освещенности:', $lightstats, old('object'),
                              false, false, [],  null, 'Датчик освещенности, значение которого будем проверять') }}

                                    {{ Form::bs_radio('equality', 'Если значение датчика освещенности:', $equality, old('equality', -1)) }}

                                    {{ Form::bs_text('lightvalue', 'Значение освещенности:', null) }}

                                    {{ Form::bs_autoselect('object_light', 'Объект:', $objects, old('object'),
                              false, false, [],  null, 'Объект, методы которого интересуют') }}

                                    {{ Form::bs_autoselect('method_light', 'Метод:', [], old('method_evening'),
                               false, false, [], null, 'Метод, который вызывается при пороговом значнии датчика освещенности') }}

                                </div>
                            </div>

                            <div class="form-group row" id="method_light_params_div"
                                 @if(!old('method_light')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_light_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_light_params_label" for="method_light_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_light_params" name="method_light_params"
                                                   type="text" value="{{ old('method_light_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script src="{{ asset('ela/js/pagescripts/motionsensor.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        let methods = [];

        $(document).ready(function () {


            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object_normal").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_normal").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object_eco").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_eco").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object_night").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_night").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object_morning").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_morning").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object_evening").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_evening").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object_guard").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_guard").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object_light").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_light").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_lightstat").chosen({width:"100%", no_results_text: "Не найдено"});

            initMotionsensorForm('');


            $("#auto_sel_device_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                    success: function (data) {
                        createMethodSelect('#auto_sel_port_id', data.ports, -1);
                        $('#auto_sel_port_id').trigger("chosen:updated");
                    }
                });
            });

            $('#auto_sel_btn_id_object').click(function() {
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

            function createMethodSelect(target, options, selected) {
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
                const id = $('#auto_sel_id_object').val();
                if (id) {
                    selected = id;
                }
                createObjectSelect('#auto_sel_id_object', objects, selected);
            }

            $('#switch_form [name=object_type]').change(function(){
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
