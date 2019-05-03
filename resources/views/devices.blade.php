

@extends('layouts._layout')

@section('breadcrumbs')
        <!-- Bread crumb -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-primary">Устройства</h3> </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Устройства</li>
                </ol>
            </div>
        </div>
        <!-- End Bread crumb -->
@endsection

@section('content')

    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-success m-b-10 m-l-5">Добавить устройство</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-title">
                <h4>Доступные устройства</h4>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Название</th>
                            <th>Тип</th>
                            <th>ip адрес</th>
                            <th>Статус</th>
                            <th></th>

                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($devices as $device)
                            <tr>
                                <th scope="row">{{ $device->id }}</th>
                                <td>{{ $device->description }}</td>
                                <td>MegaD</td>
                                <td>{{ $device->ip_address }}</td>
                                <td>
                                    @if ( $device->active  === 1)
                                        <span class="badge badge-success">Активно</span>
                                    @else
                                        <span class="badge badge-primary">Не доступно</span>
                                    @endif
                                </td>
                                <td>
                                    <button onclick="window.location.href='devices/select/{{ $device->id }}'" type="button" class="btn btn-info btn-rounded m-b-10 m-l-5">Настройка</button>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>




    <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->


@endsection