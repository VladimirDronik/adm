@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Добавление нового элемента',
        'links' => [ route('elements.index') => 'Элементы']
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('pages.edit', $pageId) }}" class="btn btn-success m-b-10 m-l-5">Список элементов</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'elements.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered', 'id' => 'elements_form']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}
                            {{ Form::bs_radio('type', 'Тип элемента*:', $types, old('type'), ['required' => true]) }}
                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                            <input name="page" type="hidden" value="{{ $pageId }}">

                            {{ Form::bs_radio('position', 'Позиция элемента*:', [1 => '1', 2 => '2'], old('type'), ['required' => true]) }}

                            <div id="parent_div">
                                {{ Form::bs_autoselect('parent', 'Родительский эелемент:', $parents, old('parent'), false, false, [], null) }}
                            </div>

                            <input name="value" type="hidden" value="12">

                            <div id="handle_div">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <div style="height: 40px;">&nbsp;</div>

                                {{ Form::bs_autoselect('id_object', 'Привязанный объект:',  $objects, old('id_object'), false, false, [], null, 'Объект, который будет отдавать данные этому элементу') }}

                                <div id="methods_div"  style="display: none">
                                    {{ Form::bs_autoselect('method', 'Метод:', [], old('method'), false, false, [], null, 'Метод объекта при нажатии на элемент') }}
                                </div>

                                {{ Form::bs_autoselect('handle', 'Идентификатор:', [], old('handle'), false, false, [], null, 'Идентификатор свойства объекта') }}
                            </div>

                            <div id="settings_div">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <div style="height: 40px;">&nbsp;</div>

                                {{ Form::bs_checkbox('settings', 'Настраиваемый элемент:', old('settings', $settings)) }}
                            </div>
                        </div>
                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
            </div>
        </div>
    </div>
    @include('components.load_modal')
    @include('elements.name_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/element.js') }}"></script>
    <script>
        const url_methods_and_handle = '{{ route('ajax.objects.methodsAndHandles') }}';

        $(document).ready(function () {
            $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_handle").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_id_object").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods_and_handle,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;

                        createMethodSelect('#auto_sel_method', data.methods, -1);
                        $('#auto_sel_method').trigger("chosen:updated");

                        createHandleSelect('#auto_sel_handle', data.handles, -1);
                        $('#auto_sel_handle').trigger("chosen:updated");
                    }
                });
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

            function createHandleSelect(target, options, selected) {
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

            $('#elements_form [name=type]').change(function() {
                if ($(this).val() === 'label') {
                    $('#handle_div').show();
                    $('#methods_div').hide();
                    $('#parent_div').show();
                } else if ($(this).val() === 'switch') {
                    $('#handle_div').show();
                    $('#methods_div').show();
                    $('#parent_div').show();
                } else {
                    $('#handle_div').hide();
                    $('#methods_div').hide();
                    $('#parent_div').hide();
                }
            });
        });
    </script>
@endsection
