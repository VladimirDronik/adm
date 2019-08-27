@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Устройства'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('devices.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить устройство</a>
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
@endsection

