@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление дверного замка', 'links' => [ route('locks.index') => 'Дверные замки']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('locks.index') }}" class="btn btn-success m-b-10 m-l-5">Список дверных замков</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'locks.store', 'method' => 'post', 'id' => 'lock_form',
                            'class' => 'form-horizontal form-bordered']) !!}
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
                                @include('locks/create_tabs/main')
                            </div>
                            <div class="tab-pane p-20 @if($tab==2) active @endif" id="portstab2" role="tabpanel">
                                <br>Свойства будут доступны после сохранения объекта
                            </div>
                            <div class="tab-pane p-20 @if($tab==4) active @endif" id="portstab4" role="tabpanel">
                                <br>События будут доступны после сохранения объекта
                            </div>
                            <div class="tab-pane p-20 @if($tab==3) active @endif" id="portstab3" role="tabpanel">
                                <br>Методы будут доступны после сохранения объекта
                            </div>
                            <div class="tab-pane p-20 @if($tab==5) active @endif" id="portstab5" role="tabpanel">
                                <br>Планировщик будет доступен после сохранения объекта
                            </div>
                        </div>
                        <input type="hidden" id="tabs-sel" value="{{ $tab }}">


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
    <script src="{{ asset('ela/js/pagescripts/lock.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';


        $(document).ready(function () {


            initLockForm();


            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id_open").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id_close").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_hitepro_device_open").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_hitepro_device_close").chosen({width:"100%", no_results_text: "Не найдено"});


            $("#auto_sel_device_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'out', 'type': 'switch, socket'},
                    success: function (data) {
                        if (data.type_device == 'Hite-pro') {
                            $('#port_id_div').hide();
                            $('#hitepro_devices_div').show();
                            createPortSelect('#auto_sel_hitepro_device_open', data.hiteProDevices, -1);
                            createPortSelect('#auto_sel_hitepro_device_close', data.hiteProDevices, -1);
                            $('#auto_sel_hitepro_device_open').trigger("chosen:updated");
                            $('#auto_sel_hitepro_device_close').trigger("chosen:updated");
                            $('#auto_sel_port_id_open option:first').prop('selected', true); //Выбираем, что бы валидация не ругалась
                            $('#auto_sel_port_id_close option:first').prop('selected', true); //Выбираем, что бы валидация не ругалась
                            $('#place').val('Hite-pro');
                        }
                        else {
                            $('#port_id_div').show();
                            $('#hitepro_devices_div').hide();
                            createPortSelect('#auto_sel_port_id_open', data.ports, -1);
                            createPortSelect('#auto_sel_port_id_close', data.ports, -1);
                            $('#auto_sel_port_id_open').trigger("chosen:updated");
                            $('#auto_sel_port_id_close').trigger("chosen:updated");
                            $('#auto_sel_hitepro_device_open option:first').prop('selected', true); //Выбираем, что бы валидация не ругалась
                            $('#auto_sel_hitepro_device_close option:first').prop('selected', true); //Выбираем, что бы валидация не ругалась
                            $('#place').val('port');
                        }
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





        });
    </script>
@endsection
