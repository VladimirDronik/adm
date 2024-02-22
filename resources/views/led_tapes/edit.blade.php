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

                        {{ Form::bs_text('name', 'Название*:', old('name', $ledTape->name), ['required' => true]) }}

                        {{ Form::bs_autoselect('room', 'Размещение:', $rooms, old('room', $ledTape->room), false, false, []) }}

                        {{ Form::bs_simple_text('Тип ленты:', $ledTape->type) }}

                        {{ Form::bs_simple_text('Имя контроллера:', $ledTape->modbusSlaver?->name) }}

                        {{ Form::bs_simple_text('Канал подключения:', $ledTape->channel) }}

                        {{ Form::bs_simple_text('Состояние:', $ledTape->object->status) }}

                        {{ Form::bs_simple_text('Яркость:', ($ledTape->type == \App\Models\LedTape::TYPE_RGB || $ledTape->type == \App\Models\LedTape::TYPE_RGBW) ? $ledTape->v : $ledTape->w) }}

                        @if($ledTape->type == \App\Models\LedTape::TYPE_RGB || $ledTape->type == \App\Models\LedTape::TYPE_RGBW)
                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix">
                                    Цвет:
                                </label>
                                <div class="col-sm-9">
                                    <div style="display: inline-block; width: 100px; height: 50px; background-color: hsl({{ $ledTape->h }}, {{ $ledTape->hsvToHsl()['s'] }}%, {{ $ledTape->hsvToHsl()['l'] }}%)"></div>
                                    <ul style="display: inline-block;">
                                        <li>&nbsp;&nbsp; h = {{ $ledTape->h }}&deg</li>
                                        <li>&nbsp;&nbsp; s = {{ $ledTape->s }}%</li>
                                        <li>&nbsp;&nbsp; v = {{ $ledTape->v }}%</li>
                                    </ul>
                                </div>
                            </div>
                        @elseif($ledTape->type == \App\Models\LedTape::TYPE_CCT)
                            {{ Form::bs_simple_text('Цветовая температура:', $ledTape->cct) }}
                        @endif

                        <input type="hidden" name="id_object" value="{{ $ledTape->id_object }}">
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
    <script>
        $(document).ready(function () {
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
