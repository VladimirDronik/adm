@extends('layouts._layout')

@section('breadcrumbs')
    @if ($filter['type'] == '')
        @include('components.breadcrumbs', ['title' => 'Cобытия'])
    @else
        @includeIf('components.breadcrumbs',
           ['title' => 'События',
            'links' => [ route('events.index') => 'События'],
            'last_link' => $filter['type_name']])
    @endif
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('events.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить событие</a>
                        <a href="{{ route('events.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="pull-right">
                            <form class="form-inline my-2 my-lg-0" method="get">
                                <input class="form-control mr-sm-2" type="text" autocomplete="off" name="name" value="{{ $filter['name'] }}" placeholder="Поиск по названию" aria-label="Поиск">

                                <select class="form-control form-control-lg" autocomplete="off" name="type" style="font-size: 1rem;">
                                    <option value="" @if($filter['type'] == '') selected @endif>Все типы</option>
                                    @foreach($types as $key => $type)
                                        <option value="{{ $key }}" @if($filter['type'] == $key) selected @endif>{{ $type }}</option>
                                    @endforeach
                                </select>
                                <button class="form-control btn btn-primary m-l-4 p-l-50 p-r-50 my-2 my-sm-0" type="submit">Найти</button>
                                <button id="reset_btn" class="form-control btn btn-default m-l-6 my-2 my-sm-0" type="button">Сбросить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <h4>@if($filter['type'] == '') События @else {{ $filter['type_name'] }} события @endif </h4>
            </div>
            <div class="card-body">
                @if(count($events))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Активность</th>
                                    <th>Название</th>
                                    <th>Расписание</th>
                                    <th>Метод</th>
                                    <th>Скрипт</th>
                                    @if($can['events.show-system'])
                                        <th class="text-center">Системное</th>
                                    @endif
                                    @if($can['events.show-hidden'])
                                        <th class="text-center">Скрытое</th>
                                    @endif
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($events as $event)
                                <tr id="tr{{$event->id}}">
                                    <td class="text-center">
                                        <input type="checkbox" class="active_checkbox" style="cursor: pointer;"
                                               data-id="{{$event->id}}" value="1" @if($event->active) checked @endif>
                                    </td>
                                    <td>
                                        @if(!$event->is_system && !$event->is_hidden)
                                            <a href="{{ route('events.edit',[$event->id]) }}" title="{{ $event->name }}">
                                                <span @if($event->active) class="event-not-active" @endif>{{ $event->name }}</span>
                                            </a>
                                        @elseif(($event->is_system && $can['events.edit-system'])
                                            || ($event->is_hidden && $can['events.edit-hidden'] && !$event->is_system))
                                            <a href="{{ route('events.edit',[$event->id]) }}" title="{{ $event->name }}">
                                                <span @if($event->active) class="event-not-active" @endif>{{ $event->name }}</span>
                                            </a>
                                        @else
                                            <span @if($event->active) class="event-not-active" @endif>{{ $event->name }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php $count = count($event->points); @endphp
                                        @if($count > 0 && $count <= 3)
                                            <div @if($event->active) class="event-not-active" @endif>
                                                @foreach($event->points as $index => $point)
                                                    {!! $point->description !!}<br>
                                                @endforeach
                                            </div>
                                        @elseif($count > 3)
                                            <div @if($event->active) class="event-not-active" @endif>
                                                {!! $event->points[0]->description !!} <br>
                                                {!! $event->points[1]->description !!}
                                            </div>
                                            <button class="btn btn-sm btn-outline-info btn-outline btn-addon" type="button" data-toggle="collapse" data-target="#collapse{{$event->id}}" aria-expanded="false" aria-controls="{{$event->id}}">
                                                Еще ({{ $count - 2 }})
                                            </button>
                                            <div class="collapse" id="collapse{{$event->id}}" @if($event->active) style="color: #343a40 !important;" @endif>
                                                @foreach($event->points as $index => $point)
                                                    @if($index > 1)
                                                        {!! $point->description !!} <br>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-danger">Расписание отсутствует</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(optional($event->emethod)->id_object)
                                            @if($can['objects'])
                                                <a href="{{ route('objects.edit',[optional($event->emethod)->id_object]) }}">
                                                    <span @if($event->active) class="event-not-active" @endif>
                                                        {{ optional($event->emethod)->name }}
                                                    </span>
                                                </a>
                                            @else
                                                <span @if($event->active) class="event-not-active" @endif>
                                                    {{ optional($event->emethod)->name }}
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($event->escript)
                                            @if($can['scripts'])
                                                <a href="{{ route('scripts.edit', [$event->script]) }}">
                                                    <span @if($event->active) class="event-not-active" @endif>
                                                        {{ optional($event->escript)->name }}
                                                    </span>
                                                </a>
                                            @else
                                                <span @if($event->active) class="event-not-active" @endif>
                                                    {{ optional($event->escript)->name }}
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    @if($can['events.show-system'])
                                        <td class="text-center">
                                            <input type="checkbox" class="system_checkbox" style="cursor: pointer;"
                                                   @if(($event->emethod && $event->emethod->is_system) || !$can['events.edit-system']) disabled @endif data-id="{{$event->id}}" value="1" @if($event->is_system) checked @endif>
                                        </td>
                                    @endif
                                    @if($can['events.show-hidden'])
                                        <td class="text-center">
                                            <input type="checkbox" class="hidden_checkbox" style="cursor: pointer;"
                                                   @if(($event->emethod && $event->emethod->is_system) || !$can['events.edit-hidden'] || ($event->is_system && !$can['events.edit-system'])) disabled @endif data-id="{{$event->id}}" value="1" @if($event->is_hidden) checked @endif>
                                        </td>
                                    @endif
                                    <td align="center">
                                        @if($event->is_system && $can['events.edit-system'])
                                            <a href="{{ route('events.edit', [$event->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        @elseif($event->is_hidden && $can['events.edit-hidden'] && !$event->is_system)
                                            <a href="{{ route('events.edit', [$event->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        @elseif(!$event->is_system && !$event->is_hidden)
                                            <a href="{{ route('events.edit', [$event->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$event->emethod || !$event->emethod->is_system || user()->is_super_admin)
                                            @if($event->is_system && $can['events.delete-system'])
                                                <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                        data-id="{{ $event->id }}" data-name="{{ $event->name }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            @elseif($event->is_hidden && $can['events.delete-hidden'] && !$event->is_system)
                                                <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                        data-id="{{ $event->id }}" data-name="{{ $event->name }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            @elseif(!$event->is_system && !$event->is_hidden)
                                                <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                        data-id="{{ $event->id }}" data-name="{{ $event->name }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            @if(count($events) > 10)
                                <tfoot>
                                    <tr>
                                        <th>Активность</th>
                                        <th>Название</th>
                                        <th>Расписание</th>
                                        <th>Метод</th>
                                        <th>Скрипт</th>
                                        @if($can['events.show-system'])
                                            <th class="text-center">Системное</th>
                                        @endif
                                        @if($can['events.show-hidden'])
                                            <th class="text-center">Скрытое</th>
                                        @endif
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $events->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $events->total() }}</p>
                @else
                    <p>События не найдены</p>
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
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить событие «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.events.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении события');
                            }
                        }
                    });
                }
            });

            $('#reset_btn').click(function() {
                window.location = '{{ route('events.index') }}';
            });

            $('[data-toggle="collapse"]').click(function() {
                $(this).toggleClass("active");
                if ($(this).hasClass("active")) {
                    $(this).text("Свернуть");
                } else {
                    $(this).text("Подробнее");
                }
                $(this).remove();
            });

            // system and hidden checkboxes

            @if($can['events.edit-system'])
                $('.system_checkbox').change(function(){
                    let is_system = this.checked ? 1 : 0;
                    let id = $(this).data('id');

                    $.ajax({
                        url: '{{ route('ajax.events.system') }}',
                        data: { '_token': _token, 'id': id, 'is_system': is_system},
                        success: function (data) {
                            if (data.result) {
                                showSuccessModal('Изменения успешно сохранены');
                            } else {
                                showErrorModal('Ошибка при изменении свойств события');
                            }
                        },
                    });
                });
            @endif

            @if($can['events.edit-hidden'])
                $('.hidden_checkbox').change(function(){
                    let is_hidden = this.checked ? 1 : 0;
                    let id = $(this).data('id');

                    $.ajax({
                        url: '{{ route('ajax.events.hidden') }}',
                        data: { '_token': _token, 'id': id, 'is_hidden': is_hidden },
                        success: function (data) {
                            if (data.result) {
                                showSuccessModal('Изменения успешно сохранены');
                            } else {
                                showErrorModal('Ошибка при изменении свойств события');
                            }
                        },
                    });
                });
            @endif

            $('.active_checkbox').change(function(){
                let active = this.checked ? 1 : 0;
                let id = $(this).data('id');

                $.ajax({
                    url: '{{ route('ajax.events.active') }}',
                    data: { '_token': _token, 'id': id, 'active': active },
                    success: function (data) {
                        if (data.result) {
                            //showSuccessModal('Изменения успешно сохранены');
                            location.reload();
                        } else {
                            showErrorModal('Ошибка при изменении свойств события');
                        }
                    },
                });
            });
        });
    </script>
@endsection
