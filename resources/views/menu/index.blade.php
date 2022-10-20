@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Меню'])
@endsection

@section('content')
    <div class="container-fluid">
        @include('menu.header')
        <div class="card">
            <div class="card-title"><h4>Меню</h4></div>
            <div class="card-body">
                @if(count($menus))
                    @include('menu.tab_header', ['active' => 'groups'])
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Название</th>
                                <th>Изображение</th>
                                <th class="text-center">Активно</th>
                                <th>Сортировка</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($menus as $menu)
                                <tr id="tr{{$menu->id}}">
                                    <td scope="row">{{ $menu->id }}</td>
                                    <td>
                                        <a href="#" id="nameMenu_{{ $menu->id }}"
                                           onclick="edit_name({{ $menu->id }});"
                                           data-toggle="modal"
                                           data-target="#nameMenuModal">{{ $menu->title }}</a>
                                    </td>
                                    <td>
                                    @if(!empty($menu->image))
                                        <!-- <img src="{{ asset($menu->image_path) }}" width="60" height="60" style="background-color: #e8e8e8;"> -->
                                            <img src="{{ asset('ela/images/views_items/'.$menu->image) }}"
                                                 id="imageMenu_{{ $menu->id }}" class="imageMenu"
                                                 data-toggle="modal" data-target="#selectImage"
                                                 onclick="updateImage({{ $menu->id }}, true);"
                                                 width="50px" height="50px">
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$menu->id}}" value="1" @if($menu->active) checked @endif>
                                    </td>
                                    <td style="width: 160px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control input-default" readonly
                                                       value="{{ $menu->sort }}">
                                            </div>
                                            <div class="col-md-6 text-left">
                                                <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $menu->id }}"
                                                        onclick="changeSort({{ $menu->id }}, 'up');" >выше</button>

                                                <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $menu->id }}"
                                                        onclick="changeSort({{ $menu->id }}, 'down');" >ниже</button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('menu.edit',[$menu->id]) }}"
                                           class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-rounded m-b-10 m-l-5 del_btn"
                                                data-type="{{ $menu->parent ? 'пункт меню' : 'группу' }}"
                                                data-id="{{ $menu->id }}" data-name="{{ $menu->name }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Название</th>
                                <th>Изображение</th>
                                <th>Активно</th>
                                <th>Сортировка</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $menus->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $menus->total() }}</p>
                @else
                    <p>Данные не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
    @include('menu.index_modals')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/menu.js') }}"></script>
    <script>
        const sortUrl = '{{ route('ajax.menu.sort') }}';
        const deleteUrl = '{{ route('ajax.menu.delete') }}';
        const storeUrl = '{{ route('ajax.menu.store') }}';
        let url = '{{ route('menu.index') }}';
        let del_id;
        function changeSort(id, direction) {
            $.ajax({
                url: '{{ route('ajax.menu.sort') }}',
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
        $(document).ready(function(){

            $('.del_btn').click(function () {
                del_id = $(this).data('id');
                const parent = $(this).data('type');
                const delRoomsMessage = parent === 'группу' ? ' и все её подгруппы' : '';
                $('#del_modal_body').text('Удалить ' + parent + ' № ' + $(this).data('id') +
                    ' «' + $(this).data('name') + '»' + delRoomsMessage + '?');

                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(del);
            $('.active_checkbox').change(function(){
                let active = this.checked ? 1 : 0;
                let view_id = $(this).attr('data-id');
                $.ajax({
                    url: '{{ route('ajax.menu.active') }}',
                    data: { '_token': _token, 'id': view_id, 'active': active},
                    success: function (data) {
                        if (data.result) {
                            showSuccessModal('Активность успешно изменена');
                        } else {
                            showErrorModal('Ошибка при изменении активности');
                        }
                    },
                });
            });
            $('#addMenuBtn').click(function() {
                $('#modalMenu #modalMenuTitle').text('Добавить новый пункт меню');
                $('#modalMenu #modalType').val('menu');
                $('#modalMenu #modal_groups_div').show();
                $('#modalMenu #nameMenu').val('');
                $('#modal_menu_init_btn').click();
            });
            $('#addGroupBtn').click(function() {
                $('#modalMenu #modalMenuTitle').text('Добавить новую группу меню');
                $('#modalMenu #modalType').val('group');
                $('#modalMenu #modal_groups_div').hide();
                $('#modalMenu #nameMenu').val('');
                $('#modal_menu_init_btn').click();
            });
        });
    </script>
@endsection