@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Сцены'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('scenes.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить сцену</a>
                        <a href="{{ route('scenes.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Сцены</h4></div>
            <div class="card-body">
                @if(count($scenes))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Изображение</th>
                                    <th>Цвет фона</th>
                                    <th class="text-center">Активно</th>
                                    <th>Сортировка</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scenes as $scene)
                                    <tr id="tr{{$scene->id}}">
                                        <td scope="row">{{ $scene->id }}</td>
                                        <td><a href="{{ route('scenes.edit',[$scene->id]) }}">{{ $scene->label }}</a></td>
                                        <td>
                                            @if(!empty($scene->image))
                                                <img src="{{ asset('ela/images/scenes/'.$scene->image) }}" width="60">
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($scene->backgroung_color))
                                                <div style="border: 1px solid gray; width: 60px; height: 40px; background-color: {{ $scene->backgroung_color }};">&nbsp;&nbsp;</div>
                                            @else
                                                Не указан
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$scene->id}}" value="1" @if($scene->active) checked @endif>
                                        </td>
                                        <td style="width: 160px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control input-default" readonly
                                                           value="{{ $scene->sort }}">
                                                </div>
                                                <div class="col-md-6 text-left">
                                                    <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $scene->id }}"
                                                            onclick="changeSort({{ $scene->id }}, 'up');" >выше</button>

                                                    <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $scene->id }}"
                                                            onclick="changeSort({{ $scene->id }}, 'down');" >ниже</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('scenes.edit',[$scene->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $scene->id }}" data-name="{{ $scene->label }}">
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
                                    <th>Цвет фона</th>
                                    <th>Активно</th>
                                    <th>Сортировка</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $scenes->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $scenes->total() }}</p>
                @else
                    <p>Сцены не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
@endsection

@section('scripts')
    <script>
        let url = '{{ route('scenes.index') }}';

        function changeSort(id, direction) {
            $.ajax({
                url: '{{ route('ajax.scenes.sort') }}',
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
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить сцену № '+$(this).attr('data-id')+' «'+$(this).attr('data-name')+'»?');
                $('#del_modal').modal('show');
            });

            $('#del_modal_btn').click(function(){
                $('#del_modal').modal('hide');
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.scenes.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении сцены');
                            }
                        }
                    });
                }
            });

            $('.active_checkbox').change(function(){
                let active = this.checked ? 1 : 0;
                let view_id = $(this).attr('data-id');

                $.ajax({
                    url: '{{ route('ajax.scenes.active') }}',
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
