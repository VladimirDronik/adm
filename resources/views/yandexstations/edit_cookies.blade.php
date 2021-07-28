@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование файла cookies»',
        'links' => [ route('yandexstations.index') => 'ЯндексСтанции'],
        'last_link' => 'Редактирование файла cookies'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('yandexstations.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок станций</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'yandexstations.updatecookies', 'method' => 'post', 'id' => 'yandexstation_form',
                            'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}


                        <div class="col-md-9">
                            <div class="mt-2">
                                {{ Form::bs_textarea('file', 'Текст файла cookies*:', $file, ['required'=>false, 'rows' => 20]) }}
                            </div>
                        </div>

                    </div>



                    {{ Form::bs_submit_btn() }}


                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>

            </div>
        </div>
    </div>




@endsection

@section('scripts')

@endsection
