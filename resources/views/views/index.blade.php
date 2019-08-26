@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Отображения</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                @if ($filter_room == '')
                    <li class="breadcrumb-item active">Отображения</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ route('views.index') }}">Отображения</a></li>
                    <li class="breadcrumb-item active">{{$filter_room_name}}</li>
                @endif
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
                        <a href="{{ route('views.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить отображение</a>
                        <a href="{{ route('views.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="pull-right">
                            <div class="dropdown room-filter" id="room-filter">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    @if($filter_room == '')
                                        Фильтр по помещению
                                    @else
                                        Фильтр по помещению: {{$filter_room_name ?? ''}}
                                        @php($filter_room = 'для помещения: '.($filter_room_name ?? ''))
                                    @endif
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('views.index') }}">Все помещения</a></li>
                                    <hr>
                                    <li><a href="{{ route('views.index',['room' => 0]) }}">{{ \App\Models\Room::COMMON_NAME }}</a></li>
                                    <hr>
                                    @foreach($rooms as $room)
                                        <li>
                                            <a href="{{ route('views.index',['room' => $room->id]) }}">
                                                <label style="background-color:{{ $room->style }}">&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;{{ $room->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <a href="{{ route('views.index') }}" class="btn btn-success m-b-10 m-l-5">Сбросить</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <h4>Элементы отображения {{$filter_room}}</h4>
            </div>
            <div class="card-body">
                @if(count($views))
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                @if ($filter_room!= '')
                                    <th>Сорт</th>
                                @endif
                                <th>Тип</th>
                                <th>Название</th>
                                <th>Статус</th>
                                <th>Вкл</th>
                                <th>Выкл</th>
                                <th>Вкл_надпись</th>
                                <th>Выкл_надпись</th>
                                <th>Значение</th>
                                <th>Помещение</th>
                                <th>Сцена</th>
                                <th>Отступы</th>
                                <th>Активно</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($views as $view)
                            <tr id="t{{$view->id}}">
                                @if($filter_room!= '')
                                    <td scope="row">{{ $view->sort }}</td>
                                @endif
                                <td>{{ $view->rus_type_name }}</td>
                                <td><a href="#" title="{{ $view->description }}">{{ $view->name }}</a></td>
                                <td>
                                    @if($view->status === 'on')
                                        <span class="badge badge-success">{{ $view->status }}</span>
                                    @else
                                        <span class="badge badge-primary">{{ $view->status }}</span>
                                    @endif
                                </td>
                                <td scope="row">
                                    @if(!empty($view->on_image))
                                        <img src="{{ asset($view->on_image_path) }}" width="25" height="25" style="fill: green;">
                                    @endif
                                </td>
                                <td scope="row">
                                    @if(!empty($view->off_image))
                                        <img src="{{ asset($view->off_image_path) }}" width="25" height="25" style="fill: green;">
                                    @endif
                                </td>
                                <td scope="row">{{ $view->short_on_title }}</td>
                                <td scope="row">{{ $view->short_off_title }}</td>
                                <td scope="row">{{ $view->value }}</td>
                                <td scope="row"><a href="#">{{ $view->room_name }}</a></td>
                                <td scope="row"><a href="#">{{ optional($view->escene)->label }}</a></td>
                                <td scope="row">{{ $view->position_left }} / {{ $view->position_top }}</td>
                                <td scope="row" align="center">
                                    <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$view->id}}" value="1" @if($view->active) checked @endif>
                                </td>
                                <td align="center">
                                    <a href="{{ route('views.edit',[$view->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </a>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-id="{{ $view->id }}"><i class="fa fa-trash fa-lg"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                @if ($filter_room != '')
                                    <th>Сорт</th>
                                @endif
                                <th>Тип</th>
                                <th>Название</th>
                                <th>Статус</th>
                                <th>Вкл</th>
                                <th>Выкл</th>
                                <th>Вкл_надпись</th>
                                <th>Выкл_надпись</th>
                                <th>Значение</th>
                                <th>Помещение</th>
                                <th>Сцена</th>
                                <th>Отступы</th>
                                <th>Активно</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $views->appends(request()->input())->links() }}
                <p class="text-right">Найдено: {{ $views->total() }}</p>
                @else
                    <p>Отображения не найдены</p>
                @endif
            </div>
        </div>
    </div>

    <div id="del_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Удалить отображение?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Удалить</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
   <script>
       $(document).ready(function(){
           let del_id;

           $('.del_btn').change(function() {
              $('#del_modal').show();
           });

           $('.active_checkbox').change(function(){
               let active = this.checked ? 1 : 0;
               let view_id = $(this).attr('data-id');

               $.ajax({
                   url: '{{ route('ajax.views.delete') }}',
                   data: { '_token': _token, 'data': data },
                   success: function (data) {
                       if (data.result) {
                           $('')
                           showSuccessModal('Отображение успешно удалено');
                       } else {
                           showErrorModal('Ошибка при удалении отображения');
                       }
                   },
                   error: function() {
                       showErrorModal('Сервер временно недоступен');
                   }
               });
           });

           //

           $('.active_checkbox').change(function(){
               let active = this.checked ? 1 : 0;

               $.ajax({
                   url: '{{ route('ajax.views.active') }}',
                   data: { '_token': _token, 'data': data },
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
