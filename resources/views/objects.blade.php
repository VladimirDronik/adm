

@extends('layouts._layout')

@section('breadcrumbs')
        <!-- Bread crumb -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-primary">Объекты</h3> </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Объекты</li>
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
                        <button type="button" class="btn btn-success m-b-10 m-l-5" data-toggle="modal" data-target="#addNewDevice">Добавить объект</button>
                        <button type="button" class="btn btn-success m-b-10 m-l-5" onclick="window.location.reload();">Обновить</button>
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
                            <th>Название</th>
                            <th>Тип</th>
                            <th>Статус</th>
                            <th>Отображение</th>
                            <th></th>

                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($objects as $object)
                            <tr>
                                <th scope="row">{{ $object->id }}</th>
                                <td><a href="#" onclick="edit_name({{ $object->id }});">{{ $object->name }}</a></td>
                                <td>{{ $object->type }}</td>
                                <td>@if($object->status == 'on')
                                        <span class="badge badge-success">{{ $object->status }}</span>
                                    @else
                                        <span class="badge badge-primary">{{ $object->status }}</span>
                                    @endif



                                </td>
                                <td>{{ $object->view }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-rounded m-b-10 m-l-5">Удалить</button>
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
