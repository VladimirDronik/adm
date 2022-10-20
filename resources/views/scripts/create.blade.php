@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление скрипта', 'links' => [ route('scripts.index') => 'Скрипты']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('scripts.index') }}" class="btn btn-success m-b-10 m-l-5">Список скриптов</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-10 col-xl-9">
                    {!! Form::open(['route' => 'scripts.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}
                            {{ Form::bs_simple_text('Системный:', 'Нет') }}
                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                            {{ Form::bs_textarea('code', 'Код скрипта*:', null, ['required'=>true, 'rows' => 20]) }}
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
    <script>
    </script>
@endsection