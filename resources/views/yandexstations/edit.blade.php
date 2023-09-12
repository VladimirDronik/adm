@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование ЯндексСтанции № '. $yandexstation->id . ' «' . $yandexstation->name .'»',
        'links' => [ route('yandexstations.index') => 'ЯндексСтанции'],
        'last_link' => 'Редактирование ЯндексСтанции'])
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
                    {!! Form::model($yandexstation, ['route' => ['yandexstations.update', $yandexstation->id], 'id' => 'yandexstation_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item"> <a class="nav-link active"  data-toggle="tab" href="#portstab1"  role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Основное</span></a> </li>
                            <li class="nav-item"> <a class="nav-link"  data-toggle="tab" href="#portstab2"  role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Методы</span></a> </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane p-20 active" id="portstab1" role="tabpanel">
                                @include('yandexstations.edit_tabs.main')
                            </div>
                            <div class="tab-pane p-20" id="portstab2" role="tabpanel">
                                @include('yandexstations.edit_tabs.methods', ['object' => $yandexstation->object])
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
