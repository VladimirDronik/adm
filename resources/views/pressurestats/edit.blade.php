@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Редактирование датчика освещенности № '. $pressurestat->id_object,
        'links' => [ route('pressurestats.index') => 'Датчики освещенности'],
        'last_link' => 'Редактирование датчика освещенности'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('pressurestats.index') }}" class="btn btn-success m-b-10 m-l-5">Список датчиков давления</a>
                        <a href="{{ route('pressurestats.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить датчик давления</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($pressurestat, ['route' => ['pressurestats.update', $pressurestat->id], 'id' => 'pressurestat_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
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
                                    @include('pressurestats/edit_tabs/main')
                                </div>
                                <div class="tab-pane p-20 @if($tab==2) active @endif" id="portstab2" role="tabpanel">
                                    @include('pressurestats/edit_tabs/prop')
                                </div>
                                <div class="tab-pane p-20 @if($tab==4) active @endif" id="portstab4" role="tabpanel">
                                    @include('pressurestats/edit_tabs/events')
                                    @include('objects.events', ['object' => $pressurestat->relatedObject])
                                </div>
                                <div class="tab-pane p-20 @if($tab==3) active @endif" id="portstab3" role="tabpanel">
                                    @include('objects.methods', ['object' => $pressurestat->relatedObject])
                                </div>
                                <div class="tab-pane p-20 @if($tab==5) active @endif" id="portstab5" role="tabpanel">
                                    @include('objects.sheduler', ['object' => $pressurestat->relatedObject])
                                </div>
                            </div>
                        </div>
                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
                <button type="button" id="init_method_btn" style="display: none;" data-toggle="modal" data-target="#method_modal">&nbsp;</button>
                <button type="button" id="init_message_btn" style="display: none;" data-toggle="modal" data-target="#message_modal">
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
    <script src="{{ asset('ela/js/pagescripts/events.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/messages.js') }}"></script>
    <script>
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const store_message_url = '{{ route('ajax.messages.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const del_message_url = '{{ route('ajax.messages.delete') }}';;
        const sub_data_url = '{{ route('ajax.load.data') }}';
        let del_id;
        let methods = [];
        let object_id = "{{ $pressurestat->id_object }}";
        const is_super_admin = "{{ user()->is_super_admin ? 1 : 0 }}";
        let del_message;

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

        function hideParamsFields(id) {
            $('#pressurestat_form #'+id+'_div').hide();
            $('#pressurestat_form #'+id).val('');
        }

        function showParamsFields(id, params) {
            $('#pressurestat_form #'+id+'_label').text(params+'*:');
            $('#pressurestat_form #'+id).val('');
            $('#pressurestat_form #'+id+'_div').show();
        }

        function getMethodParams(methodId) {
            for (let i = 0; i < methods.length; i++) {
                if (methods[i].id == methodId) {
                    return methods[i].params ? methods[i].params : '';
                }
            }

            return '';
        }

        function initMethodsVar(object_id) {
            if (object_id) {
                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                    }
                });
            }
        }

        $(document).ready(function () {
            $('#del_modal_btn').click(clickDelBtn);
            initActionModal();
            initMethodsVar("{{ $pressurestat->object }}");

            $("#auto_sel_usensor_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_on").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_off").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('method_on_params');
                hideParamsFields('method_off_params');
                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_on', data.methods, -1);
                        $('#auto_sel_method_on').trigger("chosen:updated");
                        createMethodSelect('#auto_sel_method_off', data.methods, -1);
                        $('#auto_sel_method_off').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_method_on").chosen().change(function() {
                const params = getMethodParams($(this).val());

                if (params === '') {
                    hideParamsFields('method_on_params');
                } else {
                    showParamsFields('method_on_params', params);
                }
            });

            $("#auto_sel_method_off").chosen().change(function() {
                const params = getMethodParams($(this).val());

                if (params === '') {
                    hideParamsFields('method_off_params');
                } else {
                    showParamsFields('method_off_params', params);
                }
            });

            $('#pressurestat_form input[name=type_sensor]').change(function() {
                var options = $('#pressurestat_form input[name=type_sensor]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                if (selectedOption == 'bmx280') {
                    $('#pressurestat_form input[name=optimal]').val(760);
                    $('#pressurestat_form input[name=gisteresis]').val(5);
                    $('#pressurestat_form input[name=min_alarm]').val(600);
                    $('#pressurestat_form input[name=max_alarm]').val(820);
                } else {
                    $('#pressurestat_form input[name=optimal]').val(2000);
                    $('#pressurestat_form input[name=gisteresis]').val(100);
                    $('#pressurestat_form input[name=min_alarm]').val(0);
                    $('#pressurestat_form input[name=max_alarm]').val(10000);
                }
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
    </script>
@endsection
