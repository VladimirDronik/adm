@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Настройка данных клиента'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model((object)[], ['route' => ['app-client.info.update'], 'method' => 'put', 'class' => 'form-horizontal form-bordered', 'id' => 'form']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Клиент*:', old('name', $clientInfo->name), ['required' => true]) }}
                        {{ Form::bs_text('address', 'Адрес*:', old('address', $clientInfo->address), ['required' => true]) }}
                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix" for="code">
                                <strong>Версия админ-панели:</strong>
                            </label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-12  pr-0 ">
                                        <input class="form-control" readonly autocomplete="off" name="admin_app_version" type="text" value="{{ $adminAppV }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix" for="code">
                                <strong>Версия ядра:</strong>
                            </label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-12  pr-0 ">
                                        <input class="form-control" readonly autocomplete="off" name="core_version" type="text" value="{{ $coreV }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
