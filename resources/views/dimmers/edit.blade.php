@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование диммера № '. $dimmer->object['id'] . ' «' . $dimmer->name .'»',
        'links' => [ route('dimmers.index') => 'Диммеры'],
        'last_link' => 'Редактирование диммера'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('dimmers.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок диммеров</a>
                        <a href="{{ route('dimmers.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить диммер</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($dimmer, ['route' => ['dimmers.update', $dimmer->id], 'id' => 'dimmer_form',
                            'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}


                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item"> <a class="nav-link @if($tab==1) active @endif"  data-toggle="tab" href="#portstab1"  role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Основное</span></a> </li>
                            <li class="nav-item"> <a class="nav-link @if($tab==2) active @endif"  data-toggle="tab" href="#portstab2"  role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Свойства</span></a> </li>
                            <li class="nav-item"> <a class="nav-link @if($tab==4) active @endif"  data-toggle="tab" href="#portstab4"  role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">События</span></a> </li>
                            <li class="nav-item"> <a class="nav-link @if($tab==3) active @endif"  data-toggle="tab" href="#portstab3"  role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Методы</span></a> </li>
                            <li class="nav-item"> <a class="nav-link @if($tab==5) active @endif"  data-toggle="tab" href="#portstab5"  role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Планировщик</span></a> </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane p-20 @if($tab==1) active @endif" id="portstab1" role="tabpanel">
                                @include('dimmers/edit_tabs/main')
                            </div>
                            <div class="tab-pane p-20 @if($tab==2) active @endif" id="portstab2" role="tabpanel">
                                @include('dimmers/edit_tabs/prop')
                            </div>
                            <div class="tab-pane p-20 @if($tab==4) active @endif" id="portstab4" role="tabpanel">
                                @include('dimmers/edit_tabs/events')
                                @include('objects.events', ['object' => $dimmer->object])
                            </div>
                            <div class="tab-pane p-20 @if($tab==3) active @endif" id="portstab3" role="tabpanel">
                                @include('objects.methods', ['object' => $dimmer->object])
                            </div>
                            <div class="tab-pane p-20 @if($tab==5) active @endif" id="portstab5" role="tabpanel">
                                @include('objects.sheduler', ['object' => $dimmer->object])
                            </div>
                        </div>
                        <input type="hidden" id="tabs-sel" value="{{ $tab }}">
                        <input type="hidden" id="event_idobject" name="event_idobject" value="{{ $dimmer->iobject['id'] }}">



                    </div>
                    {{ Form::bs_submit_btn() }}

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
    <script src="{{ asset('ela/js/pagescripts/dimmer.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/service.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ optional($dimmer->object)->id }}';
        const is_super_admin = {{ user()->is_super_admin ? 1 : 0 }};
        const url_device = '{{ route('ajax.devices.type_controller') }}';
        let del_id;

        $(document).ready(function () {
            initDimmerForm();
            serviceInit();

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_device_id").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': object_id, 'status': 'OUT'},
                    success: function (data) {
                        methods = data.ports;
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
    </script>
@endsection
