

@extends('layouts._layout')

@section('breadcrumbs')
        <!-- Bread crumb -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-primary">Помещения</h3> </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Помещения</li>
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
                        <button type="button" class="btn btn-success m-b-10 m-l-5" data-toggle="modal" data-target="#addNewRoom">Добавить помещение</button>
                        <button type="button" class="btn btn-success m-b-10 m-l-5" onclick="window.location.reload();">Обновить</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-title">
                <h4>Помещения</h4>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>

                            <th>Сортировка</th>
                            <th>Название</th>
                            <th>Изображение</th>
                            <th>Цвет</th>
                            <th></th>

                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($rooms as $room)
                            <tr>
                                <td><input type="text" class="form-control input-default col-sm-2" readonly
                                           value="{{ $room->sort }}">

                                    <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $room->id }}"
                                            onclick="changeSort({{ $room->id }},{{ $room->sort }}, 'UP');" >Выше</button>

                                    <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $room->id }}"
                                            onclick="changeSort({{ $room->id }},{{ $room->sort }}, 'DOWN');" >Ниже</button>

                                </td>
                                <td>
                                    <a href="#" id="nameRoom_{{ $room->id }}" onclick="edit_name({{ $room->id }});"
                                       data-toggle="modal" data-target="#nameRoomModal" >{{ $room->name }}</a>
                                </td>
                                <td><img src="{{ asset('ela/images/rooms/'.$room->image) }}" id="imageRoom_{{ $room->id }}" class="imageRoom" data-toggle="modal" data-target="#selectImage"
                                         onclick="updateImage({{ $room->id }}, true);"></td>
                                <td>
                                    <button style="background: {{ $room->style }}" id="colorRoom_{{ $room->id }}" class="btn btn-default"
                                            data-toggle="modal" data-target="#selectColor"
                                            onclick="updateColor({{ $room->id }}, true)">
                                    </button>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-danger btn-rounded m-b-10 m-l-5"
                                            data-toggle="modal" data-target="#deleteRoomModal"
                                            onclick="idRoom({{ $room->id }})"><i class="fa fa-trash fa-lg"></i></button>
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


        <!-- модальное окно добавления нового помещения -->
        <div class="modal" id="addNewRoom">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title"> Добавить новое помещение</h4>
                    </div>


                    <div class="modal-body">

                        Название помещения: <input type="text" class="form-control input-default col-sm-4" id="nameRoom" size="15"><br><br>

                        Изображение: <img src="{{ asset('ela/images/rooms/noimage.png') }}" id="image" style="background: black;"  >

                        <button data-toggle="modal" data-target="#selectImage"  class="btn btn-default m-b-10"
                        onclick="updateImage({{ $room->id }}, false);"> Выбрать</button><br><br>

                        Цветовая схема: <label class="btn btn-default" id="color"></label> &nbsp; &nbsp;
                            <button data-toggle="modal" data-target="#selectColor" onclick="updateColor({{ $room->id }}, false)"
                                    class="btn btn-default m-b-10">Выбрать
                            </button><br><br>

                    </div>




                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                        <button type="button"   class="btn btn-primary" data-dismiss="modal"  onclick="addRoom();" >Добавить</button>

                    </div>
                </div>
            </div>
        </div>


        <!-- модальное окно выбора изображения -->
        <div class="modal" id="selectImage">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Выбрать изображение</h4>
                    </div>
                    <div class="modal-body" style="background: black;" >

                        @foreach ($images as $image)
                            <img src="{{ asset('ela/images/rooms/'.$image) }}" style="cursor: pointer;" onclick="setImage('{{$image}}');"
                                 data-dismiss="modal">&nbsp;&nbsp;&nbsp;
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        

        
        
        <!-- модальное окно выбора цвета -->
        <div class="modal" id="selectColor">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title"> Выбрать цвет</h4>
                    </div>
                    
                    <div class="modal-body">
                        <button style="background:red;" data-dismiss="modal"  class="btn btn-default m-b-10" onclick="setColor('red'); ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                        <button style="background:green;" data-dismiss="modal"  class="btn btn-default m-b-10" onclick="setColor('green');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                        <button style="background:orange;" data-dismiss="modal"  class="btn btn-default m-b-10" onclick="setColor('orange');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                        <button style="background:blue;" data-dismiss="modal"  class="btn btn-default m-b-10" onclick="setColor('blue');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                    </div>
                    
                </div>
            </div>
        </div>



        <!-- модальное окно удаления помещения -->
        <div id="deleteRoomModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title"> Удалить помещение ?</h4>
                    </div>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                        <button type="button"   class="btn btn-primary" data-dismiss="modal" onclick="deleteRoom();" >Удалить</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- модальное окно изменения имени у помещения-->
        <div id="nameRoomModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title"> Назание помещения </h4>
                    </div>

                    <div class="modal-body" >

                        <input type="text" class="form-control input-default " id="nameModalData" placeholder="Input Default">
                        <button type="button" class="btn btn-default" onclick="no_name();">Убрать</button>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <button type="button"   class="btn btn-primary" data-dismiss="modal" onclick="saveNameRoom();" >Сохранить изменения</button>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
            <script src="{{ asset('ela/js/pagescripts/room.js') }}"></script>
@endsection
