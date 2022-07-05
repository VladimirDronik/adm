@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление нового элемента', 'links' => [ route('elements.index') => 'Элементы']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('pages.edit', $element->page) }}" class="btn btn-success m-b-10 m-l-5">Список элементов</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($element, ['route' => ['elements.update', $element->id], 'id' => 'elements_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">

                            {{ Form::bs_alert() }}
                            {{ Form::bs_radio('type', 'Тип элемента*:', $types, $element->type, ['required' => true]) }}
                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                            <input name="page" type="hidden" value="{{ $element->page }}">

                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix" for="id_object">
                                    Изображение:
                                </label>

                                <a data-toggle="modal" data-target="#selectImage" class="btn btn-default btn-sm m-b-5"
                                   onclick="updateImage(0, false);">
                                     <img src="{{ asset('ela/images/views_items/'.$element->image) }}"  id="imageElement"
                                                  widtth="50px" height="50px">
                                </a>

                            </div>

                            <input type="hidden" name="image" id="image">

                            {{ Form::bs_radio('position', 'Позиция элемента*:', [1 => '1', 2 => '2'], $element->position, ['required' => true]) }}

                            <div id="parent_div">
                                {{ Form::bs_autoselect('parent', 'Родительский эелемент:', $parents, $element->parent,
                                         false, false, [], null) }}
                            </div>

                            <div id="value_div">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <div style="height: 40px;">&nbsp;</div>

                                {{ Form::bs_text('value', 'Значение:', null) }}
                               {{-- {{ Form::bs_text('wh_color', 'Цвет текста для светлой темы:', null) }}
                                {{ Form::bs_text('bl_color', 'Цвет текста для темной темы:', null) }} --}}
                            </div>


                            <div id="handle_div">

                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <div style="height: 40px;">&nbsp;</div>

                                {{ Form::bs_autoselect('id_object', 'Привязанный объект:',  $objects, $element->id_object,
                                               false, false, [], null, 'Объект, который будет отдавать данные этому элементу') }}


                                <div id="methods_div"  style="display: none">
                                    {{ Form::bs_autoselect('method', 'Метод:', [], old('method'),
                                     false, false, [], null, 'Метод объекта при нажатии на элемент') }}
                                </div>


                                {{ Form::bs_autoselect('handle', 'Идентификатор:', $handles, $element->handle,
                                      false, false, [], null, 'Идентификатор свойства объекта') }}

                            </div>

                            <div id="settings_div">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <div style="height: 40px;">&nbsp;</div>

                                {{ Form::bs_checkbox('settings', 'Настраиваемый элемент:', $settings) }}
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
    @include('elements.index_modals')
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
                    $('#value_div').show();
                    $('#handle_div').show();
                    $('#methods_div').hide();
                    $('#parent_div').show();
                    $('#settings_div').show();
                } else if ($(this).val() === 'switch') {
                    $('#value_div').hide();
                    $('#handle_div').show();
                    $('#methods_div').show();
                    $('#parent_div').show();
                    $('#settings_div').show();
                } else {
                    $('#value_div').hide();
                    $('#handle_div').hide();
                    $('#methods_div').hide();
                    $('#parent_div').hide();
                    $('#settings_div').hide();
                }
            });


        });


    </script>


@endsection
