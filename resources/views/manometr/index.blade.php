@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Датчики: манометры</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item breadcrumb-item-no-link"><a href="{{ route('manometr.index') }}">Датчики</a></li>
                <li class="breadcrumb-item active">Манометры</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @include('detectors.tab_header', ['active' => 'manometr'])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('manometr.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить манометр</a>
                        <a href="{{ route('manometr.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Манометры</h4></div>
            <div class="card-body">
                @if(count($manometrs))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Текущее знач.</th>
                                    <th>Мин. порог</th>
                                    <th>Макс. порог</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($manometrs as $manometr)
                                    <tr id="tr{{$manometr->id}}">
                                        <td scope="row">{{ $manometr->iobject['id'] }}</td>
                                        <td><a href="{{ route('manometr.edit',[$manometr->id]) }}">
                                                {{ $manometr->name }}</a></td>
                                        <td>{{ $manometr->cur_value }}</td>
                                        <td>{{ $manometr->low_value }}</td>
                                        <td>{{ $manometr->high_value }}</td>
                                        @can('devices.show-object')
                                            <td>
                                                @if($manometr->object)
                                                    <a href="{{ route('objects.edit',[$manometr->object]) }}" target="_blank">{{ optional($manometr->eobject)->name }}</a>
                                                @endif
                                            </td>
                                        @endcan
                                        <td align="center" class="text-center">
                                            <a href="{{ route('manometr.edit',[$manometr->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $manometr->id }}" data-name="{{ $manometr->name }}">
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
                                    <th>Текущее знач.</th>
                                    <th>Мин. порог</th>
                                    <th>Макс. порог</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    {{ $manometrs->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $manometrs->total() }}</p>
                @else
                    <p>Датчики не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить датчик № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.manometr.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении датчика');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
