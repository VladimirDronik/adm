@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление кондиционера', 'links' => [ route('conditioners.index') => 'Кондиционеры']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('conditioners.index') }}" class="btn btn-success m-b-10 m-l-5">Кондиционеры</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'conditioners.store', 'method' => 'post', 'id' => 'conditioner_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        <div class="col-sm-12 pr-0 mt-4">
                            {{ Form::bs_autoselect('vendor_id', 'Производитель*:', $vendors, old('vendor_id'), false, false, ['required' => true], null) }}

                            <div id='model_id_div' style="display: none">
                                {{ Form::bs_autoselect('model_id', 'Модель*:', [], old('model_id'), false, false, ['required' => true], null) }}
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix">
                                <strong>Объект*:</strong>
                            </label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-sm-11 pr-0">
                                        <p>
                                            При создании кондиционера будет создан объект с таким же названием.
                                        </p>
                                    </div>
                                    <div class="col-sm-12 pr-0 mt-4">
                                        {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id'), false, false, ['required' => true], null) }}

                                        {{ Form::bs_text('wb_mir', 'Адрес WB-MIR:', null, ['required' => true]) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ Form::bs_autoselect('room_id', 'Помещение*:', $rooms, old('room_id'), false, false, ['required' => true], null) }}

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
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/conditioner.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const url_models = '{{ route('ajax.conditioners.models') }}';

        $(document).ready(function () {
            initConditionerForm();

            $("#auto_sel_vendor_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_model_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_room_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_vendor_id").chosen().change(function() {
                let vendor_id = $(this).val();
                $.ajax({
                    url: url_models,
                    data: {'_token': _token, 'vendor_id': vendor_id},
                    success: function (data) {
                        $('#model_id_div').show();
                        $('#place').val('model');
                        createModelSelect('#auto_sel_model_id', data.models, -1);
                        $('#auto_sel_model_id').trigger("chosen:updated");
                    }
                });
            });

            function createModelSelect(target, options, selected) {
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

        });
    </script>
@endsection
