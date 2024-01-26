@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование устройства DALI № '. $daliDevice->id,
        'links' => [ route('illumination.index') => 'Устройства освещения'],
        'last_link' => 'Редактирование устройства DALI'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('illumination.index') }}" class="btn btn-success m-b-10 m-l-5">Список устройств освещения</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($daliDevice, ['route' => ['mod_bus.dali_devices.update', $daliDevice->id], 'id' => 'dali_device_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_text('name', 'Название*:', old('name', $daliDevice->name), ['required' => true]) }}

                            {{ Form::bs_simple_text('Адрес:', $daliDevice->address) }}

                            {{ Form::bs_simple_text('Шлюз:', $daliDevice->dali_gateway) }}

                            {{ Form::bs_simple_text('Состояние:', $daliDevice->object ? $daliDevice->object->status : '') }}

                            {{ Form::bs_simple_text('Неисправность:', $daliDevice->failure ? 'Да' : 'Нет') }}

                            {{ Form::bs_simple_text('Яркость:', $daliDevice->brightness) }}

                            @if($daliDevice->is_cct)
                                {{ Form::bs_simple_text('Цветовая температура:', $daliDevice->cct) }}
                            @endif
                        </div>

                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
        </div>
    </div>
@endsection
