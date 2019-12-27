@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование скрипта «'. $script->name .'»',
        'links' => [ route('scripts.index') => 'Скрипты'],
        'last_link' => 'Редактирование скрипта'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('scripts.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок скриптов</a>
                        <a href="{{ route('scripts.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить скрипт</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-10 col-xl-9">
                    {!! Form::model($script, ['route' => ['scripts.update', $script->id], 'method' => 'put',
                            'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        {{ Form::bs_simple_text('Системный:', $script->system ? 'Да': 'Нет') }}
                        {{ Form::bs_simple_text('Кол-во выполнений:', (int)$script->count) }}
                        {{ Form::bs_simple_text('Название файла:', $script->link) }}
                        @if($script->system)
                            {{ Form::bs_simple_text('Название:', $script->name) }}
                            @if($script->isLinkExists())
                                <div class="form-group row">
                                    {!! Form::bs_label('code', 'Код скрипта:', false, 3) !!}
                                    <div class="col-md-9">
                                        <div class="mt-2">
                                            <pre>{{ $script->code }}</pre>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger alert-dismissible fade show">
                                    Файл {{ $script->link }} не найден.
                                </div>
                            @endif
                        @else
                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                            @if(!$script->isLinkExists())
                                <div class="alert alert-danger alert-dismissible fade show">
                                    Файл {{ $script->link }} не найден. Код будет сохранен в новый файл с таким названием.
                                </div>
                            @endif

                            {{ Form::bs_textarea('code', 'Код скрипта*:', $script->code, ['required'=>true, 'rows' => 20]) }}

                            {{ Form::bs_submit_btn() }}
                        @endif
                    </div>
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

