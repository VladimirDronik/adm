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
                                    <th>Название</th>
                                    <th>Периодичность</th>
                                    <th>Метод</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($events as $event)
                                <tr id="tr{{$event->id}}">
                                    <td><a href="{{ route('events.edit',[$event->id]) }}" title="{{ $event->name }}">{{ $event->name }}</a></td>
                                    <td>
                                        @if(count($event->points))

                                        @else
                                            <span class="text-danger">Расписание отсутствует</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(optional($event->emethod)->id_object)
                                            <a href="{{ route('objects.edit',[optional($event->emethod)->id_object]) }}">
                                                {{ optional($event->emethod)->name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('events.edit',[$event->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                data-id="{{ $event->id }}" data-name="{{ $event->name }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            @if(count($events) > 10)
                            <tfoot>
                                <tr>
                                    <th>Название</th>
                                    <th>Периодичность</th>
                                    <th>Метод</th>
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
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить событие «'+$(this).attr('data-name')+'»?');
                $('#del_modal').modal('show');
            });

            $('#del_modal_btn').click(function(){
                $('#del_modal').modal('hide');
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
        });
    </script>
@endsection
