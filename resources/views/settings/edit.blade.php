@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование параметра «'. $setting->name.'»',
        'links' => [ route('settings.index') => 'Настройки'],
        'last_link' => 'Редактирование параметра'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('settings.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок параметров</a>
                        <a href="{{ route('settings.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить параметр</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($setting, ['route' => ['settings.update', $setting->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                        {{ Form::bs_text('value', 'Значение*:', null, ['required' => true]) }}
                        {{ Form::bs_textarea('comment', 'Описание*:', null, ['required' => true]) }}
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
    <script>
    </script>
@endsection
