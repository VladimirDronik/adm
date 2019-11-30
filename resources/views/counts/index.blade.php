@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Устройства: счетчики'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('counts.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить счетчик</a>
                        <a href="{{ route('counts.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Счетчики</h4></div>
            <div class="card-body">
                @if(count($counts))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Тип</th>
                                    <th>Название</th>
                                    <th>Объект</th>
                                    <th>Кол-во импульсов</th>
                                    <th>Ед.изм.</th>
                                    <th>Значение за сегодня</th>
                                    <th>Общее значение</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($counts as $count)
                                    <tr id="tr{{$count->id}}">
                                        <td>
                                            <img src="{{ asset('ela/images/counts/'.$count->image) }}" title="{{ $count->rus_type }}" alt="{{ $count->rus_type }}" width="30" height="30">
                                        </td>
                                        <td><a href="{{ route('counts.edit', [$count->id]) }}">{{ $count->name }}</a></td>
                                        <td>@if($count->object)
                                                <a href="{{ route('objects.edit', [$count->id_object]) }}">{{ optional($count->object)->name }}</a>
                                            @else
                                                Не указан
                                            @endif
                                        </td>
                                        <td>{{ $count->impulse }}</td>
                                        <td>{{ $count->unit }}</td>
                                        <td>{{ $count->today_value}}</td>
                                        <td>{{ $count->total_value}}</td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('counts.edit',[$count->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $count->id }}" data-name="{{ $count->name }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Тип</th>
                                    <th>Название</th>
                                    <th>Объект</th>
                                    <th>Кол-во импульсов</th>
                                    <th>Ед.изм.</th>
                                    <th>Значение за сегодня</th>
                                    <th>Общее значение</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $counts->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $counts->total() }}</p>
                @else
                    <p>Счетчики не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
@endsection

@section('scripts')
    <script>
        let url = '{{ route('counts.index') }}';

        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить счетчик «'+$(this).attr('data-name')+'»?');
                $('#del_modal').modal('show');
            });

            $('#del_modal_btn').click(function(){
                $('#del_modal').modal('hide');
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.counts.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении счетчика');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
