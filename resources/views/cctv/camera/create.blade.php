@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление камеры', 'links' => [ route('cctv.index') => 'Видеонаблюдение']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('cctv.index') }}" class="btn btn-success m-b-10 m-l-5">Видеонаблюдение</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'cameras.store', 'method' => 'post', 'id' => 'camera_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name'), ['required' => true]) }}

                        {{ Form::bs_radio('vendor', 'Производитель*:', $vendors, old('vendor', 'ivideon'), ['required' => true]) }}

                        <div id='ivideon_div' hidden>
                            {{ Form::bs_text('link', 'Ссылка*:', old('link'), ['required' => true]) }}
                        </div>

                        <div id='HikVision_HiWatch_div' hidden>
                            {{ Form::bs_text('ip_address', 'IP адрес*:', old('ip_address'), ['required' => true]) }}

                            {{ Form::bs_text('login', 'Логин*:', old('login'), ['required' => true]) }}

                            {{ Form::bs_text('password', 'Пароль*:', old('password'), ['required' => true]) }}
                        </div>

                        <div id='other_div' hidden>
                            {{ Form::bs_text('link_rtsp', 'Ссылка RTSP*:', old('link'), ['required' => true]) }}
                        </div>

                        {{ Form::bs_checkbox('active', 'Активность*:', true) }}

                        <input name="type" value="ivideon" hidden>
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
        var beginOptions = $('#camera_form input[name=vendor]');
        for (var i = 0; i < beginOptions.length; i++) {
            if (beginOptions[i].checked) {
                var beginSelectedOption = beginOptions[i].value;
            }
        }

        if (beginSelectedOption == 'ivideon') {
            $('#ivideon_div').removeAttr("hidden");
            $('#camera_form input[name=link]').removeAttr("disabled");

            $('#other_div').attr("hidden", true);
            $('#camera_form input[name=link_rtsp]').attr("disabled", true);

            $('#HikVision_HiWatch_div').attr("hidden", true);
            $('#camera_form input[name=ip_address]').attr("disabled", true);
            $('#camera_form input[name=login]').attr("disabled", true);
            $('#camera_form input[name=password]').attr("disabled", true);
        } else if (beginSelectedOption == 'HikVision/HiWatch') {
            $('#HikVision_HiWatch_div').removeAttr("hidden");
            $('#camera_form input[name=ip_address]').removeAttr("disabled");
            $('#camera_form input[name=login]').removeAttr("disabled");
            $('#camera_form input[name=password]').removeAttr("disabled");

            $('#other_div').attr("hidden", true);
            $('#camera_form input[name=link_rtsp]').attr("disabled", true);

            $('#ivideon_div').attr("hidden", true);
            $('#camera_form input[name=link]').attr("disabled", true);
        } else if (beginSelectedOption == 'other') {
            $('#HikVision_HiWatch_div').removeAttr("hidden");
            $('#camera_form input[name=ip_address]').removeAttr("disabled");
            $('#camera_form input[name=login]').removeAttr("disabled");
            $('#camera_form input[name=password]').removeAttr("disabled");

            $('#other_div').removeAttr("hidden");
            $('#camera_form input[name=link_rtsp]').removeAttr("disabled");

            $('#ivideon_div').attr("hidden", true);
            $('#camera_form input[name=link]').attr("disabled", true);
        }

        $(document).ready(function () {
            $('#camera_form input[name=vendor]').change(function() {
                var options = $('#camera_form input[name=vendor]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                if (selectedOption == 'ivideon') {
                    $('#ivideon_div').removeAttr("hidden");
                    $('#camera_form input[name=link]').removeAttr("disabled");

                    $('#other_div').attr("hidden", true);
                    $('#camera_form input[name=link_rtsp]').attr("disabled", true);

                    $('#HikVision_HiWatch_div').attr("hidden", true);
                    $('#camera_form input[name=ip_address]').attr("disabled", true);
                    $('#camera_form input[name=login]').attr("disabled", true);
                    $('#camera_form input[name=password]').attr("disabled", true);
                } else if (selectedOption == 'HikVision/HiWatch') {
                    $('#HikVision_HiWatch_div').removeAttr("hidden");
                    $('#camera_form input[name=ip_address]').removeAttr("disabled");
                    $('#camera_form input[name=login]').removeAttr("disabled");
                    $('#camera_form input[name=password]').removeAttr("disabled");

                    $('#other_div').attr("hidden", true);
                    $('#camera_form input[name=link_rtsp]').attr("disabled", true);

                    $('#ivideon_div').attr("hidden", true);
                    $('#camera_form input[name=link]').attr("disabled", true);
                } else if (selectedOption == 'other') {
                    $('#HikVision_HiWatch_div').removeAttr("hidden");
                    $('#camera_form input[name=ip_address]').removeAttr("disabled");
                    $('#camera_form input[name=login]').removeAttr("disabled");
                    $('#camera_form input[name=password]').removeAttr("disabled");

                    $('#other_div').removeAttr("hidden");
                    $('#camera_form input[name=link_rtsp]').removeAttr("disabled");

                    $('#ivideon_div').attr("hidden", true);
                    $('#camera_form input[name=link]').attr("disabled", true);
                }
            });
        });
    </script>
@endsection
