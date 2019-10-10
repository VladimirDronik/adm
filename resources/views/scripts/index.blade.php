@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Скрипты'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('scripts.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить скрипт</a>
                        <a href="{{ route('scripts.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
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
                <h4>Скрипты</h4>
            </div>
            <div class="card-body">
                @if(count($scripts))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Скрипт</th>
                                    <th>Кол-во выполнений</th>
                                    <th>Системный</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scripts as $script)
                                    <tr id="tr{{$script->id}}">
                                        <td><a href="{{ route('scripts.edit',[$script->id]) }}">{{ $script->name }}</a></td>
                                        <td><a href="{{ route('scripts.edit',[$script->id]) }}">{{ $script->link }}</a></td>
                                        <td>{{ (int)$script->count }}</td>
                                        <td>
                                            @if($script->system)
                                                <span class="badge badge-success">Да</span>
                                            @else
                                                <span class="badge badge-default">Нет</span>
                                            @endif
                                        </td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('scripts.edit',[$script->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $script->id }}" data-name="{{ $script->name }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Название</th>
                                    <th>Скрипт</th>
                                    <th>Кол-во выполнений</th>
                                    <th>Системный</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $scripts->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $scripts->total() }}</p>
                @else
                    <p>Скрипты не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить скрипт «'+$(this).attr('data-name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                $('#del_cancel_btn').click();
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.scripts.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении скрипта');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
