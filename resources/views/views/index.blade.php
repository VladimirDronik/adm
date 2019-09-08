@extends('layouts._layout')

@section('breadcrumbs')
    @if ($filter_room == '')
        @include('components.breadcrumbs', ['title' => 'Отображения'])
    @else
        @includeIf('components.breadcrumbs',
           ['title' => 'Отображения',
            'links' => [ route('views.index') => 'Отображения'],
            'last_link' => $filter_room_name])
    @endif
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
                                    <li><a href="{{ route('views.index') }}">Все помещения</a></li><hr>
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
                                @if($filter_room != '')
                                    <th>Сорт</th>
                                @endif
                                <th>Тип</th>
                                <th>Название</th>
                                <th>Статус</th>
                                <th>Вкл</th>
{{--                                <th>Выкл</th>--}}
                                <th>Вкл_надпись</th>
{{--                                <th>Выкл_надпись</th>--}}
{{--                                <th>Значение</th>--}}
                                <th>Объект</th>
                                <th>Метод</th>
                                <th>Помещение</th>
                                <th>Сцена</th>
{{--                                <th>Отступы</th>--}}
                                <th>Активно</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($views as $view)
                            <tr id="tr{{$view->id}}">
                                @if($filter_room!= '')
                                    <td scope="row">{{ $view->sort }}</td>
                                @endif
                                <td>{{ $view->rus_type_name }}</td>
                                <td><a href="{{ route('views.edit',[$view->id]) }}" title="{{ $view->description }}">{{ $view->name }}</a></td>
                                <td>
                                    @if($view->status === 'on')
                                        <span class="badge badge-success">{{ $view->status }}</span>
                                    @else
                                        <span class="badge badge-primary">{{ $view->status }}</span>
                                    @endif
                                </td>
                                <td scope="row">
                                    @if(!empty($view->on_image))
                                        <img src="{{ asset($view->on_image_path) }}" width="25" height="25" style="fill: green; background-color: #e8e8e8;">
                                    @endif
                                </td>
{{--                                <td scope="row">--}}
{{--                                    @if(!empty($view->off_image))--}}
{{--                                        <img src="{{ asset($view->off_image_path) }}" width="25" height="25" style="fill: green; background-color: #e8e8e8;">--}}
{{--                                    @endif--}}
{{--                                </td>--}}
                                <td scope="row">{{ $view->short_on_title }}</td>
{{--                                <td scope="row">{{ $view->short_off_title }}</td>--}}
{{--                                <td scope="row">{{ $view->value }}</td>--}}
                                <td>
                                    @if($view->id_object)
                                        <a href="{{ route('objects.edit',$view->id_object) }}">{{ $view->object_name }}</a>
                                    @endif
                                </td>
                                <td>
                                    @if($view->id_method)
                                        @if($view->id_object)
                                            <a href="{{ route('objects.edit',$view->id_object) }}">{{ $view->method_name }}</a>
                                        @else
                                            <a href="{{ route('objects.edit',optional($view->emethod)->id_object) }}">{{ $view->method_name }}</a>
                                        @endif
                                    @endif
                                </td>
                                <td scope="row">{{ $view->room_name }}</td>
                                <td scope="row">
                                    @if($view->scene)
                                        <a href="{{ route('scenes.edit',$view->scene) }}">{{ optional($view->escene)->label }}</a>
                                    @endif
                                </td>
{{--                                <td scope="row">{{ $view->position_left }} / {{ $view->position_top }}</td>--}}
                                <td scope="row" align="center">
                                    <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$view->id}}" value="1" @if($view->active) checked @endif>
                                </td>
                                <td align="center">
                                    <a href="{{ route('views.edit',[$view->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </a>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                            data-id="{{ $view->id }}" data-name="{{ $view->rus_type_name }}">
                                        <i class="fa fa-trash fa-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                @if($filter_room != '')
                                    <th>Сорт</th>
                                @endif
                                <th>Тип</th>
                                <th>Название</th>
                                <th>Статус</th>
                                <th>Вкл</th>
{{--                                <th>Выкл</th>--}}
                                <th>Вкл_надпись</th>
{{--                                <th>Выкл_надпись</th>--}}
{{--                                <th>Значение</th>--}}
                                <th>Объект</th>
                                <th>Метод</th>
                                <th>Помещение</th>
                                <th>Сцена</th>
{{--                                <th>Отступы</th>--}}
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
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
   <script>
       $(document).ready(function(){
           let del_id;

           $('.del_btn').click(function() {
               del_id = $(this).attr('data-id');
               $('#del_modal_body').text('Удалить отображение «'+$(this).attr('data-name')+'»?');
               $('#del_modal').modal('show');
           });

           $('#del_modal_btn').click(function(){
              $('#del_modal').modal('hide');
              if (del_id) {
                  $.ajax({
                      url: '{{ route('ajax.views.delete') }}',
                      data: { '_token': _token, 'id': del_id },
                      success: function (data) {
                          if (data.result) {
                              $('#tr'+del_id).hide();
                          } else {
                              showErrorModal('Ошибка при удалении отображения');
                          }
                      }
                  });
              }
           });

           //

           $('.active_checkbox').change(function(){
               let active = this.checked ? 1 : 0;
               let view_id = $(this).attr('data-id');

               $.ajax({
                   url: '{{ route('ajax.views.active') }}',
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
