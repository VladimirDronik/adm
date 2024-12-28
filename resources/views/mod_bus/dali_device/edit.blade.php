@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование устройства DALI № '. $daliDevice->id_object,
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

                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item"> <a class="nav-link @if($tab==1) active @endif"  data-toggle="tab" href="#dali_device_tab1"  role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Основное</span></a> </li>
                                <li class="nav-item"> <a class="nav-link @if($tab==2) active @endif"  data-toggle="tab" href="#dali_device_tab2"  role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Свойства</span></a> </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane p-20 @if($tab==1) active @endif" id="dali_device_tab1" role="tabpanel">
                                    @include('mod_bus/dali_device/edit_tabs/main')
                                </div>
                                <div class="tab-pane p-20 @if($tab==2) active @endif" id="dali_device_tab2" role="tabpanel">
                                    @include('mod_bus/dali_device/edit_tabs/prop')
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
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        const id_object = '{{ $daliDevice->id_object }}';
        const group_address = '{{ $daliDevice->address }}';
        const id = '{{ $daliDevice->id }}';

        $(document).ready(function () {
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_dali_device_object_id").chosen({width:"70%", no_results_text: "Не найдено"});

            $('#alice_checkbox').click(function(){
                if ($(this).is(':checked')){
                    $('#div_command').show(100);
                } else {
                    $('#div_command').hide(100);
                }
            });

            $('#addDaliDevice').click(function () {
                let dali_device_id_object = $("#auto_sel_dali_device_object_id").chosen().val();
                if (dali_device_id_object) {
                    $.ajax({
                        url: "{{ route('ajax.mod_bus.slavers.add_dali_device_to_group') }}",
                        data: {'_token': _token, 'dali_device_id_object': dali_device_id_object, 'group_id_object': id_object, 'group_address': group_address},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal(data.message ?? 'Ошибка добавления устройства в группу');
                                }
                            }
                    });
                } else {
                    showErrorModal('Сначала выберите устройство для добавления');
                }
            });

            $('.del_btn').click(function () {
                let del_dali_device_id_object = $(this).attr('data-id_object');
                if (del_dali_device_id_object) {
                    $.ajax({
                        url: "{{ route('ajax.mod_bus.slavers.remove_dali_device_from_group') }}",
                        data: {'_token': _token, 'dali_device_id_object': del_dali_device_id_object, 'group_id_object': id_object, 'group_address': group_address},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal(data.message ?? 'Ошибка удаления устройства из группы');
                                }
                            }
                    });
                }
            });

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
                var value = $('#dali_device_form [name=brightness]').val();

                if (value && value >= 0 && value <= 100) {
                    $.ajax({
                        url: '{{ route('ajax.mod_bus.slavers.set_brightness') }}',
                        data: {'_token': _token, 'id': id, 'brightness': value},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal(data.message ?? 'Ошибка установки яркости');
                                }
                            }
                    });
                } else {
                    showErrorModal('Значение яркости должно быть целым числом в диапазоне от 0 до 100');
                }
            });

            $('#setCct').click(function () {
                var value = $('#dali_device_form [name=cct]').val();

                if (value && value >= 1000 && value <= 10000) {
                    $.ajax({
                        url: '{{ route('ajax.mod_bus.slavers.set_cct') }}',
                        data: {'_token': _token, 'id': id, 'cct': value},
                            success: function (data) {
                                if (data.result) {
                                    window.location.reload();
                                } else {
                                    showErrorModal(data.message ?? 'Ошибка установки цветовой температуры');
                                }
                            }
                    });
                } else {
                    showErrorModal('Значение цветовой температуры должно быть целым числом в диапазоне от 1000 до 10000');
                }
            });
        });
    </script>
@endsection
