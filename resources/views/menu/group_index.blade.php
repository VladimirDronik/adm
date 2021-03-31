@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
            'title' => 'Меню группы «'.$group->name.'»',
            'links' => [route('menu.index') => 'Группы меню'],
            'last_link' => 'Меню группы'
        ])
@endsection

@section('content')
    <div class="container-fluid">
        @include('menu.header')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-10">
                        @include('menu.tab_header', ['active' => $group->id])
                        @if(count($menus))
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">ID</th>
                                            <th>Название</th>
                                            <th>Изображение</th>
                                            <th>Активно</th>
                                            <th class="text-center">Сортировка</th>
                                            <th class="text-center" style="width: 80px;"></th>
                                            <th class="text-center" style="width: 80px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($menus as $menu)
                                        <tr>
                                            <td>{{ $menu->id }}</td>
                                            <td>
                                                <a href="#" id="nameMenu_{{ $menu->id }}"
                                                   onclick="edit_name({{ $menu->id }});"
                                                   data-toggle="modal"
                                                   data-target="#nameMenuModal">{{ $menu->title }}</a>
                                            </td>
                                            <td><img src="{{ asset('ela/images/views_items/'.$menu->image) }}"
                                                     id="imageMenu_{{ $menu->id }}" class="imageMenu"
                                                     data-toggle="modal" data-target="#selectImage"
                                                     onclick="updateImage({{ $menu->id }}, true);"
                                                     width="50px" height="50px">
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$menu->id}}" value="1" @if($menu->active) checked @endif>
                                            </td>
                                            <td style="width: 150px;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control input-default" readonly
                                                               value="{{ $menu->sort }}">
                                                    </div>
                                                    <div class="col-md-6 text-left">
                                                        <button type="button" class="btn btn-info btn-xs"
                                                                id="sortBtn{{ $menu->id }}"
                                                                onclick="changeSort({{ $menu->id }}, 'up');">выше
                                                        </button>

                                                        <button type="button" class="btn btn-info btn-xs"
                                                                id="sortBtn{{ $menu->id }}"
                                                                onclick="changeSort({{ $menu->id }}, 'down');">ниже
                                                        </button>
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
                                                        data-id="{{ $menu->id }}" data-name="{{ $menu->name }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    @if(count($menus) > 10)
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Название</th>
                                                <th>Изображение</th>
                                                <th>Активно</th>
                                                <th>Сортировка</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>

                            {{ $menus->appends(request()->input())->links() }}
                            <p class="text-right">Найдено: {{ $menus->total() }}</p>
                        @else
                            <p class="mt-3">Помещения не найдены</p>
                        @endif
                    </div>
                </div>
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
        const url = '{{ route('menu.group.index', [$group->id]) }}';
        const sortUrl = '{{ route('ajax.menu.sort') }}';
        const deleteUrl = '{{ route('ajax.menu.delete') }}';
        const storeUrl = '{{ route('ajax.menu.store') }}';
        let del_id;

        $(document).ready(function () {
            $('.del_btn').click(function () {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить помещение № ' + $(this).data('id') +
                    ' «' + $(this).data('name') + '»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(del);

            // add

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

        $(document).ready(function(){
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
        });

    </script>
@endsection
