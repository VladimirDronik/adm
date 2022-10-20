@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование параметра «'. $notification->name.'»',
        'links' => [ route('notifications.index') => 'Настройки'],
        'last_link' => 'Редактирование параметра'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('notifications.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок параметров</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($notification, ['route' => ['notifications.update', $notification->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_simple_text('Название:', $notification->name) }}
                        {{ Form::bs_radio('priority', 'Приоритет*:', $priority, old('priority', $notification->priority), ['required' => true]) }}
                        {{ Form::bs_textarea('message', 'Сообщение, которое отправляется пользователю*:', null, ['required' => true]) }}

                        {{ Form::bs_radio('text_flag', 'Текстовое сообщение:', $text_flag, old('text_flag', $notification->text_flag), ['required' => true]) }}
                        {{ Form::bs_radio('sound_flag', 'Звуковое сообщение:', $sound_flag, old('sound_flag', $notification->sound_flag), ['required' => true]) }}

                        {{ Form::bs_autoselect('id_sound', 'Звук оповещения:', $sounds, old('id_sound', $notification->id_sound),
                           false, false, [], null, 'Звук, который необходимо проиграть при событии') }}
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
            $("#auto_sel_id_sound").chosen({width: "100%", no_results_text: "Не найдено"});
        });

    </script>
@endsection
