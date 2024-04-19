@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Редактирование кондиционера № '. $conditioner->id_object . ' «' . $conditioner->name .'»',
        'links' => [ route('conditioners.index') => 'Кондиционеры'],
        'last_link' => 'Редактирование кондиционера',
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('conditioners.index') }}" class="btn btn-success m-b-10 m-l-5">Список кондиционеров</a>
                        <a href="{{ route('conditioners.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить кондиционер</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($conditioner, ['route' => ['conditioners.update', $conditioner->id], 'id' => 'conditioner_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}
                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#conditionerstab1" role="tab">
                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                        <span class="hidden-xs-down">Основное</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane p-20 active" id="conditionerstab1" role="tabpanel">
                                    @include('conditioners/edit_tabs/main')
                                </div>
                            </div>
                        </div>
                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
            </div>
            <div style="height: 200px;">&nbsp;</div>
            <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
        </div>
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#auto_sel_modbus_slaver_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_id_room").chosen({width:"100%", no_results_text: "Не найдено"});
        });
    </script>
@endsection
