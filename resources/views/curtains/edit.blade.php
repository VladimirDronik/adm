@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Редактирование: '.$curtain->rus_type.' № '. $curtain->id_object . ' «' . $curtain->name .'»',
        'links' => [ route('curtains.index') => 'Шторы, жалюзи, рольставни'],
        'last_link' => 'Редактирование: '.$curtain->rus_type
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('curtains.index') }}" class="btn btn-success m-b-10 m-l-5">Список штор</a>
                        <a href="{{ route('curtains.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить штору</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($curtain, ['route' => ['curtains.update', $curtain->id], 'id' => 'curtain_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if($tab==1) active @endif"  data-toggle="tab" href="#curtainstab1"  role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Основное</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if($tab==2) active @endif"  data-toggle="tab" href="#curtainstab2"  role="tab">
                                    <span class="hidden-sm-up"><i class="ti-user"></i></span>
                                    <span class="hidden-xs-down">Свойства</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if($tab==4) active @endif"  data-toggle="tab" href="#curtainstab4"  role="tab">
                                    <span class="hidden-sm-up"><i class="ti-user"></i></span>
                                    <span class="hidden-xs-down">События</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if($tab==3) active @endif"  data-toggle="tab" href="#curtainstab3"  role="tab">
                                    <span class="hidden-sm-up"><i class="ti-user"></i></span>
                                    <span class="hidden-xs-down">Методы</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if($tab==5) active @endif"  data-toggle="tab" href="#curtainstab5"  role="tab">
                                    <span class="hidden-sm-up"><i class="ti-user"></i></span>
                                    <span class="hidden-xs-down">Планировщик</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if($tab == 6) active @endif" data-toggle="tab" href="#curtainstab6" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Управление</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane p-20 @if($tab==1) active @endif" id="curtainstab1" role="tabpanel">
                                @include('curtains/edit_tabs/main')
                            </div>
                            <div class="tab-pane p-20 @if($tab==2) active @endif" id="curtainstab2" role="tabpanel">
                                @include('curtains/edit_tabs/prop')
                            </div>
                            <div class="tab-pane p-20 @if($tab==4) active @endif" id="curtainstab4" role="tabpanel">
                                @include('curtains/edit_tabs/events')
                            </div>
                            <div class="tab-pane p-20 @if($tab==3) active @endif" id="curtainstab3" role="tabpanel">
                                @include('objects.methods', ['object' => $curtain->object])
                            </div>
                            <div class="tab-pane p-20 @if($tab==5) active @endif" id="curtainstab5" role="tabpanel">
                                @include('objects.sheduler', ['object' => $curtain->object])
                            </div>
                            <div class="tab-pane p-20 @if($tab == 6) active @endif" id="curtainstab6" role="tabpanel">
                                @include('curtains.edit_tabs.control')
                            </div>
                        </div>
                        <input type="hidden" id="tabs-sel" value="{{ $tab }}">
                        <input type="hidden" id="event_idobject" name="event_idobject" value="{{ $curtain->id_object }}">

                    {{ Form::bs_submit_btn() }}


                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
                <button type="button" id="init_method_btn" style="display: none;" data-toggle="modal" data-target="#method_modal">&nbsp;</button>
                <button type="button" id="init_message_btn" style="display: none;" data-toggle="modal" data-target="#message_modal"> </button>

            </div>
        </div>
    </div>
    @include('objects.message_modal')
    @include('objects.method_modal')

    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/messages.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/events.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/service.js') }}"></script>
    <script>
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const store_message_url = '{{ route('ajax.messages.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const del_message_url = '{{ route('ajax.messages.delete') }}';
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const is_super_admin = {{ user()->is_super_admin ? 1 : 0 }};
        const set_status_url = "{{ route('ajax.curtains.set.status') }}";
        const set_percent_url = "{{ route('ajax.curtains.set.percent') }}";
        const stop_url = "{{ route('ajax.curtains.stop') }}";
        const id_object = '{{ $curtain->id_object }}';
        let del_id;
        $(document).ready(function () {
            $('#del_modal_btn').click(clickDelBtn);
            serviceInit();
            initActionModal();

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_bus_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id_open").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id_close").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_device_id").chosen().change(function() {
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': $(this).val(), 'status': 'OUT', 'type': 'switch, socket'},
                    success: function (data) {
                        methods = data.ports;
                        createPortSelect('#auto_sel_port_id_open', data.ports, -1);
                        $('#auto_sel_port_id_open').trigger("chosen:updated");
                        createPortSelect('#auto_sel_port_id_close', data.ports, -1);
                        $('#auto_sel_port_id_close').trigger("chosen:updated");
                    }
                });
            });

            //messages
            $('#apply_message_btn').click(clickApplyMessageBtn);
            // edit messages method
            $('body').on('click', '.edit_message_btn', clickEditMessageBtn);
            //delete message
            $('body').on('click', '.del_message_btn', function() {
                del_message = $(this).attr('data-method');
                $('#del_modal_body').text('Удалить уведомление ?');
                $('#del_init_btn').click();
            });
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

        $('#openBtn').click(function() {
            $.ajax({
                url: set_status_url,
                data: {
                    '_token': _token,
                    'id_object': id_object,
                    'status': 'open'
                },
            });
        });

        $('#closeBtn').click(function() {
            $.ajax({
                url: set_status_url,
                data: {
                    '_token': _token,
                    'id_object': id_object,
                    'status': 'close'
                },
            });
        });

        $('#setPercentBtn').click(function() {
            var selectedOption = $('#curtain_form input[name=percent]').val();

            if (selectedOption === '' || !Number.isInteger(Number(selectedOption))) {
                showErrorModal('Процент открытия должен быть целым числом');
            } else if (Number(selectedOption) < 0 || Number(selectedOption) > 100) {
                showErrorModal('Процент открытия должен быть в диапазоне от 0 до 100');
            } else {
                $.ajax({
                    url: set_percent_url,
                    data: {
                        '_token': _token,
                        'id_object': id_object,
                        'percent': selectedOption
                    },
                });
            }
        });

        $('#stopBtn').click(function() {
            $.ajax({
                url: stop_url,
                data: {
                    '_token': _token,
                    'id_object': id_object
                },
            });
        });
    </script>
@endsection