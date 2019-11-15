@extends('layouts._layout')

@section('breadcrumbs')
    @include('components.breadcrumbs', ['title' => 'Логирование'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('logs.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="pull-right">
                            <form class="form-inline my-2 my-lg-0" method="get">
                                <input class="form-control mr-sm-2" type="text" autocomplete="off" name="name" value="{{ $filter['start'] }}" placeholder="Поиск по названию" aria-label="Поиск">

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
                <h4>@if($filter['type'] == '') Логи @else {{ ucfirst(strtolower($filter['type'])) }} логи @endif </h4>
            </div>
            <div class="card-body">
                @if(count($logs))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th class="text-left">Тип</th>
                                    <th class="text-left">Описание</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->date->format('d.m.y H:i:s') }}</td>
                                        <td class="text-left">{{ $log->type }}</td>
                                        <td class="text-left">{{ $log->message }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if(count($logs) > 10)
                                <tfoot>
                                    <tr>
                                        <th>Дата</th>
                                        <th class="text-left">Тип</th>
                                        <th class="text-left">Описание</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $logs->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $logs->total() }}</p>
                @else
                    <p>Логи не найдены</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){

        });
    </script>
@endsection
