@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Помещения'])
@endsection

@section('content')
    <div class="container-fluid">
        @include('rooms.header')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-10">
                        @if(count($rooms))
                            @include('rooms.tab_header', ['active' => 'groups'])
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">ID</th>
                                            <th>Тип</th>
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
                                                @if($room->is_group) Группа @else Помещение @endif
                                            </td>
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
                                                @if(!$room->is_group)
                                                    <a href="{{ route('rooms.edit', [$room->id]) }}"
                                                       class="btn btn-info btn-sm btn-rounded">
                                                        <i class="fa fa-cog fa-lg"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                        class="btn btn-danger btn-sm btn-rounded m-b-10 m-l-5 del_btn"
                                                        data-id="{{ $room->id }}"
                                                        data-type="{{ $room->is_group ? 'группу' : 'помещение' }}"
                                                        data-name="{{ $room->name }}">
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
                                                <th>Тип</th>
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

    @include('rooms.index_modals')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/room.js') }}"></script>
    <script>
        const url = '{{ route('rooms.index') }}';
        const sortUrl = '{{ route('ajax.rooms.sort') }}';
        const deleteUrl = '{{ route('ajax.rooms.delete') }}';
        const storeUrl = '{{ route('ajax.rooms.store') }}';
        let del_id;

        $(document).ready(function () {
            $('.del_btn').click(function () {
                del_id = $(this).data('id');
                const type = $(this).data('type');
                const delRoomsMessage = type === 'группу' ? ' и все ее помещения' : '';
                $('#del_modal_body').text('Удалить '+ type +' № ' + $(this).data('id') +
                    ' «' + $(this).data('name') + '»'+delRoomsMessage+'?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(del);

            // add

            $('#addRoomBtn').click(function() {
                $('#modalRoom #modalRoomTitle').text('Добавить новое помещение');
                $('#modalRoom #modalType').val('room');
                $('#modalRoom #modal_groups_div').show();
                $('#modalRoom #nameRoom').val('');
                $('#modal_room_init_btn').click();
            });

            $('#addGroupBtn').click(function() {
                $('#modalRoom #modalRoomTitle').text('Добавить новую группу');
                $('#modalRoom #modalType').val('group');
                $('#modalRoom #modal_groups_div').hide();
                $('#modalRoom #nameRoom').val('');
                $('#modal_room_init_btn').click();
            });
        });
    </script>
@endsection
