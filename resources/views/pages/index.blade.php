@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Страницы'])
@endsection

@section('content')
    <div class="container-fluid">
        @include('pages.header')
        <div class="card">
            <div class="card-title"><h4>Меню</h4></div>
            <div class="card-body">
                @if(count($pages))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>Ссылка</th>
                                <th>Элементы</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pages as $page)
                                <tr id="tr{{$page->id}}">
                                    <td scope="row">{{ $page->id }}</td>
                                    <td>
                                        <a href="#" id="namePage_{{ $page->id }}"
                                           onclick="edit_name({{ $page->id }});"
                                           data-toggle="modal"
                                           data-target="#namePageModal">{{ $page->name }}</a>
                                    </td>
                                    <td>
                                        {{ $page->type }}
                                    </td>
                                    <td>
                                        <a href="#" id="linkPage_{{ $page->id }}"
                                           onclick="edit_link({{ $page->id }});"
                                           data-toggle="modal"
                                           data-target="#linkPageModal">{{ $page->link }}</a>
                                    </td>
                                    <td>
                                        {{ $page->countElements }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('menu.edit',[$page->id]) }}"
                                           class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-rounded m-b-10 m-l-5 del_btn"
                                                data-id="{{ $page->id }}" data-name="{{ $page->name }}">
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
                                <th>Тип</th>
                                <th>Ссылка</th>
                                <th>Элементы</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $pages->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $pages->total() }}</p>
                @else
                    <p>Данные не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
    @include('pages.index_modals')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/page.js') }}"></script>
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
                const delRoomsMessage = parent === 'группу' ? ' и все ее помещения' : '';
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
