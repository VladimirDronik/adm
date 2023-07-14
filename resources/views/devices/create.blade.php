@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление контроллера', 'links' => [ route('devices.index') => 'Контроллеры']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('devices.index') }}" class="btn btn-success m-b-10 m-l-5">Список контроллеров</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'devices.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered',
                    'id' => 'types_form']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_radio('type', 'Тип контроллера*:', $devtypes, old('type'), ['required' => true]) }}
                            {{ Form::bs_text('description', 'Название*:', null, ['required' => true]) }}
                            {{ Form::bs_text('ip_address', 'IP адрес*:', null, ['required' => true]) }}
                            <div id="username" hidden>
                                {{ Form::bs_text('username', 'Пользователь*:', null, ['required' => false, 'disabled' => true]) }}
                            </div>
                            <div id="password">
                                {{ Form::bs_text('password', 'Пароль*:', null, ['required' => false]) }}
                            </div>
                            <div id="port" hidden>
                                {{ Form::bs_text('port', 'Порт*:', null, ['required' => false, 'disabled' => true]) }}
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
@endsection

@section('scripts')

    <script src="{{ asset('ela/js/jquery.bubble.text.js') }}"></script>
    <script>
        function getFormFieldsByType(type) {
            if (type === 'ModbusTCP') {
                $('#port').removeAttr('hidden');
                $('#types_form input[name=port]').removeAttr('disabled');
                $('#types_form input[name=port]').attr('required', true);
                $('#types_form input[name=password]').attr('disabled', true);
                $('#password').attr('hidden', true);
                $('#types_form input[name=password]').removeAttr('required');
                $('#types_form input[name=username]').attr('disabled', true);
                $('#username').attr('hidden', true);
                $('#types_form input[name=username]').removeAttr('required');
            } else {
                $('#types_form input[name=port]').attr('disabled', true);
                $('#port').attr('hidden', true);
                $('#types_form input[name=port]').removeAttr('required');
                $('#types_form input[name=username]').attr('disabled', true);
                $('#username').attr('hidden', true);
                $('#types_form input[name=username]').removeAttr('required');
                $('#password').removeAttr('hidden');
                $('#types_form input[name=password]').removeAttr('disabled');
                $('#types_form input[name=password]').attr('required', true);
            }

            if (type === 'Monoblock 14IN/14OUT') {
                $('#types_form input[name=password]').val('sec')
            } else {
                $('#types_form input[name=password]').val('')
            }
        }

        getFormFieldsByType($('#types_form input[name=type]:checked').val())

        $('#types_form input[name=type]').change(function(){

            getFormFieldsByType($(this).val())

            return true;
        });


        $( "#types_form" ).submit(function( event ) {

            let count = 0;

            $('#load_init_btn').click();

            $('#load_modal_body').text('Создаем контроллер...');


            $(document).ready(function() {
                var $element = $('#content1_modal_body');
                var newText = 'Пингуем контроллер ...';
                bubbleText({
                    element: $element,
                    newText: newText,
                    speed: 100,
                    repeat: Infinity,
                });
            })





        });


    </script>


@endsection
