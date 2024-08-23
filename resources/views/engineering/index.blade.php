@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Котлы'])
@endsection

@section('content')
    <div class="container-fluid">
        @include('engineering.header')
        <div class="card">
            <div class="card-title"><h4>Котлы</h4></div>
            <div class="card-body">
                @if(count($equipments))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <td style="width: 30px;"></td>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>Статус</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($equipments as $equipment)
                                <tr id="tr{{$equipment->id}}">
                                    <td scope="row">{{ $equipment->id }}</td>
                                    <td> @include('engineering.type_img', compact('equipment'))</td>
                                    <td>
                                        <a href="#" id="namePage_{{ $equipment->id }}"
                                           onclick="edit_name({{ $equipment->id }});"
                                           data-toggle="modal"
                                           data-target="#namePageModal">{{ $equipment->name }}</a>
                                    </td>
                                    <td>
                                        {{ $equipment->type }}
                                    </td>

                                    <td>
                                        @if( $equipment->status  === '1')
                                            <span class="badge badge-success">Активно</span>
                                        @else
                                            <span class="badge badge-danger">Недоступно</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route($equipment->type.'.edit',[$equipment->id]) }}"
                                           class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-rounded m-b-10 m-l-5 del_btn"
                                                data-id="{{ $equipment->id }}" data-name="{{ $equipment->name }}" data-type="{{ $equipment->type }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <td style="width: 30px;"></td>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>Статус</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                   {{ $equipments->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $equipments->total() }}</p>
                @else
                    <p>Данные не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('engineering.create_modal')
    @include('engineering.delete_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/page.js') }}"></script>
    <script>
        const deleteUrl = "{{ route('ajax.engineering.delete') }}";
        const addMenuUrl = "{{ route('ajax.menu.add') }}";

        let url = "{{ route('engineering.index') }}";
        let del_id;
        let del_checkbox;

        if (document.referrer == "{{ route('boiler.create') }}") {
            let idObject = "{{ Session::get('idObject') }}";

            addMenu(idObject);
        }

        $(document).ready(function(){

            $('.del_btn').click(function () {
                del_id = $(this).data('id');

                if ($(this).data('type') == 'boiler') {
                    $('#del_menu_modal_body').html('<h5>Удалить оборудование № ' + $(this).data('id') +
                    ' «' + $(this).data('name') + '»?</h5><p>Удалить меню и страницы связанные с устройством:&nbsp;&nbsp;<input style="cursor:pointer;" checked id="del_checkbox" name="del_checkbox" type="checkbox" value="1">');
                    $('#del_menu_init_btn').click();
                    del_checkbox = 1;
                    $('#del_checkbox').change(function() {
                        del_checkbox = this.checked ? 1 : 0;
                    });
                } else {
                    $('#del_menu_modal_body').html('<h5>Удалить оборудование № ' + $(this).data('id') +
                    ' «' + $(this).data('name') + '»?</h5>');
                    $('#del_menu_init_btn').click();
                }

            });

            $('#del_menu_modal_btn').click(delWithCheckbox);

            // $('#addPageBtn').click(function() {
            //     $('#modalPage #modal_groups_div').show();
            //     $('#modalPage #namePage').val('');
            //     $('#modal_page_init_btn').click();
            // });
        });
    </script>
@endsection
