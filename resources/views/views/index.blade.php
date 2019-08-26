@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Отображения</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Главная</a></li>
                @if ($currentRoom == '')
                    <li class="breadcrumb-item active">Отображения</li>
                @else
                    <li class="breadcrumb-item"><a href="/views">Отображения</a></li>
                    <li class="breadcrumb-item active">{{$currentRoom}}</li>
                @endif
            </ol>
        </div>
    </div>
@endsection

@section('content')

    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('views.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить отображение</a>
                        <button type="button" class="btn btn-success m-b-10 m-l-5" data-toggle="modal" data-target="#addNewDevice">Добавить отображение</button>
                        <button type="button" class="btn btn-success m-b-10 m-l-5" onclick="window.location.reload();">Обновить</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="dropdown room-filter" id="room-filter">

                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">

                                @if ($currentRoom == '') Фильтр по помещению
                                @else  Фильтр по помещению: {{$currentRoom}}
                                @php ($currentRoom = 'для помещения: '.$currentRoom)
                                @endif


                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="/views">Все помещения</a></li>
                                <hr>
                                <li><a href="/views/room/0">Общие</a></li>
                                <hr>


                                @foreach ($rooms as $room)
                                    <li ><a href="/views/room/{{ $room->id }}" >
                                            <label style="background-color:{{ $room->style }}">&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;{{ $room->name }}
                                        </a>
                                    </li>

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

                <h4>Элементы отображения {{$currentRoom}}</h4>


            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>

                            @if ($currentRoom!= '')
                                <th>Сорт</th>
                            @endif
                            <th>Тип</th>
                            <th>Статус</th>
                            <th>Вкл</th>
                            <th>Выкл</th>

                            <th>Вкл_надпись</th>
                            <th>Выкл_надпись</th>
                            <th>Значение</th>
                            <th>Помещение</th>

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
                                @if ($currentRoom!= '')
                                    <td scope="row">{{ $view->sort }}</td>
                                @endif

                                <td><a href="#">{{ $view->name }}</a></td>
                                <td>@if($view->status == 'on')
                                        <span class="badge badge-success">{{ $view->status }}</span>
                                    @else
                                        <span class="badge badge-primary">{{ $view->status }}</span>
                                @endif


                                <td scope="row"><img src="/images/views_items/{{ $view->on_image }}" width="25px" height="25px" style="fill: green;"></td>
                                <td scope="row">{{ $view->off_image }}</td>
                                <td scope="row">{{$view->on_title }}</td>

                                <td scope="row">{{ $view->off_title }}</td>

                                <td scope="row">{{ $view->value }}</td>
                                    <td scope="row"><a href="#">{{ optional($view->eroom)->name }}</a></td>
                                <td scope="row">{{ optional($view->escene)->label }}</td>
                                <td scope="row">{{ $view->position_left }}</td>
                                <td scope="row">{{ $view->position_top }}</td>

                                <td scope="row">{{ $view->is_active }}</td>


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



        <!-- модальное окно добавления нового отображения -->

        <div class="modal" id="addNewDevice">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title"> Добавить новое отображение</h4>

                    </div>


                    <div class="modal-body">


                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Тип Элемента</span></a> </li>
                            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Текст и графика</span></a> </li>
                            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#messages" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Расположение</span></a> </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content tabcontent-border">

                            <div class="tab-pane active" id="home" role="tabpanel">
                                <div class="p-20">

                                    <div class="btn-group-toggle" data-toggle="buttons">

                                        <label class="btn btn-success" id="easy_button" >
                                            <input type="radio" name="typedev"  autocomplete="off" value="1"> Переключатель
                                        </label>

                                        <label class="btn btn-success" id="method_button">
                                            <input type="radio" name="typedev"  autocomplete="off" value="2"> Кнопка
                                        </label>

                                        <label class="btn btn-success" id="script_button">
                                            <input type="radio" name="typedev"  autocomplete="off" value="3"> Термометр/Гигрометр
                                        </label>

                                        <label class="btn btn-success" id="none_button">
                                            <input type="radio" name="typedev"  autocomplete="off" value="4"> Инфопанель
                                        </label>
                                    </div>

                                    <br><br>

                                    <div class="alert alert-info" id="infoAlert">
                                        Выберите тип элемента отображения.
                                    </div>




                                </div>
                            </div>

                            <div class="tab-pane  p-20" id="profile" role="tabpanel">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-3">
                                            Надпись при включении:
                                        </div>
                                        <div class="col-3">
                                            <input type="text" class="form-control input-default col-sm-12" id="name_device"  placeholder="Верхняя строка пустая">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">

                                        </div>
                                        <div class="col-3">
                                            <input type="text" class="form-control input-default col-sm-12" id="name_device"  placeholder="Нижняя строка пустая">
                                        </div>
                                    </div>

                                    <br><br><br>

                                    <div class="row">
                                        <div class="col-3">
                                            Надпись при выключении:
                                        </div>
                                        <div class="col-3">
                                            <input type="text" class="form-control input-default col-sm-12" id="name_device"  placeholder="Верхняя строка пустая">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">

                                        </div>
                                        <div class="col-3">
                                            <input type="text" class="form-control input-default col-sm-12" id="name_device"  placeholder="Нижняя строка пустая">
                                        </div>
                                    </div>

                                    <br><br><br>

                                    <div class="row">
                                        <div class="col-3">
                                            Изображение при включении:
                                        </div>
                                        <div class="col-3">
                                            <img src="/images/rooms/noimage.png" id="image" style="background: black;"  >
                                        </div>
                                    </div>
                                    <br><br><br>
                                    <div class="row">
                                        <div class="col-3">
                                            Изображение при выключении:
                                        </div>
                                        <div class="col-3">
                                            <img src="/images/rooms/noimage.png" id="image" style="background: black;"  >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane p-20" id="messages" role="tabpanel">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-3">
                                            Помещение:
                                        </div>

                                        <div class="col-3">
                                            <div class="dropdown room-filter" id="room-filter">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">

                                                    Выбрать помещение

                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">

                                                    <li><a href="/views/room/0">Общие</a></li>
                                                    <hr>

                                                    @foreach ($rooms as $room)
                                                        <li ><a href="/views/room/{{ $room->id }}" >
                                                                <label style="background-color:{{ $room->style }}">&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;{{ $room->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-3">
                                            Сцена:
                                        </div>

                                        <div class="col-3">

                                            <div class="dropdown room-filter" id="room-filter">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">

                                                    Выбрать сцену

                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">

                                                    <li><a href="/views/room/0">Общие</a></li>
                                                    <hr>

                                                    @foreach ($rooms as $room)
                                                        <li ><a href="/views/room/{{ $room->id }}" >
                                                                <label style="background-color:{{ $room->style }}">&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;{{ $room->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            Left: <input type="text" class="form-control input-default col-sm-4" id="name_device" size="5">
                                            Top: <input type="text" class="form-control input-default col-sm-4" id="name_device" size="5">
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

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

            <script src="/js/pagescripts/views.js"></script>

@endsection
