@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Датчики: термостаты</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Датчики</li>
                <li class="breadcrumb-item active">Термостаты</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @include('detectors.tab_header', ['active' => 'termostats'])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('termostats.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить термостат</a>
                        <a href="{{ route('termostats.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Термостаты</h4></div>
            <div class="card-body">
                @if(count($termostats))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Код</th>
                                    <th>Текущая темп.</th>
                                    <th>Оптим. темп.</th>
                                    <th>Гистерезис</th>
                                    <th>Режим</th>
                                    @can('devices.show-object')
                                        <th>Объект влияния</th>
                                    @endcan
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($termostats as $termostat)
                                    <tr id="tr{{$termostat->id}}">
                                        <td scope="row">{{ $termostat->id }}</td>
                                        <td><a href="{{ route('termostats.edit',[$termostat->id]) }}">
                                                {{ $termostat->name }}</a></td>
                                        <td><a href="{{ route('termostats.edit',[$termostat->id]) }}">
                                                {{ $termostat->id_termometr }}</a></td>
                                        <td>{{ $termostat->current }} &#176;С</td>
                                        <td>{{ $termostat->optimal }} &#176;С</td>
                                        <td>{{ $termostat->gisteresis }}</td>
                                        <td>{{ $termostat->rus_thermostat }}</td>
                                        @can('devices.show-object')
                                            <td>
                                                @if($termostat->object)
                                                    <a href="{{ route('objects.edit',[$termostat->object]) }}" target="_blank">{{ optional($termostat->eobject)->name }}</a>
                                                @endif
                                            </td>
                                        @endcan
                                        <td align="center" class="text-center">
                                            <a href="{{ route('termostats.edit',[$termostat->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $termostat->id }}" data-name="{{ $termostat->name }}">
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
                                    <th>Код</th>
                                    <th>Текущая темп.</th>
                                    <th>Оптим. темп.</th>
                                    <th>Гистерезис</th>
                                    <th>Режим</th>
                                    @can('devices.show-object')
                                        <th>Объект влияния</th>
                                    @endcan
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $termostats->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $termostats->total() }}</p>
                @else
                    <p>Термостаты не найдены</p>
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
                $('#del_modal_body').text('Удалить термостат № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.termostats.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении термостата');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
