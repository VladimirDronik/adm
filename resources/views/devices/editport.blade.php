@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Настройка порта '.$port->num_port.' контроллера «'.$device->description.'»',
        'links' => [ route('devices.edit', [$device->id]) => 'Контроллер '.$device->description],
        'last_link' => [ route('devices.index') => $device->description],
        'last_link' => 'Редактирование порта'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('devices.edit', [$device->id]) }}" class="btn btn-success m-b-10 m-l-5">Назад к портам</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title"><h4>Настройка порта контроллера</h4></div>
            <div class="card-body">
                {!! Form::open(['route' => 'ports.store', 'method' => 'post', 'id' => 'port_form', 'class' => 'form-horizontal form-bordered']) !!}

                {{ csrf_field() }}
                {{ Form::bs_alert() }}
                {{ Form::bs_text('comment', 'Название*:', old('comment', $port->comment), ['required' => true]) }}

                <div class="form-body">

                <div class="col-sm-12 pr-0 mt-4">
                    <div class="btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-success btn-sm @if($port->status == 'NC') active @endif">
                            <input type="radio" name="status_radio" autocomplete="off"  value="NC"> Не назначено
                        </label>

                        <label class="btn btn-success btn-sm @if($port->status == 'IN') active @endif">
                            <input type="radio" name="status_radio" autocomplete="off"  value="IN" >  IN
                        </label>

                        <label class="btn btn-success btn-sm @if($port->status == 'OUT') active @endif">
                            <input type="radio" name="status_radio" autocomplete="off" value="OUT">  OUT
                        </label>

                        <label class="btn btn-success btn-sm @if($port->status == '1WIRE') active @endif">
                            <input type="radio" name="status_radio" autocomplete="off" value="1WIRE">  1-wire
                        </label>

                        <label class="btn btn-success btn-sm @if($port->status == '1W-BUS') active @endif">
                            <input type="radio" name="status_radio" autocomplete="off" value="1W-BUS">  1-wire шина
                        </label>

                        <label class="btn btn-success btn-sm @if($port->status == 'I2C') active @endif">
                            <input type="radio" name="status_radio" autocomplete="off" value="I2C">  I2C
                        </label>

                        <input type="hidden" id="status" name="status" value="{{$port->status}}">
                        <input type="hidden" id="id_controller" name="id_controller" value="{{$device->id}}">
                        <input type="hidden" id="id_port" name="id_port" value="{{$port->id}}">
                        <input type="hidden" name="tab" value="{{ $_GET['tab'] }}">

                    </div>
                </div>


                </div>

                <div class="col-sm-12 pr-0 mt-4 text-right">
                    {{ Form::bs_submit_btn() }}
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/device.js') }}"></script>
    <script>

        $('#port_form [name=status_radio]').change(function(){

            $('#status').val($(this).val());
        });

    </script>


@endsection