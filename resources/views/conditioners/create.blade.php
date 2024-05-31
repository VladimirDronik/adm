@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Добавление кондиционера',
        'links' => [ route('conditioners.index') => 'Кондиционеры'],
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('conditioners.index') }}" class="btn btn-success m-b-10 m-l-5">Кондиционеры</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'conditioners.store', 'method' => 'post', 'id' => 'conditioner_form', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}
                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                            {{ Form::bs_autoselect('modbus_slaver_id', 'Модбас шлюз:', $modbusSlavers, old('modbus_slaver_id'), false, false, ['required' => true], null) }}

                            {{ Form::bs_autoselect('id_room', 'Помещение*:', $rooms, old('id_room'), false, false, [], null) }}
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
    <script src="{{ asset('ela/js/pagescripts/conditioner.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#auto_sel_modbus_slaver_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_id_room").chosen({width:"100%", no_results_text: "Не найдено"});
        });
    </script>
@endsection
