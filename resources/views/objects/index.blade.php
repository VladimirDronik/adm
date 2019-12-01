@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Объекты'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('objects.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить объект</a>
                        <a href="{{ route('objects.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        <div class="pull-right">
                            <form class="form-inline my-2 my-lg-0" method="get">
                                <input class="form-control mr-sm-2" type="text" name="name" value="{{ $filter_name }}" placeholder="Поиск по названию" aria-label="Поиск">
                                <button class="btn btn-primary p-l-30 p-r-30 my-2 my-sm-0" type="submit">Найти</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <h4>Объекты</h4>
                @if(count($objects))
                    <button class="btn btn-outline-danger pull-right" id="deleteAllBtn">Удалить все объекты</button>
                @endif
            </div>
            <div class="card-body">
                @if(count($objects))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="no-border-top">
                                    <th style="width: 40px;" class="text-center"><input type="checkbox" style="cursor: pointer;" id="allCheckbox" autocomplete="off"></th>
                                    <th style="width: 65px;" class="text-center">Тип</th>
                                    <th>Название</th>
                                    <th>Статус</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($objects as $object)
                                    <tr id="tr{{$object->id}}">
                                        <td  style="width: 40px;" class="text-center">
                                            <input type="checkbox" style="cursor: pointer;" autocomplete="off"
                                                   data-id="{{ $object->id }}" class="js-object-checkbox">
                                        </td>
                                        <td class="text-center">
                                            @if($object->type === 'lamp')
                                                <img width="30" height="30" title="{{ $object->rus_type }}" src="{{ asset('ela/images/objects/'.$object->type.'.png') }}">
                                            @elseif($object->type === 'socket')
                                                <img width="35" height="35" title="{{ $object->rus_type }}" src="{{ asset('ela/images/objects/'.$object->type.'.png') }}">
                                            @elseif($object->type === 'termo')
                                                <img width="60" height="35" title="{{ $object->rus_type }}" src="{{ asset('ela/images/objects/'.$object->type.'.png') }}">
                                            @elseif($object->type === 'hydro')
                                                <img width="50" height="35" title="{{ $object->rus_type }}" src="{{ asset('ela/images/objects/'.$object->type.'.png') }}">
                                            @else
                                                <img width="60" height="40" title="{{ $object->rus_type }}" src="{{ asset('ela/images/objects/'.$object->type.'.png') }}">
                                            @endif
                                        </td>
                                        <td><a href="{{ route('objects.edit',[$object->id]) }}">{{ $object->name }}</a></td>
                                        <td>
                                            @if(strtoupper($object->status) === 'ON')
                                                <span class="badge badge-success">{{ $object->status }}</span>
                                            @else
                                                <span class="badge badge-primary">{{ $object->status }}</span>
                                            @endif
                                        </td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('objects.edit',[$object->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $object->id }}" data-name="{{ $object->name }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th class="text-center">Тип</th>
                                    <th>Название</th>
                                    <th>Статус</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $objects->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $objects->total() }}</p>
                @else
                    <p>Объекты не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')

    <div id="del_all_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="del_all_modal_title">Подтверждение</h5>
                </div>
                <div class="modal-body text-left" id="del_all_modal_body" style="font-size: larger;"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="del_all_modal_btn">Удалить</button>
                    <button type="button" class="btn btn-default" id="del_all_cancel_btn" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>

    <button type="button" id="del_all_init_btn" style="display: none;" data-toggle="modal" data-target="#del_all_modal">&nbsp;</button>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить объект «'+$(this).attr('data-name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                $('#del_cancel_btn').click();
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.objects.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении объекта');
                            }
                        }
                    });
                }
            });

            // checkboxes

            let del_ids = [];
            const reloadUrl = '{{ route('objects.index') }}'
            const deleteAllBtn = $('#deleteAllBtn');

            deleteAllBtn.click(function () {
                const message = del_ids.length
                    ? 'Удалить выбранные объекты (' + del_ids.length + ')?'
                    : 'Удалить все объекты?';
                $('#del_all_modal_body').text(message);
                $('#del_all_init_btn').click();
            });

            $('#del_all_modal_btn').click(function(){
                $('#del_all_cancel_btn').click();
                $.ajax({
                    url: '{{ route('ajax.objects.delete.all') }}',
                    data: {'_token': _token, 'ids': del_ids},
                    success: function (data) {
                        if (data.result) {
                            window.location = reloadUrl;
                        } else {
                            showErrorModal('Ошибка при удалении объектов');
                        }
                    }
                });
            });

            function updateDeleteAllBtn() {
                if (del_ids.length) {
                    deleteAllBtn.text('Удалить выбранные объекты (' + del_ids.length + ')');
                } else {
                    deleteAllBtn.text('Удалить все объекты');
                }
            }

            $('.js-object-checkbox').change(function() {
                const id = parseInt($(this).attr('data-id'));
                if (this.checked) {
                    if (del_ids.indexOf(id) === -1) {
                        del_ids.push(id);
                    }
                } else {
                    for (let i = del_ids.length - 1; i >= 0; i--) {
                        if (del_ids[i] === id) {
                            del_ids.splice(i, 1);
                            break;
                        }
                    }
                }

                updateDeleteAllBtn();
            });

            function pushAllCheckboxesIds() {
                $(".js-object-checkbox").each(function () {
                    del_ids.push(parseInt($(this).attr('data-id')));
                });
            }

            $('#allCheckbox').change(function(){

                del_ids = [];

                $('.js-object-checkbox').prop('checked', this.checked);
                if (this.checked) {
                    pushAllCheckboxesIds();
                }

                updateDeleteAllBtn();
            });
        });
    </script>
@endsection
