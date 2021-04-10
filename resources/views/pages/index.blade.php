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
                                    <td>{{ $page->link }}</td>
                                    <td>
                                        {{ $page->countElements }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('pages.edit',[$page->id]) }}"
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
        const deleteUrl = '{{ route('ajax.page.delete') }}';
        const storeUrl = '{{ route('ajax.page.store') }}';
        let url = '{{ route('pages.index') }}';
        let del_id;



        $(document).ready(function(){

            $('.del_btn').click(function () {

                del_id = $(this).data('id');

                $('#del_modal_body').text('Удалить страницу № ' + $(this).data('id') +
                    ' «' + $(this).data('name') + '» и все находящиеся в ней помещения ?');
                $('#del_init_btn').click();
            });


            $('#del_modal_btn').click(del);


            $('#addPageBtn').click(function() {
                $('#modalPage #modalType').val('2field');
                $('#modalPage #modal_groups_div').show();
                $('#modalPage #namePage').val('');
                $('#modal_page_init_btn').click();
            });

        });
    </script>
@endsection
