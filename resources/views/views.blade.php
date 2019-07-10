

@extends('layouts._layout')

@section('breadcrumbs')
        <!-- Bread crumb -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-primary">Отображения</h3> </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Отображения</li>
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
                        <button type="button" class="btn btn-success m-b-10 m-l-5" data-toggle="modal" data-target="#addNewDevice">Добавить отображение</button>
                        <button type="button" class="btn btn-success m-b-10 m-l-5" onclick="window.location.reload();">Обновить</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="dropdown room-filter" id="room-filter">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Фильтр по помещению
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="/views">Все помещения</a></li>
                                <hr>
                                <li><a href="/views/room/0">Общие</a></li>
                                <hr>

                                @foreach ($rooms as $room)
                                    <li><a href="#">{{ $room->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        <button type="button" class="btn btn-success m-b-10 m-l-5" onclick="document.location.href = '/views';">Сбросить</button>


                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-title">
                <h4>Объекты</h4>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Тип</th>
                            <th>Статус</th>
                            <th>Вкл_картинка</th>
                            <th>Выкл_картинка</th>
                            <th>Вкл_надпись</th>
                            <th>Выкл_надпись</th>
                            <th>Значение</th>
                            <th>Помещение</th>
                            <th>Сорт</th>
                            <th>Сцена</th>
                            <th>Left</th>
                            <th>Top</th>
                            <th>Активно</th>
                            <th></th>

                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($views as $view)
                            <tr>
                                <th scope="row">{{ $view->id }}</th>
                                <td><a href="#">{{ $view->name }}</a></td>
                                <td>@if($view->status == 'on')
                                        <span class="badge badge-success">{{ $view->status }}</span>
                                    @else
                                        <span class="badge badge-primary">{{ $view->status }}</span>
                                    @endif

                                <td scope="row">{{ $view->on_image }}</td>
                                <td scope="row">{{ $view->off_image }}</td>
                                <td scope="row">{{ $view->on_title }}</td>
                                <td scope="row">{{ $view->off_title }}</td>

                                <td scope="row">{{ $view->value }}</td>
                                <td scope="row">{{ $view->room }}</td>
                                <td scope="row">{{ $view->sort }}</td>

                                <td scope="row">{{ $view->scene }}</td>
                                <td scope="row">{{ $view->position_left }}</td>
                                <td scope="row">{{ $view->position_top }}</td>

                                <td scope="row">{{ $view->active }}</td>


                                <td>

                                    <button type="button" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash fa-lg"></i></button>
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
            <script src="/js/pagescripts/object.js"></script>
@endsection
