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
            </div>
            <div class="card-body">
                @if(count($objects))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>Статус</th>
                                    <th>Отображение</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($objects as $object)
                                    <tr id="tr{{$object->id}}">
                                        <td scope="row">{{ $object->id }}</td>
                                        <td><a href="{{ route('objects.edit',[$object->id]) }}">{{ $object->name }}</a></td>
                                        <td>{{ $object->rus_type }}</td>
                                        <td>
                                            @if($object->status === 'on')
                                                <span class="badge badge-success">{{ $object->status }}</span>
                                            @else
                                                <span class="badge badge-primary">{{ $object->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($object->view)
                                                <a href="{{ route('views.edit',[$object->view]) }}" title="Перейти к отображению">
                                                    {{ optional($object->eview)->name }}
                                                </a>
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
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>Статус</th>
                                    <th>Отображение</th>
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
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить объект «'+$(this).attr('data-name')+'»?');
                $('#del_modal').modal('show');
            });

            $('#del_modal_btn').click(function(){
                $('#del_modal').modal('hide');
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
        });
    </script>
@endsection
