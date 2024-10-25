@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование led ленты № '. $ledTape->object['id'] . ' «' . $ledTape->name .'»',
        'links' => [ route('illumination.index') => 'Список устройств освещения'],
        'last_link' => 'Редактирование led ленты'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('illumination.index') }}" class="btn btn-success m-b-10 m-l-5">Список устройств освещения</a>
                        <a href="{{ route('led_tapes.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить led ленту</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($ledTape, ['route' => ['led_tapes.update', $ledTape->id], 'id' => 'led_tape_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if($tab==1) active @endif" data-toggle="tab" href="#tab1" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Основное</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if($tab==2) active @endif" data-toggle="tab" href="#tab2" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-user"></i></span>
                                    <span class="hidden-xs-down">Свойства</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane p-20 @if($tab==1) active @endif" id="tab1" role="tabpanel">
                                @include('led_tapes/edit_tabs/main')
                            </div>
                            <div class="tab-pane p-20 @if($tab==2) active @endif" id="tab2" role="tabpanel">
                                @include('led_tapes/edit_tabs/prop')
                            </div>
                        </div>
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
    <script src="{{ asset('ela/js/pagescripts/service.js') }}"></script>
    <script>
        $(document).ready(function () {
            serviceInit();

            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});

            function validateLedTape() {
                if (!$("#led_tape_form input[name='name']").val()) {
                    return 'Укажите название для ленты';
                }

                return '';
            }

            $("#led_tape_form button[type=submit]").click(function() {
                let message = validateLedTape();
                if (message !== '') {
                    showErrorModal(message);
                }
            });
        });
    </script>
@endsection
