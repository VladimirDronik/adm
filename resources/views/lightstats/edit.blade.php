@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование светостата № '. $lightstat->iobject['id'],
        'links' => [ route('lightstats.index') => 'Светостаты'],
        'last_link' => 'Редактирование светостата'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('lightstats.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок светостатов</a>
                        <a href="{{ route('lightstats.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить светостат</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($lightstat, ['route' => ['lightstats.update', $lightstat->id],
                            'id' => 'lightstat_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_simple_text('ID объекта:', $lightstat->iobject['id']) }}
                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                        <div class="form-group row ">

                            @if(($lightstat->iobject && $lightstat->iobject->is_system) || !$can['devices.show-object'])
                                <div class="form-group row">
                                                <label class="control-label text-right col-md-3 label-fix" for="">
                                                    Объект термостата:
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="mt-2">
                                                        <a class="a-color" href="{{ route('objects.edit', [$lightstat->id_object]) }}">
                                                            {{ $lightstat->iobject->name }} @if($lightstat->iobject && $lightstat->iobject->is_system) (системный) @endif </a>
                                                    </div>

                                            <input type="hidden" name="id_object" value="{{ $lightstat->id_object }}">
                                        @else
                                            {{ Form::bs_autoselect_and_btn('id_object', 'Объект термостата*:', $objects, old('id_object', $lightstat->id_object),
                                                false, false, ['required' => true]) }}
                                        @endif


                                        <div class="row" id="auto_object_div">

                                            <div class="col-sm-12 pr-0 mt-4">
                                                <div class="btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-success btn-sm @if($lightstat->placetype == 'port') active @endif">
                                                        <input type="radio" name="placetype_radio" autocomplete="off"  value="port"> На отдельном порту
                                                    </label>

                                                    <label class="btn btn-success btn-sm @if($lightstat->placetype == 'usensor') active @endif">
                                                        <input type="radio" name="placetype_radio" autocomplete="off" value="usensor">  В составе унив. датчика
                                                    </label>

                                                    <input type="hidden" id="placetype" name="placetype" value="{{$lightstat->placetype}}">

                                                </div>
                                            </div>

                                            <div class="col-sm-12 pr-0 mt-4" id="single_port_div" @if(($lightstat->placetype != 'port') && ($lightstat->placetype != '1wbus') )  style="display: none;" @endif>
                                                {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', is_null($deviceId) ? 0 : $deviceId),
                                                   false, false, [], null) }}

                                                {{ Form::bs_autoselect('port_SDA', 'Порт SDA:', $portsSDA, old('port_SDA', is_null($port_SCL) ? 0 : $port_SCL),
                                                    false, false, [], null) }}

                                                {{ Form::bs_autoselect('port_SCL', 'Порт SCL:', $portsSCL, old('port_SCL', is_null($port_SDA) ? 0 : $port_SDA),
                                                   false, false, [], null) }}

                                            </div>


                                            <div class="col-sm-12 pr-0 mt-4" id="usensor_div"  @if($lightstat->placetype != 'usensor') style="display: none;" @endif>
                                                {{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id', is_null($lightstat->usensor_id) ? 0 : $lightstat->usensor_id),
                                                   false, false, [], null) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div style="height: 10px;">&nbsp;</div>
                        <hr>
                        <div style="height: 40px;">&nbsp;</div>



                        {{ Form::bs_number('optimal', 'Оптимальная освещенность*:', null, ['min' => 0, 'max' => 54612, 'required' => true],
                            'Освещенность, которая должна быть в помещении') }}
                        {{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', $lightstat->gisteresis), ['min' => 0, 'max' => 1000, 'required' => true]) }}
                        {{ Form::bs_radio('mode', 'Режим*:', $types, old('mode', $lightstat->mode), ['required' => true]) }}

                        {{ Form::bs_number('min_threshold', 'Минимальная освещенность*:', old('min_threshold', $lightstat->min_threshold), ['min' => 0, 'max' => 0, 'required' => true],
                            '') }}
                        {{ Form::bs_number('max_threshold', 'Максимальная освещенность*:', old('max_threshold', $lightstat->max_threshold), ['min' => 0, 'max' => 54612, 'required' => true],
                            '') }}
                        {{ Form::bs_number('min_alarm', 'Мин. аварийная освещенность*:', old('min_alarm', $lightstat->min_alarm), ['min' => 0, 'max' => 54612, 'required' => true],
                            '') }}
                        {{ Form::bs_number('max_alarm', 'Макс. аварийная освещенность*:', old('max_alarm', $lightstat->max_alarm), ['min' => 0, 'max' => 54612, 'required' => true],
                            '') }}

                        <div style="height: 10px;">&nbsp;</div>
                        <hr>
                        <div style="height: 40px;">&nbsp;</div>

                        {{ Form::bs_autoselect_and_btn('object', 'Объект влияния:', $objects, old('object', $lightstat->object),
                            false, false, [], '', '', null, 'Объект, у которого меняем состояние', 3, $can['devices.show-object']) }}

                        {{ Form::bs_autoselect('method_on', 'Метод при включении:', $methods, old('method_on', $lightstat->method_on),
                            false, false, [], null, 'Метод объекта влияния при срабатывании светостата на включение') }}

                        <div class="form-group row" id="method_on_params_div"
                             @if(is_null($lightstat->method_on_params) && !old('method_on')) style="display: none;" @endif>
                            <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_on_params"></label>
                            <div class="col-md-9 pr-0">
                                <div class="form-group row ">
                                    <label class="control-label text-right col-md-6 label-fix" id="method_on_params_label" for="method_on_params">
                                        {{ optional($lightstat->emethod_on)->params }}*:</label>
                                    <div class="col-md-6">
                                        <input class="form-control" autocomplete="off" id="method_on_params" name="method_on_params"
                                               type="text" value="{{ old('method_on_params', $lightstat->method_on_params) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ Form::bs_autoselect('method_off', 'Метод при выключении:', $methods, old('method_off', $lightstat->method_off),
                            false, false, [], null, 'Метод объекта влияния при срабатывании светостата на выключение') }}

                        <div class="form-group row" id="method_off_params_div"
                             @if(is_null($lightstat->method_off_params) && !old('method_off')) style="display: none;" @endif>
                            <label class="control-label text-right col-md-3 label-fix" for="method_off_params"></label>
                            <div class="col-md-9 pr-0">
                                <div class="form-group row ">
                                    <label class="control-label text-right col-md-6 label-fix" id="method_off_params_label" for="method_off_params">
                                        {{ optional($lightstat->emethod_off)->params }}*:
                                    </label>
                                    <div class="col-md-6">
                                        <input class="form-control" autocomplete="off" id="method_off_params" name="method_off_params"
                                               type="text" value="{{ old('method_off_params', $lightstat->method_off_params) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', is_null($lightstat->room) ? 0 : $lightstat->room ), false, false) }}

                    </div>
                    {{ Form::bs_submit_btn() }}

                    @include('objects.methods', ['object' => $lightstat->iobject])
                    @include('objects.events', ['object' => $lightstat->iobject])

                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
                <button type="button" id="init_method_btn" style="display: none;" data-toggle="modal" data-target="#method_modal">&nbsp;</button>
            </div>
        </div>
    </div>
    @include('objects.method_modal')

    @include('components.info_modal')
    @include('components.del_modal')
    @include('components.create_object_modal', compact('object_types'))
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/lightstat.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script>
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ optional($lightstat->iobject)->id }}';
        const is_super_admin = {{ user()->is_super_admin ? 1 : 0 }};
        let del_id;
        let modal_btn_index = -1;
        let methods = [];

        $(document).ready(function () {

            initLightstatForm();
            initMethodsVar({{ optional($lightstat->eobject)->id }});

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_SDA").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_SCL").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_usensor_id").chosen({width:"100%", no_results_text: "Не найдено"});



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

            $("#auto_sel_device_id").chosen().change(function() {

                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'I2C'},
                    success: function (data) {
                        createMethodSelect('#auto_sel_port_SDA', data.ports, -1);
                        $('#auto_sel_port_SDA').trigger("chosen:updated");
                        createMethodSelect('#auto_sel_port_SCL', data.ports, -1);
                        $('#auto_sel_port_SCL').trigger("chosen:updated");
                    }
                });
            });

           // $("#auto_sel_port_SDA").click(function() {
             //   alert();
                /*
                let device_id = $("#auto_sel_device_id").val();
                alert(device_id);
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'in'},
                    success: function (data) {
                        createMethodSelect('#auto_sel_port_id', data.ports, -1);
                        $('#auto_sel_port_id').trigger("chosen:updated");
                    }

                });
                 */
            //});

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

                createObjectSelect('#auto_sel_id_object', objects,
                    modal_btn_index === 1 ? selected : $('#auto_sel_id_object').val());
                createObjectSelect('#auto_sel_object', objects,
                    modal_btn_index === 2 ? selected : $('#auto_sel_object').val());
            }

            // methods

            const cancel_btn = $('#cancel_btn');

            $('#add_btn').click(showAddModal);

            $('#apply_btn').click(clickApplyBtn);

            // edit method
            $('body').on('click', '.edit_btn', clickEditBtn);

            // change easy/script/none in modal
            $('input[type=radio][name=actions]').change(changeRadioActions);

            // delete method
            $('body').on('click', '.del_btn', function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить метод «'+$(this).attr('data-name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(clickDelBtn);
        });


        $('#lightstat_form [name=placetype_radio]').change(function(){
            if ($(this).val() === 'port') {
                $('#usensor_div').hide();
                $('#single_port_div').show();
                $('#placetype').val('port');
            } else {
                $('#usensor_div').show();
                $('#single_port_div').hide();
                $('#placetype').val('usensor');
            }

            return true;
        });


    </script>
@endsection
