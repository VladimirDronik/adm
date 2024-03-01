@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование устройства № '. $slaver->id,
        'links' => [ route('mod_bus.slavers.index') => 'Устройства'],
        'last_link' => 'Редактирование устройства'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.slavers.index') }}" class="btn btn-success m-b-10 m-l-5">Устройства</a>
                        <a href="{{ route('mod_bus.slavers.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить устройство</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($slaver, ['route' => ['mod_bus.slavers.update', $slaver->id], 'id' => 'slaver_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name', $slaver->name), ['required' => true]) }}

                        {{ Form::bs_simple_text('Тип:', $slaver->relatedType->name) }}

                        {{ Form::bs_autoselect('bus', 'Шина*:', $buses, old('bus', $slaver->bus), false, false, ['required' => true], null, null, 3, false, true) }}

                        {{ Form::bs_number('address', 'Адрес*:', old('address', $slaver->address), ['min' => 1, 'max' => 247, 'required' => true]) }}

                        @if($slaver->relatedType->type == 'ecodim-dali-gw2')
                            <br><br>
                            {{ Form::bs_title('Сеть DALI') }}

                            <button type="button" class="btn btn-success m-b-10 m-l-5" @if(\App\Models\DaliDevice::exists()) id="networkAssemblyBtn" @else id="redirectToStartNetworkAssembly" @endif>Сборка сети</button>
                            <button type="button" class="btn btn-success m-b-10 m-l-5" id="startNetworkExpansion">Расширение сети</button>

                            <br><br><br><br>
                        @elseif($slaver->relatedType->type == 'wb-led')
                            {{ Form::bs_select('wb_led_oper_mode', 'Режим работы*:', $wbLedOperModes, old('wb_led_oper_mode'), ['required' => true]) }}

                            <input type="hidden" name="old_wb_led_oper_mode" value="">
                        @endif
                    </div>

                    {{ Form::bs_submit_btn() }}

                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.load_modal')
    @include('mod_bus.slaver.modals.network_assembly')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/jquery.bubble.text.js') }}"></script>
    <script>
        let network_assembly_url = '{{ route('ajax.mod_bus.slavers.network_assembly') }}';
        let network_expansion_url = '{{ route('ajax.mod_bus.slavers.network_expansion') }}';
        let read_register_url = '{{ route('ajax.mod_bus.registers.read') }}';
        let id = '{{ $slaver->id }}';
        let wbLedModeRegisterId = '{{ $wbLedModeRegisterId }}';

        if (wbLedModeRegisterId) {
            $.ajax({
                url: read_register_url,
                data: { '_token': _token, 'id': wbLedModeRegisterId },
                success: function (data) {
                    if (data.result) {
                        $('#slaver_form [name=wb_led_oper_mode]').find('option[value="'+ data.response +'"]').prop('selected', true);
                        $('#slaver_form [name=old_wb_led_oper_mode]').val(data.response);
                    } else {
                        showErrorModal(data.response ?? 'Ошибка чтения регистра wb_led_mode');
                    }
                }
            });
        }

        $(document).ready(function () {
            $("#auto_sel_bus").chosen({width:"100%", no_results_text: "Не найдено"});

            bubbleText({
                element: $('#content1_modal_body'),
                newText: 'Выполняется. Пожалуйста, подождите ...',
                speed: 100,
                repeat: Infinity,
            });

            $('#networkAssemblyBtn').click(function() {
                $('#modal_network_assembly_init_btn').click();
            });

            $('#redirectToStartNetworkAssembly').click(function() {
                $('#startNetworkAssembly').click();
            });

            $('#startNetworkAssembly').click(function() {
                $('#load_modal_body').text('Сборка сети');
                $('#load_init_btn').click();

                $.ajax({
                    url: network_assembly_url,
                    data: { '_token': _token, 'id': id },
                    success: function (data) {
                        $('#dismiss_load_modal').click();
                        if (data.result) {
                            showSuccessModal('Сборка сети прошла успешно');
                        } else {
                            showErrorModal(data.message ?? 'Ошибка сборки сети');
                        }
                    }
                });
            });

            $('#startNetworkExpansion').click(function() {
                $('#load_modal_body').text('Расширение сети');
                $('#load_init_btn').click();

                $.ajax({
                    url: network_expansion_url,
                    data: { '_token': _token, 'id': id },
                    success: function (data) {
                        $('#dismiss_load_modal').click();
                        if (data.result) {
                            showSuccessModal('Расширение сети прошло успешно');
                        } else {
                            showErrorModal(data.message ?? 'Ошибка расширения сети');
                        }
                    }
                });
            });
        });
    </script>
@endsection
