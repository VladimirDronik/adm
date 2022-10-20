@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Настройки оповещений'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('notifications.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Параметры оповещений</h4></div>
            <div class="card-body">
                @if(count($notifications))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>Приоритет</th>
                                    <td>Текстом</td>
                                    <td>Зуком</td>
                                    <th style="width: 60px;"></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                    <tr id="tr{{$notification->id}}">
                                        <td><a href="{{ route('notifications.edit', [$notification->id]) }}">{{ $notification->name }}</a></td>
                                        <td>{{ $notification->type }}</td>
                                        <td>
                                            @if($notification->priority == 1) <span class="badge badge-danger">важные</span> @elseif($notification->priority == 2) <span class="badge badge-info">обычные</span> @else <span class="badge badge-secondary">не назначено</span> @endif
                                        </td>
                                        <td>
                                            @if($notification->text_flag == null) <span class="badge badge-danger">Выкл</span> @else <span class="badge badge-info">Вкл</span> @endif
                                        </td>
                                        <td>
                                            @if($notification->sound_flag == null) <span class="badge badge-danger">Выкл</span> @else <span class="badge badge-info">Вкл</span> @endif
                                        </td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('notifications.edit', [$notification->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>Приоритет</th>
                                    <td>Текстом</td>
                                    <td>Зуком</td>
                                    <th style="width: 60px;"></th>

                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $notifications->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $notifications->total() }}</p>
                @else
                    <p>Настройки не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
@endsection

@section('scripts')

@endsection
