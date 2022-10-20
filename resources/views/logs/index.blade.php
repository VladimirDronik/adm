@extends('layouts._layout')

@section('breadcrumbs')
    @include('components.breadcrumbs', ['title' => 'Логирование'])
@endsection

@section('css')
    <link href="{{ asset('ela/css/lib/datepicker/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('logs.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        <a href="{{ route('logs.settings') }}" class="btn btn-success m-b-10 m-l-5">Настройки</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="pull-right">
                            <form class="form-inline my-2 my-lg-0" method="get">
                                <input class="form-control mr-sm-2" id="filter_start" type="text" autocomplete="off" name="start" value="{{ $filter['start'] }}" placeholder="Начальная дата" aria-label="Поиск">
                                <input class="form-control mr-sm-2"  id="filter_end" type="text" autocomplete="off" name="end" value="{{ $filter['end'] }}" placeholder="Конечная дата" aria-label="Поиск">
                                <select class="form-control form-control-lg" autocomplete="off" name="type" style="font-size: 1rem;">
                                    <option value="" @if($filter['type'] == '') selected @endif>Все типы</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" @if($filter['type'] == $type) selected @endif>{{ $type }}</option>
                                    @endforeach
                                </select>
                                <button class="form-control btn btn-primary m-l-4 p-l-50 p-r-50 my-2 my-sm-0" id="filter_btn" type="submit">Найти</button>
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
                                    <th style="width: 180px;">Дата</th>
                                    <th class="text-left">Тип</th>
                                    <th class="text-left">Описание</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->date->format('d.m.y H:i:s') }}</td>
                                        <td class="text-left">{{ $log->rus_type }}</td>
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

    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('ela/js/lib/datepicker/datepicker-ru.min.js') }}"></script>
    <script src="{{ asset('ela/js/lib/moment/moment.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#reset_btn').click(function() {
                window.location = '{{ route('logs.index') }}';
            });

            const datepicker_options = {
                format: "dd.mm.yyyy",
                language: "ru",
                autoclose: true
            };

            $('#filter_start').datepicker(datepicker_options);
            $('#filter_end').datepicker(datepicker_options);

            $('#filter_btn').click(function() {

                let start = $('#filter_start').val().trim();
                if (start !== '' && !moment(start,"DD.MM.YYYY", true).isValid()) {
                    $('#info_modal_body').text('Недопустимая начальная дата. Дата должна быть в формате dd.mm.yyyy');
                    $("#info_modal").modal('show');
                    return false;
                }

                let end = $('#filter_end').val().trim();
                if (end !== '' && !moment(end,"DD.MM.YYYY", true).isValid()) {
                    $('#info_modal_body').text('Недопустимая конечная дата. Дата должна быть в формате dd.mm.yyyy');
                    $("#info_modal").modal('show');
                    return false;
                }

                if (start !== '' && end !== '' && moment(end,"DD.MM.YYYY", true).isBefore(moment(start,"DD.MM.YYYY", true))) {
                    $('#info_modal_body').text('Начальная дата должна быть раньше конечной');
                    $("#info_modal").modal('show');
                    return false;
                }

                return true;
            });
        });
    </script>
@endsection
