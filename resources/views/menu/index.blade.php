@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Меню'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                         <a href="{{ route('menu.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Меню</h4></div>
            <div class="card-body">
                @if(count($menus))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Изображение</th>
                                    <th class="text-center">Активно</th>
                                    <th>Сортировка</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menus as $menu)
                                    <tr id="tr{{$menu->id}}">
                                        <td scope="row">{{ $menu->id }}</td>
                                        <td>{{ $menu->title }}</td>
                                        <td>
                                            @if(!empty($menu->image))
                                                <img src="{{ asset($menu->image_path) }}" width="60" style="background-color: #e8e8e8;">
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$menu->id}}" value="1" @if($menu->active) checked @endif>
                                        </td>
                                        <td style="width: 160px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control input-default" readonly
                                                           value="{{ $menu->sort }}">
                                                </div>
                                                <div class="col-md-6 text-left">
                                                    <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $menu->id }}"
                                                            onclick="changeSort({{ $menu->id }}, 'up');" >выше</button>

                                                    <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $menu->id }}"
                                                            onclick="changeSort({{ $menu->id }}, 'down');" >ниже</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Изображение</th>
                                    <th>Активно</th>
                                    <th>Сортировка</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $menus->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $menus->total() }}</p>
                @else
                    <p>Данные не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script>
        let url = '{{ route('menu.index') }}';

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
