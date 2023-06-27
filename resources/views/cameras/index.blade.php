@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('cameras.breadcrumbs', ['title' => 'Камеры'])
@endsection

@section('content')
    <div class="container-fluid">
        @include('cameras.header')
        <div class="card">
            <div class="card-title"><h4>Камеры</h4></div>
            <div class="card-body">
                @if(count($cameras))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>Размещение</th>
                                <th>Изображение</th>
                                <th class="text-center">Сортировка</th>
                                <th class="text-center">Активно</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cameras as $camera)
                                <tr id="tr{{$camera->id}}">
                                    <td scope="row">{{ $camera->id }}</td>
                                    <td>
                                        {{ $camera->name }}
                                    </td>
                                    <td>
                                        {{ $camera->type }}
                                    </td>
                                    <td>
                                        {{ $camera->relationRoom->name }}
                                    </td>
                                    <td scope="row">
                                        <img src="{{ $camera->image }}" width="80" height="80">
                                    </td>
                                    <td style="width: 150px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control input-default" readonly
                                                       value="{{ $camera->sort }}">
                                            </div>
                                            <div class="col-md-6 text-left">
                                                <button type="button" class="btn btn-info btn-xs"
                                                        id="sortBtn{{ $camera->id }}"
                                                        onclick="changeSort({{ $camera->id }}, 'up');">выше
                                                </button>
                                                <button type="button" class="btn btn-info btn-xs"
                                                        id="sortBtn{{ $camera->id }}"
                                                        onclick="changeSort({{ $camera->id }}, 'down');">ниже
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td scope="row" align="center">
                                        <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$camera->id}}" value="1" @if($camera->active) checked @endif>
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('cameras.edit',[$camera->id]) }}"
                                           class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                data-id="{{ $camera->id }}" data-name="{{ $camera->name }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            @if(count($cameras) > 10)
                                <tfoot>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Название</th>
                                        <th>Тип</th>
                                        <th>Размещение</th>
                                        <th>Изображение</th>
                                        <th class="text-center">Сортировка</th>
                                        <th class="text-center">Активно</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $cameras->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $cameras->total() }}</p>
                @else
                    <p>Данные не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        const url = '{{ route('cameras.index') }}';
        const sortUrl = '{{ route('ajax.cameras.sort') }}';

        function changeSort(id, direction) {
            $.ajax({
                url: sortUrl,
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

        $(document).ready(function () {
            $('.active_checkbox').change(function () {
                let active = this.checked ? 1 : 0;
                let camera_id = $(this).attr('data-id');

                $.ajax({
                    url: '{{ route('ajax.cameras.active') }}',
                    data: {'_token': _token, 'id': camera_id, 'active': active},
                    success: function (data) {
                        if (data.result) {
                            showSuccessModal('Активность успешно изменена');
                        } else {
                            showErrorModal('Ошибка при изменении активности');
                        }
                    },
                });
            });

            $('.del_btn').click(function () {
                del_id = $(this).attr('data-id');
                del_name = $(this).attr('data-name');
                $('#del_modal_body').text('Удалить камеру '+del_name+'?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function () {
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.cameras.delete') }}',
                        data: {'_token': _token, 'id': del_id},
                        success: function (data) {
                            if (data.result) {
                                $('#tr' + del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении отображения');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
