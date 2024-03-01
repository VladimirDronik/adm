@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление led ленты', 'links' => [ route('illumination.index') => 'Список устройств освещения']])
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
                    {!! Form::open(['route' => 'led_tapes.store', 'method' => 'post', 'id' => 'led_tape_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name'), ['required' => true]) }}

                        {{ Form::bs_radio('type', 'Тип ленты*:', $types, old('type'), ['required' => true]) }}

                        {{ Form::bs_autoselect('room', 'Размещение:', $rooms, old('room'), false, false, []) }}
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
        $(document).ready(function () {
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});

            function validateLedTape() {
                if (!$("#led_tape_form input[name='name']").val()) {
                    return 'Укажите название для ленты';
                }
                if (!$("#led_tape_form input[name='type']:checked").val()) {
                    return 'Выберите тип ленты';
                }

                return '';
            }

            $("#led_tape_form button[type=submit]").click(function() {
                let message = validateLedTape();
                if (message !== '') {
                    showErrorModal(message);
                }
            });
        });
    </script>
@endsection
