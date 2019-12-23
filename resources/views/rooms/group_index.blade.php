@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
            'title' => 'Помещения группы «'.$group->name.'»',
            'links' => [route('rooms.index') => 'Помещения'],
            'last_link' => 'Помещения группы'
        ])
@endsection

@section('content')
    <div class="container-fluid">
        @include('rooms.header')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-10">
                        @if(count($rooms))
                            @include('rooms.tab_header', ['active' => $group->id])
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 50px;">ID</th>
                                        <th>Название</th>
                                        <th>Изображение</th>
                                        <th>Цвет</th>
                                        <th class="text-center">Сортировка</th>
                                        <th class="text-center" style="width: 80px;"></th>
                                        <th class="text-center" style="width: 80px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rooms as $room)
                                        <tr>
                                            <td>{{ $room->id }}</td>
                                            <td>
                                                <a href="#" id="nameRoom_{{ $room->id }}"
                                                   onclick="edit_name({{ $room->id }});"
                                                   data-toggle="modal"
                                                   data-target="#nameRoomModal">{{ $room->name }}</a>
                                            </td>
                                            <td><img src="{{ asset('ela/images/rooms/'.$room->image) }}"
                                                     id="imageRoom_{{ $room->id }}" class="imageRoom"
                                                     data-toggle="modal" data-target="#selectImage"
                                                     onclick="updateImage({{ $room->id }}, true);">
                                            </td>
                                            <td>
                                                <button style="background: {{ $room->color_style }}"
                                                        id="colorRoom_{{ $room->id }}" class="btn btn-default"
                                                        data-toggle="modal" data-target="#selectColor"
                                                        onclick="updateColor({{ $room->id }}, true)">
                                                </button>
                                            </td>
                                            <td style="width: 150px;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control input-default" readonly
                                                               value="{{ $room->sort }}">
                                                    </div>
                                                    <div class="col-md-6 text-left">
                                                        <button type="button" class="btn btn-info btn-xs"
                                                                id="sortBtn{{ $room->id }}"
                                                                onclick="changeSort({{ $room->id }}, 'up');">выше
                                                        </button>

                                                        <button type="button" class="btn btn-info btn-xs"
                                                                id="sortBtn{{ $room->id }}"
                                                                onclick="changeSort({{ $room->id }}, 'down');">ниже
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('rooms.edit',[$room->id]) }}"
                                                   class="btn btn-info btn-sm btn-rounded">
                                                    <i class="fa fa-cog fa-lg"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                        class="btn btn-danger btn-sm btn-rounded m-b-10 m-l-5 del_btn"
                                                        data-id="{{ $room->id }}" data-name="{{ $room->name }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    @if(count($rooms) > 10)
                                        <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Название</th>
                                            <th>Изображение</th>
                                            <th>Цвет</th>
                                            <th>Сортировка</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>

                            {{ $rooms->appends(request()->input())->links() }}
                            <p class="text-right">Найдено: {{ $rooms->total() }}</p>
                        @else
                            <p>Помещения не найдены</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')

    <!-- модальное окно добавления нового помещения -->
    <div class="modal" id="addNewRoom">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Добавить новое помещение</h4>
                </div>
                <div class="modal-body">
                    Название помещения: <input type="text" class="form-control input-default col-sm-12" id="nameRoom"
                                               size="15"><br><br>
                    Изображение: <img src="{{ asset('ela/images/rooms/noimage.png') }}" id="image"
                                      style="background: black;">
                    <button data-toggle="modal" data-target="#selectImage" class="btn btn-default btn-sm m-b-5"
                            onclick="updateImage(0, false);"> Выбрать
                    </button>
                    <br><br>
                    Цветовая схема: <label class="btn btn-default" id="color"></label> &nbsp; &nbsp;
                    <button data-toggle="modal" data-target="#selectColor" onclick="updateColor({{ 0 }}, false)"
                            class="btn btn-default btn-sm m-b-5">Выбрать
                    </button>
                    <br><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="store();">Добавить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- модальное окно выбора изображения -->
    <div class="modal" id="selectImage">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Выбрать изображение</h4>
                </div>
                <div class="modal-body" style="background: black;">
                    @foreach($images as $image)
                        <img src="{{ asset('ela/images/rooms/'.$image) }}" style="cursor: pointer;"
                             onclick="setImage('{{$image}}');"
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
                    @foreach($colors as $color)
                        <button style="background:{{$color->name}};" data-dismiss="modal" class="btn btn-default m-b-10"
                                onclick="setColor('{{$color->name}}'); ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- модальное окно изменения имени у помещения-->
    <div id="nameRoomModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Название помещения</h4>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control input-default " id="nameModalData"
                           placeholder="Введите название">
                    <button type="button" class="btn btn-default" onclick="no_name();">Убрать</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveNameRoom();">
                        Сохранить изменения
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/room.js') }}"></script>
    <script>
        const url = '{{ route('rooms.group.index', [$group->id]) }}';
        let del_id;

        function changeSort(id, direction) {
            $.ajax({
                url: '{{ route('ajax.rooms.sort') }}',
                data: {'_token': _token, 'id': id, 'direction': direction},
                success: function (data) {
                    if (data.result) {
                        window.location.href = url;
                    } else {
                        showErrorModal('Ошибка при сохранении изменений');
                    }
                }
            });
        }

        function del() {
            $('#del_modal').modal('hide');
            if (del_id) {
                $.ajax({
                    url: '{{ route('ajax.rooms.delete') }}',
                    data: {'_token': _token, 'id': del_id},
                    success: function (data) {
                        if (data.result) {
                            window.location.href = url;
                        } else {
                            showErrorModal('Ошибка при удалении помещения');
                        }
                    }
                });
            }
        }

        function store() {
            let name = $("#nameRoom").val().trim();
            let image = sessionStorage.getItem('imageRoom');
            let style = sessionStorage.getItem('colorRoom');

            sessionStorage.setItem('imageRoom', 'noimage.png');

            $.ajax({
                url: '{{ route('ajax.rooms.store') }}',
                data: {'_token': _token, 'name': name, 'image': image, 'style': style},
                success: function (data) {
                    if (data.result) {
                        window.location.href = url;
                    } else {
                        showErrorModal('Ошибка при добавлении помещения');
                    }
                }
            });
        }

        $(document).ready(function () {
            $('.del_btn').click(function () {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить помещение № ' + $(this).attr('data-id') +
                    ' «' + $(this).attr('data-name') + '»?');
                $('#del_modal').modal('show');
            });

            $('#del_modal_btn').click(del);
        });
    </script>
@endsection
