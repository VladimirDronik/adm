@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование часового пояса',
        'links' => [ route('settings.index') => 'Настройки'],
        'last_link' => 'Редактирование часового пояса'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('settings.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок параметров</a>
                        <button type="button" class="btn btn-success m-b-10 m-l-5" id="addPageBtn">Добавить параметр
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($setting, ['route' => ['time_zone.update', $setting->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix" for="code">
                                <strong>Название*:</strong>
                            </label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-12  pr-0 ">
                                        <input class="form-control" readonly autocomplete="off" name="name" type="text" value="time_zone">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::bs_autoselect('value', 'Часовой пояс*:', $timeZones, old('value', $setting->value), false, false, ['required' => true], null) }}
                        {{ Form::bs_textarea('comment', 'Описание*:', null, ['required' => true]) }}
                    </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('settings.create_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/time_zone.js') }}"></script>
    <script>
        $(document).ready(function(){
            initTimeZoneForm();
            $("#auto_sel_value").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#addPageBtn').click(function() {
                $('#modalPage #modal_groups_div').show();
                $('#modalPage #namePage').val('');
                $('#modal_page_init_btn').click();
            });
        });
    </script>
@endsection
