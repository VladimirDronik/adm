@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Редактирование кондиционера № '. $conditioner->id_object . ' «' . $conditioner->name .'»',
        'links' => [route('conditioners.index') => 'Кондиционеры'],
        'last_link' => 'Редактирование кондиционера',
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('conditioners.index') }}" class="btn btn-success m-b-10 m-l-5">Список кондиционеров</a>
                        <a href="{{ route('conditioners.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить кондиционер</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($conditioner, ['route' => ['conditioners.update', $conditioner->id], 'id' => 'conditioner_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}
                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link @if($tab == 'main') active @endif" data-toggle="tab" href="#conditionerstab1" role="tab">
                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                        <span class="hidden-xs-down">Основное</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if($tab == 'control') active @endif" data-toggle="tab" href="#conditionerstab2" role="tab">
                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                        <span class="hidden-xs-down">Управление</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane p-20 @if($tab == 'main') active @endif" id="conditionerstab1" role="tabpanel">
                                    @include('conditioners/edit_tabs/main')
                                </div>
                                <div class="tab-pane p-20 @if($tab == 'control') active @endif" id="conditionerstab2" role="tabpanel">
                                    @include('conditioners/edit_tabs/control')
                                </div>
                            </div>
                        </div>
                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
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
        const set_status_url = "{{ route('ajax.conditioners.set.status') }}";
        const set_temp_url = "{{ route('ajax.conditioners.set.temp') }}";
        const set_mode_url = "{{ route('ajax.conditioners.set.mode') }}";
        const set_fan_url = "{{ route('ajax.conditioners.set.fan') }}";
        const set_vdir_url = "{{ route('ajax.conditioners.set.vdir') }}";
        const set_hdir_url = "{{ route('ajax.conditioners.set.hdir') }}";
        const id_object = '{{ $conditioner->id_object }}';
        const tempMin = "{{ $tempSettings['min'] ?? 0 }}";
        const tempMax = "{{ $tempSettings['max'] ?? 30 }}";

        $(document).ready(function () {
            $("#auto_sel_modbus_slaver_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_id_room").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#conditioner_form input[name=status]').change(function() {
                var options = $('#conditioner_form input[name=status]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                $.ajax({
                    url: set_status_url,
                    data: {
                        '_token': _token,
                        'id_object': id_object,
                        'status': selectedOption
                    },
                });
            });

            $('#setTempBtn').click(function() {
                var selectedOption = Number($('#conditioner_form input[name=temp]').val());

                if (!selectedOption || !Number.isInteger(selectedOption)) {
                    showErrorModal('Температура должна быть целым числом');
                } else if (selectedOption < Number(tempMin) || selectedOption > Number(tempMax)) {
                    showErrorModal('Температура должна быть в диапазоне от ' + tempMin + ' до ' + tempMax);
                } else {
                    $.ajax({
                        url: set_temp_url,
                        data: {
                            '_token': _token,
                            'id_object': id_object,
                            'temp': selectedOption
                        },
                    });
                }
            });

            $('#conditioner_form input[name=mode]').change(function() {
                var options = $('#conditioner_form input[name=mode]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                $.ajax({
                    url: set_mode_url,
                    data: {
                        '_token': _token,
                        'id_object': id_object,
                        'mode': selectedOption
                    },
                });
            });

            $('#conditioner_form input[name=fan]').change(function() {
                var options = $('#conditioner_form input[name=fan]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                $.ajax({
                    url: set_fan_url,
                    data: {
                        '_token': _token,
                        'id_object': id_object,
                        'fan': selectedOption
                    },
                });
            });

            $('#conditioner_form input[name=vdir]').change(function() {
                var options = $('#conditioner_form input[name=vdir]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                $.ajax({
                    url: set_vdir_url,
                    data: {
                        '_token': _token,
                        'id_object': id_object,
                        'vdir': selectedOption
                    },
                });
            });

            $('#conditioner_form input[name=hdir]').change(function() {
                var options = $('#conditioner_form input[name=hdir]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                $.ajax({
                    url: set_hdir_url,
                    data: {
                        '_token': _token,
                        'id_object': id_object,
                        'hdir': selectedOption
                    },
                });
            });
        });
    </script>
@endsection
