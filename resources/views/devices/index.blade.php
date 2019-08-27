@extends('layouts._layout')

@section('breadcrumbs')
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-primary">Устройства</h3> </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item active">Устройства</li>
                </ol>
            </div>
        </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-success m-b-10 m-l-5" data-toggle="modal" data-target="#addNewDevice">Добавить устройство</button>
                        <a href="{{ route('devices.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <h4>Доступные устройства</h4>
            </div>
            <div class="card-body">
                @if(count($devices))
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>ip адрес</th>
                                <th>Статус</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                                <tr>
                                    <th scope="row">{{ $device->id }}</th>
                                    <td>{{ $device->description }}</td>
                                    <td>{{ optional($device->devtype)->name }}</td>
                                    <td>{{ $device->ip_address }}</td>
                                    <td>
                                        @if( $device->active  === 1)
                                            <span class="badge badge-success">Активно</span>
                                        @else
                                            <span class="badge badge-danger">Недоступно</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('devices.edit',[$device->id]) }}" type="button" class="btn btn-info btn-sm btn-rounded m-b-10 m-l-5">
                                            <i class="fa fa-cogs fa-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        @if(count($devices) > 10)
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>ip адрес</th>
                                    <th>Статус</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
                {{ $devices->appends(request()->input())->links() }}
                <p class="text-right">Найдено: {{ $devices->total() }}</p>
                @else
                    <p>Устройства не найдены</p>
                @endif
            </div>
        </div>
    </div>

        <!-- модальное окно добавления нового устройства -->
        <div class="modal" id="addNewDevice">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Добавить новое устройство</h4>
                    </div>
                    <div class="modal-body">
                            Тип устройства:
                        <br>
                        <br>
                            <div class="btn-group-toggle" data-toggle="buttons">

                                <label class="btn btn-success" id="easy_button" >
                                    <input type="radio" name="typedev"  autocomplete="off" value="1"> Monoblock 14IN/14OUT
                                </label>

                                <label class="btn btn-success" id="method_button">
                                    <input type="radio" name="typedev"  autocomplete="off" value="2"> Mega328
                                </label>

                                <label class="btn btn-success" id="script_button">
                                    <input type="radio" name="typedev"  autocomplete="off" value="3"> WIFI 4IN
                                </label>

                                <label class="btn btn-success" id="none_button">
                                    <input type="radio" name="typedev"  autocomplete="off" value="4"> WIFI 4OUT
                                </label>
                            </div>
                        <br>
                        Название устройства: <input type="text" class="form-control input-default col-sm-4" id="name_device" size="15"><br>
                        ip адрес устройства: <input type="text" class="form-control input-default col-sm-4" id="ip_device" size="15">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                        <button type="button"   class="btn btn-primary" data-dismiss="modal"  onclick="new_device();" >Добавить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- модальное окно добавления портов -->
        <div id="addports-Modal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Добавляются порты</h4>
                    </div>
                    <div class="modal-body">
                        <h5 class="m-t-30">333<span class="pull-right">85%</span></h5>
                        <div class="progress ">
                            <div class="progress-bar bg-danger wow animated progress-animated" id="progress_ports" style="width: 1%; height:6px;" role="progressbar"> <span class="sr-only">60% Complete</span> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
            <script src="{{ asset('ela/js/pagescripts/device.js') }}"></script>
@endsection
