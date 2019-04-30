

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
                        <tr>
                            <th scope="row">1</th>
                            <td>Kolor Tea Shirt For Man</td>
                            <td>MegaD</td>
                            <td>192.168.88.11</td>
                            <td><span class="badge badge-primary">Выключено</span></td>
                            <td>
                                <button type="button" class="btn btn-info btn-rounded m-b-10 m-l-5">Настройка</button>
                            </td>

                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Kolor Tea Shirt For Women</td>
                            <td>MegaD</td>
                            <td>192.168.88.12</td>
                            <td><span class="badge badge-success">Активно</span></td>
                            <td>
                                <button type="button" class="btn btn-info btn-rounded m-b-10 m-l-5">Настройка</button>
                            </td>

                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Blue Backpack For Baby</td>
                            <td>MegaD</td>
                            <td>192.168.88.13</td>
                            <td><span class="badge badge-danger">Не доступно</span></td>
                            <td>
                                <button type="button" class="btn btn-info btn-rounded m-b-10 m-l-5">Настройка</button>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>




    <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->


@endsection