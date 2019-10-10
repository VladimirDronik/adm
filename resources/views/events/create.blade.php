@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление события', 'links' => [ route('events.index') => 'События']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('events.index') }}" class="btn btn-success m-b-10 m-l-5">Список событий</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'events.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        {{ Form::bs_title('Основные данные') }}
                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                        {{ Form::bs_hr() }}
                        {{ Form::bs_simple_text('', 'Необходимо выбрать или только метод, или только скрипт') }}
                        {{ Form::bs_autoselect('object', 'Объект:', $objects, null,  false, false) }}
                        {{ Form::bs_autoselect('method', 'Метод:', [], null,  false, false) }}
                        {{ Form::bs_hr() }}
                        {{ Form::bs_autoselect('script', 'Скрипт:', $scripts, null,  false, false) }}
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
    <script>
        let url_methods = '{{ route('ajax.objects.methods') }}';

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

        function isEmptyInput(name) {
            return $('input[name='+name+']').val().trim() == '';
        }

        function isEmptyAutoSelect(name) {
            return $('#auto_sel_'+name).val().trim() == '';
        }

        function validateEvent() {
            if (isEmptyInput('name')) {
                return 'Не указано название события';
            }
            if (!isEmptyAutoSelect('script') && !isEmptyAutoSelect('method')) {
                return 'Необходимо выбрать или только метод, или только скрипт';
            }
            return '';
        }

        $(document).ready(function () {
            $("#auto_sel_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_script").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        createMethodSelect('#auto_sel_method', data.methods, -1);
                        $('#auto_sel_method').trigger("chosen:updated");
                    }
                });
            });

            $('button[type=submit]').click(function(){
                let message = validateEvent();
                if (message !== '') {
                    $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
                    $('#init_btn').click();
                    return false;
                }
            });
        });
    </script>
@endsection