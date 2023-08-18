@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Устройства: дверные замки</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item active">Дверные замки</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('locks.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить</a>
                        <a href="{{ route('locks.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Дверные замки</h4></div>
            <div class="card-body">
                @if(count($locks))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Тип</th>
                                <th>Название</th>
                                <th style="width: 60px;"></th>
                                <th style="width: 60px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($locks as $lock)
                                <tr id="tr{{$lock->id}}">
                                    <td scope="row">{{ $lock->object['id'] }}</td>
                                    <td>
                                        {{ $lock->rus_type }}
                                    </td>
                                    <td><a href="{{ route('locks.edit', [$lock->id]) }}">{{ $lock->name }}</a></td>
                                    <td align="center" class="text-center">
                                        <a href="{{ route('locks.edit', [$lock->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td align="center" class="text-center">
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                data-id="{{ $lock->id }}" data-name="{{ $lock->name }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Тип</th>
                                <th>Название</th>
                                <th style="width: 60px;"></th>
                                <th style="width: 60px;"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $locks->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $locks->total() }}</p>
                @else
                    <p>Объекты не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
@endsection

@section('scripts')
    <script>
        let url = '{{ route('locks.index') }}';
        $(document).ready(function(){
            let del_id;
            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить замок № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });
            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.locks.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении замка');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
